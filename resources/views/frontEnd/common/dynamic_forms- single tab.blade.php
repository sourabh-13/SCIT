<?php 
$home_id = Auth::user()->home_id;
$service_users = App\ServiceUser::where('home_id',$home_id)->get()->toArray();
$dynamic_forms = App\DynamicFormBuilder::getFormList();
//echo '<pre>'; print_r($dynamic_forms); echo '</pre>';
//$dynamic_forms = App\DynamicFormBuilder::select('id','title','location_ids')->->get()->toArray();
?>

<!-- Incident Report -->
<div class="modal fade" id="dynmicFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"> Forms </h4>
            </div>
            <form id="TopForm" method="post" action="{{ url('/service/dynamic-form/save') }}">                    
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0">
                            <label class="col-md-1 col-sm-1 col-xs-12 p-t-7 text-right">User: </label>
                            <div class="col-md-11 col-sm-11 col-xs-12">
                                <div class="select-style">
                                    <select name="service_user_id" class="su_n_id">
                                        <option value="0"> Select Service User </option>
                                        @foreach($service_users as $value)
                                            <option value="{{ $value['id'] }}">{{ ucfirst($value['name']) }}</option>
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

                                        $this_location_id = App\DynamicFormLocation::getLocationIdByTag('top_profile_btn');
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
                            <!--   <div class="col-md-1 col-sm-1 col-xs-1 p-0">
                                <button class="btn group-ico" type="submit" > <i class="fa fa-plus"></i> </button>
                            </div> -->
                        </div>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="below-divider"></div>
                        </div>
                        <!-- alert messages -->
                        @include('frontEnd.common.popup_alert_messages')

                        <div class="risk-tabs">
                            <!-- dynamic form fields will be shown here -->
                            <div class="dynamic-form-fields"> </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer m-t-0">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="location_id" value="{{ $this_location_id }}">
                    <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                    <button class="btn btn-warning sbt-dyn-form-btn" type="submit"> Confirm </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View/Edit dynamic form -->
<div class="modal fade" id="DynFormViewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <a class="close edit_dyn_form" href="" >
                    <i class="fa fa-pencil" title="Edit Form"></i>
                </a>
                <a class="close mdl-back-btn previous_modal_btn" pre_modal="" href="" data-toggle="modal" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-arrow-left" title="View Previous Modal"></i>
                </a>
                <h4 class="modal-title">View Details</h4>
            </div>
            <div class="modal-body">
                <form method="" id="dynFormFormData">
                    <div class="row">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0">
                            <label class="col-md-1 col-sm-1 col-xs-12 p-t-7 text-right">User: </label>
                            <div class="col-md-11 col-sm-11 col-xs-12">
                                <div class="select-style">
                                    <select name="service_user_id" class="su_id" disabled="">
                                        <option value="0"> Select Service User </option>
                                        @foreach($service_users as $value)
                                            <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0">
                            <label class="col-md-1 col-sm-1 col-xs-12 p-t-7 text-right"> Form: </label>
                            <div class="col-md-11 col-sm-12 col-xs-12">
                                <div class="select-style">
                                    <select name="dynamic_form_builder_id" class="dynamic_form_select" disabled="">
                                        <option value="0"> Select Form </option>
                                        
                                        <?php foreach($dynamic_forms as $value) {
                                            $location_ids_arr = explode(',',$value['location_ids']); ?>
                                            <option value="{{ $value['id'] }}"> {{ $value['title'] }} </option>
                                        <?php  } ?>
                                    </select>
                                </div>
                                <!-- <p class="help-block"> Choose a user and the type of form you want to fill. </p> -->
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="below-divider"></div>
                        </div>

                        <!-- alert messages -->
                        @include('frontEnd.common.popup_alert_messages')

                        <!-- Add new Details -->
                        <div class="risk-tabs">
                            <!-- dynamic form fields will be shown here -->
                            <div class="dynamic-form-fields"> </div>
                        </div>
                    </div>
                    <div class="modal-footer m-t-0">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="dynamic_form_id" class="dynamic_form_id" value="">
                        <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                        <!-- <button class="btn btn-warning sbt_edit_bmp_btn" id="vw-sbt-bmp-plan" type="button"> Continue </button> -->
                        <button class="btn btn-warning e-sbt-dyn-form-btn" disabled="" id="" type="button" data-dismiss="modal" aria-hidden="true"> Save </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- View/Edit dynamic form End -->

<!-- dynamic form script start -->
<script>
$(document).ready(function(){
    $('.dynamic_form_select').on('change', function() {   
        
        var form_select = $(this);
        var model_id    = form_select.closest('.modal').attr('id');
        
        var form_builder_id = form_select.val();
        var form_title  = $('#'+model_id+' .dynamic_form_select option:selected').text();
        
        if(form_builder_id > 0){
            
            $('.loader').show();
            $('body').addClass('body-overflow');
          
            $.ajax({
                type:'get',
                url : "{{ url('/service/dynamic-form/view/pattern') }}"+'/'+form_builder_id,
                dataType: "json",
                success:function(resp){
                    
                    if(isAuthenticated(resp) == false){
                        return false;
                    }

                    var response = resp['response'];
                    if(response == true){
                        
                        var pattern = resp['pattern'];
                        $('#'+model_id+' .dynamic-form-fields').html(pattern);
                        $('#'+model_id+' .dynamic_form_h3').html(form_title+' Details');
                        
                        $('.dpYears').datepicker({
                            //format: 'dd/mm/yyyy',
                        }).on('changeDate', function(e){
                            $(this).datepicker('hide');
                        });                        
                        //form_select.parent().removeClass('red_border');           
                    } 

                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
        } else{
            //$('.dynamic-form-fields').
            //$('.entry-default-fields').hide();
        }
    });

    $('.sbt-dyn-form-btn').click(function(){
        
        var model_id        = $(this).closest('.modal').attr('id');
        var form_id         = $(this).closest('form').attr('id');
        var service_user    = $('#'+model_id+' .su_n_id');
        var form_builder    = $('#'+model_id+' .dynamic_form_select');
        var static_title    = $('#'+model_id+' .static_title');
        
        var service_user_id = service_user.val().trim();   
        var form_builder_id = form_builder.val().trim();
        var static_title_vl = static_title.val().trim();
        var err = 0;

        if(service_user_id == 0) { 
            service_user.parent().addClass('red_border');
            err = 1;
        }else{
            service_user.parent().removeClass('red_border');
        }

        if(form_builder_id == 0) { 
            form_builder.parent().addClass('red_border');
            err = 1;
        } else{
            form_builder.parent().removeClass('red_border');
        }

        if(static_title_vl == '') { 
            static_title.addClass('red_border');
            err = 1;
        } else{
            static_title.removeClass('red_border');
        }

        if(err == 1){
            return false;
        }

        var formdata = $('#'+form_id).serialize();
        $('.loader').show();
        $('body').addClass('body-overflow');

        $.ajax({
            type : 'post',
            url: "{{ url('/service/dynamic-form/save') }}",
            data : formdata,
            dataType: 'json',
            success:function(resp){

                if(isAuthenticated(resp) == false){
                    return false;
                }
               
                if(resp == true){
                    $('#'+form_id+' span.popup_success_txt').text('Record has been Added Successfully');
                    $('#'+form_id+' .popup_success').show();
                    setTimeout(function(){$('#'+form_id+' .popup_success').fadeOut()}, 5000);
                    
                    $('#'+model_id+' .dynamic_form_select').val('0');
                    $('#'+model_id+' .dynamic-form-fields').html('');

                    //for mfc case only                    
                    /*$(".js-example-placeholder-single-mfc").select2({
                      dropdownParent: $('#mfcModal'),
                      placeholder: "Select Description"
                    });*/

                } else{
                    //show error message
                    $('#'+form_id+'  span.popup_error_txt').text("{{ COMMON_ERROR }}");
                    $('#'+form_id+' .popup_error').show();
                    setTimeout(function(){$('#'+form_id+' .popup_error').fadeOut()}, 5000);
                }

                $('.loader').hide();
                $('body').removeClass('body-overflow');
            }
        });  
        return false;  
    });

    $('.e-sbt-dyn-form-btn').click(function(){
        
        var model_id            = $(this).closest('.modal').attr('id');
        var previous_model_id   = $(this).closest('.modal').find('.previous_modal_btn').attr('pre_modal');
        var logged_box          = $('#'+previous_model_id).find('.logged-box');
        var form_id             = $(this).closest('form').attr('id');

        //var service_user    = $('#'+model_id+' .su_n_id');
        //var form_builder    = $('#'+model_id+' .dynamic_form_select');
        //var service_user_id = service_user.val().trim();   
        //var form_builder_id = form_builder.val().trim();
        //var err = 0;

        /*if(service_user_id == 0) { 
            service_user.parent().addClass('red_border');
            err = 1;
        }else{
            service_user.parent().removeClass('red_border');
        }

        if(form_builder_id == 0) { 
            form_builder.parent().addClass('red_border');
            err = 1;
        } else{
            form_builder.parent().removeClass('red_border');
        }*/

        /*if(err == 1){
            return false;
        }*/

        var formdata = $('#'+form_id).serialize();
        //alert(formdata); 
        $('.loader').show();
        $('body').addClass('body-overflow');

        $.ajax({
            type : 'post',
            url: "{{ url('/service/dynamic-form/edit') }}",
            data : formdata,
            dataType: 'json',
            success:function(resp){

                if(isAuthenticated(resp) == false){
                    return false;
                }

                if(resp == true){

                    $('#'+model_id).modal('hide');
                    $('#'+previous_model_id).modal('show');

                    $('#'+previous_model_id+' span.popup_success_txt').text('Record has been Edited Successfully');
                    $('#'+previous_model_id+' .popup_success').show();
                    setTimeout(function(){$('#'+previous_model_id+' .popup_success').fadeOut()}, 5000);
                    
                    $('#'+previous_model_id+' .logged-btn').click();

                    //$('#'+previous_model_id+' .custm-tabs' 

                    // $('#'+model_id+' .dynamic_form_select').val('0');
                    // $('#'+model_id+' .dynamic-form-fields').html('');

                    //for mfc case only                    
                    /*$(".js-example-placeholder-single-mfc").select2({
                      dropdownParent: $('#mfcModal'),
                      placeholder: "Select Description"
                    });*/

                } else{
                    //show error message
                    $('#'+previous_model_id+'  span.popup_error_txt').text("{{ COMMON_ERROR }}");
                    $('#'+previous_model_id+' .popup_error').show();
                    setTimeout(function(){$('#'+previous_model_id+' .popup_error').fadeOut()}, 5000);
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
$(document).ready(function(){    
    $(document).on('click','.dyn-form-view-data', function(){

        var previous_model_id = $(this).closest('.modal').attr('id');
        var dynamic_form_id   = $(this).attr('id');
        var form_id           = $(this).closest('form').attr('id');
        
        $('.loader').show();
        $('body').addClass('body-overflow');

        $.ajax({
            type : 'get',
            url: "{{ url('/service/dynamic-form/view/data') }}"+'/'+dynamic_form_id,
            dataType: 'json',
            success:function(resp){

                if(isAuthenticated(resp) == false){
                    return false;
                }
                
                var response        = resp['response'];
                var form_builder_id = resp['form_builder_id'];
                var form_title      = resp['form_title'];
                var service_user_id = resp['service_user_id'];
                var form_data       = resp['form_data'];

                if(response == true){
               
                    $('#'+previous_model_id).modal('hide');
                    var view_modal =  '#DynFormViewModal';

                    $(view_modal).modal('show');
                    $(view_modal+' .mdl-back-btn').attr('pre_modal',previous_model_id);

                    $(view_modal+' .dynamic_form_select').val(form_builder_id);
                    $(view_modal+' .su_id').val(service_user_id);
                    $(view_modal+' .dynamic_form_id').val(dynamic_form_id);
                    $(view_modal+' .dynamic-form-fields').html(form_data);

                } else{
                    //show error message
                    $('#'+form_id+'  span.popup_error_txt').text("{{ COMMON_ERROR }}");
                    $('#'+form_id+' .popup_error').show();
                    setTimeout(function(){$('#'+form_id+' .popup_error').fadeOut()}, 5000);
                }

                $('.loader').hide();
                $('body').removeClass('body-overflow');
            }
        });  
        return false;  
    });

    $(document).on('click','#DynFormViewModal .previous_modal_btn', function(){
        var previous_modal_id = $(this).attr('pre_modal');
        $('#'+previous_modal_id).modal('show');
    });

    $(document).on('click','#DynFormViewModal .edit_dyn_form', function(){
        
        var modal_id = 'DynFormViewModal';
        $('#'+modal_id+' .dynamic-form-fields input').attr('disabled',false);
        $('#'+modal_id+' .dynamic-form-fields textarea').attr('disabled',false);
        $('#'+modal_id+' .dynamic-form-fields select').attr('disabled',false);
        $('#'+modal_id+' .e-sbt-dyn-form-btn').attr('disabled',false);

        $('#'+modal_id+' span.popup_success_txt').text('Data is made editable');
        $('#'+modal_id+' .popup_success').show();
        setTimeout(function(){$('#'+modal_id+' .popup_success').fadeOut()}, 5000);

        $('.dpYears').datepicker({
            //format: 'dd/mm/yyyy',
        }).on('changeDate', function(e){
            $(this).datepicker('hide');
        });
        $('#'+modal_id).on('scroll',function(){
            $('.dpYears').datepicker('place')
        });
        //attr('pre_modal');
        return false;
    });
    

});
</script>
<script>
$(document).ready(function(){
    $(document).on('click','.dyn_form_del_btn', function() {   
        
        var this_record = $(this);
        var dyn_form_id = this_record.attr('id');
        
        $('.loader').show();
        $('body').addClass('body-overflow');

        $.ajax({
            type : 'get',
            url  : "{{ url('/service/dynamic-form/delete') }}"+'/'+dyn_form_id,
            success : function(resp) {
                if(isAuthenticated(resp) == false){
                    return false;
                }
                if(resp == 1) {
                    this_record.closest('.rows').remove();

                    //show success delete message
                    $('span.popup_success_txt').text("{{ DEL_RECORD }}");                   
                    $('.popup_success').show();
                    setTimeout(function(){$(".popup_success").fadeOut()}, 5000);
                } else {
                    //show delete message error
                    $('span.popup_error_txt').text('{{ COMMON_ERROR }}');
                    $('.popup_error').show();
                }

                $('.loader').hide();
                $('body').removeClass('body-overflow');
            }
        });
        
        return false;
    });
});
</script>
<!-- <script>
    //making editable click on edit of listing
    $(document).ready(function(){ 
        $(document).on('click','.edit_dyn_details', function(){
            var dyn_form_id = $(this).attr('id');
            $('.edit_dyn_details_'+dyn_form_id).removeAttr('disabled');
            $('.edit_dyn_id_'+dyn_form_id).removeAttr('disabled');
            
            $(this).closest('.cog-panel').find('.input-plusbox').toggle();
            return false;
        });
    });
</script> -->
<!-- <script>
    //saving all editable record
    $(document).ready(function(){
        $(document).on('click','.sbt-edit-dyn-record', function(){
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
                url  : "{{ url('service/dynamic-form/edit-details')  }}",
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
</script> -->

<!-- dynamic form end -->

<!-- <script>
    $(document).ready(function(){
        $('.dynamic_form_select').on('change', function() {   
            
            var form_select = $(this);
            var model_id    = form_select.closest('.modal').attr('id');
            
            var form_id     = form_select.val();
            var form_title  = $('.dynamic_form_select option:selected').text();
            
            if(form_id > 0){
                
                $('.loader').show();
                $('body').addClass('body-overflow');
              
                $.ajax({
                    type:'get',
                    url : "{{ url('/service/dynamic-form/view/pattern') }}"+'/'+form_id,
                    dataType: "json",
                    success:function(resp){
                        
                        if(isAuthenticated(resp) == false){
                            return false;
                        }

                        var response = resp['response'];
                        if(response == true){
                            
                            var pattern = resp['pattern'];
                            $('#'+model_id+' .dynamic-form-fields').html(pattern);
                            $('#'+model_id+' .dynamic_form_h3').html(form_title+' Details');
                            
                            $('.dpYears').datepicker({
                                //format: 'dd/mm/yyyy',
                            }).on('changeDate', function(e){
                                $(this).datepicker('hide');
                            });
                                         
                            //form_select.parent().removeClass('red_border');           
                        } 

                        $('.loader').hide();
                        $('body').removeClass('body-overflow');
                    }
                });

            } else{
                //$('.dynamic-form-fields').
                //$('.entry-default-fields').hide();
            }
        });
    });
</script> -->
<!-- <script>
$(document).ready(function(){
    $('.sbt-dyn-form-btn').click(function(){

        var model_id        = $(this).closest('.modal').attr('id');
        var service_user    = $('#'+model_id+' .su_n_id');
        var form_builder    = $('#'+model_id+' .dynamic_form_select');
        var service_user_id = service_user.val().trim();   
        var form_builder_id = form_builder.val().trim();
        var err = 0;

        if(service_user_id == 0) { 
            service_user.parent().addClass('red_border');
            err = 1;
        }else{
            service_user.parent().removeClass('red_border');
        }

        if(form_builder_id == 0) { 
            form_builder.parent().addClass('red_border');
            err = 1;
        } else{
            form_builder.parent().removeClass('red_border');
        }

        if(err == 1){
            return false;
        }else{ 
            return true;
        }

    });
});
</script>   -->

    <!-- <script src='https://cdn.form.io/formiojs/formio.full.min.js'></script>
    <script>
    Formio.createForm(document.getElementById('formiotest'), {
  components: [
  	{
      type: 'textfield',
      key: 'firstName',
      label: 'First Name'
    },
    {
    	type: 'textfield',
      key: 'lastName',
      label: 'Last Name'
    },
    {
    	type: 'email',
      key: 'email',
      label: 'Email'
    },
    {
    	type: 'button',
      key: 'submit',
      label: 'Submit'
    }
  ]
});

</script> -->