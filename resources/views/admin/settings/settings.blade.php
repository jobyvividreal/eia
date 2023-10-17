@extends('layouts.admin.app')

{{-- page title --}}
@section('seo_title', Str::plural($page->title) ?? '') 
@section('search-title') {{ $page->title ?? ''}} @endsection


{{-- vendor styles --}}
@section('vendor-style')
@endsection

{{-- page style --}}
@section('page-style')
  <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/pages/page-users.css')}}">
@endsection

@section('content')

@section('breadcrumb')
<div class="col s12 m6 l6"><h5 class="breadcrumbs-title"><span>{{ Str::plural($page->title) ?? ''}}</span></h5></div>
<div class="col s12 m6 l6 right-align-md">
    <ol class="breadcrumbs mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ url($page->route) }}">{{ Str::plural($page->title) ?? ''}}</a></li>
        <li class="breadcrumb-item active">List</li>
    </ol>
</div>
@endsection
<!-- edit profile start -->
<div class="section users-edit">
    <div class="card">
        <div class="card-content">
            <!-- <div class="card-body"> -->
            <ul class="tabs mb-2 row">
                <li class="tab">
                    <a class="display-flex align-items-center active" id="account-tab" href="#account">
                        <i class="material-icons mr-1">person_outline</i><span>Currency</span>
                    </a>
                </li>
                <!-- <li class="tab">
                    <a class="display-flex align-items-center" id="information-tab" href="#information">
                        <i class="material-icons mr-2">settings</i><span>Account</span>
                    </a>
                </li> -->
            </ul>
            <div class="divider mb-3"></div>
            <div class="row">

                <div class="col s12" id="account">                    
                    <!-- users edit account form start -->
                    @include('layouts.error') 
                    @include('layouts.success') 
                    {!! Form::open(['class'=>'ajax-submit', 'id'=> 'currencyForm']) !!}
                        {{ csrf_field() }}
                        {!! Form::hidden('user_id', $user->id ?? '', ['id' => 'user_id'] ); !!}
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="row">
                                    <div class="col s6 input-field">
                                        {!! Form::select('country', $variants->country, $variants->settings->country_id ?? '', ['id' => 'country', 'class' => 'select2 browser-default', 'placeholder'=>'Please select country']) !!}
                                    </div>
                                    <div class="col s6 input-field">
                                        {!! Form::select('currency', $variants->currency, $variants->settings->currency_id ?? '', ['id' => 'currency', 'class' => 'select2 browser-default', 'placeholder'=>'Please select currency']) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        {!! App\Helpers\HtmlHelper::submitButton('Submit', 'commentSubmitBtn') !!}
                                        {!! App\Helpers\HtmlHelper::resetButton('Reset', 'formResetBtn') !!}
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    {{ Form::close() }}
                    <!-- users edit account form ends -->
                </div>
                <div class="col s12" id="information">
                    <!-- users edit Info form start -->
                    <!-- users edit Info form ends -->
                </div>
            </div>
            <!-- </div> -->
        </div>
    </div>
</div>
<!-- users edit ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
@endsection

@push('page-scripts')
<script>
$('#country').select2({ placeholder: "Please select country", allowClear: true});
$('#currency').select2({ placeholder: "Please select currency", allowClear: true});

// $("#country").change(function() {
//     var currencyID = '{{$variants->settings->currency_id}}';
//     $.ajax({ type: 'POST', url: "/common/get-currency", data:{'country':this.value }, dataType: 'json',
//         success: function(data) {
//             var selectTerms = '<option value="">Please select currency</option>';
//             $.each(data.data, function(key, value) {
//               selectTerms += '<option value="' + value.id + '" >' + value.symbol + '</option>';
//             });
//             var select = $('#currency');
//             select.empty().append(selectTerms);
//             $('#currency').empty().trigger("change");
//         }
//     });
// });

$("#currencyForm").submit(function(e) {
    disableBtn("commentSubmitBtn");
    var forms = $("#currencyForm");
    $.ajax({ url: "{{ url($page->route).'/currency' }}", type: 'POST', processData: false, data: forms.serialize(), dataType: "html",
    }).done(function (a) {
        enableBtn("commentSubmitBtn");
        var data = JSON.parse(a);
        if (data.flagError == false) {
          showSuccessToaster(data.message);
        } else {
          showErrorToaster(data.message);
          printErrorMsg(data.error);
        }
    });
});

</script>
@endpush