@extends('frontEnd.layouts.master')
@section('title','Service User Location History')
@section('content')

<style type="text/css">
    .modal-btn {
        background-color: #3398CC!important;
        border: none!important;
        padding: 10px 12px!important;
        font-size: 14px!important;
        color: #fff!important;
        border-radius: 5px;
    }
</style>

<section id="main-content">
    <section class="wrapper">
  
        <section class="panel cus-calendar" >
            <header class="panel-heading">
                    Service User Location History
                  
            </header>
            <div class="panel-body">
                <!-- page start-->
                <div class="row">
                    <aside class="col-lg-9">
                        <form action="{{ url('service/location-history/'.$service_user_id) }}" method="get" class="form-inline drg-event-title">
                            
                            <div class="form-group m-r-10">
                                <label for="email">History Duration:</label>
                                Last {{ $location_history_duration }} Days
                            </div>

                            <div class="form-group">
                                <label for="email">Show Locations For:</label>

                                <input name="date" required class="form-control datetime-picker trans" type="text" value="{{ $search_date }}" autocomplete="off" maxlength="10" readonly="" />

                                <!-- <select class="selectpicker cus-slct" name="date">
                                    <?php
                                        $today = date('d-m-Y');
                                        for($i = 0; $i <= $location_history_duration; $i++) {
                                        
                                        //for($i = $location_history_duration; $i <= 0; $i--) {
                                          
                                              $day = date('d-m-Y',strtotime('-'.$i.' days'));

                                            $selected = ($search_date == $day) ? 'selected' : '';

                                            echo '<option value="'.$day.'" '.$selected.'>'.$day.'</option>';
                                        }
                                     ?>
                                  </select>  -->

                                <!-- <div class="col-md-11 col-sm-11 col-xs-12 r-p-0">
                                  <div data-date-viewmode="" data-date-format="dd-mm-yyyy" data-date="" class="input-group date dpYears">
                                    <input name="date" value="" size="16" readonly="" class="form-control" type="text">
                                    <span class="input-group-btn add-on">
                                      <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                  </div>
                                </div> -->

                            </div>
                            <!-- <button type="submit" class="btn btn-info ">Go</button> -->
                            <button class="btn group-ico" type="submit" style="width:40px;">Go</button>
                             
                        </form> 
                        <!-- <iframe frameborder="0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDHAmHRTOCxpfWKLZWlDuw6T_UasvI3XUc &q=Space+Needle,Seattle+WA" allowfullscreen width="100%;" height="500"></iframe> -->
                        <div id="location-map"></div>


                    </aside>
                    <aside class="col-lg-3">
                        <!-- <div class="col-md-12">
                            <h4 class="drg-event-title"> Restricted Locations </h4>
                            <ul class="location-list">
                                @foreach($specified_locations as $value)
                                    @if($value['location_type'] == 'R')
                                    <li id="{{ $value['id'] }}">{{ ucfirst($value['location']) }} <span class="loc-del-btn pull-right"> <i class="fa fa-times"></i> </span></li>
                                    @endif
                                @endforeach
                            </ul>
                            <div class="form-group col-md-8">
                                <div class="input-group popovr" data-toggle="modal" href="#area1">
                                    <div class="add-event label label-event"> Add Location </div>
                                    <span class="input-group-addon cus-inpt-grp-addon color-green">
                                        <i class="fa fa-plus"></i>
                                    </span>
                                </div>
                            </div>
                        </div> -->

                        <div class="col-md-12">
                            <h4 class="drg-event-title"> {{ ($location_restriction_type == 'A') ? 'Allowed' : 'Restricted' }} Locations <a class="pull-right" href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i></a></h4>
                            <ul class="location-list">
                                @foreach($specified_locations as $value)
                                    <li id="{{ $value['id'] }}">{{ ucfirst($value['location']) }} <span class="loc-del-btn pull-right"> <i class="fa fa-times"></i> </span></li>
                                @endforeach
                            </ul>

                            <div class="form-group col-md-8">
                                <div class="input-group popovr" data-toggle="modal" href="#area2">
                                    <div class="add-event label label-event"> Add Location </div>
                                    <span class="input-group-addon cus-inpt-grp-addon color-green">
                                        <i class="fa fa-plus"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
                <!-- page end-->
            </div>
        </section>
    </section>
</section>

<!-- Restricted Location Add Modal -->
<div class="modal fade" id="area1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Restricted Location</h4>
            </div>
            <form method="post" action="{{ url('/service/location-history/location/add') }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0">
                            <label class="col-md-2 col-sm-2 col-xs-12 p-t-7"> Location Name: </label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <input type="text" class="form-control" id="address" name="location">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer m-t-0">
                    {{ csrf_field() }}
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="location_type" value="R">
                    <input type="hidden" name="service_user_id" value="{{ $service_user_id }}">
                    <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                    <button class="btn btn-info" type="submit"> Confirm </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Restricted Location  Modal End -->

<!-- Allowed Location Add Modal -->
<div class="modal fade" id="area2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Allowed Location</h4>
            </div>
            <form method="post" action="{{ url('/service/location-history/location/add') }}">
                <div class="modal-body">
                    <div class="row">

                        <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                            <label class="col-md-2 col-sm-2 col-xs-12 p-t-7"> Location Name: </label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <input type="text" class="form-control" id="leave_address" name="location" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer m-t-0">
                    {{ csrf_field() }}
                    <input type="hidden" name="latitude" id="leave_latitude">
                    <input type="hidden" name="longitude" id="leave_longitude">
                    <input type="hidden" name="location_type" value="A">
                    <input type="hidden" name="service_user_id" value="{{ $service_user_id }}">
                    <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                    <button class="btn btn-info" type="submit"> Confirm </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm" style="width: 300px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change Location Type</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ url('service/location-history/change-restriction-type') }}">
            <div class="radio">
              <label><input type="radio" name="location_type" value="Restricted">Restricted</label>
            </div>
            <div class="radio">
              <label><input type="radio" name="location_type" value="Allowed">Allowed</label>
            </div>    
        </form>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="service_user_id" value="{{ $service_user_id }}">
        <button type="submit" class="modal-btn" data-dismiss="modal">Submit</button>
      </div>
    </div>
  </div>
</div>

<!-- Allowed Location Modal End -->

<script>
$(document).ready(function() {
    today  = new Date; 
    $('.datetime-picker').datetimepicker({
        format: 'dd-mm-yyyy',
        //startDate: today,
        minView : 2
    });
});
</script>

<!-- scripts for autocomplete address -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQhN-xkQiUIQ9toO-KRdb9wqtc_cGbAqo&libraries=places"></script> 
<script type="text/javascript">
    var address = document.getElementById('address');
    var autocomplete_address = new google.maps.places.Autocomplete(address);
    google.maps.event.addListener(autocomplete_address, 'place_changed', function () {
        var address = autocomplete_address.getPlace();
        document.getElementById('latitude').value = address.geometry.location.lat();
        document.getElementById('longitude').value = address.geometry.location.lng();
    });
    
    var leave_address = document.getElementById('leave_address');
    var autocomplete_leave_address = new google.maps.places.Autocomplete(leave_address);
    google.maps.event.addListener(autocomplete_leave_address, 'place_changed', function () {
        var leave_address = autocomplete_leave_address.getPlace();
        document.getElementById('leave_latitude').value = leave_address.geometry.location.lat();
        document.getElementById('leave_longitude').value = leave_address.geometry.location.lng();
    });
</script>
<!-- scripts for autocomplete address end -->

<!-- Google map script start -->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&AMP;sensor=false"></script> 
<?php $api_key = env('GOOGLE_MAP_API_KEY'); ?>
<script async defer
  src="https://maps.googleapis.com/maps/api/js?key={{ $api_key }}&callback=initMap">
</script>

<?php

    //working two locations
    // $latitude1 = '30.954836'; $longitude1 = '75.848635';   
    // $latitude = '30.9115517'; $longitude = '75.8770886';

    // $latitude  = '51.509865'; 
    // $longitude = '-0.118092';
    //echo '<pre>'; print_r($locations); die;
    if(isset($locations[0]['latitude'])){
        $latitude = $locations[0]['latitude'];
    }
    if(isset($locations[0]['longitude'])){
        $longitude = $locations[0]['longitude'];
    }
    if(isset($locations[0]['timestamp'])) {
        $timestamp = $locations[0]['timestamp'];
    }
    //echo $longitude; die;
    if(isset($locations[0])){
        unset($locations[0]);
    }

?>
<script>
$(document).ready(function(){
    //google map
    function initialize() {
        var myLatlng = new google.maps.LatLng({{ $latitude }}, {{ $longitude }});
        var mapOptions = {
            zoom: 10,
            scrollwheel: false,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById('location-map'), mapOptions);
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: '{{ date("d-m-Y g:i a", strtotime($timestamp)) }}'
        });

        <?php  foreach ($locations as $key => $value) { ?>
            var myLatlng1 = new google.maps.LatLng({{ $value['latitude'] }}, {{ $value['longitude'] }});
            var marker = new google.maps.Marker({
                position: myLatlng1,
                map: map,
                title: '{{ date("d-m-Y g:i a", strtotime($value["timestamp"])) }}'
            });
        <?php }  ?>
       
        /*var myLatlng2 = new google.maps.LatLng({{ $latitude }}, {{ $longitude }});
        var marker = new google.maps.Marker({
            position: myLatlng2,
            map: map,
            title: 'mk'
        });*/
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    
});
</script>
<!-- Google map script end -->

@endsection