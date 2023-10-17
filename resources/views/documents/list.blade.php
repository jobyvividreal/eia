@extends('layouts.app')

{{-- page title --}}
@section('seo_title', Str::plural($page->title) ?? '') 
@section('search-title') {{ $page->title ?? ''}} @endsection


{{-- vendor styles --}}
@section('vendor-style')
@endsection

{{-- page style --}}
@section('page-style')
  <!-- <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/custom/custom.css')}}"> -->
@endsection

@section('content')
@section('breadcrumb')
<div class="col s12 m6 l6"><h5 class="breadcrumbs-title"><span>{{ Str::plural($page->title) ?? ''}}</span></h5></div>
<div class="col s12 m6 l6 right-align-md">
  <ol class="breadcrumbs mb-0">
    <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ $page->route }}">{{ Str::plural($page->title) ?? ''}}</a></li>
    <li class="breadcrumb-item active">List</li>
  </ol>
</div>
@endsection
<div class="section">
@include('layouts.error') 
  <!-- Borderless Table -->
  <div class="row">
    <div class="col s12">
        <div id="borderless-table" class="card card-tabs">
            <div class="card-content data-table-container">
                <div class="card-title">
                    <div class="row right">
                      <div class="col s12 m12 ">
                          @can('documents-create')
                  <a class="btn mb-1 waves-effect waves-light cyan" href="javascript:" onclick="proceedToDocument()" name="action">Create New {{Str::singular($page->title)}}<i class="material-icons right">add</i></a>
                @endcan
                          <a class="dropdown-settings btn mb-1 waves-effect waves-light cyan" href="#!" data-target="dropdown1" id="customerListBtn"><i class="material-icons hide-on-med-and-up">settings</i><span class="hide-on-small-onl">List {{Str::plural($page->title)}}</span><i class="material-icons right">arrow_drop_down</i></a>
                          <ul class="dropdown-content" id="dropdown1" tabindex="0">
                            <li tabindex="0"><a class="grey-text text-darken-2 archiveBtn" data-type="active" href="javascript:" > Active </a></li>
                            <li tabindex="0"><a class="grey-text text-darken-2 archiveBtn" data-type="1" href="javascript:"> Archived </a></li>
                          </ul>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col s12 m6 ">
                        <h4 class="card-title">{{ Str::singular($page->title) ?? ''}} List</h4>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="data-table-container">
                      <form id="page-form" name="page-form">
                        {!! Form::hidden('document_status', '', ['id' => 'document_status'] ); !!}
                        {!! Form::hidden('archiveStatus', '0', ['id' => 'archiveStatus'] ); !!}
                        <div class="row">
                          <div class="input-field col m6 s12">
                            {!! Form::select('project_id', $variants->projects, '', ['id' => 'project_id', 'class' => 'select2 browser-default', 'placeholder'=>'Please select a Project']) !!}
                          </div>
                          <div class="input-field col m6 s12">
                            {!! Form::select('eia_id', [], '', ['id' => 'eia_id', 'class' => 'select2 browser-default', 'placeholder'=>'Please select EIA']) !!}
                          </div>
                        </div>
                        <div class="row">
                          <div class="input-field col m6 s12">
                            {!! Form::text('searchTitle', '', array('id' => 'searchTitle', 'placeholder' => 'Search Document ID..', 'class' => 'typeahead autocomplete')) !!}
                          </div>
                          <div class="input-field col m4 s12">
                            <div style="margin-top: 10px;">
                              <button type="button" class="btn mr-2 cyan" id="page-show-result-button" >Show Result</button>
                              <button type="button" class="btn" id="page-filterFormClearButton">Clear Filter </button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                <div id="view-borderless-table">
                  <div class="row">
                    <div class="col s12">
                      <table id="data-table-documents" class="display data-tables" data-url="{{ $page->link }}" data-form="page" data-length="10">
                        <thead>
                          <tr>
                            <th width="20px"  data-orderable="false" data-column="DT_RowIndex"> No </th>
                            <th width="200px" data-orderable="false" data-column="document_number"> Document Number </th>
                            <th width="250px" data-orderable="false" data-column="title"> Title </th>
                            <th width="200px" data-orderable="false"  data-column="date_of_entry"> Date of Creation</th>
                            <th width="250px" data-orderable="false" data-column="status"> Status </th>
                            <th width="300px" data-orderable="false"  data-column="brief_description"> Brief Description </th>
                            <th width="200px" data-orderable="false" data-column="comment"> Remarks/Comments  </th>                            
                            <th width="250px" data-orderable="false" data-column="action"> Action </th> 
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div><!-- START RIGHT SIDEBAR NAV -->
@include('projects.full_name')
@include('layouts.full-text')
@include('documents.manage')
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('admin/vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin/vendors/data-tables/js/dataTables.select.min.js')}}"></script>
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
@endsection

@push('page-scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<!-- typeahead -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<!-- typeahead -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
<script src="{{asset('admin/js/custom/documents/documents.js')}}"></script>
<script>
</script>
@endpush