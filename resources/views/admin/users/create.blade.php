@extends('layouts.admin.app')

{{-- page title --}}
@section('seo_title', Str::plural($page->title) ?? '') 
@section('search-title') {{ $page->title ?? ''}} @endsection


{{-- vendor styles --}}
@section('vendor-style')
  <!-- <link rel="stylesheet" type="text/css" href="{{asset('admin/vendors/data-tables/css/select.dataTables.min.css')}}"> -->
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
        <li class="breadcrumb-item"><a href="{{ url(ROUTE_PREFIX.'/home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ url($page->route) }}">{{ Str::plural($page->title) ?? ''}}</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>
</div>
@endsection
<div class="seaction">
    <div class="card">
        <div class="card-content">
            <p class="caption mb-0">Create User page description.</p>
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
                                {!! App\Helpers\HtmlHelper::listLinkButton(url($page->route), 'List ' . Str::plural($page->title)  ) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6 ">
                                <h4 class="card-title">{{ Str::singular($page->title) ?? ''}} List</h4>
                            </div>
                        </div>
                    </div>
                    @include('layouts.error') 
                    @include('layouts.success') 
                    {!! Form::open(['class'=>'ajax-submit','id'=> Str::camel($page->title).'Form']) !!}
                        {{ csrf_field() }}
                        {!! Form::hidden('user_id', $user->id ?? '', ['id' => 'user_id'] ); !!}
                        <div class="row">
                            <div class="input-field col m6 s12">
                                {!! Form::select('designationId', $variants->designations, $user->designation_id ?? '', ['id' => 'designationId', 'class' => 'select2 browser-default', 'placeholder'=>'Please select designation']) !!}
                            </div>
                            <div class="input-field col m6 s12">
                                {!! Form::select('departmentId', $variants->departments, $user->department_id ?? '', ['id' => 'departmentId', 'class' => 'select2 browser-default', 'placeholder'=>'Please select department']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m6 s12">
                                {!! Form::text('name', $user->name ?? '', array('id' => 'name')) !!}
                                <label for="name" class="label-placeholder active"> First Name <span class="red-text">*</span></label>
                            </div>
                            <div class="input-field col m6 s12">
                                {!! Form::text('last_name', $user->last_name ?? '', array('id' => 'last_name')) !!}
                                <label for="last_name" class="label-placeholder active"> Last Name </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m6 s12">
                                {!! Form::text('email', $user->email ?? '', array('id' => 'email')) !!}
                                <label for="email" class="label-placeholder active"> E-mail <span class="red-text">*</span></label>
                            </div>
                            <div class="input-field col m2 s12">
                                {!! Form::select('phone_code', $variants->phonecode, $user->phone_code ?? '', ['id' => 'phone_code', 'class' => 'select2 browser-default', 'placeholder'=>'Please select phone code']) !!}
                            </div>
                            <div class="input-field col m4 s12">
                                {!! Form::text('mobile', $user->mobile ?? '', array('id' => 'mobile')) !!}
                                <label for="mobile" class="label-placeholder active"> Mobile </label>
                            </div>
                        </div>
                        <div class="row">
                            <!--<div class="input-field col m6 s12">-->
                            <!--    <input name="dob" id="dob" type="text" value="{{ $user->dob ?? ''}}">-->
                            <!--    <label for="dob">DOB</label>-->
                            <!--</div>-->

                            <div class="input-field col m6 s12">
                                {!! Form::select('roles[]', $variants->roles , $assigned_roles ?? [] , ['id' => 'roles' ,'class' => 'select2 browser-default', 'multiple' => 'multiple' ]) !!}
                                <label for="roles" class="label-placeholder active">Role <span class="red-text">*</span></label>
                            </div>


                        </div>
                        <div class="row">
                            <!-- Design Issue when Right align
                                <div class="col s12 display-flex justify-content-end mt-3">
                                <button type="submit" class="btn indigo" id="submit-btn"> Save changes</button>
                                <button type="button" class="btn btn-light" id="form-reset-btn">Reset</button>
                            </div> -->
                            <div class="input-field col s12">
                                {!! App\Helpers\HtmlHelper::submitButton('Submit', 'submit-btn') !!}
                                {!! App\Helpers\HtmlHelper::resetButton('Reset', 'form-reset-btn') !!}
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div><!-- START RIGHT SIDEBAR NAV -->
@endsection

@section('page-action')
  @can('billing-create')
    <a href="{{ url(ROUTE_PREFIX.'/'.$page->route.'/create/') }}" class="btn waves-effect waves-light cyan breadcrumbs-btn right" type="submit" name="action">Add<i class="material-icons right">add</i></a>
  @endcan 
@endsection

{{-- vendor scripts --}}
@section('vendor-script')

@endsection

@push('page-scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- <script src="{{asset('admin/js/custom/users/users.js')}}"></script> -->
<script>
$('#phone_code').select2({ placeholder: "Please select phone code", allowClear: true});
$('#roles').select2({ placeholder: "Please select role", allowClear: true });
$('#designationId').select2({ placeholder: "Please select designation", allowClear: true });
$('#departmentId').select2({ placeholder: "Please select department", allowClear: true });

$('input[name="dob"]').daterangepicker({
  singleDatePicker: true,
  showDropdowns: true,
  minYear: 1901,
  drops: "up",
  maxYear: parseInt(moment().format('YYYY'),10),
  autoApply: true,
  locale: { format: 'YYYY-MM-DD'},
});

if ($("#{{Str::camel($page->title)}}Form").length > 0) {
  var validator = $("#{{Str::camel($page->title)}}Form").validate({ 
    rules: {
        name: { 
            required: true, 
            maxlength: 200
        },
        mobile: { 
            minlength:3, 
            maxlength:15, 
            mobileFormat:true, // Needs confirmation from Sukhesh regarding number format
            remote: { url: "{{ url(ROUTE_PREFIX.'/common/is-unique-mobile') }}", type: "POST",
                data: {
                    user_id: function () { return $('#user_id').val(); }
                }
            },
        },
        email: { 
            required: true, 
            email: true, 
            emailFormat:true,
            remote: { url: "{{ url(ROUTE_PREFIX.'/common/is-unique-email') }}", type: "POST",
                data: {
                    user_id: function () { return $('#user_id').val(); }
                }
            },
        },
        "roles[]": {
            required: true,
        },
    },
    messages: { 
        name: {
            required: "Please enter First name",
            maxlength: "Length cannot be more than 200 characters",
        },
        mobile: {
            maxlength: "Length cannot be more than 15 numbers",
            minlength: "Length must be 3 numbers",
            remote: "The Mobile has already been taken"
        },
        email: {
            required: "Please enter E-mail",
            email: "Please enter a valid E-mail address",
            emailFormat: "Please enter a valid E-mail address",
            remote: "The E-mail has already been taken"
        },
        "roles[]": {
            required: "Please choose roles",
        },
    },
    submitHandler: function (form) {
      disableBtn("submit-btn");
      id            = $("#user_id").val();
      user_id       = "" == id ? "" : "/" + id;
      formMethod    = "" == id ? "POST" : "PUT";
      var forms = $("#{{Str::camel($page->title)}}Form");
      $.ajax({ url: "{{ url($page->route) }}" + user_id, type: formMethod, processData: false, 
      data: forms.serialize(), dataType: "html",
      }).done(function (a) {
        enableBtn("submit-btn");
        var data = JSON.parse(a);
        if (data.flagError == false) {
          showSuccessToaster(data.message);
          setTimeout(function () { 
            window.location.href = "{{ url($page->route) }}";                
          }, 2000);
        } else {
          showErrorToaster(data.message);
          printErrorMsg(data.error);
        }
      });
    },
    errorPlacement: function(error, element) {
        if (element.is("select")) {
            error.insertAfter(element.next('.select2'));
        }else {
            error.insertAfter(element);
        }
    },
    errorElement : 'div',
  })
}

jQuery.validator.addMethod("emailFormat", function (value, element) {
    return this.optional(element) || /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm.test(value);
}, "Please enter a valid email address");  

jQuery.validator.addMethod("mobileFormat", function (value, element) {
    return this.optional(element) || /^([0-9\s\-\+\(\)]*)$/igm.test(value);
}, "Please enter a valid mobile number");  

$("#form-reset-btn").click(function (){
    validator.resetForm();
    $("#{{Str::camel($page->title)}}Form").find("input[type=text]").val("");
    $("#phone_code").val('').trigger('change');
    $("#roles").val('').trigger('change');
});

</script>
@endpush

