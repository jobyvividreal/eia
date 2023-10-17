<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <base href="{{ url('/') }}">
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="keywords" content="@yield('seo_description', '')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="ThemeSelect">

    <link rel="apple-touch-icon" href="{{ asset('admin/images/favicon/apple-touch-icon-152x152.png') }}">
    <link rel="shortcut icon" href="{{ asset('admin/images/favicon.ico') }}"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- BEGIN: VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/vendors/vendors.min.css')}}">
    <!-- END: VENDOR CSS-->

    <!-- BEGIN: Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/themes/horizontal-menu-template/materialize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/themes/horizontal-menu-template/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/layouts/style-horizontal.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/pages/login.css') }}">
    <!-- END: Page Level CSS-->
    
    <link rel=canonical href="{{ url('/') }}"/>
    <title>Login | {{ config('app.name') }} </title>
    @stack('page-css')
</head>
<body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 1-column login-bg blank-page" data-open="click" data-menu="vertical-modern-menu" data-col="1-column">

        <div class="row">
          <div class="col s12">
              <div class="container">
              
              @yield('content')

              </div>
              <div class="content-overlay"></div>


      </div>
  </div>
    <script src="{{asset('admin/js/vendors.min.js')}}"></script>
    <script src="{{asset('admin/vendors/toastr/toastr.min.js')}}"></script>
    @stack('page-scripts')

</body>
</html>
