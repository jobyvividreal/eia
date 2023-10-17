@extends('auth.auth_app')

@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('admin/css/pages/forgot.css')}}">
@endsection

@section('content')
<div id="login-page" class="row">  
    <div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 login-card bg-opacity-8">
        <form id="userPasswordForm" name="userPasswordForm" role="form" method="" action="" class="ajax-submit">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="row">
                <div class="input-field col s12"><h5 class="ml-4">Create Password</h5></div>
            </div>

            <div class="card-alert card red lighten-5 print-error-msg" style="display:none"><div class="card-content red-text"><ul></ul></div></div>          
            
            <div class="row margin">
                <div class="input-field col s12">
                <i class="material-icons prefix pt-2">person_outline</i>
                {!! Form::text('email', '', array('placeholder' => 'Email', 'autocomplete' => 'off')) !!}
                <label for="email" class="label-placeholder active"> Enter your email id <span class="red-text">*</span></label>
                </div>
            </div>
            <div class="row margin">
                <div class="input-field col s12">
                <i class="material-icons prefix pt-2">lock_outline</i>
                {!! Form::password('password', array( 'id' => 'password' ,'placeholder' => 'Password')) !!}
                <label for="password" class="label-placeholder active"> Enter your Password <span class="red-text">*</span></label>
                <small class="col-sm-2 ">Password must be 6 to 10 characters which contain at least one numeric digit, one uppercase and one lowercase letter</small>
                </div>
            </div>
            <div class="row margin">
                <div class="input-field col s12">
                <i class="material-icons prefix pt-2">lock_outline</i>
                {!! Form::password('password_confirmation', array('placeholder' => 'Confirm Password')) !!}
                <label for="password" class="label-placeholder active"> Enter Confirm Password <span class="red-text">*</span></label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                <button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" id="submit-btn" type="submit" name="action">Submit </button>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6 m6 l6">
                <p class="margin right-align medium-small"><a href="{{ url('login') }}">Login</a></p>
                </div>
                <div class="input-field col s6 m6 l6">
                
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('page-scripts')
<script src="{{ asset('admin/js/common-script.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript">

if ($("#userPasswordForm").length > 0) {
    var validator = $("#userPasswordForm").validate({ 
        rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                strongPassword: true,
            },
            password_confirmation: {
                equalTo: "#password"
            },
        },
        messages: { 
            email: {
                email: "Please enter a valid email address.",
                required: "Please enter a email address.",
            },
            password: {
                required: "Please enter password",
                minlength: "Passwords must be at least 6 characters in length",
                maxlength: "Length cannot be more than 10 characters",
            },
            password_confirmation: {
                equalTo: "Passwords are not matching",
            },
        },
        submitHandler: function (form) {
          $('#submit-btn').html('Please Wait...');
          $("#submit-btn"). attr("disabled", true);
            id          = $("#user_id").val();
            userId      = "" == id ? "" : "/" + id;
            var forms   = $("#userPasswordForm");
            $.ajax({ url: "{{ url('create-password') }}", type: "POST", processData: false, 
            data: forms.serialize(), dataType: "html",
            }).done(function (a) {              
                $('#submit-btn').html('Submit');
                $("#submit-btn"). attr("disabled", false); 
                var data = JSON.parse(a);
                if(data.flagError == false){
                    showSuccessToaster(data.message);
                    setTimeout(function () { 
                        window.location.href = "{{ url('login')}}";                    
                    }, 2000);

                }else{
                //   showErrorToaster(data.message);
                  printErrorMsg(data.error);
                }
            });
        },
        errorElement : 'div',
    })
}

jQuery.validator.addMethod(
    "strongPassword",
    function (value, element) {
        return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,10}$/gim.test(value);
    },
    "Please enter a strong password"
);

$(".alert-danger").delay(1000).addClass("in").toggle(true).fadeOut(3000);

</script>
@endpush
