@extends('layouts.app')

{{-- page title --}}
@section('seo_title', Str::plural($page->title) ?? '') 
@section('search-title') {{ $page->title ?? ''}} @endsection

{{-- vendor styles --}}
@section('vendor-style')

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
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ url($page->route) }}">{{ Str::plural($page->title) ?? ''}}</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>
</div>
@endsection
<div class="seaction">
    <div class="card">
        <div class="card-content">
            <p class="caption mb-0">Create {{ Str::singular($page->title) ?? ''}} page description.</p>
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
                                <h4 class="card-title">{{ Str::singular($page->title) ?? ''}} Form</h4>
                            </div>
                        </div>
                    </div>
                    @include('layouts.error') 
                    @include('layouts.success') 
                    {!! Form::open(['class'=>'ajax-submit', 'id'=> Str::camel($page->title).'Form']) !!}
                        {{ csrf_field() }}
                        {!! Form::hidden('pageTitle', Str::camel($page->title), ['id' => 'pageTitle'] ); !!} 
                        {!! Form::hidden('pageRoute', url($page->route), ['id' => 'pageRoute'] ); !!}
                        {!! Form::hidden('projectId', $project->id ?? '', ['id' => 'projectId'] ); !!}
                        <div class="row">
                            <div class="input-field col m12 s12">
                                {!! Form::text('name', $project->name ?? '', array('id' => 'name')) !!}
                                <label for="name" class="label-placeholder active"> Title <span class="red-text">*</span></label>
                            </div>
                        </div>


                        <div class="row">
                            <div class="input-field col m12 s12">
                                {!! Form::text('project_code_id', $project->project_code_id ?? '', array('id' => 'project_code_id')) !!}
                                <label for="project_code_id" class="label-placeholder active"> Project ID <span class="red-text">*</span></label>
                            </div>  
                        </div>


                        <div class="row">
                            <div class="input-field col m6 s12">
                                {!! Form::text('dateOfCreated', $project->date_of_created ?? '', array('id' => 'dateOfCreated')) !!}
                                <label for="dateOfCreated" class="label-placeholder active"> Date of Creation <span class="red-text">*</span></label>
                            </div>   
                            <div class="input-field col m6 s12">
                                {!! Form::select('companyId', $variants->companies, $project->company_id ?? '', ['id' => 'companyId', 'class' => 'select2 browser-default', 'placeholder'=>'Please select Company']) !!}
                                <label for="companyId" class="label-placeholder active"> Please select company<span class="red-text">*</span></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m6 s12">
                                {!! Form::select('categoryId', $variants->categories, $project->category_id ?? '', ['id' => 'categoryId', 'class' => 'select2 browser-default', 'placeholder'=>'Please select category']) !!}
                                <label for="categoryId" class="label-placeholder active"> Please select category<span class="red-text">*</span></label>
                            </div>   
                            <div class="input-field col m6 s12">
                                {!! Form::select('projectTypeId', $variants->projectTypes ?? [], $project->project_type ?? '', ['id' => 'projectTypeId', 'class' => 'select2 browser-default', 'placeholder'=>'Please select project type']) !!}
                                <label for="projectTypeId" class="label-placeholder active"> Please select project type<span class="red-text">*</span></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col m6 s12">
                                {!! Form::text('totalBudget', $project->total_budget ?? '', array('id' => 'totalBudget')) !!}
                                <label for="totalBudget" class="label-placeholder active"> Total Budget </label>
                            </div> 
                            <div class="input-field col m6 s12">
                                {!! Form::text('locationName', $project->location_name ?? '', array('id' => 'locationName')) !!}
                                <label for="locationName" class="label-placeholder active"> Location </label>
                            </div>  
                            
                        </div>
                        <div class="row">
                            <div class="input-field col m6 s12">
                                {!! Form::text('latitude', $project->latitude ?? '', array('id' => 'latitude')) !!}
                                <label for="latitude" class="label-placeholder active"> Latitude <span class="red-text">*</span></label>
                            </div>   
                            <div class="input-field col m6 s12">
                                {!! Form::text('longitude', $project->longitude ?? '', array('id' => 'longitude')) !!}
                                <label for="longitude" class="label-placeholder active"> Longitude <span class="red-text">*</span></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                {!! App\Helpers\HtmlHelper::submitButton('Submit', 'formSubmitButton') !!}
                                {!! App\Helpers\HtmlHelper::resetButton('Reset', 'formResetButton') !!}
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{asset('admin/js/custom/project/project.js')}}"></script>
<script>

</script>
@endpush

