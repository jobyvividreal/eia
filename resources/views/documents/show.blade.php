@extends('layouts.app')

{{-- page title --}}
@section('seo_title', Str::plural($page->title) ?? '') 
@section('search-title') {{ $page->title ?? ''}} @endsection

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('admin/vendors/quill/quill.snow.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
  <link rel="stylesheet" type="text/css" href="{{asset('admin/css/pages/app-sidebar.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/css/pages/app-email.css')}}">
  <style>
    #documentDetailsSection .app-email .content-area {
      margin-top: 0px;
      width: auto;
    }
    .smalltxt {
      font-size: 11px;
      color: #a9a9a9;
    }
  </style>
@endsection

@section('content')

@section('breadcrumb')
<div class="col s12 m6 l6"><h5 class="breadcrumbs-title"><span>{{ Str::plural($page->title) ?? ''}}</span></h5></div>
<div class="col s12 m6 l6 right-align-md">
  <ol class="breadcrumbs mb-0">
    <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ url('projects') }}">Projects</a></li>
    <li class="breadcrumb-item"><a href="{{ url($page->projectRoute) }}">{{ Str::limit(strip_tags($document->eia->project->name), 20) ?? 'Show' }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url($page->eiaRoute) }}">{{ Str::limit(strip_tags($document->eia->code_id), 20) ?? 'Show' }}</a></li>
    <li class="breadcrumb-item active">{{ Str::limit(strip_tags($document->document_number), 20) ?? 'Show' }}</li>
  </ol>
</div>
@endsection
<!-- users view start -->
<div class="section users-view">
  <!-- users view media object start -->
  <div class="card-panel">
    <div class="row">
      <div class="col s12 m7">
        <div class="display-flex media">
          <div class="media-body">
            <h6 class="media-heading"><span>Project: </span><span class="users-view-name">{{ $document->eia->project->name ?? ''}} </span></h6>
            <h5 class="media-heading"><span>EIA Id: </span><span class="users-view-name">{{ $document->eia->code_id ?? ''}} </span></h5>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- users view media object ends -->
  <!-- users view card data start -->
  <div class="card" id="documentDetailsSection">
    <div class="card-content">
      <div class="row">
        <div class="col s12 m6">
          <!-- $document->children -->
          @isset($document->latestFile)
            <h6 class="mb-4 mt-4"><i class="material-icons">info_outline</i> {{ Str::plural($page->title) ?? ''}} Details</h6>
            @if(count($document->children) > 0) 
              <img src="{{$document->children[0]->latestFile->file_preview}}" style="max-height: 400px;" class="responsive-img" alt="">
            @else
              <img src="{{$document->latestFile->file_preview}}" style="max-height: 400px;" class="responsive-img" alt="">
            @endif
            <div class="email-header">
            <div class="left-icons">
              <span class="action-icons">
                <a href="{{$page->documentViewURL}}" target="_blank" ><i class="material-icons">remove_red_eye</i></a>
                @if(count($document->children) > 0) 
                  <a href="{{route('documents.download', $document->children[0]->latestFile->name)}}"><i class="material-icons">file_download</i></a>
                @else
                  <a href="{{route('documents.download', $document->latestFile->name)}}"><i class="material-icons">file_download</i></a>
                @endif
                <!-- $page->documentDownloadURL -->
              </span>
            </div>
          </div>
            <!-- <a class="waves-effect waves-light btn gradient-45deg-purple-deep-orange z-depth-4 mt-2" href="https://eia.vividreal.co.in/documents/file/download/MUSMHX0A0NXFG3FN4EPQ_1159079-immap-ihf_humanitarian_access_response_-_landmine_erw_contamination_areas_aug_2018.png" style="margin-top: 10px; !important">Download</a>  -->
          @endisset
          @if($document->is_assigned == 0)
            @can('documents-task-create')
              <p id=""><a class="waves-effect waves-light btn gradient-45deg-purple-deep-orange z-depth-4 mt-2 assign-task" id="" data-id="{{$document->id}}" href="javascript:" style="margin-top: 10px; !important">Assign Task</a></p>                  
            @endcan
            @if(!empty($document->completedTask))
              <div class="col s12 m12 card-details mt-2" id="taskDetailsDiv">
                <h5><a href="javascript:" class="mt-8 blue-text">Completed Task Details</a></h5>
                <div class="card-panel mt-2">
                  <p>{{$document->completedTask->completed_note}}</p>
                  <div class="mt-1 smalltxt">Completed by <a href="javascript:">{{$document->completedTask->assignedBy->name}}</a> on {{$document->completedTask->created_at->format('M d, h:i A')}}</div>
                </div>
              </div>
            @endif
            @else
            <div class="col s12 m12 card-details mt-2" id="taskDetailsDiv">
              <h5><a href="javascript:" class="mt-8 blue-text">Task Details</a></h5>
              <div class="card-panel mt-2">
                  <p>{{ $activeTask[0]->tasks->details}}</p>
                  <div class="mt-1 smalltxt">Assigned by 
                    <a href="javascript:">
                      {{ ($activeTask[0]->tasks->assigned_by == auth()->user()->id) ? 'You' : $activeTask[0]->tasks->assignedBy->name }}
                    </a> on {{$activeTask[0]->tasks->created_at->format('M d, h:i A')}}
                  </div>
                  <div class="row mt-2">
                    <div class="col s2">
                      <img src="{{$activeTask[0]->tasks->assignedTo->profile_url}}" width="40" alt="news" class="circle responsive-img mr-3">
                    </div>
                    <div class="col s3 p-0 mt-1"><span class="pt-2">
                      {{ ($activeTask[0]->tasks->assigned_to == auth()->user()->id) ? 'You' : $activeTask[0]->tasks->assignedTo->name}}
                    </span></div>
                    @can('documents-task-edit')
                      <div class="col s7 mt-1 right-align">
                        <a href="javascript:" class="assign-task"><span class="material-icons"> edit </span></a>
                      </div>
                    @endcan
                  </div>
              </div>
            </div>
          @endif
        </div>
        <div class="col s12 m6" style="margin-top: 40px">
          <table class="striped">
            <tbody>
              <tr>
                <td width="30%">Document Number:</td>
                <td>{{$document->document_number ?? ''}}</td>
              </tr>
              <tr>
                <td width="30%">Title of Document: </td>
                <td>{{$document->title ?? ''}}</td>
              </tr>
              <tr>
                <td>Uploaded by: </td>
                <td>{{$document->uploadedBy->name ?? ''}}</td>
              </tr> 
              <tr>
                <td>Date of Entry: </td>
                <td>{{$document->date_of_entry ?? ''}}</td>
              </tr>
              <tr>
                <td>Status: </td>
                <td>
                  @if(count($document->children) > 0) 
                    {!! App\Helpers\HtmlHelper::statusText($document->children[0]->stage_id, $document->children[0]->status) !!}
                  @else
                    {!! App\Helpers\HtmlHelper::statusText($document->stage_id, $document->status) !!}
                  @endif
                </td>
              </tr>
              <tr>
                <td>Project stage: </td>
                <td>
                  @php 
                    $stageID    = (count($document->children) > 0) ? $document->children[0]->stage_id : $document->stage_id;
                    $statusID   = (count($document->children) > 0) ? $document->children[0]->status : $document->status;
                    $documentID = (count($document->children) > 0) ? $document->children[0]->id : $document->id;                    
                  @endphp 
                  {!! Form::select('stage', $variants->stages, $stageID ?? '', ['id' => 'stage', 'class' => 'select2 browser-default document-stage', 'placeholder'=>'Please select Stage']) !!}
                </td>
              </tr>
              <tr>
                <td>Document status: </td>
                <td>
                  {!! Form::select('status', $variants->documentStatuses, $statusID ?? '', ['id' => 'status', 'class' => 'select2 browser-default', 'placeholder'=>'Please select Status']) !!}
                </td>
              </tr>
              @can('documents-manage-status')
              <tr>
                <td></td>
                <td style="text-align:right">
                  {!! Form::hidden('documentStatusId', $documentID ?? '', ['id' => 'documentStatusId']); !!}
                  {!! App\Helpers\HtmlHelper::submitButton('Submit', 'documentStatusUpdateBtn') !!}
                </td>
              </tr>
              @endcan
              <tr>
                <td>Brief Description: </td>
                <td>  

                  @if(count($document->children) > 0) 
                    {{ Str::limit(strip_tags($document->children[0]->brief_description), 100)}}
                    @if(strlen(strip_tags($document->children[0]->brief_description)) > 100)
                      <a href="javascript:void(0);" onclick="getFullDescription({{$document->id}})">View</a>
                    @endif
                  @else
                    {{ Str::limit(strip_tags($document->brief_description), 100)}}
                    @if(strlen(strip_tags($document->brief_description)) > 100)
                      <a href="javascript:void(0);" onclick="getFullDescription({{$document->id}})">View</a>
                    @endif
                  @endif



                  
                </td>
              </tr>
              <tr>
                <td>Remarks/Comments: </td>
                <td>  
                  {{ Str::limit(strip_tags($document->comment), 100)}}
                  @if(strlen(strip_tags($document->comment)) > 100)
                    <a href="javascript:void(0);" onclick="getFullComment({{$document->id}})">View</a>
                  @endif
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col s12 m12" style="margin-top: 10px">
          <div class="row commentContainer">  
            @can('documents-comment-create')
              <div class="input-field col m10 s12 commentArea">
                <textarea id="documentComment" class="materialize-textarea commentField" name="comment" cols="50" rows="10" placeholder="Enter comment"></textarea>  
                <div id="documentComment-error-{{$documentID}}" class="error documentComment-error" style="display:none;"></div>
              </div>
              <div class="input-field col m2 s12" style="margin-top: 37px; !important">
                <a href="javascript:" class="text-sub save-comment-btn" data-id="{{$documentID}}"><i class="material-icons mr-2">send</i></a>
              </div>
            @endcan
          </div>
          <!-- Content Area Starts -->
          <div class="app-email" id="latestComment{{$documentID}}"></div>
          @if(count($document->children) > 0) 
            @forelse ($document->children[0]->comments as $comment)
              <div class="app-email" id="docCommentsDiv{{$comment->id}}">
                <div class="content-area">
                  <div class="app-wrapper">
                    <div class="card card card-default scrollspy border-radius-6 fixed-width">
                      <div class="card-content p-0 pb-2">
                        <div class="collection email-collection">
                          <div class="email-brief-info collection-item animate fadeUp">
                            <a class="list-content" href="javascript:">
                              <div class="list-title-area">
                                <div class="user-media">
                                  <img src="{{$comment->commentedBy->profile_url}}" alt="" class="circle z-depth-2 responsive-img avtar">
                                  <div class="list-title">
                                    {{($comment->commented_by == auth()->user()->id) ? 'You' : $comment->commentedBy->name}}
                                  </div>
                                </div>
                              </div>
                              <div class="list-desc">{{$comment->comment}}</div>
                            </a>
                            <div class="list-right">
                              <div class="list-date">{{$comment->created_at->format('M d, h:i A')}}</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @empty
            @endforelse
          @else
            @forelse ($document->comments as $comment)
              <div class="app-email" id="docCommentsDiv{{$comment->id}}">
                <div class="content-area">
                  <div class="app-wrapper">
                    <div class="card card card-default scrollspy border-radius-6 fixed-width">
                      <div class="card-content p-0 pb-1">
                        <div class="collection email-collection">
                          <div class="email-brief-info collection-item animate fadeUp">
                            <a class="list-content" href="javascript:">
                              <div class="list-title-area">
                                <div class="user-media">
                                  <img src="{{$comment->commentedBy->profile_url}}" alt="" class="circle z-depth-2 responsive-img avtar">
                                  <div class="list-title">
                                    {{($comment->commented_by == auth()->user()->id) ? 'You' : $comment->commentedBy->name}}
                                  </div>
                                </div>
                              </div>
                              <div class="list-desc">{{$comment->comment}}</div>
                            </a>
                            <div class="list-right">
                              <div class="list-date">{{$comment->created_at->format('M d, h:i A')}}</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @empty
            @endforelse
          @endif
        </div>
      </div>
    </div>
  </div>
  <!-- users view card data ends -->
  <!-- users view card details start -->
  <div class="card">
    <div class="card-content">
      <div class="card-title">
        <div class="row right">
          <div class="col s12 m12 ">
            <!-- <a class="dropdown-settings btn mb-1 waves-effect waves-light cyan" href="#!" data-target="dropdown1" id="customerListBtn">
              <i class="material-icons hide-on-med-and-up">settings</i>
              <span class="hide-on-small-onl">Order By</span>
              <i class="material-icons right">arrow_drop_down</i></a>
              <ul class="dropdown-content" id="dropdown1" tabindex="0">
                <li tabindex="0"><a class="grey-text text-darken-2 listBtn" data-type="active" href="javascript:" > Latest </a></li>
                <li tabindex="0"><a class="grey-text text-darken-2 listBtn" data-type="inactive" href="javascript:"> Last Commented </a></li>
              </ul>
            </a> -->
            {!! App\Helpers\HtmlHelper::createLinkButton(url($page->route.'/'.$document->id.'/create'), 'Add New Document') !!}
          </div>
        </div>
        <div class="row">
          <div class="col s12 m6 ">
            <h4 class="card-title"> Documents List</h4>
          </div>
        </div>
      </div>
      <div class="row">
        {!! Form::open(['class'=>'ajax-submit', 'id' => Str::camel($page->title).'Form', "enctype" => "multipart/form-data"]) !!}
        {{ csrf_field() }}
        {!! Form::hidden('documentId', $document->id ?? '', ['id' => 'documentId']); !!}
        {{ Form::close() }}
        <div class="col s10" id="subDocumentsDiv"></div>
      </div>
    </div>
  </div>
  <!-- users view card details ends -->
</div>
<!-- users view ends -->
@include('documents.assign_task')
@include('layouts.full-text')
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<!-- <script src="{{asset('admin/vendors/sortable/jquery-sortable-min.js')}}"></script> -->
<!-- <script src="{{asset('admin/vendors/quill/quill.min.js')}}"></script> -->
@endsection

@push('page-scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{asset('admin/js/custom/documents/documents.js')}}"></script>
<script></script>
@endpush