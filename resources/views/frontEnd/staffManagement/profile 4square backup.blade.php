@extends('frontEnd.layouts.master')
@section('title','Service User Profile')
@section('content')

     <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
        <!-- page start-->

        <div class="row">
            <div class="col-md-12">
                <section class="panel">
                    <div class="panel-body profile-information">
                       <div class="col-md-3">
                            <?php
                                $user_image = $patient->image;
                                if($afc_status == 1) {
                                    $profile_color = 'profile_active';
                                } else {
                                    $profile_color = 'profile_inactive';
                                }
                                if(empty($user_image)){
                                    $user_image = 'default_user.jpg';
                                } 
                            ?> 
                           <div class="profile-pic text-center">
                               <img src="{{ serviceUserProfileImagePath.'/'.$user_image }}" alt="" class="profile_click {{ $profile_color }}"/>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="profile-desk">
                               <h1>{{ $patient->name }}</h1>
                               <span class="text-muted">{{ date('d/m/Y',strtotime($patient->date_of_birth)) }}</span>
                               <p class="top-def">{{ $patient->short_description }}</p>
                                
                                <p>
                                    <span class="profile-bigico"> 
                                        <a href="{{ url('/service/calendar/'.$service_user_id) }}"><i class="fa fa-calendar"></i></a>
                                        <a data-toggle="modal" href="#filemngrModal"><i class="fa fa-file-pdf-o"></i></a> 
                                    </span>
                                </p>
                                
                               <p><strong style="color:#3399CC;">Height</strong> : {{ $patient->height }}<br>
                               <strong style="color:#3399CC;">Weight</strong> : {{ $patient->weight }}<br>
                               <strong style="color:#3399CC;">Hair & Eyes</strong> : {{ $patient->  hair_and_eyes }}<br>
                               <strong style="color:#3399CC;">Markings</strong> : {{ $patient->markings }}<br></p>
                               <!--<a href="#" class="btn btn-primary">Read Full Profile</a>-->
                           </div>
                       </div>
                       <div class="col-md-3">
                           <div class="profile-statistics">
                                <?php
                                    $current_year = date('Y');
                                    $birth = date('Y',strtotime($patient->date_of_birth));
                                    $diff_year = $current_year-$birth;
                                    //echo $diff_year;
                                ?>
                               <h1>{{ $diff_year }}</h1>
                               <p>Years Old</p>
                               <h1>{{ $patient->admission_number }}</h1>
                               <p>Admission Number</p>  
                               <h1>{{ $patient->section }}</h1>
                               <p>Section</p>
                                <?php $risk_status = App\Risk::overallRiskStatus($service_user_id);
                                
                                    if($risk_status == 1){
                                        $color = 'orange-clr';
                                        $risk_status = 'Historic';
                                    } else if($risk_status == 2){
                                        $color = 'red-clr';
                                        $risk_status = 'High';

                                    } else{
                                        $color = 'darkgreen-clr';
                                        $risk_status = 'No';
                                    }
                                ?>
                               <h1 id="su_risk_status" class="{{ $color }}">{{ $risk_status }}</h1>
                               <p>Risk</p>                             
                           </div>
                       </div>
                    </div>
                </section>
            </div> 
            <div class="col-md-12">
                <section class="panel">
                    <header class="panel-heading tab-bg-dark-navy-blue cus-panel-heading">
                        <ul class="nav nav-tabs nav-justified ">
                            <li class="active">
                                <a data-toggle="tab" href="#overview">
                                    Overview
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#job-history">
                                    Care History
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#contacts" class="contact-map">
                                    Contacts
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#settings">
                                    Full Profile
                                </a>
                            </li>
                        </ul>
                    </header>
                    <div class="panel-body">
                        <div class="tab-content tasi-tab">
                            <div id="overview" class="tab-pane active">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            
                                            <div class="col-md-3 col-sm-4 col-xs-12 ">
                                                <div class="profile-nav alt">
                                                    <a href="{{ url('/service/earning-scheme/'.$service_user_id) }}">
                                                        <section class="panel text-center" style="border-style:solid; border-color:#cccccc;">
                                                            <div class="user-heading alt wdgt-row purple-bg">
                                                                <i class="fa fa-star-half-o"></i>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="wdgt-value">
                                                                    <h4 class="count">Earning<br>Scheme</h4>
                                                                    <p></p>
                                                                </div>
                                                            </div>
                                                        </section>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-4 col-xs-12 daily-record-list">
                                                <div class="profile-nav alt" >
                                                    <section class="panel text-center" style="border-style:solid; border-color:#cccccc;">
                                                        <div class="user-heading alt wdgt-row bg-blue">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="wdgt-value">
                                                                <h4 class="count">Daily<br>Record</h4>
                                                                <p></p>
                                                            </div>
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3 col-sm-4 col-xs-12 health_record_view_btn">
                                                <div class="profile-nav alt" >
                                                    <section class="panel text-center" style="border-style:solid; border-color:#cccccc;">
                                                        <div class="user-heading alt wdgt-row terques-bg">
                                                            <i class="fa fa-heartbeat"></i>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="wdgt-value">
                                                                <h4 class="count">Health<br>Record</h4>
                                                                <p></p>
                                                            </div>
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-4 col-xs-12">
                                                <div class="profile-nav alt">
                                                    <!-- <a href="{{ url('/service/placement-plans/'.$service_user_id) }}"> -->
                                                    <section class="panel text-center" style="border-style:solid; border-color:#cccccc;">
                                                        <div class="user-heading alt wdgt-row red-bg">
                                                            <i class="fa fa-map-marker"></i>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="wdgt-value">
                                                                <h4 class="count">Placement<br>Plan</h4>
                                                                <p></p>
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <!-- </a> -->
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="below-divider"></div>
                                            </div>
                                            @include('frontEnd.serviceUserManagement.elements.risk') 
                                        </div> 
                                    </div>

                                    <div class="col-md-4">
                                        <div class="feed-box text-center"></div>
                                        <div class="profile-nav alt"></div>
                                        
                                        @include('frontEnd.serviceUserManagement.elements.su_profile_notification')
                                        @include('frontEnd.serviceUserManagement.elements.care_team')
                                        
                                    </div>
                                </div>
                            </div>
                            @include('frontEnd.serviceUserManagement.elements.care_history')                            
                            @include('frontEnd.serviceUserManagement.elements.contacts')
                            @include('frontEnd.serviceUserManagement.elements.profile_detail_info')

                        </div>
                    </div>    
                </section>
            </div>
        </div>
        <!-- page end-->
        </section>
    </section>
    <!--main content end-->

    @include('frontEnd.serviceUserManagement.elements.daily_record')  
    @include('frontEnd.serviceUserManagement.elements.health_record')
    @include('frontEnd.serviceUserManagement.elements.file_manager')
    @include('frontEnd.serviceUserManagement.elements.bmp-rmp')

    @include('frontEnd.common.incident_report')

<script>
    $('.input-plusbox').hide();
    $('.input-plus').on('click',function(){
        $(this).closest('.cog-panel').find('.input-plusbox').toggle();
    });
</script>


<script>
$(document).ready(function()
{
    $("#img_upload1").change(function()
    {   
        var img_name = $(this).val();
        if(img_name != "" && img_name!=null)
        {
            var img_arr=img_name.split('.');
            var ext = img_arr.pop();
            ext     = ext.toLowerCase();
            if(ext =="jpg" || ext =="jpeg" || ext =="gif" || ext =="png")
            {
                input=document.getElementById('img_upload1');
                if(input.files[0].size > 2097152 || input.files[0].size <  10240)
                {
                  $(this).val('');
                  $("#img_upload1").removeAttr("src");
                  alert("image size should be at least 10KB and upto 2MB");
                  return false;
                }
             }
           else
            {
                $(this).val('');
                alert('Please select an image .jpg, .png, .gif file format type.');
            }
        }
    return true;
    }); 
});
</script>

<script>
    $(function() {
        $("#add_care_team").validate({
            rules: {
                
                job_title: {
                    required: true,  
                    regex: /^[a-zA-Z'.\s]{1,40}$/          
                },
                name: {
                    required: true,
                    regex: /^[a-zA-Z'.\s]{1,40}$/
                },
                email: {
                    required: true,
                    email: true
                },
                address:{ 
                    required: true,
                    regex: /^[a-zA-Z0-9'.\s]{1,100}$/
                },
                image:{
                    required: true
                },
                phone_no:{
                    required: true,
                    regex: /^[0-9'.\s]{10,13}$/
                }
            },
            messages: {
                job_title: "This field is required.",
                name: "This field is required.",
                email: "This field is required.",
                address: "This field is required.",
                address: "This field is required.",
                image: "This field is required.",
                phone_no: "This field is required.",
                
               
            },
            submitHandler: function(form) {
              form.submit();
            }
        })
        return false;   
    });
</script>

<script>
    $(document).ready(function(){  
        $(document).on('click','.cancel-btn',function(){
            $('#add_care_team').find('input').val('');
            $('#add_care_team').find('textarea').val('');
            $('#add_care_team').find('img').attr('src','');
            $('label.error').hide();
            var token = "{{ csrf_token() }}";
            $('input[name=\'_token\']').val(token);
        });
    });
</script>

<script>
    $(document).ready(function(){
        // console.log('asd');
        $('.settings').on('click',function(){
            console.log('asd');
            $(this).find('.pop-notifbox').toggleClass('active');
            $(this).closest('.cog-panel').siblings('.cog-panel').find('.pop-notifbox').removeClass('active');
        });
        $(window).on('click',function(e){
            e.stopPropagation();
            var $trigger = $(".settings");
            if($trigger !== e.target && !$trigger.has(e.target).length){
                $('.pop-notifbox').removeClass('active');
            }
        });
    });
</script>

<script>
    $(document).ready(function(){
        $('#overview .profile-nav').on('click',function(){
            $(this).find('.overviw-dropdown').toggle();
            $(this).parent('div').siblings('div').find('.overviw-dropdown').hide();
        });
        $(window).on('click',function(e){
            e.stopPropagation();
            var $trigger = $("#overview .profile-nav");
            if($trigger !== e.target && !$trigger.has(e.target).length){
                $('.overviw-dropdown').hide();
            }
        });
    });
</script>
<!--  AIzaSyBxsKWUJ690EsTa1o0Q2VF6BWXgIiFPKZo -->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&AMP;sensor=false"></script> 
<?php $api_key = env('GOOGLE_MAP_API_KEY'); ?>
<script async defer
  src="https://maps.googleapis.com/maps/api/js?key={{ $api_key }}&callback=initMap">
</script>

<script>
$(document).ready(function(){
    //google map
    function initialize() {
        var myLatlng = new google.maps.LatLng({{ $latitude }}, {{ $longitude }});
        var mapOptions = {
            zoom: 15,
            scrollwheel: false,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: '{{ $patient->name }}'
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    
    $('.contact-map').click(function(){
    
        //google map in tab click initialize
        function initialize() {
            var myLatlng = new google.maps.LatLng({{ $latitude }}, {{ $longitude }});
            var mapOptions = {
                zoom: 15,
                scrollwheel: false,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: '{{ $patient->name }}'
            });
        }
        google.maps.event.addDomListener(window, 'click', initialize);
    });
    });
</script>

<script>
    // profile status change
    $(document).ready(function(){
        // $('.sum_profile_click').dblclick(function(){
        //      // alert(1); return false;
        //     $(this).addClass('profile_active_status');
        //     var service_user_id = $(this).attr('id');
        //     //var service_user_id = "{{ $patient->id }}";

        //     $('.loader').show();
        //     $('body').addClass('body-overflow');

        //     $.ajax({
        //         type   : 'get',
        //         url    : "{{ url('/service/user-profile/status/') }}"+'/'+service_user_id,
        //         success:function(resp){ 
        //             if(isAuthenticated(resp) == false){
        //                 return false;
        //             } 
        //             if(resp == '1') {                       
        //                 if($('.profile_active_status').hasClass('profile_active')) {
        //                     $('.profile_active_status').removeClass('profile_active');
        //                     $('.profile_active_status').addClass('profile_inactive');
        //                 } else {
        //                     $('.profile_active_status').removeClass('profile_inactive')
        //                      $('.profile_active_status').addClass('profile_active');

        //                 }
        //             } else { 

        //             } 
        //             $('.profile_active_status').removeClass('profile_active_status');
        //             $('.loader').hide();
        //             $('body').removeClass('body-overflow');
        //         }
        //     });
        //     return false;
        // });

        // $('.sum_profile_click').click(function(){
        //     var su_id = $(this).attr('id');
        //     window.location.href = "{{ url('/service/user-profile') }}"+'/'+su_id;
        // });

        //yp photo right click functionality 
        $(function () {
            $('.profile_click').bind('contextmenu', function (e) {
                var service_user_id = "{{ $service_user_id }}";
                $(this).addClass('profile_active_status');
                $('.loader').show();
                $('body').addClass('body-overflow');

            $.ajax({
                type   : 'get',
                url    : "{{ url('/service/user-profile/afc-status/update') }}"+'/'+service_user_id,
                success:function(resp){ 
                    if(isAuthenticated(resp) == false){
                        return false;
                    } 

                    if(resp == 'true') {                       
                        if($('.profile_active_status').hasClass('profile_active')) {
                            $('.profile_active_status').removeClass('profile_active');
                            $('.profile_active_status').addClass('profile_inactive');
                        } else {
                            $('.profile_active_status').removeClass('profile_inactive')
                             $('.profile_active_status').addClass('profile_active');
                        }
                        //show success message
                        $('.ajax-alert-suc').find('.msg').text('MFC/AFC status has been changed successfully.');
                        $('.ajax-alert-suc').show();
                        setTimeout(function(){$(".ajax-alert-suc").fadeOut()}, 5000);

                    } else if(resp == 'AUTH_ERR') {
                        $('.ajax-alert-err').find('.msg').text("{{ UNAUTHORIZE_ERR }}");
                        $('.ajax-alert-err').show();
                        setTimeout(function(){$(".ajax-alert-err").fadeOut()}, 5000);
                    } else { 
                        $('.ajax-alert-err').find('.msg').text('Some Error Occured. Status can not be updated.');
                        $('.ajax-alert-err').show();
                        setTimeout(function(){$(".ajax-alert-err").fadeOut()}, 5000);
                    } 

                    $('.profile_active_status').removeClass('profile_active_status');
                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
            return false;
            e.preventDefault();
            });
        });
    });
</script>

@endsection