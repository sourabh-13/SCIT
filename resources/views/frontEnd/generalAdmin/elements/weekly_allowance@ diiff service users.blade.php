<!--  AddWeeklyMoney model start -->
<div class="modal fade" id="AddWeeklyMoney" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Set weekly allowance for service users</h4>
            </div>
            <form id="deposit_weekly_amount" method="post" action="{{ url('weekly-allowance/update') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <!-- <label class="col-md-3 m-t-20 text-right">Enter Amount</label>
                            <div class="col-md-9">
                                <div class="style-input m-t-15">
                                    <input type="text" name="amount_add" value="{{ $home->weekly_allowance }}" class="form-control">
                                </div>
                            </div> -->
                            <!-- <div class="modal-footer incen-foot">
                                <button type="submit" class="btn btn-primary save-target-btn">Add Money</button>
                            </div> -->
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h3 class="m-tb-10 clr-blue fnt-20"> Service User's List </h3>
                            </div>
                            <div class="service-list-serv">
                                <?php
                                    $weekly_allowance_service_users = $home->weekly_allowance_service_users;
                                    $weekly_allowance_service_users = explode(',',$weekly_allowance_service_users); 

                                    $pre_selected_su = count($weekly_allowance_service_users);
                                    $total_su = count($service_users);
                                ?>

                                <div class="selct-serv-all">
                                    <div class="checkbox">
                                        <label><input type="checkbox" class="all_su_select" value="" {{ ($total_su == $pre_selected_su) ? 'checked' : '' }} > Select All</label>
                                    </div>
                                </div>
                                <ul>
                                    
                                    @foreach($service_users as $value)
                                        <?php 
                                        if(!empty($value['image'])){
                                            $user_image = $value['image'];
                                        } else{
                                            $user_image = 'default_user.jpg';
                                        } ?> 
                                    <li>
                                        <!-- <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="service_users[]" class="su" value="{{ $value['id'] }}" {{ (in_array($value['id'],$weekly_allowance_service_users)) ? 'checked' : '' }}>
                                                <img src="{{ serviceUserProfileImagePath.'/'.$user_image }}" class="img-responsive1">{{ $value['name'] }}
                                            </label>
                                        </div> -->
                                        <div class="col-md-12">
                                        <div class="col-md-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="service_users[]" class="su" value="{{ $value['id'] }}" >
                                                    <img src="{{ serviceUserProfileImagePath.'/'.$user_image }}" class="img-responsive1">{{ $value['name'] }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control m-t-10" name="" placeholder="Enter Amount" />
                                        </div>
                                        </div>
                                    </li>
                                    @endforeach

                                    <!-- <li>
                                        <div class="col-md-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="service_users[]" class="su" value="{{ $value['id'] }}" >
                                                    <img src="{{ serviceUserProfileImagePath.'/'.$user_image }}" class="img-responsive1">{{ $value['name'] }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control m-t-10" name="" />
                                        </div>
                                    </li> -->
    
                                </ul>
                            </div>
                            <label class="col-md-12 m-t-10"> Note: This money will be automatically added to all selected service user's My money on every week.</label>

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
</div>
<!--  AddWeeklyMoney model end -->

<script>
    $(document).on('click','#AddWeeklyMoney .all_su_select', function(){
        if($(this).is(':checked')) {
            $('#AddWeeklyMoney .su').prop('checked',true); 
        } else{
            $('#AddWeeklyMoney .su').prop('checked',false); 
        }
    });
</script>


