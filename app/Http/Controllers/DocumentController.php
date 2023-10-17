<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\HtmlHelper;
use App\Helpers\FunctionHelper;
use App\Models\DocumentStatus;
use App\Models\DocumentFile;
use App\Models\EiaStage;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Models\Eia;
use Validator;
use DataTables;
use Carbon;
use PDF;
use App\Jobs\SendTaskAssignedEmailJob;
use App\Models\TaskAssign;

class DocumentController extends Controller
{
    protected $title        = 'Documents';
    protected $viewPath     = 'documents';
    protected $route        = 'documents';
    protected $uploadPath   = 'documents';

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
        $page                       = collect();
        $variants                   = collect();
        $user                       = auth()->user();
        $page->title                = $this->title;
        $page->link                 = url($this->route);
        $page->route                = url($this->route); 
        $variants->projects         = Project::pluck('name','id'); 
        return view($this->viewPath . '.list', compact('page', 'variants', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($eiaId)
    {
        $eia                = Eia::find($eiaId); 
        if($eia) {
            $page                       = collect();
            $variants                   = collect();
            $user                       = auth()->user();
            $page->title                = $this->title;
            $page->route                = url($this->route); 
            $page->projectRoute         = url('projects/'.$eia->project_id); 
            $page->eiaRoute             = url('eias/'.$eia->id); 
            $variants->documentStatuses = DocumentStatus::pluck('name','id'); 
            $variants->stages           = EiaStage::pluck('name','id'); 
            $page->dropzoneActive       = true;
            return view($this->viewPath . '.create', compact('page', 'variants', 'eia', 'user'));
        }
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $validator = \Validator::make($request->all(), 
                            [ 'documentNumber' => 'required|unique:documents,document_number'],
                            [ 'documentNumber.unique' => 'Document Number is already used', 'codeId.required' => 'Please enter Document Number ']);

        if ($validator->passes()) {
            $document                       = new Document();
            $document->eia_id               = $request->eiaId;
            $document->document_number      = $request->documentNumber;
            $document->date_of_entry        = FunctionHelper::dateToUTC($request->dateOfEntry, 'Y-m-d H:i:s');
            $document->code                 = FunctionHelper::documentCode();
            $document->title                = $request->title;
            $document->brief_description    = $request->briefDescription;
            $document->uploaded_by          = auth()->user()->id;
            $document->created_by           = auth()->user()->id;
            $document->document_type        = $request->documentType;
            $document->comment              = $request->comment;
            $document->stage_id             = $request->stage;
            $document->parent_id            = 0;
            $document->status               = $request->status;
            $document->save();

            if($document) {
                foreach($request->documents as $key => $documentFile) {
                    $docFile                   = new DocumentFile();
                    $docFile->document_id      = $document->id;
                    $docFile->name             = $documentFile;
                    $docFile->original_name    = $request->documentOrg[$key];
                    $docFile->path             = '/app/public/'.$this->uploadPath.'/'.$documentFile;
                    $docFile->uploaded_by      = auth()->user()->id;
                    $docFile->save();
                }
            }
            return ['flagError' => false, 'message' => Str::singular($this->title). " added successfully"];
        }
        return ['flagError' => true, 'message' => "Errors Occurred. Please check !",  'error' => $validator->errors()->all()];
    }

    /**
     * Display a listing of the resource in datatable.
     * @throws \Exception
     */
    public function lists(Request $request, $eiaId = null)
    {
        $detail     =  Document::select(['documents.*'])
                        ->whereHas('eia', function($q){
                            $q->where('is_permit', 0);                                
                        })->where('parent_id', 0)->orderBy('id', 'DESC');
                        
        if (isset($request->form)) {
            foreach ($request->form as $search) {
                if ($search['value'] != NULL && $search['name'] == 'searchTitle') {
                    $name       = strtolower($search['value']);
                    $detail     = $detail->where( function($query) use ($name) {
                        $query->where('document_number', 'LIKE', "{$name}%");
                        $query->orWhere('title', 'LIKE', "{$name}%");
                    });
                }
                if ($search['value'] != NULL && $search['name'] == 'eia_id') {
                    $detail         = $detail->where('eia_id',  $search['value']);
                }

                if ($search['value'] != NULL && $search['name'] == 'archiveStatus') {
                    $detail     = $detail->where('is_archived', $search['value']);
                } 
            }
        } else {

            $detail     = $detail->where('is_archived', 0);
        }
        
        if ($eiaId!= null) {
            $detail     = $detail->where('eia_id', $eiaId);
        }



        return Datatables::eloquent($detail)
            ->addIndexColumn()
            ->editColumn('date_of_entry', function($detail) {
                return $detail->formatted_date_of_entry; 
            })
            ->editColumn('document_number', function($detail) {
                $link           = '';
                if($detail->is_archived != 1) {
                    $link           = '';
                    $documentRoute  = (auth()->user()->can('documents-details')) ? $this->route.'/'.$detail->id : 'javascript:';
                    $link           .= '<a href="'. $documentRoute.'">'.$detail->document_number.'</a>';
                    return $link ;
                } else {
                    $link           .= '<a href="javascript:">'.$detail->document_number.'</a>';
                    return $link ;
                }
            })
            ->editColumn('title', function($detail) {
                $title          = '';
                $link           = '';
                $title          = Str::limit(strip_tags($detail->title), 15);
                $documentRoute  = (auth()->user()->can('documents-details')) ? $this->route.'/'.$detail->id : 'javascript:';
                if($detail->is_archived != 1) {
                    $link           .= '<a href="'. $documentRoute.'">'.$title.'</a>';
                } else {
                    $link           .= '<a href="javascript:">'.$title.'</a>';
                }
                
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
            ->addColumn('action', function($detail) use ($eiaId) {
                $action = '';
                if($detail->is_archived != 1)  { 
                    if(auth()->user()->can('documents-edit')) {
                        $action .= HtmlHelper::editButton(url('documents/'.$detail->id.'/edit'), $detail->id);
                    }  
                    if(auth()->user()->can('documents-delete')) {
                        $action .= '<a href="javascript:void(0);" class="btn btn-danger btn-sm btn-icon mr-2 archive-item" data-title="Archive" data-tooltip="Archive" data-id="'.$detail->id.'" data-url="'.url($this->route.'/archive/'.$detail->id).'"><i class="material-icons">archive</i></a>';
                    }
                        // $action .= HtmlHelper::disableButton(url($this->route), $detail->id, 'Inactive');
                } else {
                    $action .= '<a href="javascript:void(0);" class="btn btn-danger btn-sm btn-icon mr-2 gradient-45deg-green-teal archive-item" data-title="Unarchive" data-tooltip="Unarchive" data-id="'.$detail->id.'" data-url="'.url($this->route.'/archive/'.$detail->id).'"><i class="material-icons">restore</i></a>';
                    $action .= '<a href="'.url($this->route.'/archived/'.$detail->id).'" target="_blank" class="btn btn-danger btn-sm btn-icon mr-2 gradient-45deg-light-blue-cyan" data-title="View" data-tooltip="View"><i class="material-icons">remove_red_eye</i></a>';

                }
                return $action;
            })
            ->removeColumn('id')
            ->escapeColumns([])
            ->make(true);                    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Document $document)
    {
        if ($request->ajax()) {
            if ($document) {
                return ['flagError' => false, 'document' => $document];
            } else {
                return ['flagError' => true, 'message' => "Data not found, Try again!"];
            }
        } else {
            if ($document) {
                if ($document) {

                    if($document->is_archived == 1){
                        return redirect('documents')->with('document-archived', 'Not found. Document moved to Archive!');
                    }

                    $page                       = collect();
                    $variants                   = collect();
                    $user                       = auth()->user();
                    $assignedId                 = '';
                    $activeTask                 = array();
                    $completedTask              = array();
                    $page->title                = $this->title;
                    $page->route                = url($this->route);
                    $variants->users            = User::where([['status', '=', 1], ['is_admin', 0]])->pluck('name','id'); 
                    $page->projectRoute         = url('projects/'.$document->eia->project_id); 
                    $page->eiaRoute             = url('eias/'.$document->eia->id);  
                    $variants->documentStatuses = DocumentStatus::pluck('name','id'); 
                    $variants->stages           = EiaStage::pluck('name','id'); 
                    if($document->is_assigned != 0) {
                        $activeTask             = Document::with('tasks')->whereHas("tasks", function($query) use ($document) { 
                                                            $query->where("status", 0)->where("document_id", $document->id); 
                                                    })->get();
                        $assignedId             = $activeTask[0]->tasks->id;
                    } 
                    $documentID                 = (count($document->children) > 0 ) ? $document->children[0]->id : $document->id;
                    // $page->documentName         = (count($document->children) > 0 ) ? $document->children[0]->latestFile->name : $document->latestFile->name;
                    $page->documentViewURL      = (count($document->children) > 0 ) ? $document->children[0]->latestFile->file_view : $document->latestFile->file_view;
                    $page->documentDownloadURL  = (count($document->children) > 0 ) ? $document->children[0]->latestFile->file_download : $document->latestFile->file_download;
                    return view($this->viewPath . '.show', compact('page', 'variants', 'document', 'user', 'activeTask', 'assignedId'));
                }
                abort(404);
            }
            abort(404);
            
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        if ($document) {
            $page                       = collect();
            $variants                   = collect();
            $user                       = auth()->user();
            $page->title                = $this->title;
            $page->route                = url($this->route);
            $eia                        = Eia::find($document->eia_id);
            $page->projectRoute         = url('projects/'.$eia->project_id); 
            $page->eiaRoute             = url('eias/'.$eia->id); 
            $variants->documentStatuses = DocumentStatus::pluck('name','id'); 
            $variants->stages           = EiaStage::pluck('name','id');
            $page->dropzoneActive       = false; 
            return view($this->viewPath . '.create', compact('page', 'variants', 'eia', 'document', 'user'));
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        $validator = \Validator::make($request->all(), 
                            [ 'documentNumber' => 'required|unique:documents,document_number,'.$document->id],
                            [ 'documentNumber.unique' => 'Document Number is already used', 'codeId.required' => 'Please enter Document Number ']);

        if ($validator->passes()) {
            $document->eia_id               = $request->eiaId;
            $document->document_number      = $request->documentNumber;
            $document->date_of_entry        = FunctionHelper::dateToUTC($request->dateOfEntry, 'Y-m-d H:i:s');
            $document->title                = $request->title;
            $document->brief_description    = $request->briefDescription;
            $document->document_type        = $request->documentType;
            $document->comment              = $request->comment;
            $document->stage_id             = $request->stage;
            $document->status               = $request->status;
            $document->save();
            return ['flagError' => false, 'message' => Str::singular($this->title). " updated successfully"];
        }
        return ['flagError' => true, 'message' => "Errors Occurred. Please check !",  'error' => $validator->errors()->all()];
    }

    /**
     * Upload Document file.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileList(Request $request)
    {
        $data       = [];
        $images     = DocumentFile::where('document_id', $request->documentId)->get()->toArray();
        // $files = scandir($storeFolder);
        // foreach ( $files as $file ) {
        //     if ($file !='.' && $file !='..' && in_array($file,$tableImages)) {       
        //         $obj['name'] = $file;
        //         $file_path = public_path('uploads/gallery/').$file;
        //         $obj['size'] = filesize($file_path);          
        //         $obj['path'] = url('public/uploads/gallery/'.$file);
        //         $data[] = $obj;
        //     }
        // }
        foreach ($images as $image) {
            $obj['name']        = $image['original_name'];
            $obj['path']        = asset('storage/documents/' . $image['name']);
            $data[] = $obj;
        }
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        if (!$document->children->isEmpty()) {
            $errors = array('Cant Delete !, There are files under this Document');
            return ['flagError' => true, 'message' => "Cant Delete !, There are files under this Document",  'error' => $errors];
        }
        $document->delete();
        return ['flagError' => false, 'message' =>  $this->title. " disabled successfully"];
    }

    /**
     * Upload Document file.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadDocument(Request $request)
    {  
        if ($request->file('file')) {
            $image      = $request->file('file');
            $name       = $image->getClientOriginalName();
            $fileName   = FunctionHelper::documentName($name);
            // if(env('APP_ENV') == 'local') {
                $path = storage_path().'/app/public/'.$this->uploadPath;
                if (!file_exists($path)) {
                    Storage::makeDirectory($path, 0755);
                }
                $path       = $image->storeAs($this->uploadPath, $fileName, 'public');
            // } else {
                // Store Image in S3
                // $request->image->storeAs('images', $fileName, 's3');
            // }
            return response()->json(['filename' => $fileName, 'name' => $name ]);
        }
    }

    /**
     * Remove Document file.
     * 
     * @return \Illuminate\Http\Response
     */
    public function fileRemove(Request $request)
    {
        $name =  $request->get('name');
        File::where(['name' => $name])->delete();
        
        if ($name != '') {
            \Illuminate\Support\Facades\Storage::delete('public/documents/' . $name);
        }
        return $name;
    }

    /**
     * Download Document file.
     *
     * @return \Illuminate\Http\Response
     */
    function downloadFile(Request $request, $file)
    {
        $img_extensions = ['jpg', 'jpeg', 'png'];
        $doc_extensions = ['docx', 'doc'];
        $xls_extensions = ['xls', 'xlsx'];

        if(in_array(pathinfo($file, PATHINFO_EXTENSION), $img_extensions)) {

            $path = storage_path('app/public/documents/'.$file);
            return response()->download($path);

        } elseif(in_array(pathinfo($file, PATHINFO_EXTENSION), $doc_extensions)) {

            $path = storage_path('app/public/documents/'.$file);
            return response()->download($path);

        } elseif(in_array(pathinfo($file, PATHINFO_EXTENSION), $xls_extensions)) {

            $path = storage_path('app/public/documents/'.$file);
            return response()->download($path);

        } elseif(pathinfo($file, PATHINFO_EXTENSION) == 'pdf') {

            $path = storage_path('app/public/documents/'.$file);
            $headers = ['Content-Type: application/pdf'];

            return response()->download($path, $file, $headers);

        } else {

            $file_name = asset('admin/images/image-not-found.png');
        }

    }
    
    /**
     * Download Document file.
     *
     * @return \Illuminate\Http\Response
     */
    function downloadPDfFile(Request $request, $file)
    {
        // $document =  asset('storage/documents/' . $file);
        // $pdf = PDF::loadView($file);
        // return $pdf->download('test.pdf');
    }
    
    /**
     * Download Document file.
     *
     * @return \Illuminate\Http\Response
     */
    function viewDocumentFile(Request $request, $document)
    {
        // $pdf = PDF::loadView($document);
        // return $pdf->stream('whateveryourviewname.pdf');
    }

    public function autocomplete(Request $request)
    {
        $data       = array();
        $result     = Document::select("documents.id", "documents.document_number")
                                ->where("document_number","LIKE","%{$request->search}%")
                                ->orWhere("title","LIKE","%{$request->search}%")->get();
        if ($result) {
            foreach($result as $row) {
                $data[] = array(['id' => $row->id, 'name' => $row->document_number]);
            }
        } else {
            $data = [];
        }
        return response()->json($result);
    }

    /**
     * Remove Document file.
     * 
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request)
    {
        $document            = Document::find($request->document_Id);
        if ($document) {
            $document->stage_id = $request->stage_id;
            $document->status   = $request->status_id;
            $document->save();
            return ['flagError' => false, 'message' => $this->title. " updated successfully"];
        }
        abort(404);
    }   

    /**
     * Remove Document file.
     * 
     * @return \Illuminate\Http\Response
     */
    public function archive(Request $request, $id)
    {
        $document            = Document::find($id);
        if ($document) {
            $archive_status         = ($document->is_archived == 0)?1:0;
            $document->is_archived  = $archive_status;
            $document->save();

            $action     = ($document->is_archived == 1)? 'archived': 'unarchived';
            return ['flagError' => false, 'message' => $this->title. " " .$action. " successfully", 'redirect' => $request->redirect];
        }
        $errors = array('Document Not found');
        return ['flagError' => true, 'message' => "Errors Occurred. Please check !",  'error' => $errors];
    }

/**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function viewArchived(Request $request, $id)
    {
        $document = Document::find($id);
        if ($document) {

            if($document->is_archived == 0){
                return redirect('documents')->with('document-archived', 'Not found. Document moved from Archive!');
            }

            $page                       = collect();
            $variants                   = collect();
            $user                       = auth()->user();
            $assignedId                 = '';
            $activeTask                 = array();
            $completedTask              = array();
            $page->title                = $this->title;
            $page->route                = url($this->route);
            $variants->users            = User::where([['status', '=', 1], ['is_admin', 0]])->pluck('name','id'); 
            $page->projectRoute         = url('projects/'.$document->eia->project_id); 
            $page->eiaRoute             = url('eias/'.$document->eia->id);  
            $variants->documentStatuses = DocumentStatus::pluck('name','id'); 
            $variants->stages           = EiaStage::pluck('name','id'); 
            if($document->is_assigned != 0) {
                $activeTask             = Document::with('tasks')->whereHas("tasks", function($query) use ($document) { 
                                                    $query->where("status", 0)->where("document_id", $document->id); 
                                            })->get();
                $assignedId             = $activeTask[0]->tasks->id;
            } 

            $documentID                 = (count($document->children) > 0 ) ? $document->children[0]->id : $document->id;
            $page->documentViewURL      = (count($document->children) > 0 ) ? $document->children[0]->latestFile->file_view : $document->latestFile->file_view;
            $page->documentDownloadURL  = (count($document->children) > 0 ) ? $document->children[0]->latestFile->file_download : $document->latestFile->file_download;
            return view($this->viewPath . '.show-archived', compact('page', 'variants', 'document', 'user', 'activeTask', 'assignedId', 'documentID'));
        }
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteVersion(Request $request, $id)
    {
        $document = Document::find($id);
        if ($document) {

                $subDocument =  DocumentFile::where('document_id', $id)->first();

                if($subDocument) {

                    \Illuminate\Support\Facades\Storage::delete('public/' . $this->uploadPath . '/' . $subDocument->name);
                    $subDocument->delete();

                } else {
                    $errors = array('Not found!, Document not found, Try again');
                    return ['flagError' => true, 'message' => "Not found!, Document not found, Try again",  'error' => $errors];
                }


            $document->delete();
            return ['flagError' => false, 'message' => $this->title. " deleted successfully"];
        }
        $errors = array('Not found!, Document not found, Try again');
        return ['flagError' => true, 'message' => "Not found!, Document not found, Try again",  'error' => $errors];
    }
    
    
}