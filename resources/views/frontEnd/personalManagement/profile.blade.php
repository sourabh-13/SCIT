@extends('frontEnd.layouts.master')
@section('title','My Profile')
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
                                $user_image = $manager_profile->image;
                                if(empty($user_image)){
                                    $user_image = 'default_user.jpg';
                                } 
                            ?> 
                           <div class="profile-pic text-center">
                               <img src="{{ userProfileImagePath.'/'.$user_image }}" alt="" class="profile_staff"/>
                           </div>
                        </div>
                        <div class="col-md-6 col-sm-8 col-xs-12">
                           <div class="profile-desk" style="padding:0px">
                                <h1>{{ $manager_profile->name }}</h1>
                                <span class="text-muted">{{ $manager_profile->job_title }}</span>
                                <p class="top-def">{{ $manager_profile->description }}</p>
                                <p>
                                    <span class="profile-bigico" style="font-size:30px"> 
                                        <!-- <a href="#" title="Calendar"><i class="fa fa-calendar"></i></a> -->
                                        <!-- <a href="#" title="MFC" class="mfc"><i class="fa fa-user-times"></i></a> 
                                        <a href="#" title="LS" class="living-skill-list"><i class="fa fa-child"></i></a> 
                                        <a href="#" title="File Manager"><i class="fa fa-file-pdf-o"></i></a> -->
                                         <?php
                                            $url = "/";
                                            $logo_url = "/images/scits.png";
                                            $scits = "Scits";
                                            $facebook_slug = ''; //now not in use
                                            //$facebook_slug="http://www.facebook.com/sharer/sharer.php?u=http://www.socialcareitsolutions.co.uk&pictures=".asset('public/images/scits.png')."&p[title]=Scits&redirect_uri=".url("/fb_close/".Auth::user()->id);
                            
                                            //$facebook_slug="http://www.facebook.com/sharer.php?u=".url($url)."&t=Scits&p[url]=".asset($logo_url)."&p[title]=".$scits;
                                        /*    $twitter_slug="https://twitter.com/intent/tweet?url=".url('/');
                                            $google_slug="https://plus.google.com/share?url=".url('/');
                                        */
                                            $twitter_slug="https://twitter.com/intent/tweet?url=http://www.socialcareitsolutions.co.uk&pictures";
                                            $google_slug="https://plus.google.com/share?url=http://www.socialcareitsolutions.co.uk&pictures";
                                            define('HTTP_ROOT', url('/'));
                                        ?>
                                        <!-- <a data-toggle="modal" href="#AddWeeklyMoney" class="usd-icon1" title="Update weekly allowance"><i class="fa fa-credit-card-alt"></i></a> --> <!-- fa-gbp -->
                                        <a href="#" class="usd-icon add-petty-cash" title="Deposit petty cash" style="font-size:36px;"> <i class="fa fa-money"></i> </a> 
                                  
                                        <a onclick="facebook('https://www.facebook.com/dialog/feed?app_id=197755410760254&link=www.socialcareitsolutions.co.uk&picture=<?php echo HTTP_ROOT.'public/images/scits.png'; ?>&name=Just posted &caption=scits Post&description=<?php  echo 'desc'; ?>.&message=Facebook%20Dialogs%20are%20so%20easy!& redirect_uri=<?php echo HTTP_ROOT ?>/fb_close');" href="#" title="Share on facebook"><i class="fa fa-facebook"></i></a>   

                                        <!-- <a href="{{$facebook_slug}}" title="Share on facebook"><i class="fa fa-facebook"></i></a> -->
                                        <a href="{{$twitter_slug}}" title="Share on facebook"><i class="fa fa-twitter"></i></a>
                                        <a href="{{$google_slug}}" title="Share on google"><i class="fa fa-google"></i></a>
                                        
                                        <!-- <a onclick="facebook('https://www.facebook.com/dialog/feed?app_id=197755410760254&link=www.socialcareitsolutions.co.uk&picture=<?php echo HTTP_ROOT.'public/images/scits.png'; ?>&name=Just posted &caption=scits Post&description=<?php  echo 'desc'; ?>.&message=Facebook%20Dialogs%20are%20so%20easy!& redirect_uri=<?php echo HTTP_ROOT ?>/fb_close');" href="#"> -->      
                            
                                        <!-- <button onclick="fb_share()">fb</button> -->
                                    </span>
                                </p>
                                
                               <p>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="profile-statistics">
                                <h1>{{ $manager_profile->payroll }}</h1>
                                <p>Pay Roll</p>
                                <h1>{{ $manager_profile->holiday_entitlement }}(Hrs)</h1>
                                <p>Holiday Entitlement</p>  
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
                                <a data-toggle="tab" href="#manager_access_rights">
                                    Access Rights
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#my_profile_contacts" class="profile-contact-map">
                                    Contacts
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#my_profile_info">
                                    Full Profile
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#profile_settings">
                                    Settings
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
                                            <div class="col-md-3 col-sm-4 col-xs-12 my_annual_record" manager_id="{{ $manager_id }}">
                                                <div class="profile-nav alt">
                                                        <section class="panel text-center">
                                                            <div class="user-heading alt wdgt-row purple-bg">
                                                                <i class="fa fa-files-o"></i>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="wdgt-value">
                                                                    <h4 class="count">Manage<br>Annual Leave</h4>
                                                                    <p></p>
                                                                </div>
                                                            </div>
                                                        </section>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-4 col-xs-12">
                                                <div class="profile-nav alt" >
                                                    <a href="{{ url('/staff/rota/view') }}">
                                                        <section class="panel text-center">
                                                            <div class="user-heading alt wdgt-row bg-blue">
                                                                <i class="fa fa-sliders"></i>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="wdgt-value">
                                                                    <h4 class="count">Manage<br>Rota</h4>
                                                                    <p></p>
                                                                </div>
                                                            </div>
                                                        </section>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- fa-hourglass-end  fa-life-ring  fa-hourglass-half  fa-hourglass-start  fa-clock-o  fa-tachometer  fa-sliders   -->
                                            <div class="col-md-3 col-sm-4 col-xs-12 my_sick_record" manager_id="{{ $manager_id }}">
                                                <div class="profile-nav alt" >
                                                   <!--  <a href="#mySickLeaveModal" data-toggle="modal"> -->
                                                        <section class="panel text-center">
                                                            <div class="user-heading alt wdgt-row terques-bg">
                                                                <i class="fa fa-bed"></i>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="wdgt-value">
                                                                    <h4 class="count">Manage<br>Sick Records</h4>
                                                                    <p></p>
                                                                </div>
                                                            </div>
                                                        </section>
                                                  <!--   </a> -->
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-4 col-xs-12 my_task_allocation_list" manager_id="{{ $manager_id }}">
                                                <div class="profile-nav alt">
                                                    <section class="panel text-center">
                                                        <div class="user-heading alt wdgt-row red-bg">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="wdgt-value">
                                                                <h4 class="count">Task<br>Allocation</h4>
                                                                <p></p>
                                                            </div>
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>

                                            <!-- <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="below-divider"></div>
                                            </div> -->
                                        </div> 
                                    </div>

                                    <div class="col-md-4">
                                        <div class="feed-box text-center"></div>
                                        <div class="profile-nav alt"></div>
                                        <!-- notification start -->
                                        <section class="panel">
                                            @include('frontEnd.common.notification_bar')
                                        </section>
                                        <!-- <section class="panel m-0">
                                            <header class="panel-heading"> Notification <span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a> <a href="javascript:;" class="fa fa-cog"></a> <a href="javascript:;" class="fa fa-times"></a> </span> 
                                            </header>
                                            <div class="panel-body  min-ht-0">
                                            </div>
                                        </section> -->
                                    </div>
                                </div>
                            </div>
                            @include('frontEnd.personalManagement.elements.user_rights')
                            @include('frontEnd.personalManagement.elements.my_profile_contacts')
                            @include('frontEnd.personalManagement.elements.profile_detail')
                            @include('frontEnd.personalManagement.elements.profile_settings')
                        </div>
                    </div>    
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->

<!--  AddWeeklyMoney model start -->
<!-- <div class="modal fade" id="AddWeeklyMoney" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Set weekly allowance of service user</h4>
            </div>
            <form id="deposit_weekly_amount" method="post" action="{{ url('weekly-allowance/update') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <label class="col-md-4 m-t-20 text-right">Enter Amount</label>
                            <div class="col-md-6">
                                <div class="style-input m-t-15">
                                    <input type="text" name="amount_add" value="{{ $weekly_allowance }}" class="form-control">
                                </div>
                            </div>
                            <label class="col-md-12 m-t-10"> Note: This money will be automatically added to all the service user's account in every week.</label>
                            <div class="modal-footer recent-task-sec p-b-5">
                                <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                                <button class="btn btn-warning" type="submit"> Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>        
            </form>
        </div>
    </div>
</div> -->
<!--  AddWeeklyMoney model end -->

<script type="text/javascript">
    function facebook(url)
    {
        window.open( url, "myWindow","status = 1, height = 462, width = 830,left = 100,top= 100, resizable = yes, scrolling=yes" )
    }

</script>

@include('frontEnd.personalManagement.elements.annual_leaves')
@include('frontEnd.personalManagement.elements.sick_leaves')
@include('frontEnd.personalManagement.elements.task_allocation')
@include('frontEnd.personalManagement.elements.change_password')
@include('frontEnd.personalManagement.elements.petty_cash')
@include('frontEnd.personalManagement.elements.petty_cash_detail')




<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&AMP;sensor=false"></script> 
<?php $api_key = env('GOOGLE_MAP_API_KEY'); ?>
<script async defer
  src="https://maps.googleapis.com/maps/api/js?key={{ $api_key }}&callback=initMap">
</script>

<script>
    $(document).ready(function(){
        autosize($("textarea"));
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
                title: '{{ $manager_profile->name }}'
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
        
        $('.profile-contact-map').click(function(){
        
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
                    title: '{{ $manager_profile->name }}'
                });
            }
            google.maps.event.addDomListener(window, 'click', initialize);
        });
    });
</script>

<script>
    // validations for weekly money allowance
    $(function() {
        $("#deposit_weekly_amount").validate({
            rules: {
        
                amount_add: {
                    required: true,
                    regex: /^[0-9 .]{1,10}$/
                },     
            },
            messages: {
               
                amount_add:{ 
                    required: "This Field is required.",
                    regex: "This Field should contain numbers only."
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        })
        return false;   
    });
    
</script>

@endsection
