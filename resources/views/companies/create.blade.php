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
        <li class="breadcrumb-item active">{{__('locale.Create')}}</li>
    </ol>
</div>
@endsection
<div class="seaction">
    <div class="card">
        <div class="card-content">
            <p class="caption mb-0">{{__('messages.Dummy Text')}}</p>
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
                                {!! App\Helpers\HtmlHelper::listLinkButton(url($page->route), __('locale.List').' '.Str::plural(__('locale.'.$page->title))) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6 ">
                                <h4 class="card-title">{{ Str::singular(__('locale.'.$page->title)) ?? ''}} {{__('locale.Form')}}</h4>
                            </div>
                        </div>
                    </div>
                    @include('layouts.error') 
                    @include('layouts.success') 
                    {!! Form::open(['class'=>'ajax-submit','id'=> Str::camel($page->title).'Form']) !!}
                        {{ csrf_field() }}
                        {!! Form::hidden('companyId', $company->id ?? '', ['id' => 'companyId'] ); !!}
                        {!! Form::hidden('pageTitle', Str::camel($page->title), ['id' => 'pageTitle'] ); !!} 
                        {!! Form::hidden('pageRoute', url($page->route), ['id' => 'pageRoute'] ); !!}
                        <div class="row">
                            <div class="input-field col m6 s12">
                                {!! Form::text('name', $company->name ?? '', array('id' => 'name')) !!}
                                <label for="name" class="label-placeholder active"> Name <span class="red-text">*</span></label>
                            </div>
                            <div class="input-field col m6 s12">
                                {!! Form::text('contact_name', $company->contact_name ?? '', array('id' => 'contact_name')) !!}
                                <label for="contact_name" class="label-placeholder active"> Contact Name</label>
                            </div>
                            <div class="input-field col m6 s12">
                                {!! Form::text('email', $company->email ?? '', array('id' => 'email')) !!}
                                <label for="email" class="label-placeholder active"> E-mail <span class="red-text">*</span></label>
                            </div>
                            <div class="input-field col m6 s12">
                                {!! Form::text('contact', $company->contact ?? '', array('id' => 'contact')) !!}
                                <label for="contact" class="label-placeholder active"> Contact Number </label>
                            </div>
                            <div class="input-field col m12 s12">
                                {!! Form::textarea('address', $company->address ?? '',  ['id' => 'address', 'class' => 'materialize-textarea']) !!}
                                <label for="address" class="label-placeholder active"> Address </label>
                            </div>
                            <div class="input-field col m12 s12">
                                {!! Form::textarea('details', $company->details ?? '',  ['id' => 'details', 'class' => 'materialize-textarea']) !!}
                                <label for="details" class="label-placeholder active"> More Details </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                {!! App\Helpers\HtmlHelper::submitButton('Submit', 'formSubmitBtn') !!}
                                {!! App\Helpers\HtmlHelper::resetButton('Reset', 'formResetBtn') !!}
                            </div>
                        </div>
                    {{ Form::close() }}
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
<script src="{{asset('admin/js/custom/company/company.js')}}"></script>
@endpush

