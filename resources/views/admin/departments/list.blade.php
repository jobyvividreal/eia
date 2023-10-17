@extends('layouts.admin.app')

{{-- page title --}}
@section('seo_title', Str::plural(__('locale.'.$page->title)) ?? '') 
@section('search-title') {{ __('locale.'.$page->title) ?? ''}} @endsection


{{-- vendor styles --}}
@section('vendor-style')

@endsection

{{-- page style --}}
@section('page-style')

@endsection


@section('content')

@section('breadcrumb')
<div class="col s12 m6 l6"><h5 class="breadcrumbs-title"><span>{{ Str::plural(__('locale.'.$page->title)) ?? ''}}</span></h5></div>
<div class="col s12 m6 l6 right-align-md">
    <ol class="breadcrumbs mb-0">
        <li class="breadcrumb-item"><a href="{{ url(ROUTE_PREFIX.'/home') }}">{{__('locale.Dashboard')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ url($page->route) }}">{{ Str::plural(__('locale.'.$page->title)) ?? ''}}</a></li>
        <li class="breadcrumb-item active">{{__('locale.List')}}</li>
    </ol>
</div>
@endsection
<div class="section">
  <div class="card">
      <div class="card-content">
          <p class="caption mb-0">{{__('messages.Dummy Text')}}.</p>
      </div>
  </div>

  <!-- Borderless Table -->
  <div class="row">
      <div class="col s12">
          <div id="borderless-table" class="card card-tabs">
              <div class="card-content data-table-container">
                  <div class="card-title">
                      <div class="row right">
                        <div class="col s12 m12 ">
                            {!! App\Helpers\HtmlHelper::createAjaxButton(__('locale.Create New'). ' ' . Str::plural(__('locale.'.$page->title))) !!}
                        </div>
                      </div>
                      <div class="row">
                        <div class="col s12 m6 ">
                            <h4 class="card-title">{{ Str::singular(__('locale.'.$page->title)) ?? ''}} {{__('locale.List')}}</h4>
                        </div>
                      </div>
                      <div class="row">
                          <form id="page-form" name="page-form">
                        </form>
                      </div>
                  </div>
                  <div id="view-borderless-table">
                    <div class="row">
                      <div class="col s12">
                        <table id="dataTableDepartments" class="display data-tables" data-url="{{ $page->link.'/lists' }}" data-form="page" data-length="10">
                          <thead>
                              <tr>
                                  <th width="20px" data-orderable="false" data-column="DT_RowIndex"> No </th>
                                  <th width="" data-orderable="false" data-column="name"> Name </th>
                                  <th width="100px" data-orderable="false" data-column="status"> Status </th>
                                  <th width="200px" data-orderable="false" data-column="action"> Action </th>
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
@include('admin.departments.manage')
@endsection

{{-- vendor scripts --}}
@section('vendor-script')

@endsection

@push('page-scripts')
<!-- <script src="{{asset('admin/js/scripts/data-tables.js')}}"></script> -->
<script src="{{asset('admin/js/custom/departments/departments.js')}}"></script>
<script>


</script>
@endpush

