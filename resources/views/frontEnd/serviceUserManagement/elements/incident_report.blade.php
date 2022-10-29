
<!-- Add SUIncidentReport Modal -->
<div class="modal fade my_plan_model" id="IncidentAddModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"> {{ $labels['incident_report']['label'] }} </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12 serch-btns text-right">
                        <button class="btn label-default add-new-btn active" type="button"> Add New </button>
                        <button class="btn label-default logged-btn active logged-incident-btn" type="button"> Logged Reports </button>
                        <button class="btn label-default search-btn active" type="button"> Search </button>
                    </div>

                    <!-- Add new Details -->
                    <div class="add-new-box risk-tabs custm-tabs">
                        <form method="post" action="" id="incident_form">
                            <!-- <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0">
                                <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> User: </label>
                                <div class="col-md-11 col-sm-11 col-xs-12">
                                    <div class="select-style">
                                        <select name="su_id" disabled="disabled">
                                            <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 ">
                                <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> Add: </label>
                                <div class="col-md-11 col-sm-11 col-xs-12">
                                    <div class="select-style title-name">
                                        <select disabled="disabled">
                                            <option>{{ $labels['incident_report']['label'] }}</option>
                                        </select>
                                    </div>
                                    <p class="help-block"></p>
                                </div>
                            </div> -->
                            <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0">
                                <label class="col-md-1 col-sm-1 col-xs-12 p-t-7 text-right">User: </label>
                                <div class="col-md-11 col-sm-11 col-xs-12">
                                    <div class="select-style">
                                        <select name="service_user_id" class="su_n_id" disabled="" >
                                            <option value="0"> Select Service User </option>
                                            @foreach($service_users as $value)
                                                <option value="{{ $value['id'] }}" {{ ($service_user_id == $value['id']) ? 'selected' : '' }}>{{ ucfirst($value['name']) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0">
                                <label class="col-md-1 col-sm-1 col-xs-12 p-t-7 text-right"> Add: </label>
                                <div class="col-md-11 col-sm-12 col-xs-12">
                                    <div class="select-style">
                                        <select name="dynamic_form_builder_id" class="dynamic_form_select">
                                            <option value="0"> Select Form </option>
                                            <?php
                                            $this_location_id = App\DynamicFormLocation::getLocationIdByTag('incident_report');
                                            foreach($dynamic_forms as $value) {
                                            
                                                $location_ids_arr = explode(',',$value['location_ids']);

                                                if(in_array($this_location_id,$location_ids_arr)) { 
                                                ?>
                                                    <option value="{{ $value['id'] }}"> {{ ucfirst($value['title']) }} </option>
                                                <?php } 
                                            } ?>
                                        </select>
                                    </div>
                                    <p class="help-block"> Choose a user and the type of form you want to fill. </p>
                                </div>
                            </div>

                            <!--<div class="col-md-12 col-sm-12 col-xs-12">-->
                            <!--    <div class="below-divider">-->
                            <!--    </div>-->
                            <!--</div>-->

                            <!-- alert messages -->
                            @include('frontEnd.common.popup_alert_messages')
                            <div class="dynamic-form-fields"> </div>
                            <!-- <div class="col-md-12 col-sm-12 col-xs-12">
                                <h3 class="m-t-0 m-b-20 clr-blue rmp-details fnt-20"> Details </h3>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 cog-panel">
                                <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                                    <label class="col-md-1 col-sm-1 col-xs-12"> Title: </label>
                                    <div class="col-md-11 col-sm-11 col-xs-12 r-p-0">
                                        <div class="input-group popovr">
                                            <input name="incident_report_title" value="" type="text" class="form-control incident_plan" maxlength="255" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 cog-panel datepicker-sttng">      
                                <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                                    <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> Date: </label>
                                    <div class="col-md-11 col-sm-11 col-xs-12 r-p-0">
                                      <div data-date-viewmode="" data-date-format="dd-mm-yyyy" data-date="" class="input-group date dpYears">
                                        <input name="incident_report_date" value="" size="16" readonly="" class="form-control" type="text" maxlength="255">
                                        <span class="input-group-btn add-on">
                                          <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dynamic-incident-form-fields">
                                {!! $form_pattern['incident_report'] !!}
                            </div> -->
                            <!-- </div> -->   
                        
                            <div class="modal-footer m-t-0 m-b-15 modal-bttm">
                                <input type="hidden" name="location_id" value="{{ $this_location_id }}">
                                <input type="hidden" name="service_user_id" value="{{ $service_user_id }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                                <button class="btn btn-warning sbt-dyn-form-btn" type="submit"> Confirm </button>
                            </div>
                        </form>
                    </div>
                  
                    <!-- logged plans -->
                    <div class="logged-box risk-tabs custm-tabs">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3 class="m-t-0 m-b-20 clr-blue fnt-20"> Logged Reports </h3>
                        </div>
                         <!-- alert messages -->
                        @include('frontEnd.common.popup_alert_messages')
                        <form method="post" id="edit-incident-form">
                            <div class="modal-space modal-pading view-incident-record">  
                                    <!-- record shown using Ajax -->               
                            </div>
                            <div class="modal-footer m-t-0 recent-task-sec">
                                <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                                <button class="btn btn-warning sbt-edit-incident-record" type="button"> Confirm</button>
                            </div>
                        </form>                            
                    </div>

                    <!-- Search Box -->
                    <div class="search-box risk-tabs custm-tabs">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3 class="m-t-0 m-b-20 clr-blue fnt-20">Search</h3>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 p-0 srch-field">
                            <label class="col-md-2 col-sm-2 col-xs-12 p-t-7 cus-lbl"> Title: </label>
                            <div class="col-md-9 col-sm-9 col-xs-12 m-b-15 title">
                                <input type="text" name="search_incident_record" class="form-control" maxlength="255">
                            </div>
                        </div>
                        <!-- alert messages -->
                        @include('frontEnd.common.popup_alert_messages')
                        <form id="searched-incident-records-form" method="post">
                            <div class="modal-space modal-pading searched-record text-center">
                            <!--searched Record List using ajax -->
                            </div>
                        </form>
                        <div class="modal-footer m-t-0 recent-task-sec">
                            <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                            <button class="btn btn-warning search-incident-btn" type="button"> Confirm</button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add SUIncidentReport Modal End -->

<!-- View/Edit SUIncidentReport -->
<div class="modal fade" id="IncidentReptView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <a class="close mdl-back-btn v-inc-bck-btn" href="" data-toggle="modal" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-arrow-left" title=""></i>
                </a>
                <h4 class="modal-title"> {{ $labels['incident_report']['label'] }} </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0">
                        <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> User: </label>
                        <div class="col-md-11 col-sm-11 col-xs-12">
                            <div class="select-style">
                                <select name="su_id" disabled="disabled">
                                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 ">
                        <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> Add: </label>
                        <div class="col-md-11 col-sm-11 col-xs-12">
                            <div class="select-style">
                                <select disabled="disabled">
                                    <option>{{ $labels['incident_report']['label'] }}</option>
                                </select>
                            </div>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="below-divider"></div>
                    </div>

                    <!-- alert messages -->
                    @include('frontEnd.common.popup_alert_messages')

                    <!-- Add new Details -->
                    <div class="risk-tabs">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3 class="m-t-0 m-b-20 clr-blue fnt-20"> Details </h3>
                        </div>
                    <form method="post" action="" id="edit_incident_form">
                        <div class="col-md-12 col-sm-12 col-xs-12 cog-panel">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                                <label class="col-md-1 col-sm-1 col-xs-12"> Title: </label>
                                <div class="col-md-11 col-sm-11 col-xs-12 p-l-30">
                                    <div class="input-group popovr">
                                        <input name="v-incident-r-title" value="" class="form-control" type="text" maxlength="255">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 cog-panel datepicker-sttng">      
                            <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                                <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> Date: </label>
                                <div class="col-md-11 col-sm-11 col-xs-12 p-l-30">
                                  <div data-date-viewmode="" data-date-format="dd-mm-yyyy" data-date="" class="input-group date dpYears">
                                    <input name="v-incident-r-date" value="" size="16" readonly="" class="form-control" type="text" maxlength="255">
                                    <span class="input-group-btn add-on">
                                      <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="edit-dynamic-incident-form-fields">
                          <!-- created form fields from controller will be placed here -->
                        </div>
                        
                    </div>
                    
                </div>
            </div>
                <div class="modal-footer m-t-0">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="su_incident_id" value="">
                    <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                    <!-- <button class="btn btn-warning sbt_edit_incident_btn" id="vw-sbt-incident-plan" type="button"> Continue </button> -->
                    <button class="btn btn-warning sbt_edit_incident_btn" type="button"> Continue </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- View/Edit SUIncidentReport End -->


<script>
    $(document).ready(function() {
        //popup open on incident tile
        $(document).on('click','.incident_plan_modal', function(){
        $('input[name=\'search_incident_record\']').val('');
        $('#IncidentAddModal').modal('show');
        });
        $('#IncidentAddModal').on('scroll',function(){
            $('.dpYears').datepicker('place')
        });

        $(document).on('click','.v-inc-bck-btn', function(){
            $('#IncidentAddModal').modal('show');
        });


    })
</script>


<script>
    //add new incident
    /*$(document).ready(function(){
        $(document).on('click','.sbt-incident-btn', function(){
            //alert(1); return false;
           // var incident_form_title = $('input[name=\'incident_title_name\']').val();
            var incident_form_title = $('#incident_form').find('.incident_plan').val();
            var incident_form_date  = $('input[name=\'incident_report_date\']').val();
            error = 0;
            incident_form_title = jQuery.trim(incident_form_title);
            if(incident_form_title == '' || incident_form_title == null) {
                $('input[name=\'incident_report_title\']').addClass('red_border');
                error = 1;
            } else {
                 $('input[name=\'incident_report_title\']').removeClass('red_border');
            }
            if(incident_form_date == '' || incident_form_date == null) {
                $('input[name=\'incident_report_date\']').parent().addClass('red_border');
                error = 1;
            } else {
                 $('input[name=\'incident_report_date\']').parent().removeClass('red_border');
            }
            if(error == 1) {
                return false;
            }

            var formdata = $('#incident_form').serialize();
           // alert(formdata); return false;

            $('.loader').show();
            $('body').addClass('body-overflow');

            $.ajax({
                type: 'post',
                url : "{{ url('/service/incident-report/add') }}",
                data: formdata,
                dataType: 'json',
                success: function(resp) {
                    //alert(resp); return false;
                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    var response = resp['response'];
                    if(response == '1') {
                        $('.dynamic-incident-form-fields').find('input').val('');
                        $('.dynamic-incident-form-fields').find('textarea').val('');
                        $('input[name=\'incident_report_title\']').val('');
                        $('input[name=\'incident_report_date\']').val('');

                        //show success message
                        $('span.popup_success_txt').text('Incident Report Details Added Successfully');
                        $('.popup_success').show();
                        setTimeout(function(){$(".popup_success").fadeOut()}, 5000);

                    }   else {
                        $('span.popup_error_txt').text('Some Error Occurred. Please try again later.');
                        $('.popup_error').show();
                        setTimeout(function(){$(".popup_error").fadeOut()}, 5000);
                    }

                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
           return false;
        });
    });*/
</script>

<script>
    //logged btn click view incident title
    $(document).ready(function(){
        $(document).on('click','.logged-incident-btn', function(){

            $('.loader').show();
            $('body').addClass('body-overflow');

            var service_user_id = "{{ $service_user_id}}";

            $.ajax({
                type : 'get',
                url  : "{{ url('/service/incident-report/views') }}"+'/'+service_user_id,
                success : function(resp) {
                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    if(resp == '') {
                        $('.view-incident-record').html('<div class="text-center p-b-20" style="width:100%">No Records found.</div>');    
                    } else {
                        $('.view-incident-record').html(resp);
                    }

                    // $('.view-incident-record').html(resp);
                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
            return false;
        });
    });
</script>


<script>
    // delete the incident record
    /*$(document).ready(function(){
        $(document).on('click','.delete-incident-btn', function(){
            var su_incident_id = $(this).attr('su_incident_id');
            var this_record = $(this);

            $('.loader').show();
            $('body').addClass('body-overflow');

            $.ajax({
                type : 'get',
                url  : "{{ url('/service/incident-report/delete/') }}"+'/'+su_incident_id,
                success : function(resp) {
                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    if(resp == 1) {
                        this_record.closest('.remove-incident-row').remove();

                        //show success delete message
                        $('span.popup_success_txt').text('Incident Report Deleted Successfully');                   
                        $('.popup_success').show();
                        setTimeout(function(){$(".popup_success").fadeOut()}, 5000);
                    } else {
                        //show delete message error
                        $('span.popup_error_txt').text('Error Occured');
                        $('.popup_error').show();
                    }

                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
            return false;
        });
    });*/
</script>

<script>
    //view incident form with values
    /*$(document).ready(function(){
        $(document).on('click','.incident-view', function(){
            
            var view_btn = $(this);
            var su_incident_id = view_btn.attr('su_incident_id');

            $('.loader').show();
            $('body').addClass('body-overflow');

            $.ajax({
                type : 'get',
                url  : "{{ url('/service/incident-report/view_incident/') }}"+'/'+su_incident_id,
                dataType : 'json',
                success : function(resp) {
                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    var response = resp['response'];
                    if(response == true) {
                        var su_incident_id      = resp['su_incident_id'];
                        var incident_form_title = resp['incident_title_name'];
                        var v_incident_r_date   = resp['v_incident_r_date'];
                        var incident_form       = resp['incident_form'];

                        $('input[name=\'su_incident_id\']').val(su_incident_id);
                        $('input[name=\'v-incident-r-title\']').val(incident_form_title);
                        $('input[name=\'v-incident-r-date\']').val(v_incident_r_date);
                        $('.edit-dynamic-incident-form-fields').html(incident_form);

                        $('.dpYears').datepicker({
                            //format: 'dd/mm/yyyy',
                        }).on('changeDate', function(e){
                            $(this).datepicker('hide');
                        });
                        $('#IncidentReptView').on('scroll',function(){
                            $('.dpYears').datepicker('place')
                        });
                        
                        $('#IncidentReptView').modal('show');
                    } else {
                        $('span.popup_error_txt').text('Some Error Occurred. Please try again later.');
                        $('.popup_error').show();
                        setTimeout(function(){$(".popup_error").fadeOut()}, 5000);
                    }
                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                    $('.pop-notifbox').removeClass('active');
                }
            });
            return false;
        });
    });*/
</script>

<script>
    $(document).ready(function(){
        //saving edit record of incident form 
        $(document).on('click','.sbt_edit_incident_btn', function(){

            var sbt_btn = $(this);

            var edit_incident_title = $('input[name=\'v-incident-r-title\']').val();
            var edit_incident_date = $('input[name=\'v-incident-r-date\']').val();
            var su_incident_id = $('input[name=\'su_incident_id\']').val();
            //alert(su_incident_id); alert(edit_incident_title); return false; 
            error = 0;
            edit_incident_title = jQuery.trim(edit_incident_title);
            if(edit_incident_title == '' || edit_incident_title == null) {
                $('input[name=\'v-incident-r-title\']').addClass('red_border');
                error = 1;
            } else {
                $('input[name=\'v-incident-r-title\']').removeClass('red_border');
            }
            if(edit_incident_date == '' || edit_incident_date == null) {
                $('input[name=\'v-incident-r-date\']').parent().addClass('red_border');
                error = 1;
            } else {
                $('input[name=\'v-incident-r-date\']').parent().removeClass('red_border');
            }
            if(error == 1) {
                return false;
            }
            var formdata =  $('#edit_incident_form').serialize();
            //alert(formdata); return false;
            $('.loader').show();
            $('body').addClass('body-overflow'); 

            $.ajax({
                type : 'post',
                url  : "{{ url('/service/incident-report/edit_incident/') }}"+'/'+su_incident_id,
                data : formdata,
                dataType: 'json',
                success : function(resp) {

                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    var response = resp['response'];
                    if(response == '1') {
                        //know which tab is currently active logged or search  incident tab
                        if($('.logged-incident-btn').hasClass('active')) {
                            $('.logged-incident-btn').click();
                        } else {
                            update_search_list()
                        }
                        $('#IncidentReptView').modal('hide');
                        $('#IncidentAddModal').modal('show');
                        //show success message
                        $('span.popup_success_txt').text('Incident Report Details Editted Successfully');
                        $('.popup_success').show();
                        setTimeout(function(){$(".popup_success").fadeOut()}, 5000);
                    } else {
                        $('span.popup_error_txt').text('Some Error Occurred. Please try again later.');
                        $('.popup_error').show();
                        setTimeout(function(){$(".popup_error").fadeOut()}, 5000);
                    }

                    $('.loader').hide();
                    $('body').removeClass('body-overflow');

                }
            });
        });

        //when enter press on search box
        $('input[name=\'search_incident_record\']').keydown(function(event) { 
            var keyCode = (event.keyCode ? event.keyCode : event.which);   
            if (keyCode == 13) {
                $('.search-incident-btn').click();
                return false;
            }
        });

        //when incident search confirm button is clicked
        $(document).on('click','.search-incident-btn', function() {
            update_search_list()
            return false;
        });

        function update_search_list() {
            var search_input = $('input[name=\'search_incident_record\']');
            var search = search_input.val();

            search = jQuery.trim(search);
            search = search.replace(/[&\/\\#,+()$~%.'":*?<>^@{}]/g, '');

            if(search == '') {
                search_input.addClass('red_border');
                return false;
            } else {
                search_input.removeClass('red_border');
            }
            
            var formdata = $('#searched-incident-records-form').serialize();
            //alert(formdata); //return false;
            var service_user_id = "{{ $service_user_id }}";

            $('.loader').show();
            $('body').addClass('body-overflow');

            $.ajax({
                type : 'post',
                url  : "{{ url('/service/incident-report/views/') }}"+'/'+service_user_id+'?search='+search,
                data : formdata,
                success :function(resp) {
                     if(isAuthenticated(resp) == false){
                        return false;
                    }
                    if(resp == ''){
                        $('.searched-record').html('No Records found.');
                    } else{
                        $('.searched-record').html(resp);
                    }
                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
            return false;
        }
    });
</script>

<script>
    //pagination of incident
    $(document).ready(function(){
        //$(document).on('click','.incident_paginate .pagination li', function(){
        $(document).on('click','#IncidentAddModal .pagination li', function(){
    
            var page_no = $(this).children('a').text();
            if(page_no == '') {
                return false;
            }
            if(isNaN(page_no)) {
                var new_url = $(this).children('a').attr('href');
                page_no = new_url[new_url.length -1];
            }
            $('.loader').show();
            $('body').addClass('body-overflow');

            $.ajax({
                type : 'get',
                url  : "{{ url('/service/incident-report/views/') }}"+'/'+"{{ $service_user_id }}"+"?page="+page_no,
                success : function(resp) {
                    if(isAuthenticated(resp) == false) {
                        return false;
                    }
                    $('.view-incident-record').html(resp);

                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
            return false;

        });
    });
</script>