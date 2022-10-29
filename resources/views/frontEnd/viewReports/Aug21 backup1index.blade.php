@extends('frontEnd.layouts.master')
@section('title','View Report')
@section('content')

<style type="text/css">
    .orange-bg {
        background: #ed6a22 none repeat scroll 0 0;
    }
    .bg-darkgreen {
        background: #4ab661 none repeat scroll 0 0;
    }
</style>

<!--main content start-->
<section id="main-content">
    <section class="wrapper">
    <!-- page start-->
        <!-- page start-->
        <div class="col-md-12 col-sm-12 col-xs-12 metro-main p-0">
            <form class="form-horizontal" method="post" action="{{ url('/user/record') }}">

                <div class="form-group col-md-4 col-sm-4 col-xs-12 p-0 metro-design">
                    <label class="col-md-4 col-sm-4 col-xs-12 control-label"> Report type: </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="select-style">
                        <?php
                            $selected = ''; 
                            if(isset($report_type)) {
                                $selected = 'selected';
                            }
                        ?>
                            <select name="report_type" id="report_type_select">
                                <option value="ALL"> All </option>
                                <option value="INDIVIDUAL" {{ $selected }}> Individual </option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-offset-4 col-md-4 col-sm-offset-4 col-sm-4 col-xs-12 p-0">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 metro-design">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label"> Date Range: </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <!-- <input class="form-control" type="text"> -->
                            <input name="from_date" required class="form-control change_date trans" type="text" value="" autocomplete="off" maxlength="10" readonly="" id="datetime-picker" />
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-12 p-0">
                            <label class="col-md-12 col-sm-12 col-xs-12 control-label"> To: </label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <!-- <input class="form-control" type="text"> -->
                            <input name="to_date" required class="form-control trans" id="datetime-picker1" type="text" value="" autocomplete="off" maxlength="10" readonly="" />
                        </div>
                    </div>
                </div>
                
                <div class="form-group col-md-4 col-sm-4 col-xs-12 p-0 metro-design">
                    <label class="col-md-4 col-sm-4 col-xs-12 control-label"> User type: </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="select-style">
                        <?php
                            $selected = '';
                            if(isset($user_type)) {
                                $selected = 'selected';
                            }
                        ?>
                            <select id="user_type" name="user_type">
                                <option value=""> Select </option>
                                <option value="SERVICE_USER" {{ $selected }}> Service User </option>
                                <option value="STAFF"> Staff </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-4 col-sm-4 col-xs-12 p-0 metro-design">
                    <label class="col-md-4 col-sm-4 col-xs-12 control-label"> User: </label>
                    <div class="col-md-8 col-sm-4 col-xs-12">
                       <div class="select-style">
                       <?php  ?>
                            <select name="select_user" id="select_user">
                                <option value=""> Select </option>
                                <?php 
                                    $selected = '';
                                    foreach($service_user as $su) {
                                        if(isset($select_user)) {
                                            if($select_user == $su->id) {
                                                $selected = 'selected'; 
                                            } else {
                                                $selected = '';
                                            }          
                                        }
                                ?>
                                    <option value="{{ $su->id}}" {{ $selected }}>{{ $su->name }}</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-4 col-sm-4 col-xs-12" id="confirm_btn">
                    <!-- <input type="hidden" name="_token" value="csrf_"> -->
                    {{ csrf_field() }}
                    <div class="col-md-8 col-sm-4 col-xs-12">
                       <button type="submit" class="btn btn-warning">Confirm</button>
                    </div>
                </div>

            </form>
        </div>
        <!--mini statistics start-->
        <div class="row">
            <div class="col-md-4">
                <section class="panel">
                    <div class="panel-body">
                        <div class="top-stats-panel">
                            <div class="gauge-canvas">
                                <h4 class="widget-h">Money Spent</h4>
                                <canvas width="160" height="100" id="gauge"></canvas>
                            </div>
                            <ul class="gauge-meta clearfix">
                                <li id="gauge-textfield" class="pull-left gauge-value"></li>
                                <li class="pull-right gauge-title">{{ $count['cash_withdraw'] }}</li>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-md-4">
                <section class="panel">
                    <div class="panel-body">
                        <div class="top-stats-panel">
                            <h4 class="widget-h">Star Given</h4>
                            <div class="star-big text-center">
                                <div class="star-biginner">
                                    <i class="fa fa-star-o"></i>
                                    <span class="star-bigno">{{ $count['star'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-md-4">
                <section class="panel">
                    <div class="panel-body">
                        <div class="top-stats-panel">
                            <h4 class="widget-h">all areas</h4>
                            <div class="bar-stats">
                                <ul class="progress-stat-bar clearfix">
                                    <li data-percent="{{ ($record_score['daily_record']/25)*100 }}%"><span class="progress-stat-percent bg-blue"></span></li>
                                    <li data-percent="{{ ($record_score['living_skill']/25)*100 }}%"><span class="progress-stat-percent"></span></li>
                                    <li data-percent="{{ ($record_score['education_record']/25)*100 }}%"><span class="progress-stat-percent pink"></span></li>
                                    <li data-percent="{{ ($record_score['mfc']/25)*100 }}%"><span class="progress-stat-percent yellow-b"></span></li>
                                </ul>
                                <ul class="bar-legend">
                                    <li><span class="bar-legend-pointer bg-blue"></span>{{ $labels['daily_record']['label'] }} <!-- General Behaviour pink --></li>
                                    <li><span class="bar-legend-pointer green"></span>{{ $labels['living_skill']['label'] }}</li>
                                    <li><span class="bar-legend-pointer pink"></span>{{ $labels['education_record']['label'] }}</li>
                                    <li><span class="bar-legend-pointer yellow-b"></span>{{ $labels['mfc']['label'] }}</li>
                                </ul>
                                <div class="daily-sales-info">
                                    <span class="sales-count">{{ floor($record_score['obtained']) }}%</span> <span class="sales-label">scores earned so far {{ $record_score['obtained'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <!-- <section class="panel">
                    <header class="panel-heading">
                        Risks
                    <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                        <a href="javascript:;" class="fa fa-cog"></a>
                        <a href="javascript:;" class="fa fa-times"></a>
                     </span>
                    </header>
                    <div class="panel-body">


                        <div class="chartJS">


                            <canvas id="pie-chart-js" height="250" width="800" ></canvas>

                        </div>
                        <ul class="pie-legend">
                            <li><span class="bar-legend-pointer light-green"></span> School work </li>
                            <li><span class="bar-legend-pointer light-red"></span> Behaviour </li>
                            <li><span class="bar-legend-pointer light-yellow"></span> Attitude </li>
                        </ul>

                    </div>
                </section> -->
            </div>

            <div class="col-sm-6">
                <!-- <section class="panel">
                    <header class="panel-heading">
                        Area Chart
                    <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                        <a href="javascript:;" class="fa fa-cog"></a>
                        <a href="javascript:;" class="fa fa-times"></a>
                     </span>
                    </header>
                    <div class="panel-body">
                        <div class="chartJS">
                            <canvas id="line-chart-js" height="250" width="800" ></canvas>
                        </div>
                    </div>
                </section> -->

               <!--  <section class="panel">
                    <header class="panel-heading">
                        Line Chart
                    <span class="tools pull-right">
                        <a href="javascript:;" class="fa fa-chevron-down"></a>
                        <a href="javascript:;" class="fa fa-cog"></a>
                        <a href="javascript:;" class="fa fa-times"></a>
                     </span>
                    </header>
                    <div class="panel-body">
                        <div id="graph-line"></div>
                    </div>
                </section> -->
            </div>
        </div>
        <!-- page end-->
        <div class="row">
                <div class="col-md-3">
                    <div class="mini-stat clearfix">
                        <span class="mini-stat-icon bg-red"><i class="fa fa-exclamation-triangle"></i></span>
                        <div class="mini-stat-info">
                            <span>{{ $count['in_danger'] }}</span>
                            In Danger
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mini-stat clearfix">
                        <span class="mini-stat-icon orange-bg"><i class="fa fa-exclamation"></i></span>
                        <div class="mini-stat-info">
                            <span>{{ $count['assistance'] }}</span>
                            Need Assistance
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mini-stat clearfix">
                        <span class="mini-stat-icon bg-darkgreen"><i class="fa fa-phone"></i></span>
                        <div class="mini-stat-info">
                            <span>{{ $count['req_cb'] }}</span>
                            Request Callback
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mini-stat clearfix">
                        <span class="mini-stat-icon bg-blue" ><i class="fa fa-envelope-o"></i></span>
                        <div class="mini-stat-info">
                            <span>{{ $count['off_mesg'] }}</span>
                            Message Office
                        </div>
                    </div>
                </div>  
        </div>
        <div class="row">
            <div class="col-md-6">
                <section class="panel">
                    <div class="panel-body">
                        <div class="top-stats-panel">
                            <div class="daily-visit">
                                <h4 class="widget-h">AFC</h4>
                                <div id="daily-visit-chart" style="width:100%; height: 100px; display: block">

                                </div>
                                <ul class="chart-meta clearfix">
                                    <li class="pull-left visit-chart-value">3233</li>
                                    <li class="pull-right visit-chart-title"><i class="fa fa-arrow-up"></i> 15%</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-md-6">
                <section class="panel">
                    <div class="panel-body">
                        <div class="top-stats-panel">
                            <div class="daily-visit">
                                <h4 class="widget-h">MFC</h4>
                                <div id="daily-visit-chart2" style="width:100%; height: 100px; display: block">

                                </div>
                                <ul class="chart-meta clearfix">
                                    <li class="pull-left visit-chart-value">3233</li>
                                    <li class="pull-right visit-chart-title"><i class="fa fa-arrow-up"></i> 15%</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</section>
<!--main content end-->
<script>
    $(document).ready(function(){
        var report_type = $('#report_type_select').val();
        if(report_type == 'ALL') {
            $('select[name=\'user_type\']').html("<option value='SERVICE_USER' selected=''> Service User </option>"+"<option value='STAFF' > Staff </option>");
            $('select[name=\'select_user\']').attr('disabled', true);
            // $('#confirm_btn').hide();
        }

        $('#report_type_select').change(function(){
            var report_type = $('#report_type_select').val();
            if(report_type == 'ALL') { 
                $('select[name=\'user_type\']').html("<option value='SERVICE_USER' selected=''> Service User </option>"+"<option value='STAFF' > Staff </option>");
                $('select[name=\'select_user\']').html("<option value=''> Select </option>");
                $('select[name=\'select_user\']').attr('disabled', true);
                // $('#confirm_btn').hide();
            } else {
                $('select[name=\'user_type\']').html("<option value=''>Select</option>"+"<option value='SERVICE_USER'> Service User </option>"+"<option value='STAFF'> Staff </option>");
                $('select[name=\'select_user\']').attr('disabled', false);
                // $('#confirm_btn').show();
            }
        });

        $('#user_type').change(function(){
            var user_type_id = $(this).val();
            
            if(user_type_id == '') {
                $('select[name=\'select_user\']').html("<option value=''>Select</option>");
                return false;
            } else if(user_type_id == 'SERVICE_USER') {
                $('select[name=\'select_user\']').html("<option value=''>Select Service User</option>");
            } else {
                $('select[name=\'select_user\']').html("<option value=''>Select Staff</option>");
            }

            $('.loader').show();
            $('body').addClass('body-overflow');

            $.ajax({
                type : 'get',
                url  : "{{ url('/users') }}"+'/'+user_type_id,
                success : function(resp) {
                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    if(resp != '') {
                        if(user_type_id == 'SERVICE_USER') {
                            $('#select_user').html('<option value="0">Select Service User</option>'+resp);
                        } else {
                            $('#select_user').html('<option value="0">Select Staff</option>'+resp);
                        }
                    } else {
                        $('#select_user').html('<option value="0">Select</option>');
                    }

                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
            return false;
        });
    });
</script>

<script>
    /* $(document).ready(function(){
        $('#select_user').change(function(){
            var service_user_id = $(this).val();
            // alert(service_user_id); return false;
           // location.reload();
           window.location.href = "{{ url('/view-report') }}"+'/'+service_user_id;
        });
    }); */
</script>

<script>
    $(document).ready(function() {
        today  = new Date; 
        $('#datetime-picker').datetimepicker({
           // format: 'dd-mm-yyyy',
            format: 'dd-mm-yyyy',
            // startDate: today,
            minView : 2
        });
        $('.change_date').datetimepicker({
           format: 'dd-mm-yyyy',
            //format: 'yyyy-mm-dd',
            startDate: today,
            minView : 2
        });

        $('#datetime-picker1').datetimepicker({
            format: 'dd-mm-yyyy',
            //format: 'yyyy-mm-dd',
            // startDate: today,
            minView : 2,
        });

        $('#datetime-picker1').on('click', function(){
            $('#datetime-picker1').datetimepicker('show');
        });

        $('#datetime-picker').on('click', function(){
            $('#datetime-picker').datetimepicker('show');
        });

        $('#datetime-picker').on('change', function(){
            $('#datetime-picker').datetimepicker('hide');
        });

        $('#datetime-picker1').on('change', function(){
            $('#datetime-picker1').datetimepicker('hide');
        });
    });
</script>

<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<script src="{{ url('public/frontEnd/js/gauge.js') }}"></script>
<script src="{{ url('public/frontEnd/js/skycons.js') }}"></script>
<script class="include" type="text/javascript" src="{{ url('public/frontEnd/js/jquery.dcjqaccordion.2.7.js') }}"></script>
<!-- <script src="{{ url('public/frontEnd/js/jquery.scrollTo.min.js') }}"></script> -->
<!-- <script src="{{ url('public/frontEnd/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js') }}"></script> -->
<!-- <script src="{{ url('public/frontEnd/js/jquery.nicescroll.js') }}"></script> -->
<!-- morris chart -->
<!-- <script src="{{ url('public/frontEnd/js/morris-chart/morris.js') }}"></script>
<script src="{{ url('public/frontEnd/js/morris-chart/raphael-min.js') }}"></script>
<script src="{{ url('public/frontEnd/js/morris.init.js') }}"></script> -->
<!--Easy Pie Chart-->
<script src="{{ url('public/frontEnd/js/dashboard.js') }}"></script>
<!-- <script src="{{ url('public/frontEnd/js/easypiechart/jquery.easypiechart.js') }}"></script> -->
<!--Sparkline Chart-->
<!-- <script src="{{ url('public/frontEnd/js/sparkline/jquery.sparkline.js') }}"></script> -->
<!--jQuery Flot Chart-->
<script src="{{ url('public/frontEnd/js/flot-chart/jquery.flot.js') }}"></script>
<script src="{{ url('public/frontEnd/js/flot-chart/jquery.flot.tooltip.min.js') }}"></script>
<!-- <script src="js/flot-chart/jquery.flot.resize.js"></script>
<script src="js/flot-chart/jquery.flot.pie.resize.js"></script> -->
<!--Chart JS-->
<!-- <script src="{{ url('public/frontEnd/js/chart-js/Chart.js') }}"></script> -->
<!-- <script src="{{ url('public/frontEnd/js/chartjs.init.js') }}"></script> -->
<!--common script init for all pages-->
<script>
    //javascript of gauge (meter - points untill nexr star)
    if (Gauge) {
        /*Knob*/
        var opts = {
            lines: 12, // The number of lines to draw
            angle: 0, // The length of each line
            lineWidth: 0.48, // The line thickness
            pointer: {
                length: 0.6, // The radius of the inner circle
                strokeWidth: 0.03, // The rotation offset
                color: '#464646' // Fill color
            },
            limitMax: false,
            limitMin: false, 
            colorStart: '#fa8564', // Colors
            colorStop: '#fa8564', // just experiment with them
            strokeColor: '#F1F1F1', // to see which ones work best for you
            generateGradient: true
        };

        var max_val = "{{ floor($count['cash_withdraw']) }}"; //remaining petty cash balance
        // var max_val = 100;
        // set_val = 30;
     /*   var set_val     = "{{ floor($count['cash_withdraw']) }}"; 
        set_val     = parseInt(set_val);
        if(set_val==0){
            set_val = 0.00001;
        }*/
        var expenditure = "{{ floor($count['expenditure']) }}"; //spent cash
        expenditure = parseInt(expenditure);
        if(expenditure==0){
            expenditure = 0.00001;
        }

        var target = document.getElementById('gauge'); // your canvas element
        var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
        gauge.maxValue = max_val; // set max gauge value
        gauge.animationSpeed = 32; // set animation speed (32 is default value)
        gauge.set(expenditure); // set actual value
        gauge.setTextField(document.getElementById("gauge-textfield"));
    }
</script>

<script>
        //(line chart)
        if ($.fn.plot) {

            var d1 = [
                [0, 10],
                [1, 20],
                [2, 33],
                [3, 24],
                [4, 45],
                [5, 96],
                [6, 47],
                [7, 18],
                [8, 11],
                [9, 13],
                [10, 21]

            ];
            var data = ([{
                label: "Too",
                data: d1,
                lines: {
                    show: true,
                    fill: true,
                    lineWidth: 2,
                    fillColor: {
                        colors: ["rgba(255,255,255,.1)", "rgba(160,220,220,.8)"]
                    }
                }
            }]);
            var options = {
                grid: {
                    backgroundColor: {
                        colors: ["#fff", "#fff"]
                    },
                    borderWidth: 0,
                    borderColor: "#f0f0f0",
                    margin: 0,
                    minBorderMargin: 0,
                    labelMargin: 20,
                    hoverable: true,
                    clickable: true
                },
                // Tooltip
                tooltip: true,
                tooltipOpts: {
                    content: "%s X: %x Y: %y",
                    shifts: {
                        x: -60,
                        y: 25
                    },
                    defaultTheme: false
                },

                legend: {
                    labelBoxBorderColor: "#ccc",
                    show: false,
                    noColumns: 0
                },
                series: {
                    stack: true,
                    shadowSize: 0,
                    highlightColor: 'rgba(30,120,120,.5)'

                },
                xaxis: {
                    tickLength: 0,
                    tickDecimals: 0,
                    show: true,
                    min: 2,

                    font: {

                        style: "normal",


                        color: "#666666"
                    }
                },
                yaxis: {
                    ticks: 3,
                    tickDecimals: 0,
                    show: true,
                    tickColor: "#f0f0f0",
                    font: {

                        style: "normal",


                        color: "#666666"
                    }
                },
                //        lines: {
                //            show: true,
                //            fill: true
                //
                //        },
                points: {
                    show: true,
                    radius: 2,
                    symbol: "circle"
                },
                colors: ["#87cfcb", "#48a9a7"]
            };
            var plot = $.plot($("#daily-visit-chart"), data, options);
        }
</script> 

<script>
        //(line chart2)
        if ($.fn.plot) {

            var d1 = [
                [0, 10],
                [1, 20],
                [2, 33],
                [3, 24],
                [4, 45],
                [5, 96],
                [6, 47],
                [7, 18],
                [8, 11],
                [9, 13],
                [10, 21]

            ];
            var data = ([{
                label: "Too",
                data: d1,
                lines: {
                    show: true,
                    fill: true,
                    lineWidth: 2,
                    fillColor: {
                        colors: ["rgba(255,255,255,.1)", "rgba(160,220,220,.8)"]
                    }
                }
            }]);
            var options = {
                grid: {
                    backgroundColor: {
                        colors: ["#fff", "#fff"]
                    },
                    borderWidth: 0,
                    borderColor: "#f0f0f0",
                    margin: 0,
                    minBorderMargin: 0,
                    labelMargin: 20,
                    hoverable: true,
                    clickable: true
                },
                // Tooltip
                tooltip: true,
                tooltipOpts: {
                    content: "%s X: %x Y: %y",
                    shifts: {
                        x: -60,
                        y: 25
                    },
                    defaultTheme: false
                },

                legend: {
                    labelBoxBorderColor: "#ccc",
                    show: false,
                    noColumns: 0
                },
                series: {
                    stack: true,
                    shadowSize: 0,
                    highlightColor: 'rgba(30,120,120,.5)'

                },
                xaxis: {
                    tickLength: 0,
                    tickDecimals: 0,
                    show: true,
                    min: 2,

                    font: {

                        style: "normal",
                        color: "#666666"
                    }
                },
                yaxis: {
                    ticks: 3,
                    tickDecimals: 0,
                    show: true,
                    tickColor: "#f0f0f0",
                    font: {

                        style: "normal",


                        color: "#666666"
                    }
                },
                //        lines: {
                //            show: true,
                //            fill: true
                //
                //        },
                points: {
                    show: true,
                    radius: 2,
                    symbol: "circle"
                },
                colors: ["#87cfcb", "#48a9a7"]
            };
            var plot = $.plot($("#daily-visit-chart2"), data, options);
        }
</script> 


@endsection
