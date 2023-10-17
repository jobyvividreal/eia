@extends('layouts.app')

{{-- page title --}}
@section('seo_title', Str::plural($page->title) ?? '') 
@section('search-title') {{ $page->title ?? ''}} @endsection

{{-- vendor styles --}}
@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('admin/vendors/flag-icon/css/flag-icon.min.css')}}">
  <!-- <link rel="stylesheet" type="text/css" href="{{asset('admin/vendors/data-tables/css/jquery.dataTables.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('admin/vendors/data-tables/css/select.dataTables.min.css')}}"> -->
@endsection

{{-- page style --}}
@section('page-style')
  <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/custom/custom.css')}}">
@endsection

@section('content')

@section('breadcrumb')
<div class="col s12 m6 l6"><h5 class="breadcrumbs-title"><span>{{ $page->title ?? ''}}</span></h5></div>
<div class="col s12 m6 l6 right-align-md">
  <ol class="breadcrumbs mb-0">
    <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ url($page->route) }}">{{ Str::plural($page->title) ?? ''}}</a></li>
    <li class="breadcrumb-item active">List</li>
  </ol>
</div>
@endsection
<div class="section">
  <div class="card">
    <div class="card-content">
      <p class="caption mb-0">All approved EIAs and related documents for Oil and Gas projects in Iraq. </p>
    </div>
  </div>
  <!-- Borderless Table -->
  <div class="row">
    <div class="col s12">
      <div id="borderless-table" class="card card-tabs">
        <div class="card-content data-table-container">
          <!-- <div class="card-title"> -->
          <div class="row right">
            <div class="col s12 m12 ">
                {!! App\Helpers\HtmlHelper::listLinkButton(url($page->route), 'List') !!}
            </div>
          </div>
          <div class="row">
            <div class="col s12 m6 "><h4 class="card-title">{{ $page->title ?? ''}} List</h4></div>
          </div>
          <div class="row">
            <div class="card-content data-table-container">
              <form id="page-form" name="page-form">

              </form>
            </div>
          </div>
          <!-- </div> -->
          <div id="view-borderless-table">
            <div class="row">
              <div class="col s12">
                <table id="data-table-permit-documents" class="display data-tables" data-url="{{ $page->route }}" data-form="page" data-length="10">
                  <thead>
                    <tr>
                      <th width="20px" data-orderable="false" data-column="DT_RowIndex"> No </th>
                      <th width="300px" data-orderable="false" data-column="permit_code">Permit ID</th>
                      <th width="150px" data-orderable="false" data-column="project_id">Project ID</th> 
                      <th width="150px" data-orderable="false" data-column="date_of_approval"> Date of Approval </th>
                      <th width="200px" data-orderable="true" data-column="certificate_number"> Environmental Approval/Certificate Number </th>
                      <th width="250px" data-orderable="false" data-column="status"> Status of the Permit</th>
                      <th width="200px" data-orderable="true" data-column="comment"> Remarks/Comments </th>
                                
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
<script src="{{asset('admin/js/custom/permits/permits.js')}}"></script>
@endpush

