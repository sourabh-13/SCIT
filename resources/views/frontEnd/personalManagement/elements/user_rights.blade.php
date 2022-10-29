<div id="manager_access_rights" class="tab-pane">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <div class="panel-body">
                    <div class="position-center">
                        <form role="form" action="" method="post">
                            <div class="form-group">
                               
                               <div class="form-group">
                                <div class=" col-sm-10">
                                <label>Dashboard</label>
                                  <?php  foreach($dashboard as $value){ ?>
                                  <div class="checkbox"> 
                                    <label><input type="checkbox" name="access_id[]" value="{{ $value['id'] }}" {{ (in_array($value['id'],$user_rights)) ? 'checked':'' }} disabled="">{{ ucfirst($value['module_name']) }}</label>
                                  </div>
                                  <?php } ?>
                                </div>
                            </div>
                    
                          <?php foreach($managements as $management){ ?>
                            <div class="form-group">
                                <div class=" col-sm-10">
                                <label>{{ ucfirst($management['name']) }}</label>
                                  <?php foreach($management['module_list'] as $module){ ?>
                                  <div class="checkbox"> 
                                    <!-- name="module_code[]" value="{{ $module['module_code'] }}" -->
                                        <?php
                                        $chekd_checkbx = 0;
                                        $total_checkbx = 0;
                                        foreach($module['sub_modules'] as $sub_modules){
                                            if(in_array($sub_modules['id'],$user_rights)){
                                                $chekd_checkbx++; 
                                            }
                                            $total_checkbx++;
                                        }
                                    
                                        if($total_checkbx == $chekd_checkbx){
                                            $selected = 'y';
                                        } else{
                                            $selected = 'n';
                                        }
                                    
                                          ?>

                                    <label><input type="checkbox" class="acc_heading_chkbox" {{ ($selected == 'y') ? 'checked':'' }} disabled=""> {{ ucfirst($module['module_name']) }}</label>
                                    <ul type="none" class="sub-checkbox">
                                        <?php  foreach($module['sub_modules'] as $sub_modules){ ?>

                                        <li><label><input type="checkbox" name="access_id[]" value="{{ $sub_modules['id'] }}" {{ (in_array($sub_modules['id'],$user_rights)) ? 'checked':'' }} disabled="">{{ ucfirst($sub_modules['submodule_name']) }}</label></li>
                                        
                                        <?php } ?>
                                    </ul>
                                  </div>
                                  <?php } ?>
                                 
                                </div>
                            </div>
                          <?php } ?>

                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

