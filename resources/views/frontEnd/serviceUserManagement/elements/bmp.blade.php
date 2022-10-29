
<!-- Add Behaviour Management Plans Modal -->
<div class="modal fade my_plan_model" id="BMPAddModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"> {{ $labels['bmp']['label'] }} </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12 serch-btns text-right">
                        <button class="btn label-default add-new-btn active" type="button"> Add New </button>
                        <button class="btn label-default logged-btn active logged-bmp-btn" type="button"> Logged Plans </button>
                        <button class="btn label-default search-btn active" type="button"> Search </button>
                    </div>
                    <!-- Add new Details -->
                    <div class="add-new-box risk-tabs custm-tabs">
                        <form method="post" action="" id="bmp_form">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0">
                                <label class="col-md-1 col-sm-1 col-xs-12 p-t-7 text-right">User: </label>
                                <div class="col-md-11 col-sm-11 col-xs-12">
                                    <div class="select-style">
                                        <select name="service_user_id" class="su_n_id" disabled="">
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

                                            $this_location_id = App\DynamicFormLocation::getLocationIdByTag('bmp');
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
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="below-divider"></div>
                            </div>
                            <!-- alert messages -->
                            @include('frontEnd.common.popup_alert_messages')
                        
                            <div class="dynamic-form-fields"> </div>
                            <div class="modal-footer m-t-0 m-b-15 modal-bttm">
                                <!-- <input type="hidden" name="plan_detail" value=""> -->
                                <input type="hidden" name="service_user_id" value="{{ $service_user_id }}">
                                <input type="hidden" name="location_id" value="{{ $this_location_id }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                                <button class="btn btn-warning sbt-dyn-form-btn" type="submit"> Confirm </button> 
                                <!-- sbt-bmp-btn  -->
                            </div>
                        </form>
                    </div>
                  
                    <!-- logged plans -->
                    <div class="logged-box risk-tabs custm-tabs">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3 class="m-t-0 m-b-20 clr-blue fnt-20"> Logged Records </h3>
                        </div>
                         <!-- alert messages -->
                        @include('frontEnd.common.popup_alert_messages')
                        <form method="post" id="edit-bmp-form">
                            <div class="modal-space modal-pading view-bmp-record">  
                                    <!-- record shown using Ajax -->               
                            </div>
                            <div class="modal-footer m-t-0 recent-task-sec">
                                <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                                <button class="btn btn-warning sbt-edit-bmp-record" type="button"> Confirm</button>
                            </div>
                        </form>                            
                    </div>

                    <!-- Search Box -->
                    <div class="search-box risk-tabs custm-tabs">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3 class="m-t-0 m-b-20 clr-blue fnt-20">Search</h3>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 p-0 srch-field">
                            <label class="col-md-1 col-sm-1 col-xs-12 p-t-7 cus-lbl text-right"> Title: </label>
                            <div class="col-md-11 col-sm-11 col-xs-12 m-b-15 title">
                                <input type="text" name="search_bmp_record" class="form-control" maxlength="255">
                            </div>
                        </div>
                        <!-- alert messages -->
                        @include('frontEnd.common.popup_alert_messages')
                        <form id="searched-bmp-records-form" method="post">
                            <div class="modal-space modal-pading searched-record text-center">
                            <!--searched Record List using ajax -->
                            </div>
                        </form>
                        <div class="modal-footer m-t-0 recent-task-sec">
                            <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                            <button class="btn btn-warning search-bmp-btn" type="button"> Confirm</button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add Behaviour Management Plans Modal End -->

<!-- View/Edit BMP Plan -->
<!-- <div class="modal fade" id="bmpModalView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <a class="close mdl-back-btn" id="vw_plan_bck_btn" href="" data-toggle="modal" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-arrow-left" title=""></i>
                </a>
                <h4 class="modal-title">{{ $labels['bmp']['label'] }} - View </h4>
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
                                    <option>{{ $labels['bmp']['label'] }}</option>
                                </select>
                            </div>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="below-divider"></div>
                    </div>

                    @include('frontEnd.common.popup_alert_messages')

                    <div class="risk-tabs">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3 class="m-t-0 m-b-20 clr-blue"> BMP Details </h3>
                        </div>
                    <form method="post" action="" id="edit_bmp_form">
                        <div class="col-md-12 col-sm-12 col-xs-12 cog-panel">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                                <label class="col-md-1 col-sm-1 col-xs-12"> Title: </label>
                                <div class="col-md-11 col-sm-11 col-xs-12 r-p-0 p-l-30">
                                    <div class="input-group popovr">
                                        <input name="edit_bmp_title" value="" class="form-control v-rmp_title" type="text" maxlength="255">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 cog-panel">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 ">
                                <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> Sent To: </label>
                                <div class="col-md-11 col-sm-11 col-xs-12 r-p-0 p-l-30">
                                  <div class="select-style">
                                    <select name="edit_sent_to">
                                      <option value="0">All contact</option>
                                      <option value="1">staff</option>
                                      <option value="2">relative</option>
                                    </select>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="edit-dynamic-bmp-form-fields">
                        </div>
                        
                    </div>
                    
                </div>
            </div>
                <div class="modal-footer m-t-0">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="su_bmp_id" value="">
                    <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                    <button class="btn btn-warning sbt_edit_bmp_btn" id="vw-sbt-bmp-plan" type="button" data-dismiss="modal" aria-hidden="true"> Continue </button>
                </div>
            </form>
        </div>
    </div>
</div> -->
<!-- View/Edit BMP Plan End -->


<script>
    $(document).ready(function() {
        //popup open on bmp tile
        $(document).on('click','.bmp_plan_modal', function(){
            $('input[name=\'search_bmp_record\']').val('');
            $('#BMPAddModal').modal('show');
        });
        // FOR bmp back btn while view/edit
        // $(document).on('click','.view-bmp-back-btn', function(){
        //     $('#BMPAddModal').modal('show');
        // });
        // FOR bmp/rmp in daily record back btn while view/edit
        // $(document).on('click','.plan-back-btn1', function(){
        //     $('#PlanRecordModal').modal('show');
        // });
        // For bmp view modal submit
        // $(document).on('click','.sbt-bmp-back-btn', function(){
        //     $('#BMPAddModal').modal('show');
        // });
        //For bmp/rmp view modal submit
        // $(document).on('click','.sbt-plan-back-btn', function(){
        //     $('#PlanRecordModal').modal('show');
        // });
        // $('#BMPAddModal').on('scroll',function(){
        //     $('.dpYears').datepicker('place')
        // });
    })
</script>

<script>
    //add new bmp
    /*$(document).ready(function(){
        $(document).on('click','.sbt-bmp-btn', function(){
            //alert(1); return false;
           // var bmp_form_title = $('input[name=\'bmp_title_name\']').val();
            var bmp_form_title = $('#bmp_form').find('.bmp_plan').val();
            error = 0;
            bmp_form_title = jQuery.trim(bmp_form_title);
            if(bmp_form_title == '' || bmp_form_title == null) {
                $('input[name=\'bmp_title_name\']').addClass('red_border');
                error = 1;
            } else {
                 $('input[name=\'bmp_title_name\']').removeClass('red_border');
            }
            if(error == 1) {
                return false;
            }

            var formdata = $('#bmp_form').serialize();
           // alert(formdata); return false;

            $('.loader').show();
            $('body').addClass('body-overflow');

            $.ajax({
                type: 'post',
                url : "{{ url('/service/bmp/add') }}",
                data: formdata,
                dataType: 'json',
                success: function(resp) {
                    //alert(resp); return false;
                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    var response = resp['response'];
                    if(response == '1') {
                        $('.dynamic-bmp-form-fields').find('input').val('');
                        $('.dynamic-bmp-form-fields').find('textarea').val('');
                        $('input[name=\'bmp_title_name\']').val('');

                        //show success message
                        $('span.popup_success_txt').text('BMP Details Added Successfully');
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
    //logged btn click view bmp title
    $(document).ready(function(){
        $(document).on('click','.logged-bmp-btn', function(){

            $('.loader').show();
            $('body').addClass('body-overflow');

            var service_user_id = "{{ $service_user_id}}";

            $.ajax({
                type : 'get',
                url  : "{{ url('/service/bmp/view') }}"+'/'+service_user_id,
                success : function(resp) {
                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    if(resp == '') {
                        $('.view-bmp-record').html('<div class="text-center p-b-20" style="width:100%">No Records found.</div>');    
                    } else {
                        $('.view-bmp-record').html(resp);
                    }

                    //$('.view-bmp-record').html(resp);
                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
            return false;
        });
    });
</script>

<script>
    //making editable click on edit 
    $(document).ready(function(){
        $(document).on('click','.edit_bmp_details', function(){
            var su_bmp_id = $(this).attr('su_bmp_id');
           
            $('.edit_bmp_details_'+su_bmp_id).removeAttr('disabled');
            // $('.edit_bmp_review_'+su_bmp_id).removeAttr('disabled');
            // $('.edit_bmp_plan_'+su_bmp_id).removeAttr('disabled');
            $('.edit_bmp_id_'+su_bmp_id).removeAttr('disabled');
            $(this).closest('.cog-panel').find('.input-plusbox').toggle();
        });
    });
</script>

<script>
    //saving editable record in bmp
    $(document).ready(function(){
        $(document).on('click','.sbt-edit-bmp-record', function(){ 
            var enabled = 0;
            $('.view-bmp-record .edit_rcrd').each(function(index) {
                var is_disable = $(this).attr('disabled');
                if(is_disable == undefined) {
                    enabled = 1;
                }
            });
            if(enabled == 0) {
                return false;
            }
            var formdata =  $('#edit-bmp-form').serialize();
           
            $('.loader').show();
            $('body').addClass('body-overflow');

            $.ajax({
                type : 'post',
                url  : "{{ url('/service/bmp/edit')  }}",
                data : formdata,
                success : function(resp) {
                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    $('.view-bmp-record').html(resp);
                    $('span.popup_success_txt').text('Updated Successsfully');
                    $('.popup_success').show();
                    setTimeout(function(){$(".popup_success").fadeOut()}, 5000); 

                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
            return false;
        });
    });
</script>

<!-- <script>
    // delete the bmp record
    $(document).ready(function(){
        $(document).on('click','.delete-bmp-btn', function(){
            var su_bmp_id = $(this).attr('su_bmp_id');
            //alert(su_bmp_id); return false;
            var this_record = $(this);

            $('.loader').show();
            $('body').addClass('body-overflow');

            $.ajax({
                type : 'get',
                url  : "{{ url('/service/bmp/delete/') }}"+'/'+su_bmp_id,
                success : function(resp) {
                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    if(resp == 1) {
                        this_record.closest('.remove-bmp-row').remove();

                        //show success delete message
                        $('span.popup_success_txt').text('BMP Deleted Successfully');                   
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
    });
</script> -->

<script>
    //view bmp form with values
    /*$(document).ready(function(){
        $(document).on('click','.bmp-view', function(){
            
            var view_btn = $(this);
            var su_bmp_id = view_btn.attr('su_bmp_id');

            $('.loader').show();
            $('body').addClass('body-overflow');

            $.ajax({
                type : 'get',
                url  : "{{ url('/service/bmp/view_bmp/') }}"+'/'+su_bmp_id,
                dataType : 'json',
                success : function(resp) {
                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    var response = resp['response'];
                    if(response == true) {
                        var su_bmp_id      = resp['su_bmp_id'];
                        var bmp_form_title = resp['bmp_title_name'];
                        var bmp_sent_to    = resp['bmp_sent_to'];
                        var bmp_form       = resp['bmp_form'];

                        $('input[name=\'su_bmp_id\']').val(su_bmp_id);
                        $('input[name=\'edit_bmp_title\']').val(bmp_form_title);
                        $('select[name=\'edit_sent_to\']').val(bmp_sent_to);
                        $('.edit-dynamic-bmp-form-fields').html(bmp_form);

                        $('.dpYears').datepicker({
                            //format: 'dd/mm/yyyy',
                        }).on('changeDate', function(e){
                            $(this).datepicker('hide');
                        });
                        $('#bmpModalView').on('scroll',function(){
                            $('.dpYears').datepicker('place')
                        });
                        
                        var model_name = $(view_btn).closest('.my_plan_model').attr('id');

                        if(model_name == 'PlanRecordModal'){

                            $('#vw_plan_bck_btn').attr('class','close mdl-back-btn plan-back-btn1');
                            $('#vw-sbt-bmp-plan').attr('class','btn btn-warning sbt_edit_bmp_btn sbt-plan-back-btn');    


                            // if($('.logged-plan-btn').hasClass('active')){
                            //     alert('log');
                            // } else{
                            //     alert('src');
                            // }

                        }else if(model_name == 'BMPAddModal'){
                            $('#vw_plan_bck_btn').attr('class','close mdl-back-btn view-bmp-back-btn'); 
                            $('#vw-sbt-bmp-plan').attr('class','btn btn-warning sbt_edit_bmp_btn sbt-bmp-back-btn');

                        }



                        // $('#BMPAddModal').modal('hide');
                        $('#bmpModalView').modal('show');
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
        //saving edit record of bmp form 
        $(document).on('click','.sbt_edit_bmp_btn', function(){

            var sbt_btn = $(this);

            var edit_bmp_title = $('input[name=\'edit_bmp_title\']').val();
            var su_bmp_id = $('input[name=\'su_bmp_id\']').val();
            //alert(su_bmp_id); alert(edit_bmp_title); return false; 
            error = 0;
            edit_bmp_title = jQuery.trim(edit_bmp_title);
            if(edit_bmp_title == '' || edit_bmp_title == null) {
                $('input[name=\'edit_bmp_title\']').addClass('red_border');
                error = 1;
            } else {
                $('input[name=\'edit_bmp_title\']').removeClass('red_border');
            }
            if(error == 1) {
                return false;
            }
            var formdata =  $('#edit_bmp_form').serialize();
            //alert(formdata); return false;
            $('.loader').show();
            $('body').addClass('body-overflow'); 

            $.ajax({
                type : 'post',
                url  : "{{ url('/service/rmp/edit_bmp/') }}"+'/'+su_bmp_id,
                data : formdata,
                dataType: 'json',
                success : function(resp) {

                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    var response = resp['response'];
                    if(response == '1') {
                        $('#bmpModalView').modal('hide');

                        //know which tab is currently active logged or search  bmp tab
                        if($('.logged-bmp-btn').hasClass('active')) {
                            $('.logged-bmp-btn').click();
                        } else {
                            update_search_list()
                        }
                        // know which tab is currently active logged or search  bmp/rmp in daily record tab
                        if($('.logged-plan-btn').hasClass('active')) {
                            $('.logged-plan-btn').click();
                        } else {
                            //update_search_bmp_list()
                            $('.search-bmp-rmp-btn').click();
                        }

                        // if($(sbt_btn).parent().closest('.modal-header').find('a.plan-back-btn')) {
                         
                        // } else {
                        //     $('#vw-sbt-bmp-plan').addClass('sbt-bmp-back-btn');
                        // }

                       // $('#BMPAddModal').modal('show');

                        //show success message
                        $('span.popup_success_txt').text('BMP Details Editted Successfully');
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
        $('input[name=\'search_bmp_record\']').keydown(function(event) { 
            var keyCode = (event.keyCode ? event.keyCode : event.which);   
            if (keyCode == 13) {
                $('.search-bmp-btn').click();
                return false;
            }
        });

        //when bmp search confirm button is clicked
        $(document).on('click','.search-bmp-btn', function() {
            update_search_list()
            return false;
        });

        function update_search_list() {
            var search_input = $('input[name=\'search_bmp_record\']');
            var search = search_input.val();

            search = jQuery.trim(search);
            search = search.replace(/[&\/\\#,+()$~%.'":*?<>^@{}]/g, '');

            if(search == '') {
                search_input.addClass('red_border');
                return false;
            } else {
                search_input.removeClass('red_border');
            }
            
            var formdata = $('#searched-bmp-records-form').serialize();
            //alert(formdata); //return false;
            var service_user_id = "{{ $service_user_id }}";

            $('.loader').show();
            $('body').addClass('body-overflow');

            $.ajax({
                type : 'post',
                url  : "{{ url('/service/bmp/view/') }}"+'/'+service_user_id+'?search='+search,
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
    //pagination of bmp
    $(document).ready(function(){
        //$(document).on('click','.bmp_paginate .pagination li', function(){
        $(document).on('click','#BMPAddModal .pagination li', function(){
    
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
                url  : "{{ url('/service/bmp/view/') }}"+'/'+"{{ $service_user_id }}"+"?page="+page_no,
                success : function(resp) {
                    if(isAuthenticated(resp) == false) {
                        return false;
                    }
                    $('.view-bmp-record').html(resp);

                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
            return false;

        });
    });
</script>