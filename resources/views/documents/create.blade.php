@extends('layouts.app')

{{-- page title --}}
@section('seo_title', Str::plural($page->title) ?? '') 
@section('search-title') {{ $page->title ?? ''}} @endsection

{{-- vendor styles --}}
@section('vendor-style')

@endsection

{{-- page style --}}
@section('page-style')
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.css">
@endsection


@section('content')

@section('breadcrumb')
<div class="col s12 m6 l6"><h5 class="breadcrumbs-title"><span>{{ Str::plural($page->title) ?? ''}}</span></h5></div>
<div class="col s12 m6 l6 right-align-md">
    <ol class="breadcrumbs mb-0">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>        
        <li class="breadcrumb-item"><a href="{{ url('projects') }}">Projects</a></li>        
        <li class="breadcrumb-item"><a href="{{ url($page->projectRoute) }}">{{ Str::limit(strip_tags($eia->project->name), 20) ?? 'Show' }}</a></li>
        <li class="breadcrumb-item"><a href="{{ url($page->eiaRoute) }}">{{ Str::limit(strip_tags($eia->code_id), 20) ?? 'Show' }}</a></li>
        <li class="breadcrumb-item active">{{ Str::plural($page->title) ?? ''}}</li>
    </ol>
</div>
@endsection
<div class="seaction">
    
  <!-- users view media object ends -->
    <!-- <div class="card">
        <div class="card-content">
            <p class="caption mb-0">Create {{ $page->title ?? ''}} page description.</p>
        </div>
    </div> -->
    <div class="card-panel">
        <div class="row">
            <div class="col s12 m12">
                <div class="display-flex media">
                    <div class="media-body">
                        <h6 class="media-heading"><span>Project: </span><span class="users-view-name">{{ $eia->project->name ?? ''}} </span></h6>
                        <h5 class="media-heading"><span>EIA Id: </span><span class="users-view-name">{{ $eia->code_id ?? ''}} </span></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Basic Form-->
    <!-- jQuery Plugin Initialization -->
    <div class="row">
        <!-- Form Advance -->
        <div class="col s12 m12 l12">
            <div id="Form-advance" class="card card card-default scrollspy">
                <div class="card-content">
                    <div class="card-title">
                        <div class="row right">
                            <div class="col s12 m12 ">
                                {!! App\Helpers\HtmlHelper::listLinkButton(url()->previous(), 'Back') !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6 ">
                                <h4 class="card-title"> {{ $page->title ?? ''}} Form</h4>
                            </div>
                        </div>
                    </div>
                    @include('layouts.error') 
                    @include('layouts.success') 
                    {!! Form::open(['class'=>'ajax-submit','id'=> Str::camel($page->title).'Form', "enctype" => "multipart/form-data"]) !!}
                        {{ csrf_field() }}
                        {!! Form::hidden('pageTitle', Str::camel($page->title), ['id' => 'pageTitle']); !!} 
                        {!! Form::hidden('pageRoute', $page->route, ['id' => 'pageRoute'] ); !!}
                        {!! Form::hidden('eiaRoute', $page->eiaRoute, ['id' => 'eiaRoute'] ); !!}
                        {!! Form::hidden('projectId', $eia->project->id ?? '', ['id' => 'projectId']); !!}
                        {!! Form::hidden('eiaId', $eia->id ?? '', ['id' => 'eiaId'] ); !!}
                        {!! Form::hidden('documentId', $document->id ?? '', ['id' => 'documentId']); !!}
                        {!! Form::hidden('FileUploadRoute', url('documents/file/upload'), ['id' => 'FileUploadRoute'] ); !!}
                        {!! Form::hidden('FileListRoute', route('documents.file.list'), ['id' => 'FileListRoute'] ); !!}
                        {!! Form::hidden('FileRemoveRoute', route('documents.file.remove'), ['id' => 'FileRemoveRoute'] ); !!}
                        {!! Form::hidden('documentFile', '', ['id' => 'documentFile'] ); !!}
                        <div class="row">
                            <div class="input-field col m6 s12">
                                {!! Form::text('documentNumber', $document->document_number ?? '', array('id' => 'documentNumber')) !!}
                                <label for="documentNumber" class="label-placeholder active"> Document Number <span class="red-text">*</span></label>
                            </div>
                            <div class="input-field col m6 s12">
                                {!! Form::text('dateOfEntry', $document->date_of_entry ?? '', array('id' => 'dateOfEntry')) !!}
                                <label for="dateOfEntry" class="label-placeholder active"> Date of Entry <span class="red-text">*</span></label>
                            </div>   
                        </div>
                        <div class="row">
                            <div class="input-field col m6 s12">
                                {!! Form::text('title', $document->title ?? '', array('id' => 'title')) !!}
                                <label for="title" class="label-placeholder active"> Title of Document <span class="red-text">*</span></label>
                            </div>
                            <div class="input-field col m3 s12">
                                {!! Form::select('stage', $variants->stages, $document->stage_id ?? '', ['id' => 'stage', 'class' => 'select2 browser-default', 'placeholder'=>'Please select Stage']) !!}
                                <label for="stage" class="label-placeholder active"> Please select stage<span class="red-text">*</span></label>
                            </div>
                            <div class="input-field col m3 s12">
                                {!! Form::select('status', $variants->documentStatuses, $document->status ?? '', ['id' => 'status', 'class' => 'select2 browser-default', 'placeholder'=>'Please select Status']) !!}
                                <label for="status" class="label-placeholder active"> Please select status<span class="red-text">*</span></label>
                            </div>
                        </div>
                        <div class="row">  
                            <div class="input-field col m12 s12">
                                {!! Form::textarea('briefDescription', $document->brief_description ?? '',  ['id' => 'briefDescription', 'class' => 'materialize-textarea']) !!}
                                <label for="briefDescription" class="label-placeholder active"> Brief Description <span class="red-text">*</span></label>    
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m6 s12">
                                {!! Form::text('uploadedBy', $user->name ?? '', array('id' => 'uploadedBy', 'disabled' => 'disabled')) !!}
                                <label for="title" class="label-placeholder active"> Uploaded by <span class="red-text">*</span></label>
                            </div>   
                            
                        </div>
                        <div class="row">  
                            <div class="input-field col m12 s12">
                                {!! Form::textarea('comment', $document->comment ?? '',  ['id' => 'comment', 'class' => 'materialize-textarea']) !!}
                                <label for="comment" class="label-placeholder active"> Remarks / Comments <span class="red-text">*</span> </label>    
                            </div>
                        </div>
                            <div class="row" style="@if($page->dropzoneActive == false) display:none;  @endif">  
                                <div class="input-field col m12 s12">
                                    <div class="dropzone" id="document-dropzone"></div>   
                                </div>
                                <label for="dropzone" class="label-placeholder active"> Maximum:250 MB | Document Format: jpeg, jpg, png, pdf, doc, docx, xls, xlsx </label>
                                <div id="file-error" class="error red-text"></div>
                            </div>
                            
                        <div class="row">
                            <div class="input-field col s12">
                                {!! App\Helpers\HtmlHelper::submitButton('Submit', 'formSubmitButton') !!}
                                {!! App\Helpers\HtmlHelper::resetButton('Reset', 'formResetButton') !!}
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div><!-- START RIGHT SIDEBAR NAV -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')

@endsection

@push('page-scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="{{asset('admin/js/custom/documents/documents.js')}}"></script>
<script>

</script>
@endpush