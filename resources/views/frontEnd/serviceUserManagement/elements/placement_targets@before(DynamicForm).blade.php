<!-- add active target details model start -->
<div class="modal fade" id="viewTargetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title view-target-title"> </h4>
            </div>
            <form method="post" id="add-active-target-details" action="{{ url('/service/placement-plan/edit') }}">
                <div class="modal-body">
                    <div class="row">

                        <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                            <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> User: </label>
                            <div class="col-md-11 col-sm-11 col-xs-12">
                                <div class="select-style">
                                    <select name="service_user_id" disabled="">
                                        <option value="{{ $service_user->id }}"> {{ $service_user->name }} </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                            <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> Type: </label>
                            <div class="col-md-11 col-sm-11 col-xs-10">
                                <div class="select-style">
                                    <select name="plan_builder_id" disabled="">
                                        <option value="0"> Placement Plan</option>
                                    </select>
                                
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="below-divider"></div>
                        </div>
                        <!-- alert messages -->
                        @include('frontEnd.common.popup_alert_messages')

                        <div class="risk-tabs" >
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h3 class="m-t-0 m-b-20 clr-blue fnt-20"> Details </h3>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 cog-panel">
                                <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                                    <label class="col-md-1 col-sm-2 col-xs-12 p-t-7"> Task: </label>
                                    <div class="col-md-11 col-sm-9 col-xs-12 r-p-0 p-l-30">
                                        <div class="input-group popovr">
                                            <input name="target_task" required value="" class="form-control bmp_risk" type="text" maxlength="255">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 cog-panel">
                                <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                                    <label class="col-md-1 col-sm-2 col-xs-12 p-t-7"> Detail: </label>
                                    <div class="col-md-11 col-sm-9 col-xs-12 r-p-0 p-l-30">
                                        <div class="input-group popovr">
                                           <textarea name="target_description" required class="form-control" rows="2" cols="20" placeholder="Detail" maxlength="1000"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12 cog-panel datepicker-sttng">      
                                <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                                    <label class="col-md-1 col-sm-2 col-xs-12 p-t-7"> Date: </label>
                                    <div class="col-md-11 col-sm-9 col-xs-12 r-p-0 p-l-30">
                                        <div data-date-viewmode="" data-date-format="dd-mm-yyyy" data-date="" class="input-group date"> <!-- dpYears -->
                                           <input name="target_date" required type="text" value="" readonly="" size="16" class="form-control date-pick" maxlength="10">
                                            <span class="input-group-btn add-on datetime-picker1" >
                                                <input style="left:-1px; top:0px;" type="text" value="" name="" id="target-new-date" class="form-control date-btn">
                                                <button class="btn btn-primary" type="button"><span class="glyphicon glyphicon-calendar"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="created-fields">
                            <!-- active-target-fields -->
                            </div>
                                    
                            <div class="entry-data-fields">
                            <!-- dynamic data fields should be shown here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer m-t-0">
                    <input type="hidden" name="service_user_id" value="{{ $service_user_id }}">
                    <input type="hidden" name="placement_plan_id" value="" >
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                    <button class="btn btn-warning submit-target-details" type="submit"> Confirm </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- add active target details model end -->

<div class="modal fade" id="qqaReviewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title qqa-review-title"> QQA-Review </h4>
            </div>
            <form method="post" action="{{ url('/service/placement-plan/add-qqa-review') }}" id="add-qqa-review">
                <div class="modal-body">
                    <div class="row">

                        <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0">
                            <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> Task: </label>
                            <div class="col-md-11 col-sm-11 col-xs-12">
                                <div class="select-style">
                                    <input name="target_task" disabled="disabled" value="" class="form-control " type="text" maxlength="255">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0">
                            <label class="col-md-1 col-sm-1 col-xs-12 p-t-7">QQA: </label>
                            <div class="col-md-11 col-sm-11 col-xs-12">
                                <textarea name="qqa_review" required rows="5" class="form-control" maxlength="1000"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer m-t-0">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="service_user_id" value="{{ $service_user_id }}">
                    <input type="hidden" name="placement_plan_id" value="">
                    <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                    <button class="btn btn-warning save-qqa-review" type="submit"> Confirm </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    //alert(1);
    today  = new Date; 
    $('#target-new-date').datetimepicker({
        format: 'dd-mm-yyyy',
        startDate: today,
        minView : 2,
        setDate : new Date(2017,06,01)
    }).on("change.dp",function (e) { 
        var currdate =$(this).data("datetimepicker").getDate();
        var newFormat = currdate.getDate()+"-" +(currdate.getMonth() + 1)+"-"+currdate.getFullYear();
        $('.date-pick').val(newFormat);
    });

    $('#target-new-date').on('click', function(){
        $('#target-new-date').datetimepicker('show');
    });

    $( "#viewTargetModal" ).scroll(function() {
        $('#new-date-su').datetimepicker('place')
    });
    
    $('#target-new-date').on('change', function(){
        $('#target-new-date').datetimepicker('hide');
    });
});
</script>

<script>
    $(document).on('click','.view_qqa_review_btn',function(){

        var target_task = $(this).parent().attr('target_task');
        var qqa = $(this).attr('qqa');
        var placement_plan_id = $(this).parent().attr('target_id');

        $('input[name=\'target_task\']').val(target_task);
        $('textarea[name=\'qqa_review\']').val(qqa);
        $('input[name=\'placement_plan_id\']').val(placement_plan_id);
        // $('input[name=\'placement_plan_id\']') = $(this).parent().attr('target_id');
        $('#qqaReviewModal').modal('show');

    });
</script>

<!-- <script>
    $(document).ready(function(){
        $('.save-qqa-review').click(function(){
        
            var qqa_review = $('textarea[name=\'qqa_review\']').val();
            var err = 0;

            if(qqa_review == '') { 
                $('textarea[name=\'qqa_review\']').addClass('red_border');
                err = 1;
            } else{
                $('textarea[name=\'qqa_review\']').removeClass('red_border');
            }

            if(err == 1){
                return false;
            } else{ 
                return true;
            }
        });
    });
</script> -->

<script>
    
        $(document).on('click', '.view_active_target_btn', function(){
          
            if ($(this).hasClass('active-targets')){
                $('.view-target-title').text('Active Targets');
            }
            else{
                $('.view-target-title').text('Make Target Active');   
            }

            var target_id = $(this).parent().attr('target_id');

            $('.loader').show();
            $('body').addClass('body-overflow');

            $.ajax({
                type : 'get',
                url  : "{{ url('/service/placement-plan/target/view') }}"+'/'+target_id,
                dataType : 'json',
                success:function(resp){

                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    
                    var response = resp['response'];
                    
                    if(response == true)    {
                    
                        var task            = resp['task'];
                        var description     = resp['description'];
                        var date            = resp['date'];
                        var pp_form         = resp['pp_form'];
                        
                        $('input[name=\'target_task\']').val(task);
                        $('textarea[name=\'target_description\']').val(description);

                        $('input[name=\'target_date\']').val(date);
                        $('input[name=\'target_date\']').parent().attr('data-date',date);

                        $('input[name=\'placement_plan_id\']').val(target_id);
                        $('.created-fields').html(pp_form);
                        $('#viewTargetModal').modal('show');

                        //date

                    } else{
                        var error = resp['error'];
                        if(error == 'AUTH'){
                            alert('{{ UNAUTHORIZE_ERR }}');
                        } else{
                            alert('{{ COMMON_ERROR }}');
                        }
                    }

                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
        });
   
</script>