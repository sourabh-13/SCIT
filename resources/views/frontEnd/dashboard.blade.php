@extends('frontEnd.layouts.master')
@section('title','Dashboard')
@section('content')
<style type="text/css">
    .wdgt-value {
        padding: 24px 0;
    }

</style>

<!--main content start-->
<section id="main-content">
    <section class="wrapper p-t-80">
        <div class="container p-0">
            <div class="col-md-7 col-sm-7 col-xs-12 p-0">
            <!-- style="display: none" -->
                <a href="{{ url('/service-user-management') }}">
                    <div class="col-md-6">
                        <div class="profile-nav alt">
                            <section class="panel text-center">
                                <div class="user-heading alt wdgt-row bg-yellow"> <i class="fa fa-briefcase"></i> </div>
                                <div class="panel-body">
                                    <div class="wdgt-value">
                                        <h1 class="count">Service User Management</h1>
                                        <p></p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </a>
                <a href="{{url('service/daily-logs')}}">
                <div class="col-md-6">
                    <div class="profile-nav alt">
                            <!-- #PoliProcModal -->
                            <section class="panel text-center">
                                <div class="user-heading alt wdgt-row terques-bg"> <i class="fa fa-book"></i></div>
                                <div class="panel-body">
                                    <div class="wdgt-value">
                                        <h1 class="count">Daily Log</h1>
                                        <p></p>
                                    </div>
                                </div>
                            </section>
                        
                    </div>
                </div>
            </a>
                <a data-target="#dynmicFormModal" data-toggle="modal" class="MainNavText">
                    <div class="col-md-6">
                        <div class="profile-nav alt">
                            <section class="panel text-center">
                                <div class="user-heading alt wdgt-row lightRed"> <i class="fa fa-wpforms"></i> </div>
                                <div class="panel-body">
                                    <div class="wdgt-value">
                                        <h1 class="count">Forms</h1>
                                        <p></p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </a>
                <a href="{{ url('/system/calendar') }}">
                    <div class="col-md-6">
                        <div class="profile-nav alt">
                            <section class="panel text-center">
                                <div class="user-heading alt wdgt-row label-daily"> <i class="fa fa-calendar"></i> </div>
                                <div class="panel-body">
                                    <div class="wdgt-value">
                                        <h1 class="count">Calendar</h1>
                                        <p></p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </a>
                <!-- <a href="{{ url('/staff-management') }}">
                    <div class="col-md-6">
                        <div class="profile-nav alt">
                            <section class="panel text-center">
                                <div class="user-heading alt wdgt-row bg-purple"> <i class="fa fa-group"></i> </div>
                                <div class="panel-body">
                                    <div class="wdgt-value">
                                        <h1 class="count">Staff<br>Management</h1>
                                        <p></p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </a> -->
                <!-- <div class="col-md-6">
                    <div class="profile-nav alt">
                        <a data-target="#PoliProcModal" data-toggle="modal" class="MainNavText" >    <!-- #PoliProcModal --
                            <section class="panel text-center">
                                <div class="user-heading alt wdgt-row terques-bg"> <i class="fa fa-book"></i></div>
                                <div class="panel-body">
                                    <div class="wdgt-value">
                                        <h1 class="count">Policies &<br>Procedures</h1>
                                        <p></p>
                                    </div>
                                </div>
                            </section>
                        </a>
                    </div>
                </div> -->
                <!-- <a href="{{ url('/staff-management') }}">
                    <div class="col-md-6">
                        <div class="profile-nav alt">
                            <section class="panel text-center">
                                <div class="user-heading alt wdgt-row bg-purple"> <i class="fa fa-group"></i> </div>
                                <div class="panel-body">
                                    <div class="wdgt-value">
                                        <h1 class="count">Staff<br>Management</h1>
                                        <p></p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </a> -->
                <!-- <a href="{{ url('/system-management') }}">
                    <div class="col-md-6">
                        <div class="profile-nav alt">
                            <section class="panel text-center">
                                <div class="user-heading alt wdgt-row bg-green"> <i class="fa fa-pencil "></i></div>
                                <div class="panel-body">
                                    <div class="wdgt-value">
                                        <h1 class="count">System<br>Management</h1>
                                        <p></p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </a> -->

                <!-- <a href="{{ url('/view-reports') }}">
                    <div class="col-md-6">
                        <div class="profile-nav alt">
                            <section class="panel text-center">
                                <div class="user-heading alt wdgt-row bg-pink"> <i class="fa fa-file-text-o"></i> </div>
                                <div class="panel-body">
                                    <div class="wdgt-value">
                                        <h1 class="count">View<br>Reports</h1>
                                        <p></p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </a> -->
                <!-- <a href="{{ url('/general-admin') }}">
                     <div class="col-md-6">
                        <div class="profile-nav alt">
                            <section class="panel text-center">
                                <div class="user-heading alt wdgt-row bg-blue"> <i class="fa fa-cogs"></i></div>
                                <div class="panel-body">
                                    <div class="wdgt-value">
                                        <h1 class="count">General<br>Admin</h1>
                                        <p></p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </a> -->
            </div>

            @include('frontEnd.common.sidebar_dashboard')
            @include('frontEnd.policies_procedures')

        </div>
    </section>
</section> 

<!--main content end-->
@endsection