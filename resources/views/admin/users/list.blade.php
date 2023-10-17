@extends('layouts.admin.app')

{{-- page title --}}
@section('seo_title', Str::plural(__('locale.Users')) ?? '') 
@section('search-title') {{ __('locale.Users') ?? ''}} @endsection


{{-- vendor styles --}}
@section('vendor-style')

@endsection

{{-- page style --}}
@section('page-style')

@endsection


@section('content')

@section('breadcrumb')
<div class="col s12 m6 l6"><h5 class="breadcrumbs-title"><span>{{ Str::plural(__('locale.Users')) ?? ''}}</span></h5></div>
<div class="col s12 m6 l6 right-align-md">
    <ol class="breadcrumbs mb-0">
        <li class="breadcrumb-item"><a href="{{ url(ROUTE_PREFIX.'/dashboard') }}">{{__('locale.Dashboard')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ url($page->route) }}">{{ Str::plural(__('locale.Users')) ?? ''}}</a></li>
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
                            {!! App\Helpers\HtmlHelper::createLinkButton(url($page->route.'/create'), __('locale.Create New'). ' ' .Str::singular(__('locale.'.__('locale.'.$page->title))), ) !!}
                            <a class="dropdown-settings btn mb-1 waves-effect waves-light cyan" href="#!" data-target="dropdown1" id="customerListBtn"><i class="material-icons hide-on-med-and-up">settings</i><span class="hide-on-small-onl"> {{__('locale.List'). ' ' . Str::plural(__('locale.'.$page->title))}}</span><i class="material-icons right">arrow_drop_down</i></a>
                            <ul class="dropdown-content" id="dropdown1" tabindex="0">
                              <li tabindex="0"><a class="grey-text text-darken-2 listBtn active" data-type="active" href="javascript:" > {{__('locale.Active')}} </a></li>
                              <li tabindex="0"><a class="grey-text text-darken-2 listBtn" data-type="disabled" href="javascript:"> {{__('locale.Inactive')}} </a></li>
                            </ul>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col s12 m6 ">
                            <h4 class="card-title">{{ Str::singular(__('locale.'.$page->title)) ?? ''}} List</h4>
                        </div>
                      </div>
                      <div class="row">
                          <form id="page-form" name="page-form">
                            {!! Form::hidden('user_type', '', ['id' => 'user_type'] ); !!}
                            {!! Form::hidden('status', '', ['id' => 'status'] ); !!}
                            <div class="col s12 m3">
                                <label for="users-list-verified">User Details</label>
                                <div class="input-field">
                                  {!! Form::text('name', '' , ['id' => 'name']) !!}
                                  <label for="name" class="label-placeholder active">User Name, Email or Mobile </label>
                                </div>
                            </div>
                            <div class="col s12 m3">
                                <label for="users-list-role">Role</label>
                                <div class="input-field" style="margin-top:8px">
                                  {!! Form::select('roles[]', $variants->roles , [], ['id' => 'roles' ,'class' => 'select2 browser-default form-control', 'multiple' => 'multiple' ]) !!}
                                </div>
                            </div>
                            
                            <div class="col s12 m4 display-flex show-btn">
                              <div class="col s12 ">
                                <button type="button" class="btn btn-block indigo waves-effect waves-light" id="page-show-result-button" style="margin-top: 60px;">Show Result</button>
                              </div>
                              <div class="col s12 ">
                                <button type="button" class="btn btn-block indigo waves-effect waves-light" id="page-clear-button" style="margin-top: 60px;">Clear</button>
                              </div>
                            </div>
                        </form>
                      </div>
                  </div>
                  <div id="view-borderless-table">
                    <div class="row">
                      <div class="col s12">
                        <table id="data-table-billing" class="display data-tables" data-url="{{ $page->link.'/lists' }}" data-form="page" data-length="10">
                          <thead>
                              <tr>
                                  <th width="20px" data-orderable="false" data-column="DT_RowIndex"> No </th>
                                  <th width="200px" data-orderable="false" data-column="name"> Name </th>
                                  <th width="200px" data-orderable="false" data-column="email"> E-mail </th>
                                  <th width="150px" data-orderable="false" data-column="mobile"> Mobile </th>
                                  <th width="250px" data-orderable="false" data-column="role"> Roles </th>
                                  <th width="100px" data-orderable="false" data-column="status"> Status </th>
                                  <th width="150px" data-orderable="false" data-column="action"> Action </th>
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
@endsection

{{-- vendor scripts --}}
@section('vendor-script')

@endsection

@push('page-scripts')
<script src="{{asset('admin/js/scripts/data-tables.js')}}"></script>
<script>
$('#roles').select2({ placeholder: "Please choose roles", allowClear: true });

</script>
@endpush