
<!-- Risk RMP Plan Add-->
<div class="modal fade" id="rmpAddModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close rmp-modal-close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"> Risk Management Plan - Add</h4>
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
                            <div class="select-style title-name">
                                <select disabled="disabled">
                                    <option> Risk Management Plan </option>
                                </select>
                            </div>
                            <p class="help-block"></p>
                        </div>
                        <!--  <div class="col-md-1 col-sm-1 col-xs-1 p-0">
                            <button class="btn group-ico" type="submit" > <i class="fa fa-plus"></i> </button>
                        </div> -->
                    </div>
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="below-divider"></div>
                    </div>

                    <!-- alert messages -->
                    @include('frontEnd.common.popup_alert_messages')

                    <!-- Add new Details -->
                    <div class="risk-tabs">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3 class="m-t-0 m-b-20 clr-blue rmp-details fnt-20"> RMP Details </h3>
                        </div>
        <form method="post" action="" id="rmp_risk_form">
                        <div class="col-md-12 col-sm-12 col-xs-12 cog-panel">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                                <label class="col-md-1 col-sm-1 col-xs-12"> Title: </label>
                                <div class="col-md-11 col-sm-11 col-xs-12 r-p-0">
                                    <div class="input-group popovr">
                                        <input name="rmp_title" value="" type="text" class="form-control" maxlength="255" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 cog-panel">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 ">
                                <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> Sent To: </label>
                                <div class="col-md-11 col-sm-11 col-xs-10">
                                  <div class="select-style">
                                    <select name="sent_to">
                                      <option value="0">All contact</option>
                                      <option value="1">staff</option>
                                      <option value="2">relative</option>
                                    </select>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="dynamic-rmp-fields">
                            {!! $form_pattern['su_rmp'] !!}
                        </div>
                    </div>   
                </div>
            </div>
            <div class="modal-footer m-t-0">
                <input type="hidden" name="service_user_id" value="{{ $patient->id }}">
                <input type="hidden" name="su_risk_id" value="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button class="btn btn-default rmp-modal-close" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                <button class="btn btn-warning smt_rmp_btn" type="submit"> Confirm </button>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- View Risk RMP Plan -->
<div class="modal fade" id="rmpViewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <a class="close view-risk-back-btn mdl-back-btn" href="" data-toggle="modal" data-dismiss="modal" data-target="">
                    <i class="fa fa-arrow-left" title=""></i>
                </a>
                <h4 class="modal-title"> Risk Management Plan - View</h4>
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
                                    <option> Risk Management Plan </option>
                                </select>
                            </div>
                            <p class="help-block"></p>
                        </div>
                       <!--  <div class="col-md-1 col-sm-1 col-xs-1 p-0">
                            <button class="btn group-ico" type="submit" > <i class="fa fa-plus"></i> </button>
                        </div> -->
                    </div>
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="below-divider"></div>
                    </div>

                    <!-- alert messages -->
                    @include('frontEnd.common.popup_alert_messages')

                    <!-- Add new Details -->
                    <div class="risk-tabs">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3 class="m-t-0 m-b-20 clr-blue fnt-20"> RMP Details </h3>
                        </div>
                    <form method="post" action="" id="edit_rmp_risk_form">
                        <div class="col-md-12 col-sm-12 col-xs-12 cog-panel">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                                <label class="col-md-1 col-sm-1 col-xs-12"> Title: </label>
                                <div class="col-md-11 col-sm-11 col-xs-12 r-p-0 p-l-30">
                                    <div class="input-group popovr">
                                        <input name="edit_rmp_title" value="" class="form-control v-rmp_title" type="text" maxlength="255"><!-- v-rmp_title -->
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
                        <div class="edit-dynamic-rmp-fields">
                          <!-- created form fields from controller will be placed here -->
                        </div>
                        
                    </div>
                    
                </div>
            </div>
                <div class="modal-footer m-t-0">
                    <input type='hidden' name='su_risk_id' value=''>
                    <input type='hidden' name='su_rmp_id' value=''>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                    <button class="btn btn-warning smt_edit_rmp_btn" type="button" data-dismiss="modal"> Continue </button>
                    <!-- <button class="btn btn-warning" data-toggle="modal" data-target="#riskViewModal" type="button" data-dismiss="modal"> Continue </button> -->
                </div>
            </form>
        </div>
    </div>
</div>



<!-- <script>
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
</script> -->
<!-- 
<script>
    $(document).ready(function(){
        $('.change_risk_submit_btn').on('click', function(){

            $('#riskDesc').modal('hide');
            $('#rmpAddModal').modal('show');

            $('.dynamic-rmp-fields').find('input').val('');
            $('.dynamic-rmp-fields').find('textarea').val('');
            $('input[name=\'rmp_title\']').val('');

            var su_risk_id = $(this).attr('risk_id');

            
            $('input[name=\'su_risk_id\']').val(su_risk_id);
            
            return false;
        });
    });
</script> -->

<script>
    $(document).ready(function(){
        $('.smt_rmp_btn').on('click', function(){
            // alert('y'); return false;
            var rmp_title = $('input[name=\'rmp_title\']').val();

            error = 0;
            rmp_title = jQuery.trim(rmp_title);
            if(rmp_title == '' || rmp_title == null) {
                $('input[name=\'rmp_title\']').addClass('red_border');
                error = 1;
            } else {
                 $('input[name=\'rmp_title\']').removeClass('red_border');
            }
            if(error == 1) {
                return false;
            }

            var formdata = $('#rmp_risk_form').serialize();
            $('.loader').show();
            $('body').addClass('body-overflow');

            $.ajax({
                type:  'post',
                url :  "{{ url('/service/risk/rmp/add') }}",
                data:   formdata,
                dataType: 'json',
                success:function(resp) {
                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    var response = resp['response'];
                    if(response == '1') {
                        $('.dynamic-rmp-fields').find('input').val('');
                        $('.dynamic-rmp-fields').find('textarea').val('');
                        $('input[name=\'rmp_title\']').val('');
                        $('#rmpAddModal').modal('hide');

                        $('#incdntRprtModal').modal('show');

                        //show success message
                        $('span.popup_success_txt').text('RMP Risk Details Added Successfully.');
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
        $('#rmpAddModal').on('scroll',function(){
            $('.dpYears').datepicker('place')
        });
       
        
    });
</script>

<!-- sticky notification script on modal close-->
<script>
    $('.rmp-modal-close').click(function(){
        //var risk_rmp_notif_ttl = $('.rmp-details').text();
        var risk_name = $('input[name=\'risk_name\']').val();
        var unique_id = $.gritter.add({
                title: 'Risk Details',
                text: 'Please fill the required RMP/Incident Report Form of '+risk_name+' risk',
                //text: 'Please fill the required RMP/Incident Report Form of  .',
                sticky: true,
                class_name: 'my-sticky-class'
        });
    });
</script>

<script>
        //var risk_inc_notif_ttl = $('.inc-details').text();
        //$(document).ready(function(){
        /* var Gritter = function () {    
            alert('mk'); 
            var risk_inc_notif_ttl = 'mk';
            
            var unique_id = $.gritter.add({
                    title: risk_inc_notif_ttl,
                    text: 'Please fill the necessary form and submit it.',
                    sticky: true,
                    // (int | optional) the time you want it to be alive for before fading out
                    //time: '',
                    // (string | optional) the class name you want to apply to that specific message
                    class_name: 'my-sticky-class'
            });
        }();*/
        //});
</script>
