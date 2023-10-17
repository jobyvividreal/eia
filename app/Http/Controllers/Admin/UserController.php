<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\HtmlHelper;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Country;
use App\Models\User;
use App\Events\UserRegistered;
use App\Jobs\WelcomeUserJob;
use Event;
use Crypt;
use DataTables;
use Validator;
use DB;

class UserController extends Controller
{
    protected $title    = 'Users';
    protected $viewPath = 'admin.users';
    protected $route    = 'admin/users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page                   = collect();
        $variants               = collect();
        $page->title            = $this->title;
        $page->link             = url($this->route);
        $page->route            = $this->route;
        $variants->roles        = Role::where('id', '!=', 1)->orderBy('id', 'ASC')->pluck('name', 'name')->all();
        return view($this->viewPath . '.list', compact('page', 'variants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page                       = collect();
        $variants                   = collect();
        $page->title                = $this->title;
        $page->link                 = url($this->route);
        $page->route                = $this->route;
        $variants->phonecode        = Country::select("id", DB::raw('CONCAT(" +", phonecode, " (", name, ")") AS phone_code'))->pluck('phone_code', 'id');
        $variants->roles            = Role::where('id', '!=', 1)->orderBy('id', 'ASC')->pluck('name', 'name')->all();
        $variants->designations     = Designation::where('status', 1)->orderBy('id', 'ASC')->pluck('name', 'id');
        $variants->departments      = Department::where('status', 1)->orderBy('id', 'ASC')->pluck('name', 'id');
        return view($this->viewPath . '.create', compact('page', 'variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response♣
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['name' => 'required', 'email' => 'required|email|unique:users', 'mobile' => 'nullable|min:3|max:15|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users'],
            ['name.required' => 'Please enter First name', 'email.required' => 'Please enter E-mail']
        );

        if ($validator->passes()) {
            $input                      = $request->all();
            $input['mobile']            = Str::remove(' ', $input['mobile']);
            $input['designation_id']    = $input['designationId'];
            $input['department_id']     = $input['departmentId'];
            // $input['dob']               = date('Y-m-d', strtotime($input['dob']));
            $user                       = User::create($input);

            $user->assignRole($request->input('roles'));
            $token        = Str::random(64);
            $user->password_create_token    = $token;
            $user->save();

            // Queue and Job
            dispatch(new WelcomeUserJob($user));
            // Events and Listeners
            event(new UserRegistered($user));

            return ['flagError' => false, 'message' => $this->title . " added successfully"];
        }
        return ['flagError' => true, 'message' => "Errors Occurred. Please check !",  'error' => $validator->errors()->all()];
    }

    /**
     * Display a listing of the resource in datatable.
     * @throws \Exception
     */
    public function lists(Request $request)
    {
        $detail =  User::select(['name', 'last_name', 'email', 'phone_code', 'mobile', 'status', 'deleted_at', 'id',])->where('is_admin', '!=', '1');

        if (isset($request->form)) {
            foreach ($request->form as $search) {

                if ($search['value'] != NULL && $search['name'] == 'name') {
                    $name       = strtolower($search['value']);
                    $detail     = $detail->where(function ($query) use ($name) {
                        $query->where('name', 'LIKE', "{$name}%")
                            ->orWhere('email', 'LIKE', "%{$name}%")
                            ->orWhere('mobile', 'LIKE', "%{$name}%");
                    });
                }

                if ($search['value'] != NULL && $search['name'] == 'roles[]') {
                    $role       = strtolower($search['value']);
                    $detail->whereHas("roles", function ($query) use ($role) {
                        $query->where("name", $role);
                    });
                }

                if ($search['value'] != NULL && $search['value'] == 'disabled') {
                    $detail         = $detail->onlyTrashed();
                }

                // print_r($search);

            }
        }
        $detail->orderBy('id', 'desc');
        return Datatables::eloquent($detail)
            ->addIndexColumn()
            ->editColumn('name', function ($detail) {
                return $detail->full_name;
            })
            ->editColumn('mobile', function ($detail) {
                $phone_code     = (!empty($detail->phoneCode->phonecode) ? '+' . $detail->phoneCode->phonecode : '');
                $mobile         = (!empty($detail->mobile) ? $phone_code . ' ' . $detail->mobile : '');
                return $mobile;
            })
            ->editColumn('status', function ($detail) {
                if ($detail->deleted_at == null) {
                    if ($detail->status != 2) {
                        $checked    = ($detail->status == 1) ? 'checked' : '';
                        $html       = '<div class="switch"><label> <input type="checkbox" ' . $checked . ' id="' . $detail->id . '" data-url="' . url($this->route . '/update-status') . '" class="manage-status" data-id="' . $detail->id . '"> <span class="lever"></span> </label> </div>';
                        return $html;
                    }
                }
            })
            ->addColumn('role', function ($detail) {
                $html   = '';
                if ($detail->roles) {
                    foreach ($detail->roles as $role) {
                        $html .= $role->name . ', ';
                    }
                }
                return rtrim($html, ', ');
            })
            ->addColumn('action', function ($detail) {
                $action = '';
                if ($detail->deleted_at == null) {
                    $action .= HtmlHelper::editButton(url($this->route . '/' . $detail->id . '/edit'), $detail->id);
                    $action .= HtmlHelper::disableButton(url($this->route), $detail->id);
                } else {
                    $action .= HtmlHelper::restoreButton(url($this->route . '/restore'), $detail->id);
                }
                // $action .= '<a href="javascript:void(0);" id="' . $detail->id . '" onclick="deleteBill(this.id)"  class="btn red btn-sm btn-icon mr-2" title="Delete"><i class="material-icons">delete</i></a>'; 
                return $action;
            })
            ->removeColumn('id')
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user               = User::find($id);
        if ($user) {
            $page                   = collect();
            $variants               = collect();
            $page->title            = $this->title;
            $page->link             = url($this->route);
            $page->route            = $this->route;
            $page->form_url         = url($this->route . '/' . $user->id);
            $page->form_method      = 'PUT';
            $variants->roles        = Role::where('id', '!=', 1)->orderBy('id', 'ASC')->pluck('name', 'name')->all();
            $variants->phonecode    = Country::select("id", DB::raw('CONCAT(" +", phonecode, " (", name, ")") AS phone_code'))->pluck('phone_code', 'id');
            $assigned_roles         = $user->roles->pluck('name', 'name')->all();
            $variants->designations = Designation::where('status', 1)->orderBy('id', 'ASC')->pluck('name', 'id');
            $variants->departments  = Department::where('status', 1)->orderBy('id', 'ASC')->pluck('name', 'id');
            return view($this->viewPath . '.create', compact('user', 'variants', 'assigned_roles', 'page'));
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        $validator = Validator::make(
            $request->all(),
            ['name' => 'required', 'email' => 'required|email|unique:users,email,' . $id, 'mobile' => 'nullable|min:3|max:15|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users,email,' . $id],
            ['name.required' => 'Please enter First name', 'email.required' => 'Please enter E-mail']
        );


        if ($validator->passes()) {
            $input                      = $request->all();
            $user                       = User::find($id);
            $input['mobile']            = Str::remove(' ', $input['mobile']);
            $input['designation_id']    = $input['designationId'];
            $input['department_id']     = $input['departmentId'];
            // $input['dob']               = date('Y-m-d', strtotime($input['dob']));
            $user->update($input);

            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($request->input('roles'));
            return ['flagError' => false, 'message' => $this->title . " details updated successfully"];
        }
        return ['flagError' => true, 'message' => "Errors occurred Please check !",  'error' => $validator->errors()->all()];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Request $request)
    {
        if (!$user->projects->isEmpty()) {
            $errors = array('Cant Delete !, There are active Projects created by user');
            return ['flagError' => true, 'message' => "Cant Delete !, There are active Project created by user",  'error' => $errors];
        }

        $user->delete();
        return ['flagError' => false, 'message' => " User disabled successfully"];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function restore($id, Request $request)
    {
        $user   = User::where('id', $id)->withTrashed()->first();
        $user->restore();
        return ['flagError' => false, 'message' => " User enabled successfully"];
    }

    // User update status 
    public function updateStatus(Request $request)
    {
        $user       = User::findOrFail($request->post_id);
        if ($user) {
            $status         = ($user->status == 0) ? 1 : 0;
            $user->status   = $status;
            $user->save();
            return ['flagError' => false, 'message' => $this->title . " status updated successfully"];
        }
        return ['flagError' => true, 'message' => "Errors occurred Please check !",  'error' => $validator->errors()->all()];
    }
}
