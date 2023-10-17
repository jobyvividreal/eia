<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\MoveToPermitFormRequest​;
use App\Helpers\HtmlHelper;
use App\Helpers\FunctionHelper;
use App\Models\DocumentStatus;
use App\Models\Document;
use App\Models\EiaStage;
use App\Models\Permit;
use App\Models\Eia;
use Validator;
use DataTables;

class PermitController extends Controller
{

    protected $title    = 'Permit';
    protected $viewPath = 'permits';
    protected $route    = 'permits';


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->lists($request); 
        }
        $page                   = collect();
        $variants               = collect();
        $page->title            = $this->title;
        $page->route            = url($this->route); 
        return view($this->viewPath . '.list', compact('page', 'variants'));
    }

    /**
     * Display a listing of the resource in datatable.
     * @throws \Exception
     */
    public function lists(Request $request, $projectId = null)
    {
        $detail     =  Permit::select(['permits.*'])->whereActive(1);
        
        if (isset($request->form)) {

        }
        else {
            $detail         = $detail->orderBy('id', 'DESC');
        }
        return Datatables::eloquent($detail)
            ->addIndexColumn()
            ->addColumn('project_id', function($detail) {
                $link   = '';
                $link   .= '<a href="projects/'. $detail->eia->id.'">'.$detail->eia->project->project_code_id.'</a>';
                return $link ;
            })
            ->editColumn('permit_code', function($detail) {
                $link   = '';
                $link   .= '<a href="'.$this->route .'/'. $detail->id.'">'.$detail->permit_code.'</a>';
                return $link ;
            })
            ->editColumn('comment', function($detail) {
                $comment    = '';
                $comment    = Str::limit(strip_tags($detail->comment), 50);
                // if (strlen(strip_tags($detail->comment)) > 50) {
                //     $comment .= '<a href="javascript:void(0);" class="view-more-details" data-column="comment" data-url="'.url($this->route.'/'.$detail->id).'" data-id="'.$detail->id.'" >View</a>';
                // }
                return $comment ;
            })
            ->editColumn('status', function($detail) {
                $status = '';
                $status .= HtmlHelper::statusText($detail->stage_id, $detail->status);
                return $status;
            })
            ->editColumn('date_of_approval', function($detail) {
                return date('d/m/Y', strtotime( $detail->date_of_approval)); 
            })
            // ->removeColumn('id')
            ->escapeColumns([])
            ->make(true);                    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function createDocument($permitID)
    {
        $permit                = Permit::find($permitID); 
        if($permit) {
            $page                       = collect();
            $variants                   = collect();
            $page->title                = $this->title;
            $page->route                = url($this->route); 
            $page->projectRoute         = url('projects/'.$permit->eia->project_id); 
            $page->eiaRoute             = url('eias/'.$permit->eia->id); 
            $page->permitRoute          = url('permits/'.$permitID); 
            $page->documentStoreRoute   = url('documents'); 
            $user                       = auth()->user();
            $variants->documentStatuses = DocumentStatus::pluck('name','id'); 
            $variants->stages           = EiaStage::pluck('name','id'); 
            $eia                        = Eia::find($permit->eia_id); 
            return view($this->viewPath . '.create-document', compact('page', 'variants', 'permit', 'eia', 'user'));
        }
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MoveToPermitFormRequest​ $request)
    {     
        $input                      = $request->all();
        $input['created_by']        = auth()->user()->id;
        $permit                     = Permit::firstOrCreate(['eia_id' => $request->eia_id], $input );
        return redirect('permits/'. $permit->id)->with('success', 'Please enter permit details.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Permit $permit)
    {
        if ($permit) {
            $page                       = collect();
            $variants                   = collect();
            $page->title                = $this->title;
            $page->route                = url($this->route); 
            $page->projectRoute         = url('projects/'.$permit->eia->project_id); 
            $variants->documentStatuses = DocumentStatus::pluck('name','id'); 
            $variants->stages           = EiaStage::pluck('name','id');
            return view($this->viewPath . '.show', compact('page', 'variants', 'permit'));
        }
        abort(404); 
    }

    /**
     * Display a listing of the resource in datatable.
     * @throws \Exception
     */
    public function listDocuments(Request $request, $id = null)
    {
        $permit     = Permit::find($id);
        $detail     = Document::select(['documents.*'])
                        ->whereHas('eia', function($q){
                            $q->where('is_permit', 1);                                
                        })->where('eia_id', $permit->eia_id)->where('parent_id', 0);
                        
        if (isset($request->form)) {

        }
        else {
            $detail         = $detail->orderBy('id', 'DESC');
        }
        return Datatables::eloquent($detail)
            ->addIndexColumn()
            ->editColumn('date_of_entry', function($detail) {
                return $detail->formatted_date_of_entry; 
            })
            ->editColumn('document_number', function($detail) {
                $link       = '';
                $link       .= '<a href="documents/'. $detail->id.'">'.$detail->document_number.'</a>';
                return $link ;
            })
            ->editColumn('title', function($detail) {
                $title      = '';
                $link       = '';
                $title       = Str::limit(strip_tags($detail->title), 15);
                $link       .= '<a href="documents/'. $detail->id.'">'.$title.'</a>';
                return $link ;
            })
            ->editColumn('status', function($detail) {
                $status = '';
                $status .= HtmlHelper::statusText($detail->stage_id, $detail->status);
                return $status;
            })
            ->editColumn('brief_description', function($detail) {
                $description    = '';
                $description    = Str::limit(strip_tags($detail->brief_description), 40);
                if (strlen(strip_tags($detail->brief_description)) > 50) {
                    $description .= '<a href="javascript:void(0);" class="view-more-details" data-column="brief_description" data-url="'.url($this->route.'/'.$detail->id).'" data-id="'.$detail->id.'" >View</a>';
                }
                return $description ;
            })
            ->editColumn('document_type', function($detail) {
                $documentType   = '';
                $documentType = ($detail->document_type == 1) ? 'Hard Copy' : 'Soft Copy';
                return $documentType;
            })
            ->editColumn('comment', function($detail) {
                $comment    = '';
                $comment    = Str::limit(strip_tags($detail->comment), 40);
                if (strlen(strip_tags($detail->comment)) > 50) {
                    // onclick='viewMoreDetails(\"".$detail->comment."\")'
                    $comment .= '<a href="javascript:void(0);" class="view-more-details" data-column="comment" data-url="'.url($this->route.'/'.$detail->id).'" data-id="'.$detail->id.'" >View</a>';
                }
                return $comment ;
            })
            ->addColumn('action', function($detail) {
                $action = '';
                if ($detail->deleted_at == null) { 
                    $action .= HtmlHelper::editButton(url('eias/'.$detail->eia->id.'/documents/'.$detail->id.'/edit'), $detail->id);
                    $action .= HtmlHelper::disableButton(url($this->route), $detail->id, 'Inactive');
                } else {
                    $action .= HtmlHelper::restoreButton(url($this->route.'/restore'), $detail->id);
                }
                return $action;
            })
            ->removeColumn('id')
            ->escapeColumns([])
            ->make(true);                    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permit $permit)
    {
        $validator = \Validator::make($request->all(), 
                            [ 'permit_code' => 'required|unique:permits,permit_code,'.$permit->id],
                            [ 'permit_code.unique' => 'Permit ID is already used', 'permit_code.required' => 'Please enter Permit ID']);
        if ($validator->passes()) {  
            
            // Update EIA Status to Permit
            Eia::where('id', $permit->eia_id)->update(['is_permit' => 1]);

            $permit->permit_code            = str_replace(' ', '', $request->permit_code);
            $permit->certificate_number     = $request->certificate_number;
            $permit->status                 = $request->status;
            $permit->comment                = $request->comment;
            $permit->date_of_approval       = FunctionHelper::dateToUTC($request->date_of_approval, 'Y-m-d H:i:s');
            $permit->active                 = 1;
            $permit->updated_by             = auth()->user()->id;
            $permit->save();
            return ['flagError' => false, 'message' => $this->title. " updated successfully"];
        }
        return ['flagError' => true, 'message' => "Errors Occurred. Please check !", 'error' => $validator->errors()->all()];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}