<?php

namespace App\Http\Controllers\frontEnd\SystemManagement;
use App\Http\Controllers\frontEnd\SystemManagementController;
use Illuminate\Http\Request;
use App\LivingSkill;
use DB, Auth;

class LivingSkillController extends SystemManagementController
{
    public function index(){

        $home_id = Auth::user()->home_id;
        $living_skills = LivingSkill::where('home_id',$home_id)->where('status','1')->where('is_deleted','0')->orderBy('id','desc')->get();
       
        foreach($living_skills as $key => $value) {
            
            if($value->status == 1) {
                $skill_set_btn_class = "clr-blue";
            } else {
                $skill_set_btn_class = "clr-grey";
            }
            
        /*status_record_change_btn*/        
        echo '<div class="col-md-12 col-sm-12 col-xs-12 cog-panel p-0 r-p-15 record_row">

                        <div class="form-group col-md-9 col-sm-9 col-xs-12 r-p-0">
                            <div class="input-group popovr">
                                <span class="input-group-addon cus-inpt-grp-addon"> <label> <input type="checkbox" value="'.$value->id.'"/> </label> </span>
                                <input type="text" name="edit_skill_desc[]" class="form-control edit_skill_desc_'.$value->id.' edit_skill" disabled value="'.$value->description.'"/>
                                <input type="hidden" name="edit_skill_id[]" value="'.$value->id.'" disabled="disabled" class="edit_skill_id_'.$value->id.'" />

                                <span class="input-group-addon cus-inpt-grp-addon '.$skill_set_btn_class.' settings">
                                    <i class="fa fa-cog"></i>
                                    <div class="pop-notifbox">
                                        <ul class="pop-notification" type="none">
                                            <li> <a href="#" living_skill_id="'.$value->id.'" class="edit_skill_btn"> <span> <i class="fa fa-pencil"></i> </span> Edit </a> </li>
                                            <li> <a href="delete" living_skill_id="'.$value->id.'"  class="delete_skill_btn"> <span class="color-red"> <i class="fa fa-exclamation-circle"></i> </span> Remove </a> </li>
                                            <li> <a href="#" living_skill_id="'.$value->id.'" class="skill_status_change" name="status_living_skill"> <span class="color-yellow"> <i class="fa fa-bolt"></i> </span> Active/Inactive </a> 
                                            </li>
                                        </ul>
                                    </div>
                                </span>

                            </div>
                        </div>
                    </div>
                </div>';
                }
        }
        
    public function add(Request $request){
        
        if($request->isMethod('post'))
        {
           $data = $request->all();
           $home_id = Auth::user()->home_id;

           $skill                 = new LivingSkill;
           $skill->description    = $data['skill_description']; 
           // $record->score      = $data['record_score'];
           $skill->status         = 1;
           $skill->home_id        = $home_id;

           if($skill->save()){
            //echo '0'; die;
            $res = $this->index();
            echo $res;
           }
           else{
            echo '0';
            }
            die;
        }
    }

   public function edit(Request $request){
      
        $data = $request->all();

        $skill_ids = $data['edit_skill_id']; 

        if(!empty($skill_ids)){
            foreach ($skill_ids as $key => $skill_id) {
                
                $living_skill = LivingSkill::where('id',$skill_id)->where('home_id',Auth::user()->home_id)->first();
                if(!empty($living_skill)){
                    // $living_skill->score        = $data['edit_record_score'][$key];
                    $living_skill->description  = $data['edit_skill_desc'][$key];
                    $living_skill->save();
                }
            }
        }
        $res = $this->index();
        echo $res;  die;
    }

    public function delete($living_skill_id)    {

        if(!empty($living_skill_id))    {
            $res = LivingSkill::where('id', $living_skill_id)->where('home_id',Auth::user()->home_id)->update(['is_deleted'=>'1']);
            echo $res;
        }
    }

    public function update_status($living_skill_id = null){
        
        // echo $living_skill_id; die;
        $living_skill = LivingSkill::where('id', $living_skill_id)->where('home_id',Auth::user()->home_id)->first();
        // echo "<pre>"; print_r($living_skill); die; 
        
        if(!empty($living_skill)){
            if($living_skill->status == '0'){
                $new_status = 1;
            } else{
                $new_status = 0;
            }
            //echo '$new_status='.$new_status;
            $living_skill->status = $new_status;
           
            if($living_skill->save()){
                echo true;
            } else{
                echo false;
            }
        } else{
            echo false;
        }
        die;
    }
    public function living_skill_delete(Request $request){
        
        $skill_del = LivingSkill::whereIn('id', $request->skill_id)->where('home_id',Auth::user()->home_id)->update(['is_deleted'=>'1']);
            
        if($skill_del){
            echo '1';
        }else{
            echo "2";
        }
    }
}