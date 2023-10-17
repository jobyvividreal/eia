<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <base href="{{ url('/') }}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="@yield('seo_keyword', '')">
    <meta name="keyword" content="@yield('seo_description', '')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="EIA">
    <title>{{ config('app.name') }} @if (!Request::is('/')) | @yield('seo_title', '') @endif</title>
    <link rel="shortcut icon" href="{{ asset('admin/images/favicon.ico') }}"/>
    <link rel="apple-touch-icon" href="{{ asset('admin/images/favicon/apple-touch-icon-152x152.png') }}">
    @include('layouts.general_css')
    
</head>
<body class="horizontal-layout page-header-light horizontal-menu preload-transitions 2-columns " data-open="click" data-menu="horizontal-menu" data-col="2-columns">
    @include('layouts.header')
    {{-- @include('layouts.nav') --}}
    <div id="main" class="">
      <div class="row">
        @include('layouts.breadcrumbs')  
        <div class="col s12">
          <div class="container">
            @yield('content')
          </div>
          <div class="content-overlay"></div>
        </div>
      </div>
    </div>
  <!-- END: Page Main-->
  @include('layouts.footer')
  @include('layouts.general_js')
  @stack('page-scripts')
</body>
</html>
