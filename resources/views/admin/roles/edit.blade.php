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
        <li class="breadcrumb-item active">{{__('locale.View')}}</li>
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
                      <div class="row">
                        <div class="col s12 m6 ">
                            <h4 class="card-title">Manage {{ $role->name }} Permissions </h4>
                        </div>
                      </div>
                  </div>
                  <div class="card-content">
                  
                    {!! Form::model($role, ['method' => 'PATCH', 'route' => ['admin.roles.update', $role->id]]) !!}
                      @csrf
                      <div class="row">
                        @include('layouts.success') 
                        @include('layouts.error')
                        <div class="col m6 s12">
                          {!! Form::text('name',  $role->name ?? '') !!} 
                          <!-- <label for="name" class="label-placeholder active">Role name <span class="red-text">*</span></label> -->
                        </div>
                        <div class="col m6 s12">
                          <button class="btn waves-effect waves-light" type="reset" name="reset">Reset <i class="material-icons right">refresh</i></button>
                          <button class="btn cyan waves-effect waves-light" type="submit" name="action" id="submit-btn">Submit <i class="material-icons right">send</i></button>
                        </div>
                        

                        <div class="col s12">                    
                          <ul class="collection with-header">
                            @foreach($permissions as $value)
                              <li class="collection-header"><h6>{{ $value->name }}</h6></li>
                                @php $permission = Spatie\Permission\Models\Permission::where('parent', '=', $value->id)->get();  @endphp
                                @foreach($permission as $row)
                                  <li class="collection-item">
                                    <div>{{ $row->name }}
                                      <a href="#!" class="secondary-content">
                                        @php 
                                          $checked  = '';
                                          $checked  = in_array($row->id, $rolePermissions) ? "checked" : "";
                                        @endphp
                                        <p class=""><label><input type="checkbox" class="payment-types filled-in" name="permission[]" data-type="{{$row->id}}" id="permission{{$row->id}}" {{in_array($row->id, $rolePermissions) ? "checked" : ""}} value="{{$row->id}}"><span></span></label></p>
                                      </a>
                                    </div>
                                  </li>
                                @endforeach

                            @endforeach
                          </ul>
                        </div>
                        
                      </div>
                    {!! Form::close() !!}
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