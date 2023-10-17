@extends('layouts.app')

{{-- page title --}}
@section('seo_title', Str::plural($page->title) ?? '') 
@section('search-title') {{ $page->title ?? ''}} @endsection

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
        <li class="breadcrumb-item"><a href="{{ url('home') }}">{{__('locale.Dashboard')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ url($page->route) }}">{{ Str::plural(__('locale.'.$page->title)) ?? ''}}</a></li>
        <li class="breadcrumb-item active">{{__('locale.List')}}</li>
    </ol>
</div>
@endsection
<div class="section">
  <div class="card">
      <div class="card-content">
          <p class="caption mb-0">Companies that operate Oil and Gas projects in Iraq</p>
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
                        @can('companies-create')
                          {!! App\Helpers\HtmlHelper::createLinkButton(url($page->route.'/create'), __('locale.Create New'). ' ' .Str::singular(__('locale.'.$page->title))) !!}
                        @endcan
                        @can('companies-list')
                          <a class="dropdown-settings btn mb-1 waves-effect waves-light cyan" href="#!" data-target="dropdown1" id="customerListBtn"><i class="material-icons hide-on-med-and-up">settings</i><span class="hide-on-small-onl"> {{__('locale.List'). ' ' . Str::plural(__('locale.'.$page->title))}}</span><i class="material-icons right">arrow_drop_down</i></a>
                          <ul class="dropdown-content" id="dropdown1" tabindex="0">
                            <li tabindex="0"><a class="grey-text text-darken-2 listBtn" data-type="active" href="javascript:" > {{__('locale.Active')}} </a></li>
                            <li tabindex="0"><a class="grey-text text-darken-2 listBtn" data-type="inactive" href="javascript:"> {{__('locale.Inactive')}} </a></li>
                          </ul>
                        @endcan
                      </div>
                    </div>
                    <div class="row">
                      <div class="col s12 m6 ">
                          <h4 class="card-title">{{ Str::singular($page->title) ?? ''}} List</h4>
                      </div>
                    </div>
                    <div class="row">
                        <form id="page-form" name="page-form">
                          {!! Form::hidden('status', '', ['id' => 'status'] ); !!}
                      </form>
                    </div>
                  </div>
                    <div id="view-borderless-table">
                      <div class="row">
                        <div class="col s12">
                          <table id="data-table-company" class="display data-tables" data-url="{{ $page->link.'/lists' }}" data-form="page" data-length="30">
                            <thead>
                              <tr>
                                <th width="20px" data-orderable="false" data-column="DT_RowIndex"> No </th>
                                <th width="" data-orderable="false" data-column="name"> Name </th>
                                <th width="" data-orderable="false" data-column="contact_name"> Contact Name	 </th>
                                <th width="" data-orderable="false" data-column="email"> E-mail </th>
                                <th width="" data-orderable="false" data-column="contact"> Contact </th>
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
@include('companies.manage')
@endsection

{{-- vendor scripts --}}

@push('page-scripts')
<script src="{{asset('admin/js/scripts/data-tables.js')}}"></script>
<script src="{{asset('admin/js/custom/company/company.js')}}"></script>
@endpush

