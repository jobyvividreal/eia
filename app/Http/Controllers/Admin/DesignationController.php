<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Designation;
use App\Helpers\HtmlHelper;
use Validator;
use DataTables;

class DesignationController extends Controller
{
    protected $title    = 'Designations';
    protected $viewPath = 'admin.designations';
    protected $route    = 'admin/designations';

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
                            [ 'name' => 'required|unique:designations'],
                            [ 'name.required' => 'Please enter Designations'] );

        if ($validator->passes()) {
            $designation                = new Designation();
            $designation->name          = $request->name;
            $designation->save();
            return ['flagError' => false, 'message' => Str::singular($this->title). " added successfully"];
        }
        return ['flagError' => true, 'message' => "Errors Occurred. Please check !",  'error' => $validator->errors()->all()];
    }

    /**
     * Display a listing of the resource in datatable.
     * @throws \Exception
     */
    public function lists(Request $request)
    {
        $detail =  Designation::select(['name', 'status', 'id', ]);
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
                $action .= HtmlHelper::deleteButton(url($this->route), $detail->id);
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
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function edit(Designation $designation)
    {
        if ($designation) {
            return ['flagError' => false, 'data' => $designation];
        } else {
            return ['flagError' => true, 'message' => "Data not found, Try again!"];
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Designation $designation)
    {
        $validator = Validator::make($request->all(), 
                            [ 'name' => 'required|unique:designations,name,'.$designation->id],
                            [ 'name.required' => 'Please enter Designations']);

        if ($validator->passes()) {
            if($designation) {
                $designation->name     = $request->name;
                $designation->save();
                return ['flagError' => false, 'message' => Str::singular($this->title). " details updated successfully"];
            }
            return ['flagError' => true, 'message' => "Errors Occurred. Please check !",  'error' => $validator->errors()->all()];
        }
        return ['flagError' => true, 'message' => "Errors Occurred. Please check !",  'error' => $validator->errors()->all()];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Designation $designation, Request $request)
    {
        if($designation) {

            if (!$designation->users->isEmpty()) {
                return ['flagError' => true, 'message' => "Cant Delete! Designation is used by Users "];
            } else {
                $designation->delete();
                return ['flagError' => false, 'message' => Str::singular($this->title). " deleted successfully"];
            }
        }
        return ['flagError' => true, 'message' => "Errors Occurred. Please check !", 'error' => ['error' => 'Error! Please try again']];
    }

    // User update status 
    public function updateStatus(Request $request)
    {
        $designation       = Designation::findOrFail($request->post_id);
        if ($designation) {
            $status                 = ($designation->status == 0)?1:0;
            $designation->status    = $status;
            $designation->save();
            return ['flagError' => false, 'message' => Str::singular($this->title). " status updated successfully"];
        }
        return ['flagError' => true, 'message' => "Errors occurred Please check !",  'error'=>$validator->errors()->all()];
    }
}