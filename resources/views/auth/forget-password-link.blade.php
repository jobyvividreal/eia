@extends('auth.auth_app')

@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('admin/css/pages/forgot.css')}}">
@endsection

@section('content')
    <div id="login-page" class="row">
        
        <div class="col s12 m6  card-panel border-radius-6 login-card ">
            <form method="POST" action="{{ route('reset.password.post') }}" id="resetPasswordForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="row">
                    <div class="input-field col s12">
                    <h5 class="ml-4">Reset Password</h5>
                    @include('layouts.success')
                    @include('layouts.error')

                    
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">person_outline</i>
                        <input id="email_address" type="email"  name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        <label for="email" class="center-align">Email</label>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">lock</i>
                        <input id="password" type="password"  name="password" value="" required autofocus>
                        <label for="password" class="center-align">Password</label>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix pt-2">lock</i>
                        <input id="password_confirmation" type="password" name="password_confirmation" value="" required autofocus>
                        <label for="password_confirmation" class="center-align"> Confirm Password</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                    <button type="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12 mb-1" id="submit-btn"> Reset Password </button>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6 m6 l6">
                    <p class="margin medium-small"><a href="{{url('login')}}">Back to Login</a></p>
                    </div>
                    <div class="input-field col s6 m6 l6">
                    <!-- <p class="margin right-align medium-small"><a href="">Register</a></p> -->
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('page-scripts')
<script type="text/javascript">
    $("#submit-btn").on("click", function(event){
        event.preventDefault();
        $('#submit-btn').html('Please Wait...');
        $("#submit-btn"). attr("disabled", true);
        $( "#resetPasswordForm" ).submit();
    });
    $(".card-alert .close").click(function(){$(this).closest(".card-alert").fadeOut("slow")});
</script>
@endpush


 
