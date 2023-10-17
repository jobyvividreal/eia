<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ProjectType;
use App\Helpers\HtmlHelper;
use Validator;
use DataTables;

class ProjectTypeController extends Controller
{
    protected $title    = 'Project Types';
    protected $viewPath = 'admin.project_types';
    protected $route    = 'admin/project-types';
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
        $page                   = collect();
        $variants               = collect();
        $page->title            = $this->title;
        $page->link             = url($this->route);
        $page->route            = $this->route; 
        return view($this->viewPath . '.create', compact('page', 'variants'));
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
                            [ 'name' => 'required|unique:project_types'],
                            [ 'name.required' => 'Please enter Project type'] );

        if ($validator->passes()) {
            $projectType                = new ProjectType();
            $projectType->name          = $request->name;
            $projectType->description   = $request->description;
            $projectType->save();
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
        $detail =  ProjectType::select(['name', 'status', 'id', ]);
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
                $action .= HtmlHelper::editButton(url($this->route.'/'.$detail->id.'/edit'), $detail->id);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $projectType               = ProjectType::find($id);        
        if($projectType) {
            $page                   = collect();
            $variants               = collect();
            $page->title            = $this->title;
            $page->link             = url($this->route);
            $page->route            = $this->route; 
            $page->form_url         = url($this->route . '/' . $projectType->id);
            $page->form_method      = 'PUT';
            return view($this->viewPath . '.create',compact('projectType','variants', 'page'));
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
        $validator = Validator::make($request->all(), 
                            [ 'name' => 'required|unique:project_types,name,'.$id],
                            [ 'name.required' => 'Please enter Project type'] );

        if ($validator->passes()) {
            $projectType                = ProjectType::find($id);  
            $projectType->name          = $request->name;
            $projectType->description   = $request->description;
            $projectType->save();
            return ['flagError' => false, 'message' => Str::singular($this->title). " updated successfully"];
        }
        return ['flagError' => true, 'message' => "Errors occurred Please check !",  'error'=> $validator->errors()->all()];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectType $projectType, Request $request)
    {
        if (!$projectType->project->isEmpty()) {
            return ['flagError' => true, 'message' => "Cant Delete! ProjectType is used in Projects "];
        } else {
            $projectType->delete();
            return ['flagError' => false, 'message' => Str::singular($this->title). " deleted successfully"];
        }
        return ['flagError' => true, 'message' => "Errors Occurred. Please check !", 'error' => ['error' => 'Error! Please try again']];
    }

    // User update status 
    public function updateStatus(Request $request)
    {
        $projectType       = ProjectType::findOrFail($request->post_id);
        if ($projectType) {
            $status                 = ($projectType->status == 0)?1:0;
            $projectType->status    = $status;
            $projectType->save();
            return ['flagError' => false, 'message' => Str::singular($this->title). " status updated successfully"];
        }
        return ['flagError' => true, 'message' => "Errors occurred Please check !",  'error'=>$validator->errors()->all()];
    }
}
