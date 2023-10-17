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
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
@endsection


@section('content')

@section('breadcrumb')
<div class="col s12 m6 l6"><h5 class="breadcrumbs-title"><span>{{ Str::plural($page->title) ?? ''}}</span></h5></div>
<div class="col s12 m6 l6 right-align-md">
    <ol class="breadcrumbs mb-0">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ url($page->route) }}">{{ Str::plural($page->title) ?? ''}}</a></li>
        <li class="breadcrumb-item active"> {{ Str::limit(strip_tags($project->name), 20) ?? 'Show' }}</li>
        <!-- <li class="breadcrumb-item active">View</li> -->
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
            <h6 class="media-heading"><span>Project Title: </span><span class="users-view-name">{{ $project->name ?? ''}} </span></h6>
            <h6 class="media-heading"><span>Project ID: </span><span class="users-view-name">{{ $project->project_code_id ?? ''}} </span></h6>
          </div>
        </div>
      </div>
      <div class="col s12 m2 quick-action-btns display-flex justify-content-end align-items-center pt-2">
        <!-- <a href="{{ url($page->route.'/'.$project->id.'/edit')}}" class="btn-small indigo">Edit </a> -->
        <!-- <a href="{{ url($page->route)}}" class="btn-small indigo">Back </a> -->
      </div>
    </div>
  </div>




  <!-- users view media object ends -->
  <!-- users view card data start -->
  <div class="card">
    <div class="card-content">
      <div class="row">
        <div class="col s12 m6">
            <h6 class="mb-2 mt-2"><i class="material-icons">info_outline</i> {{ Str::singular($page->title) ?? ''}} Details</h6>
            <table class="striped">
                <tbody>
                <tr>
                    <td>Project ID:</td>
                    <td>{{ $project->project_code_id ?? ''}}</td>
                </tr>
                <tr>
                    <td>Title:</td>
                    <td class="">{{ $project->name ?? ''}}</td>
                </tr>
                <tr>
                    <td>Date Of Creation:</td>
                    <td class="">{{ $project->formatted_date_of_creation ?? ''}}</td>
                </tr>
                <tr>
                    <td>Category:</td>
                    <td class="">{{ $project->category->name ?? ''}}</td>
                </tr>
                <tr>
                    <td>Type:</td>
                    <td><span class="">{{ $project->projectType->name ?? ''}}</span></td>
                </tr>
                <tr>
                    <td>Total EIAs:</td>
                    <td><span class="">{{count($project->eias)}}</span></td>
                </tr>
                <tr>
                    <td>Total Budget:</td>
                    <td><span class="">{{ App\Helpers\FunctionHelper::currency() . ' ' . number_format($project->total_budget) ?? ''}}</span></td>
                </tr>
   
                </tbody>
            </table>
        </div>
        <div class="col s12 m6">
         <h6 class="mb-2 mt-2"><i class="material-icons">info_outline</i> Project Proponent/Developer ID Profile</h6>
            <table class="striped">
                <tbody>
                <tr>
                    <td>Name of Company:</td>
                    <td>{{ $project->company->name ?? ''}}</td>
                </tr>
                <tr>
                    <td>Name of Contact:</td>
                    <td class="">{{ $project->company->contact_name ?? ''}}</td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td class="">{{ $project->company->address ?? ''}}</td>
                </tr>
                <tr>
                    <td>E-mail:</td>
                    <td class="">{{ $project->company->email ?? ''}}</td>
                </tr>
                <tr>
                    <td>Contact Information:</td>
                    <td><span class="">{{ $project->company->contact ?? ''}}</span></td>
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
        <!--<div id="mapx" class="mapdiv" style="height: 500px; width: 530%;"></div>-->
        <div id="map" style="height: 500px"></div>
      </div>
    </div>
  </div>

  <!-- users view card details start -->
  <div class="card">
    <div class="card-content">
        <div class="card-title">
            <div class="row right">
              <div class="col s12 m12 ">
                @can('eia-create')
                  {!! App\Helpers\HtmlHelper::createLinkButton(url($page->route.'/'.$project->id.'/eias/create'), 'Add New EIA') !!}
                @endcan
              </div>
            </div>
        </div>

      <div class="row">
        <div class="col s12">
            <div id="view-borderless-table">
              <div class="row">
                <div class="col s12 m6 ">
                  <h4 class="card-title">EIA Lists</h4>
                </div>
                <div class="col s12">
                  <table id="data-table-projects" class="display data-tables" data-url="{{ $page->route.'/'.$project->id.'/eias/lists' }}" data-form="page" data-length="10">
                    <thead>
                      <tr>
                        <th width="20px" data-orderable="false" data-column="DT_RowIndex"> No </th>
                        <th width="300px" data-orderable="false" data-column="code_id">EIA ID</th>
                        <th width="150px" data-orderable="false" data-column="project_id">Project ID</th>                      
                        <th width="200px" data-orderable="true" data-column="date_of_entry"> Date of Creation</th>
                        <th width="250px" data-orderable="false" data-column="status"> Status </th>
                        <th width="200px" data-orderable="true" data-column="project_team_leader"> Project Team Leader </th>
                        <th width="150px" data-orderable="false" data-column="cost_of_develop"> Cost Of Proposed Development </th>  
                        <th width="150px" data-orderable="false" data-column="gps_coordinates"> Location  </th>                           
                        <th width="250px" data-orderable="false" data-column="action"> Action </th> 
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
        </div>
      </div>
      <!-- </div> -->
    </div>
  </div>
  <!-- users view card details ends -->

</div>
<!-- users view ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')

@endsection

@push('page-scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{asset('admin/js/custom/project/project.js')}}"></script>
<!--<script src="https://unpkg.com/@fxi/mxsdk/dist/mxsdk.umd.js"></script>-->
<!-- <script src="{{asset('admin/js/custom/project/map.js')}}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
<script>

var pageTitle  = $("#pageTitle").val();
   var pageRoute  = $("#pageRoute").val();
   var map        = new L.Map('map');

   $(function() {
      initMap()
   })

   function initMap() {
      var markers = <?php echo json_encode($variants->mapMarkers); ?>;
      var map_view_layer = ($('#map_view_layer').val() != '') ? $('#map_view_layer').val() : 1;

      L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
         attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
      }).addTo(map);

      map.attributionControl.setPrefix(''); // Don't show the 'Powered by Leaflet' text.
      var iraq = new L.LatLng(33.097, 44.077);
      map.setView(iraq, 5);

      //Loop through the markers array
      for (var i = 0; i < markers.length; i++) {
          var lat = markers[i][0];
          var lon = markers[i][1];
          var popupText = markers[i][2];
          var markerLocation = new L.LatLng(lat, lon);
          var marker = new L.Marker(markerLocation);
          map.addLayer(marker);
          marker.bindPopup(popupText);
      }
   }
</script>
@endpush