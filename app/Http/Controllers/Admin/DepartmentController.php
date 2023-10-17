<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Department;
use App\Helpers\HtmlHelper;
use Validator;
use DataTables;

class DepartmentController extends Controller
{
    protected $title    = 'Departments';
    protected $viewPath = 'admin.departments';
    protected $route    = 'admin/departments';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page                   = collect();
        $variants               = collect();
        $user                   = auth()->user();
        $page->title            = $this->title;
        $page->link             = url($this->route);
        $page->route            = $this->route;  
        return view($this->viewPath . '.list', compact('page', 'variants', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), 
                            [ 'name' => 'required|unique:departments'],
                            [ 'name.required' => 'Please enter Department'] );

        if ($validator->passes()) {
            $department                = new Department();
            $department->name          = $request->name;
            $department->description   = $request->description;
            $department->save();
            return ['flagError' => false, 'message' => $this->title. " added successfully"];
        }
        return ['flagError' => true, 'message' => "Errors Occurred. Please check !",  'error' => $validator->errors()->all()];
    }

    /**
     * Display a listing of the resource in datatable.
     * @throws \Exception
     */
    public function lists(Request $request)
    {
        $detail =  Department::select(['name', 'status', 'id', ]);
        $detail->orderBy('id', 'desc');
        return Datatables::eloquent($detail)
            ->addIndexColumn()
            ->editColumn('status', function($detail){
                $checked    = ($detail->status == 1) ? 'checked' : '';
                $html       = '<div class="switch"><label> <input type="checkbox" '.$checked.' id="' . $detail->id . '" data-url="'.url($this->route.'/update-status').'" class="manage-status" data-id="'.$detail->id.'"> <span class="lever"></span> </label> </div>';
                return $html;
            })
            ->addColumn('action', function($detail){
                $action = '';
                $action .= HtmlHelper::editAjaxButton($detail->id);
                $action .= HtmlHelper::disableButton(url($this->route), $detail->id);
                return $action;
            })
            ->removeColumn('id')
            ->escapeColumns([])
            ->make(true);                    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        if ($department) {
            return ['flagError' => false, 'data' => $department];
        } else {
            return ['flagError' => true, 'message' => "Data not found, Try again!"];
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), 
                            [ 'name' => 'required|unique:departments,name,'.$department->id],
                            [ 'name.required' => 'Please enter Department']);

        if ($validator->passes()) {
            if($department) {
                $department->name          = $request->name;
                $department->description   = $request->description;
                $department->save();
                return ['flagError' => false, 'message' => $this->title. " details updated successfully"];
            }
            return ['flagError' => true, 'message' => "Errors Occurred. Please check !",  'error' => $validator->errors()->all()];
        }
        return ['flagError' => true, 'message' => "Errors Occurred. Please check !",  'error' => $validator->errors()->all()];
    }

    // User update status 
    public function updateStatus(Request $request)
    {
        $department       = Department::findOrFail($request->post_id);
        if ($department) {
            $status                 = ($department->status == 0)?1:0;
            $department->status    = $status;
            $department->save();
            return ['flagError' => false, 'message' => Str::singular($this->title). " status updated successfully"];
        }
        return ['flagError' => true, 'message' => "Errors occurred Please check !",  'error'=>$validator->errors()->all()];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        if (!$department->users->isEmpty()) {
            return ['flagError' => true, 'message' => "Cant Delete! Department is used by Users "];
        } else {
            $department->delete();
            return ['flagError' => false, 'message' => Str::singular($this->title). " deleted successfully"];
        }
        return ['flagError' => true, 'message' => "Errors Occurred. Please check !", 'error' => ['error' => 'Error! Please try again']];
    }
}
