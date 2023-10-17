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
@endsection

@section('content')

@section('breadcrumb')
<div class="col s12 m6 l6"><h5 class="breadcrumbs-title"><span>{{ Str::plural($page->title) ?? ''}}</span></h5></div>
<div class="col s12 m6 l6 right-align-md">
  <ol class="breadcrumbs mb-0">
    <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ url('projects') }}">Projects</a></li>
    <li class="breadcrumb-item"><a href="{{ url($page->projectRoute) }}">{{ Str::limit(strip_tags($permit->eia->project->name), 20) ?? 'Show' }}</a></li>
    <li class="breadcrumb-item active">{{ Str::limit(strip_tags($permit->permit_code), 20) ?? 'Permit' }}</li>
  </ol>
</div>
@endsection
<!-- users view start -->
<div class="section">
  <!-- users view media object start -->
  <div class="card-panel">
    <div class="row">
      <div class="col s12 m12">
        <div class="display-flex media">
          <div class="media-body">
            <h6 class="media-heading"><span>Project Title: </span><span class="users-view-name">{{ $permit->eia->project->name ?? ''}} </span></h6>
            <h6 class="media-heading"><span>Project ID: </span><span class="users-view-name">{{ $permit->eia->project->project_code_id ?? ''}} </span></h6>
            <h6 class="media-heading"><span>Permit ID: </span><span class="users-view-name">{{ $permit->permit_code ?? ''}} </span></h6>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- users view media object ends -->
  <!-- users view card data start -->
  
      <div id="Form-advance" class="card card card-default scrollspy">
        <div class="card-content">
          <div class="card-title">
            <div class="row right">
              <div class="col s12 m12 ">
                {!! App\Helpers\HtmlHelper::listLinkButton($page->route, 'Back') !!}
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
          {!! Form::open(['class'=>'ajax-submit','id'=> Str::camel($page->title).'Form']) !!}
              {{ csrf_field() }}
              {!! Form::hidden('permitID', $permit->id ?? '', ['id' => 'permitID'] ); !!}
              {!! Form::hidden('pageRoute', $page->route, ['id' => 'pageRoute'] ); !!}
              <div class="row">
                <div class="input-field col m6 s12">
                  {!! Form::text('project_code_id', $permit->eia->project->project_code_id ?? '', array('id' => 'project_code_id', 'disabled' => 'disabled')) !!}
                  <label for="codeId" class="label-placeholder active"> Project Id <span class="red-text">*</span></label>
                </div>
                <div class="input-field col m6 s12">
                  {!! Form::text('permit_code', $permit->permit_code ?? '', array('id' => 'permit_code')) !!}
                  <label for="permit_code" class="label-placeholder active"> Permit Id <span class="red-text">*</span></label>
                </div>
              </div>
              <div class="row">
                <div class="input-field col m6 s12">
                  {!! Form::select('status', $variants->documentStatuses, $permit->status ?? '', ['id' => 'status', 'class' => 'select2 browser-default', 'placeholder'=>'Please select status']) !!}
                  <label for="status" class="label-placeholder active"> Please select status<span class="red-text">*</span></label>
                </div>
                <div class="input-field col m6 s12">
                  {!! Form::text('date_of_approval', $permit->date_of_approval ?? '', array('id' => 'date_of_approval')) !!}
                  <label for="date_of_approval" class="label-placeholder active"> Date of Approval <span class="red-text">*</span></label>
                </div> 
              </div>
              <div class="row">  
                <div class="input-field col m6 s12">
                  {!! Form::text('certificate_number', $permit->certificate_number ?? '', array('id' => 'certificate_number')) !!}
                  <label for="certificate_number" class="label-placeholder active"> Certificate Number </label>    
                </div>
              </div>
              <div class="row">  
                <div class="input-field col m12 s12">
                  {!! Form::textarea('comment', $permit->comment ?? '',  ['id' => 'comment', 'class' => 'materialize-textarea']) !!}
                  <label for="comment" class="label-placeholder active"> Remarks / Comments </label>    
                </div>
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
 
  <!-- users view card data ends -->
  <!-- users view card details start -->
  <div class="card">
    <div class="card-content">
      <div class="card-title">
        <div class="row right">
          <div class="col s12 m12">
            {!! App\Helpers\HtmlHelper::createLinkButton(url($page->route.'/'.$permit->id.'/documents/create'), 'Add New Document') !!}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col s12 m6 "><h4 class="card-title">Permit Document Lists</h4></div>
        <div class="col s12">
          <table id="data-table-permit-documents" class="display data-tables" data-url="{{ $page->route.'/documents/list/'.$permit->id }}" data-form="page" data-length="10">
            <thead>
              <tr>
                <th width="20px" data-orderable="false" data-column="DT_RowIndex"> No </th>
                <th width="250px" data-orderable="false" data-column="document_number"> Document Number </th>
                <th width="250px" data-orderable="false" data-column="title"> Title </th>
                <th width="200px" data-orderable="true" data-column="date_of_entry"> Date of Creation </th>
                <th width="250px" data-orderable="false" data-column="status"> Status </th>
                <th width="300px" data-orderable="true" data-column="brief_description"> Brief Description </th>
                <th width="200px" data-orderable="false" data-column="comment"> Remarks/Comments </th>                            
                <th width="250px" data-orderable="false" data-column="action"> Action </th>   
              </tr>
            </thead>
          </table>
        </div>
       </div>
    </div>
  </div>
  <!-- users view card details ends -->
</div>
<!-- users view ends -->
@include('layouts.full-text')
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
@endsection

@push('page-scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{asset('admin/js/custom/permits/permits.js')}}"></script>
<script></script>
@endpush