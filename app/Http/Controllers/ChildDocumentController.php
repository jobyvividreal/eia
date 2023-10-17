<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\FunctionHelper;
use App\Helpers\HtmlHelper;
use App\Models\DocumentFile;
use App\Models\Document;
use App\Models\Project;
use App\Models\DocumentStatus;
use App\Models\EiaStage;

class ChildDocumentController extends Controller
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
            $document   = Document::find($request->documentId);
            if ($document) {
                $subDocumentsHTML       =  $this->loadSubDocumentsHTML($document);  
                return ['flagError' => false, 'document' => $document, 'html' => $subDocumentsHTML];
            } else {
                return ['flagError' => true, 'message' => "Data not found, Try again! "];
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($documentId)
    {
        $document                       = Document::find($documentId); 
        if($document) {
            $page                       = collect();
            $variants                   = collect();
            $user                       = auth()->user();
            $eia                        = $document->eia;
            $page->title                = $this->title;
            $page->route                = url($this->route.'/'.$documentId.'/store'); 
            $page->projectRoute         = url('projects/'.$document->eia->project_id); 
            $page->documentRoute        = url($this->route.'/'.$document->id); 
            $page->eiaRoute             = url('eias/'.$document->eia->id); 
            $variants->documentStatuses = DocumentStatus::pluck('name','id'); 
            $variants->stages           = EiaStage::pluck('name','id'); 
            $page->stageID              = (count($document->children) > 0) ? $document->children[0]->stage_id : $document->stage_id;
            return view($this->viewPath . '.create_child', compact('page', 'variants', 'eia', 'document', 'user'));
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
        $document                       = new Document();
        $document->eia_id               = $request->eiaId;
        $document->document_number      = $request->documentNumber;
        $document->date_of_entry        = FunctionHelper::dateToUTC($request->dateOfEntry, 'Y-m-d H:i:s');
        $document->code                 = FunctionHelper::documentCode();
        $document->brief_description    = $request->briefDescription;
        $document->uploaded_by          = auth()->user()->id;
        $document->created_by           = auth()->user()->id;
        $document->stage_id             = $request->stageID;
        $document->parent_id            = $request->documentId;
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
        return ['flagError' => false, 'message' => $this->title. " added successfully"];
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
        //
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
        //
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
     * Return HTML data.
     *
     */
    public function loadSubDocumentsHTML($document) 
    {

        $html       = '';
        $parentHTML = '';

        if( count($document->children) > 0) {

            foreach($document->children as $key => $child) {

                if($key != 0) {
               
                    $html .= '<div class="card animate fadeUp"><div class="card-content"><div class="row" id="product-four"><div class="col s12 m6">';
                    $html .= '<h5>' . HtmlHelper::statusText($child->stage_id, $child->status) . '</h5>';
                    $html .= '<img src="'.$child->latestFile->file_preview.'" class="responsive-img" style="max-height: 400px;" alt="">';

                    if(auth()->user()->can('documents-delete')) {
                    $html .= '<div class="email-header"><div class="left-icons"><span class="action-icons">';
                    $html .= '<a href="javascript:" data-id="'.$child->id.'" onclick="deleteDocument('.$child->id.')" class="delete-document-version"><i class="material-icons">delete</i></a>';
                    $html .= '</span></div> </div>';
                    }
                    $html .= '</div>';
         



                    $html .= '<div class="col s12 m6"><p style="text-align: right;"></p><table class="striped"><tbody>';
                    $html .= '<tr><td width="30%">Date of Entry:</td><td>'.$child->date_of_entry.'</td></tr>' ;    
                    $html .= '<tr><td width="30%">Uploaded By:</td><td>'.$child->uploadedBy->name.'</td></tr>' ; 
                    $html .= '<tr><td width="30%">Description:</td><td>';
                    
                    $html .= Str::limit(strip_tags($child->brief_description), 100);
                    if(strlen(strip_tags($child->brief_description)) > 100) {
                        $html .= '<a href="javascript:void(0);" onclick="getFullDescription('.$child->id.')" class="" data-column="brief_description" data-url="'.url('documents/'.$child->id).'" data-id="'.$child->id.'" >View</a>';
                    }
                    
                    $html .= '</td></tr>' ;    
                    $html .= '</tbody></table></div>';
                    $html .= '<div class="col s12 m12">';

                    if(auth()->user()->can('documents-comment-create')) {
                        $html .= '<div class="row commentContainer" id="commentContainer'.$child->id.'">';
                        $html .= '<div class="input-field col m10 s12 commentArea">';
                        $html .= '<textarea id="comment" class="materialize-textarea commentField" name="comment" cols="50" rows="10" placeholder="Comments"></textarea>';
                        $html .= '<div id="documentComment-error-'.$child->id.'" class="error documentComment-error" style="display:none;"></div></div>';
                        $html .= '<div class="input-field col m2 s12" style="margin-top: 37px; ! important">';
                        $html .= '<a href="javascript:" class="text-sub subDocument-save-comment-btn" data-id="'.$child->id.'"><i class="material-icons mr-2"> send </i></a></div> </div>';
                        $html .= '<div class="app-email" id="latestComment'.$child->id.'"></div>';           
                    }   
                    if(count($child->comments) > 0) {
                        foreach($child->comments as $comment) {
                            $html .= '<div class="app-email" id="docCommentsDiv'.$document->id.'"><div class="content-area"><div class="app-wrapper"><div class="card card card-default scrollspy border-radius-6 fixed-width">';
                            $html .= '<div class="card-content p-0 pb-2"><div class="collection email-collection"><div class="email-brief-info collection-item animate fadeUp ">';
                            $html .= '<a class="list-content" href="javascript:"><div class="list-title-area"><div class="user-media">';
                            $html .= '<img src="'.$comment->commentedBy->profile_url.'" alt="" class="circle z-depth-2 responsive-img avtar"><div class="list-title">'.$comment->commentedBy->name.'</div></div></div>';
                            $html .= '<div class="list-desc">'.$comment->comment.'</div></a><div class="list-right"><div class="list-date">'.$comment->created_at->format('M d, h:i A').'</div>';
                            $html .= '</div></div></div></div></div></div></div></div>';
                        }
                    }

                    $html .= '</div></div></div></div>' ; 
                }   
            }

                $parentHTML .= '<div class="card animate fadeUp"><div class="card-content"><div class="row" id="product-four"><div class="col s12 m6">';
                $parentHTML .= '<h5>' . HtmlHelper::statusText($document->stage_id, $document->status) . '</h5>';
                $parentHTML .= '<img src="'.$document->latestFile->file_preview.'" class="responsive-img" style="max-height: 400px;" alt=""></div>';

                $parentHTML .= '<div class="col s12 m6"><p style="text-align: right;"></p><table class="striped"><tbody>';
                $parentHTML .= '<tr><td width="30%">Date of Entry:</td><td>'.$document->date_of_entry.'</td></tr>' ;    
                $parentHTML .= '<tr><td width="30%">Uploaded By:</td><td>'.$document->uploadedBy->name.'</td></tr>' ;   

                $parentHTML .= '<tr><td width="30%">Description:</td><td>';
                
                $parentHTML .= Str::limit(strip_tags($document->brief_description), 100);
                if(strlen(strip_tags($document->brief_description)) > 100) {
                    $parentHTML .= '<a href="javascript:void(0);" onclick="getFullDescription('.$document->id.')" class="" data-column="brief_description" data-url="'.url('documents/'.$document->id).'" data-id="'.$document->id.'" >View</a>';
                }
                
                $parentHTML .='</td></tr>' ;    
                $parentHTML .= '</tbody></table></div>';

                $parentHTML .= '<div class="col s12 m12">';
                if(auth()->user()->can('documents-comment-create')) {
                    $parentHTML .= '<div class="row commentContainer" id="commentContainer'.$document->id.'">';
                    $parentHTML .= '<div class="input-field col m10 s12 commentArea">';
                    $parentHTML .= '<textarea id="comment" class="materialize-textarea commentField" name="comment" cols="50" rows="10" placeholder="Comments"></textarea>';
                    // $html .= '<label for="comment" class="label-placeholder active">  </label>';
                    $parentHTML .= '<div id="documentComment-error-'.$document->id.'" class="error documentComment-error" style="display:none;"></div></div>';
                    $parentHTML .= '<div class="input-field col m2 s12" style="margin-top: 37px; ! important">';
                    $parentHTML .= '<a href="javascript:" class="text-sub subDocument-save-comment-btn" data-id="'.$document->id.'"><i class="material-icons mr-2"> send </i></a></div> </div>';
                }
                $parentHTML .= '<div class="app-email" id="latestComment'.$document->id.'"></div>';           
                if(count($document->comments) > 0) {
                    foreach($document->comments as $comment) {
                        $parentHTML .= '<div class="app-email" id="docCommentsDiv'.$document->id.'"><div class="content-area"><div class="app-wrapper"><div class="card card card-default scrollspy border-radius-6 fixed-width">';
                        $parentHTML .= '<div class="card-content p-0 pb-2"><div class="collection email-collection"><div class="email-brief-info collection-item animate fadeUp ">';
                        $parentHTML .= '<a class="list-content" href="javascript:"><div class="list-title-area"><div class="user-media">';
                        $parentHTML .= '<img src="'.$comment->commentedBy->profile_url.'" alt="" class="circle z-depth-2 responsive-img avtar"><div class="list-title">'.$comment->commentedBy->name.'</div></div></div>';
                        $parentHTML .= '<div class="list-desc">'.$comment->comment.'</div></a><div class="list-right"><div class="list-date">'.$comment->created_at->format('M d, h:i A').'</div>';
                        $parentHTML .= '</div></div></div></div></div></div></div></div>';
                    }
                }
                $parentHTML .= '</div></div></div></div>' ;
                $html .= $parentHTML;
        } else {

        }
        return $html;
    }
}