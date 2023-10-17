@extends('layouts.app')

{{-- page title --}}
@section('seo_title', Str::plural($page->title) ?? '') 
@section('search-title') {{ $page->title ?? ''}} @endsection

{{-- vendor styles --}}
@section('vendor-style')

@endsection

{{-- page style --}}
@section('page-style')
  <link rel="stylesheet" type="text/css" href="{{asset('admin/css/pages/page-users.css')}}">
  <style>
    .share-modal-section{
      cursor: pointer;
    }
    .download-section{
      cursor: pointer;
    }
    .slider-section input{
      opacity: 1 !important;
      z-index: 9 !important;
      width: 50%;
    }    
    #loder {
      position: absolute;
      top: 165%;
      width: 97.5%;
      background: #000000d4;
      height: 71%;
      left: 50%;
      display: flex;
      -webkit-transform: translate(-50%, -50%);
      -moz-transform: translate(-50%, -50%);
      z-index: 99;
      transform: translate(-50%, -50%);
      justify-content: center;
      align-items: center;
    }
    #loder span {
      width: 12px;
      height: 17px;
      background: #12a4d5;
      margin: 0px 2px;
      display: inline-block;
      vertical-align: middle;
      animation-name: lodering;
      animation-duration: 450ms;
      animation-iteration-count: infinite;
      animation-direction: alternate;
      -webkit-animation-name: lodering;
      -webkit-animation-duration: 450ms;
      -webkit-animation-iteration-count: infinite;
      -webkit-animation-direction: alternate;
      -moz-animation-name: lodering;
      -moz-animation-duration: 450ms;
      -moz-animation-iteration-count: infinite;
      -moz-animation-direction: alternate;
    }
    #loder span:nth-of-type(2) {
      animation-delay: 0.2s;
    }
    #loder span:nth-of-type(3) {
      animation-delay: 0.4s;
    }
    #loder span:nth-of-type(4) {
      animation-delay: 0.6s;
    }
    @keyframes lodering {
      0% {
        height: 17px;
      }
      100% {
        height: 38px;
      }
    }
    @-webkit-keyframes lodering {
      0% {
        height: 17px;
      }
      100% {
        height: 38px;
      }
    }
    @-moz-keyframes lodering {
      0% {
        height: 17px;
      }
      100% {
        height: 38px;
      }
    }
  </style>
@endsection

@section('content')

@section('breadcrumb')
<div class="col s12 m6 l6"><h5 class="breadcrumbs-title"><span>{{ Str::plural($page->title) ?? ''}}</span></h5></div>
<div class="col s12 m6 l6 right-align-md">
  <ol class="breadcrumbs mb-0">
    <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ url('projects') }}">Projects</a></li>
    <li class="breadcrumb-item"><a href="{{ url($page->projectRoute) }}">{{ Str::limit(strip_tags($eia->project->name), 20) ?? 'Show' }}</a></li>
    <li class="breadcrumb-item active">{{ Str::limit(strip_tags($eia->code_id), 20) ?? 'Show' }}</li>
  </ol>
</div>
@endsection
<!-- users view start -->
<div class="section">
  <!-- users view media object start -->
  <div class="card-panel">
    <div class="row">
      <div class="col s12 m12">
        <div class="display-flex media">
          <div class="media-body">
            <h6 class="media-heading"><span>Project Title: </span><span class="users-view-name">{{ $eia->project->name ?? ''}} </span></h6>
            <h6 class="media-heading"><span>Project ID: </span><span class="users-view-name">{{ $eia->project->project_code_id ?? ''}} </span></h6>
            <h5 class="media-heading"><span>EIA ID: </span><span class="users-view-name">{{ $eia->code_id ?? ''}} </span></h5>
              @can('eia-move-to-permit')
                {!! Form::open(['method' => 'POST', 'url' => 'permits']) !!}
                  {{ csrf_field() }}
                  {!! Form::hidden('eia_id', $eia->id ?? '', ['id' => 'eiaId'] ); !!}
                  <div class="row">
                    <div class="col s12 m12">
                      <div class="input-field col s12">
                        <p><label>
                          <input type="checkbox" id="moveToPermit" name="moveToPermit" /> <span> Move to Permit </span>
                          @error('moveToPermit')
                            <div class="error">{{ $message }}</div>
                          @enderror
                        </label></p>
                      </div>
                      <!-- <input type="checkbox" name="vehicle1" value="Bike">
                      <label for="vehicle1"> I have a bike</label><br> -->
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s12">
                      {!! App\Helpers\HtmlHelper::submitButton('Submit', 'moveToPermitSubmitBtn') !!}
                    </div>
                  </div>
                {{ Form::close() }}
              @endcan
          </div>
        </div>
      </div>
      <!-- <div class="col s12 m5 quick-action-btns display-flex justify-content-end align-items-center pt-2">
        <a href="{{ url($page->route.'/'.$eia->id.'/edit')}}" class="btn-small indigo">Edit </a>
        <a href="{{ url($page->route)}}" class="btn-small indigo">Back </a>
      </div>  -->
    </div>
  </div>
  <!-- users view media object ends -->
  <!-- users view card data start -->
  <div class="card">
    <div class="card-content">
      <div class="row">
        <div class="col s12 m6">
          <h6 class="mb-2 mt-2"><i class="material-icons">info_outline</i>{{ Str::plural($page->title) ?? ''}} Details </h6>
          <table class="striped">
            <tbody>
              <tr>
                <td>EIA Id:</td>
                <td><a href="{{ url($page->route.'/'.$eia->id)}}">{{ $eia->code_id ?? ''}}</a></td>
              </tr>
              <tr>
                <td>Date Of Creation:</td>
                <td>{{ $eia->formatted_date_of_entry }}</td>
              </tr>
              <tr>
                <td>Project Team Leader:</td>
                <td>{{ $eia->project_team_leader ?? ''}}</td>
              </tr>
              <tr>
                <td>Status:</td>
                <td>{!! App\Helpers\HtmlHelper::statusText($eia->stage_id, $eia->status) !!}</td>
              </tr>
              <tr>
                <td>Cost Of Proposed Develop:</td>
                <td><span class="">{{ App\Helpers\FunctionHelper::currency() . ' ' . number_format($eia->cost_of_develop) ?? ''}}</span></td>
              </tr>
              <tr>
                <td>Address:</td>
                <td>{{ $eia->address ?? ''}}</td>
              </tr>
              <tr>
                <td>Latitude & Longitude:</td>
                <td>{{ $eia->latitude ?? ''}}, {{ $eia->longitude ?? ''}}</td>
              </tr>
              <tr>
                <td>Zoom to Location:</td>
                <td><a href="javascript:" id="zoomToLatLong">Zoom </a></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col s12 m6">
          <h6 class="mb-2 mt-2"><i class="material-icons">info_outline</i>Project Details </h6>
          <table class="striped">
            <tbody>
              <tr>
                <td>Project ID:</td>
                <td>{{ $eia->project->project_code_id ?? ''}}</td>
              </tr>
              <tr>
                <td>Project Title:</td>
                <td>{{ $eia->project->name ?? ''}}</td>
              </tr>
              <tr>
                <td>Category:</td>
                <td class="">{{ $eia->project->category->name ?? ''}}</td>
              </tr>
              <tr>
                <td>Type:</td>
                <td><span class="">{{ $eia->project->projectType->name ?? ''}}</span></td>
              </tr>
              <tr>
                <td>Total EIAs:</td>
                <td><span class="">{{count($eia->project->eias)}}</span></td>
              </tr>
              <tr>
                <td>Total Budget:</td>
                <td><span class="">{{ App\Helpers\FunctionHelper::currency() . ' ' . number_format($eia->project->total_budget) ?? ''}}</span></td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- users view card data ends -->
  
  <div class="card-panel">
    <div class="row">
      <div class="col s12 m12">
        <h6 class="media-heading"><span>Project Map</span></h6>

        <div id="loder" style="display:none;"><span></span><span></span><span></span><span></span></div>

        <div id="mapx" class="mapdiv" style="height: 500px; width: 100%;"></div> 
      </div>
    </div>
  </div>
  
  <!-- users view card details start -->
  <div class="card">
    <div class="card-content">
      <div class="card-title">
        <div class="row right">
          <div class="col s12 m12">
            @can('documents-create')
              {!! App\Helpers\HtmlHelper::createLinkButton(url($page->route.'/'.$eia->id.'/documents/create'), 'Add New Document') !!}
            @endcan
            <a class="dropdown-settings btn mb-1 waves-effect waves-light cyan" href="#!" data-target="dropdown1" id="customerListBtn"><i class="material-icons hide-on-med-and-up">settings</i><span class="hide-on-small-onl">List Documents</span><i class="material-icons right">arrow_drop_down</i></a>
            <ul class="dropdown-content" id="dropdown1" tabindex="0">
              <li tabindex="0"><a class="grey-text text-darken-2 archiveBtn" data-type="active" href="javascript:" > Active </a></li>
              <li tabindex="0"><a class="grey-text text-darken-2 archiveBtn" data-type="1" href="javascript:"> Archived </a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="data-table-container">
          <form id="page-form" name="page-form">
            {!! Form::hidden('archiveStatus', 0, ['id' => 'archiveStatus'] ); !!}
            {!! Form::hidden('latitude', $eia->latitude ?? '', ['id' => 'latitude'] ); !!}
            {!! Form::hidden('longitude', $eia->longitude ?? '', ['id' => 'longitude'] ); !!}
          </form>
        </div>
      </div>
      <div class="row">
        @can('documents-list')
          <div class="col s12 m6 "><h4 class="card-title">Document Lists</h4></div>
          <div class="col s12">
            <table id="data-table-documents" class="display data-tables" data-url="{{ $page->route.'/'.$eia->id.'/documents/lists' }}" data-form="page" data-length="10">
              <thead>
                <tr>
                  <th width="20px" data-orderable="false" data-column="DT_RowIndex"> No </th>
                  <th width="250px" data-orderable="false" data-column="document_number"> Document Number </th>
                  <th width="250px" data-orderable="false" data-column="title"> Title </th>
                  <th width="200px" data-orderable="true" data-column="date_of_entry"> Date of Creation </th>
                  <th width="250px" data-orderable="false" data-column="status"> Status </th>
                  <th width="300px" data-orderable="true" data-column="brief_description"> Brief Description </th>
                  <!-- <th width="150px" data-orderable="false" data-column="document_type"> Document Type </th> -->
                  <th width="200px" data-orderable="false" data-column="comment"> Remarks/Comments </th>                            
                  <th width="250px" data-orderable="false" data-column="action"> Action </th>   
                </tr>
              </thead>
            </table>
          </div>
        @endcan  
       </div>
      <!-- </div> -->
    </div>
  </div>
  <!-- users view card details ends -->
</div>
<!-- users view ends -->
@include('layouts.full-text')
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
@endsection

@push('page-scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{asset('admin/js/custom/documents/documents.js')}}"></script>
<script src="https://unpkg.com/@fxi/mxsdk/dist/mxsdk.umd.js"></script>
<script>
  jQuery(function ($) {
    let latitude  = $("#latitude").val();
    let longitude = $("#longitude").val();
    var manager = new mxsdk.Manager({
      container: document.getElementById('mapx'),
      url: 'https://app.mapx.org/?project=MX-933-32I-JHC-ZM2-8YS&language=en&theme=classic_light',
    });

    $("#loder").show();

    manager.on('ready', () => {
      $("#loder").hide();
      $('#zoomToLatLong').click(async function (e) {
        e.preventDefault();
        // manager.ask('set_map_jump_to',{lat:46,lng:23, zoom:5});
        // manager.ask('map_fly_to',{center:[33,43], zoom:5});
        const map = await manager.ask('map', {
          method: 'flyTo',
          parameters: [{ center: { lat: latitude, lng: longitude }, zoom: 15 }]
        });
      });
    });
  });

  // jQuery(function ($) {
  //   manager.on('ready', () => {
  //     $('#loadMap')
  //       .click(async function (e) {
  //         e.preventDefault();
  //         // manager.ask('set_map_jump_to',{lat:33.312805,lng:44.361488, zoom:5});
  //         // Create a default Marker and add it to the map.
  //         // const marker1 = new mxsdk.Marker()
  //         // .setLngLat([12.554729, 55.70651])
  //         // .addTo(manager);
  //       });
  //   });
  // });
</script>
@endpush