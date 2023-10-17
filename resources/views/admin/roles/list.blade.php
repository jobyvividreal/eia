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
<div class="col s12 m6 l6"><h5 class="breadcrumbs-title"><span>{{ Str::plural(__('locale.Roles')) ?? ''}}</span></h5></div>
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
                <!-- {!! App\Helpers\HtmlHelper::createLinkButton(url($page->route.'/create'), __('locale.Create New'). ' ' .Str::singular(__('locale.'.__('locale.'.$page->title))), ) !!} -->
              </div>
            </div>
            <div class="row">
              <div class="col s12 m6 ">
                <h4 class="card-title">{{ Str::singular(__('locale.'.$page->title)) ?? ''}} List</h4>
              </div>
            </div>
          </div>
          <div id="view-borderless-table">
            <div class="row">
              <div class="col s12">
                @include('layouts.success') 
                @include('layouts.error')
                <table class="display">
                  <thead>
                    <tr>
                      <th width="20px" data-orderable="false" data-column="DT_RowIndex"> No </th>
                      <th width="200px" data-orderable="false" data-column="name"> Name </th>
                      <th width="150px" data-orderable="false" data-column="action"> Action </th>
                    </tr>
                  </thead>
                  @foreach ($roles as $key => $role)
                    <tr>
                      <td>{{ $loop->index+1 }}</td>
                      <td>{{ $role->name }}</td>
                      <td> 
                        <!-- <a href="{{ url(ROUTE_PREFIX.'/roles/'.$role->id) }}" class="btn mr-2 blue tooltipped" data-tooltip="View details"><i class="material-icons">visibility</i></a> -->
                        <a href="{{ url(ROUTE_PREFIX.'/roles/'.$role->id.'/edit') }}" class="btn mr-2 orange tooltipped" data-tooltip="Manage Permissions"><i class="material-icons">vpn_key</i></a>
                        @can('role-delete')
                          {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id], 'name'=>'roleForm', 'style'=>'display:inline']) !!}
                            <!-- <a href="javascript:void(0);" id="{{$role->id}}" class="btn btn-sm btn-icon mr-2 role-delete-btn" title="Delete Role"><i class="material-icons">cancel</i> </a> -->
                          {!! Form::close() !!}
                        @endcan
                      </td>
                    </tr>
                  @endforeach
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
</script>
@endpush