@extends('layouts.app')

{{-- page title --}}
@section('seo_title', Str::plural($page->title) ?? '')

@section('page-style')
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css/pages/dashboard.css') }}">
<link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
<style>
.dash-card a {
    color: #FFFFFF;
    text-decoration: none;
}
</style>
@endsection

@section('content')
   <div class="section">
      <!-- card stats start -->
      <div id="card-stats" class="pt-0">
            <div class="row">
               <div class="col s12 m6 l3">
                  <div class="card animate fadeLeft">
                     <div class="card-content cyan white-text">
                        <p class="card-stats-title"><i class="material-icons">description</i>Total Projects</p>
                        <h4 class="card-stats-number white-text">{{$variants->projects->count()}}</h4>
                        <p class="card-stats-compare dash-card">
                           <i class="material-icons">link</i> 
                           <a href="{{url('projects')}}"><span class="cyan text text-lighten-5">View More</span></a>
                        </p>
                     </div>
                  </div>
               </div>
               <div class="col s12 m6 l3">
                  <div class="card animate fadeLeft">
                     <div class="card-content red accent-2 white-text">
                        <p class="card-stats-title"><i class="material-icons">attach_file</i> EIAs</p>
                        <h4 class="card-stats-number white-text">{{$variants->eia->count()}}</h4>
                        <p class="card-stats-compare dash-card">
                           <i class="material-icons">link</i> 
                           <a href="{{ url('eias') }}"><span class="text text-lighten-5">View More</span></a>
                        </p>
                     </div>
                  </div>
               </div>
               <div class="col s12 m6 l3">
                  <div class="card animate fadeRight">
                     <div class="card-content orange lighten-1 white-text">
                        <p class="card-stats-title"><i class="material-icons">description</i> Documents </p>
                        <h4 class="card-stats-number white-text">{{$variants->documents->count()}}</h4>
                        <p class="card-stats-compare dash-card">
                           <i class="material-icons">link</i> 
                           <a href="{{ url('documents') }}"><span class=" text text-lighten-5">View More</span></a>
                        </p>
                     </div>
                  </div>
               </div>
               <div class="col s12 m6 l3">
                  <div class="card animate fadeRight">
                     <div class="card-content green lighten-1 white-text">
                        <p class="card-stats-title"><i class="material-icons">assessment</i> Permits</p>
                        <h4 class="card-stats-number white-text">{{$variants->permits->count()}}</h4>
                        <p class="card-stats-compare dash-card">
                           <i class="material-icons">link</i> 
                           <a href="{{ url('permits') }}"><span class=" text text-lighten-5">View More</span></a>
                        </p>
                     </div>
                  </div>
               </div>
            </div>
      </div>
      <!--card stats end-->
      <!--chart dashboard start-->
      <div id="chart-dashboard">
         <div class="row">
            <div class="card">
               <div class="card-content">
                  <div class="row">
                  {!! Form::open(['class'=>'page-form', 'id'=> 'pageForm']) !!}
                  {{ csrf_field() }}
                  {!! Form::hidden('pageTitle', Str::camel($page->title), ['id' => 'pageTitle'] ); !!}
                  {!! Form::hidden('pageRoute', url($page->route), ['id' => 'pageRoute'] ); !!}
                  {{ Form::close() }}
                  <div class="input-field col m4 s12">
                     <select name="map_view_layer" id="map_view_layer">
                        <option value="" disabled>Choose your option</option>
                        <option value="1" selected>Projects</option>
                        <option value="2">EIA </option>
                     </select>
                  </div>
               </div>
               <div id="map" style="height: 500px"></div>
               </div>
            </div>
            <!-- <div class="col s12 m8 l8">
               <div class="card animate fadeUp">
                     <div class="card-move-up waves-effect waves-block waves-light">
                        <div class="move-up cyan darken-1">
                           <div>
                                 <span class="chart-title white-text">Revenue</span>
                                 <div class="chart-revenue cyan darken-2 white-text">
                                    <p class="chart-revenue-total">$4,500.85</p>
                                    <p class="chart-revenue-per"><i class="material-icons">arrow_drop_up</i> 21.80 %</p>
                                 </div>
                                 <div class="switch chart-revenue-switch right">
                                    <label class="cyan-text text-lighten-5">
                                       Month <input type="checkbox" /> <span class="lever"></span> Year
                                    </label>
                                 </div>
                           </div>
                           <div class="trending-line-chart-wrapper"><canvas id="revenue-line-chart" height="70"></canvas>
                           </div>
                        </div>
                     </div>
                     <div class="card-content">
                        <a class="btn-floating btn-move-up waves-effect waves-light red accent-2 z-depth-4 right">
                           <i class="material-icons activator">filter_list</i>
                        </a>
                        <div class="col s12 m3 l3">
                           <div id="doughnut-chart-wrapper">
                                 <canvas id="doughnut-chart" height="200"></canvas>
                                 <div class="doughnut-chart-status">
                                    <p class="center-align font-weight-600 mt-4">4500</p>
                                    <p class="ultra-small center-align">Sold</p>
                                 </div>
                           </div>
                        </div>
                        <div class="col s12 m2 l2">
                           <ul class="doughnut-chart-legend">
                                 <li class="mobile ultra-small"><span class="legend-color"></span>Mobile</li>
                                 <li class="kitchen ultra-small"><span class="legend-color"></span> Kitchen</li>
                                 <li class="home ultra-small"><span class="legend-color"></span> Home</li>
                           </ul>
                        </div>
                        <div class="col s12 m5 l6">
                           <div class="trending-bar-chart-wrapper"><canvas id="trending-bar-chart" height="90"></canvas></div>
                        </div>
                     </div>
                     <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">Revenue by Month <i class="material-icons right">close</i>
                        </span>
                        <table class="responsive-table">
                           <thead>
                                 <tr>
                                    <th data-field="id">ID</th>
                                    <th data-field="month">Month</th>
                                    <th data-field="item-sold">Item Sold</th>
                                    <th data-field="item-price">Item Price</th>
                                    <th data-field="total-profit">Total Profit</th>
                                 </tr>
                           </thead>
                           <tbody>
                                 <tr>
                                    <td>1</td>
                                    <td>January</td>
                                    <td>122</td>
                                    <td>100</td>
                                    <td>$122,00.00</td>
                                 </tr>
                                 <tr>
                                    <td>2</td>
                                    <td>February</td>
                                    <td>122</td>
                                    <td>100</td>
                                    <td>$122,00.00</td>
                                 </tr>
                                 <tr>
                                    <td>3</td>
                                    <td>March</td>
                                    <td>122</td>
                                    <td>100</td>
                                    <td>$122,00.00</td>
                                 </tr>
                                 <tr>
                                    <td>4</td>
                                    <td>April</td>
                                    <td>122</td>
                                    <td>100</td>
                                    <td>$122,00.00</td>
                                 </tr>
                                 <tr>
                                    <td>5</td>
                                    <td>May</td>
                                    <td>122</td>
                                    <td>100</td>
                                    <td>$122,00.00</td>
                                 </tr>
                                 <tr>
                                    <td>6</td>
                                    <td>June</td>
                                    <td>122</td>
                                    <td>100</td>
                                    <td>$122,00.00</td>
                                 </tr>
                                 <tr>
                                    <td>7</td>
                                    <td>July</td>
                                    <td>122</td>
                                    <td>100</td>
                                    <td>$122,00.00</td>
                                 </tr>
                                 <tr>
                                    <td>8</td>
                                    <td>August</td>
                                    <td>122</td>
                                    <td>100</td>
                                    <td>$122,00.00</td>
                                 </tr>
                                 <tr>
                                    <td>9</td>
                                    <td>Septmber</td>
                                    <td>122</td>
                                    <td>100</td>
                                    <td>$122,00.00</td>
                                 </tr>
                                 <tr>
                                    <td>10</td>
                                    <td>Octomber</td>
                                    <td>122</td>
                                    <td>100</td>
                                    <td>$122,00.00</td>
                                 </tr>
                                 <tr>
                                    <td>11</td>
                                    <td>November</td>
                                    <td>122</td>
                                    <td>100</td>
                                    <td>$122,00.00</td>
                                 </tr>
                                 <tr>
                                    <td>12</td>
                                    <td>December</td>
                                    <td>122</td>
                                    <td>100</td>
                                    <td>$122,00.00</td>
                                 </tr>
                           </tbody>
                        </table>
                     </div>
               </div>
            </div> -->
            <!-- <div class="col s12 m4 l4">
               <div class="card animate fadeUp">
                     <div class="card-move-up teal accent-4 waves-effect waves-block waves-light">
                        <div class="move-up">
                           <p class="margin white-text">Browser Stats</p>
                           <canvas id="trending-radar-chart" height="114"></canvas>
                        </div>
                     </div>
                     <div class="card-content  teal">
                        <a class="btn-floating btn-move-up waves-effect waves-light red accent-2 z-depth-4 right">
                           <i class="material-icons activator">done</i>
                        </a>
                        <div class="line-chart-wrapper">
                           <p class="margin white-text">Revenue by country</p>
                           <canvas id="line-chart" height="114"></canvas>
                        </div>
                     </div>
                     <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">Revenue by country <i class="material-icons right">close</i>
                        </span>
                        <table class="responsive-table">
                           <thead>
                                 <tr>
                                    <th data-field="country-name">Country Name</th>
                                    <th data-field="item-sold">Item Sold</th>
                                    <th data-field="total-profit">Total Profit</th>
                                 </tr>
                           </thead>
                           <tbody>
                                 <tr>
                                    <td>USA</td>
                                    <td>65</td>
                                    <td>$452.55</td>
                                 </tr>
                                 <tr>
                                    <td>UK</td>
                                    <td>76</td>
                                    <td>$452.55</td>
                                 </tr>
                                 <tr>
                                    <td>Canada</td>
                                    <td>65</td>
                                    <td>$452.55</td>
                                 </tr>
                                 <tr>
                                    <td>Brazil</td>
                                    <td>76</td>
                                    <td>$452.55</td>
                                 </tr>
                                 <tr>
                                    <td>India</td>
                                    <td>65</td>
                                    <td>$452.55</td>
                                 </tr>
                                 <tr>
                                    <td>France</td>
                                    <td>76</td>
                                    <td>$452.55</td>
                                 </tr>
                                 <tr>
                                    <td>Austrelia</td>
                                    <td>65</td>
                                    <td>$452.55</td>
                                 </tr>
                                 <tr>
                                    <td>Russia</td>
                                    <td>76</td>
                                    <td>$452.55</td>
                                 </tr>
                           </tbody>
                        </table>
                     </div>
               </div>
            </div> -->
         </div>
      </div>
      <!--chart dashboard end-->
   </div>
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{ asset('admin/vendors/sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('admin/vendors/chartjs/chart.min.js') }}"></script>
@endsection

@push('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
<script>
   var pageTitle  = $("#pageTitle").val();
   var pageRoute  = $("#pageRoute").val();
   var map        = new L.Map('map');

   $('#map_view_layer').on('change', function() {
       map.eachLayer(function (layer) {
         map.removeLayer(layer);
      });
      initMap()
   });

   $(function() {
      initMap()
   })

   function initMap() {
      var markers = [];
      var map_view_layer = ($('#map_view_layer').val() != '') ? $('#map_view_layer').val() : 1;

      L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
         attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
         // maxZoom: 18,
         // center: [0, 0]
      }).addTo(map);

      map.attributionControl.setPrefix(''); // Don't show the 'Powered by Leaflet' text.

      var iraq = new L.LatLng(33.097, 44.077);

      map.setView(iraq, 4);

      $.ajax({
         url: pageRoute + "/get-map-layer",
         type: "GET",
         data: {'map_view_layer': map_view_layer}
      }).done(function(data) {

         if (data.flagError == false) {
            // markers.clearMarkers();
            markers = data.mapMarkers;

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
         } else {
            showErrorToaster(data.message);
         }
      }).fail(function() {
         showErrorToaster("Something went wrong!");
      });
   }
</script>
@endpush