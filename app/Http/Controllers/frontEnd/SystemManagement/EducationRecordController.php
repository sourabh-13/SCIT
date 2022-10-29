<?php

namespace App\Http\Controllers\frontEnd\SystemManagement;
use App\Http\Controllers\frontEnd\SystemManagementController;
use Illuminate\Http\Request;
use App\EducationRecord;
use DB, Auth;

class EducationRecordController extends SystemManagementController
{
    public function index(){

        $home_id     = Auth::user()->home_id;
        // echo "<pre>"; print_r($home_id); die;
        $edu_records = EducationRecord::where('home_id',$home_id)->where('is_deleted','0')->orderBy('id','desc')->get();
        foreach($edu_records as $key => $value) {
            
            if($value->status == 1){
                $edu_set_btn_class = "clr-blue";
            } else{
                $edu_set_btn_class = "clr-grey";
            }
        echo '<div class="col-md-12 col-sm-12 col-xs-12 cog-panel p-0 r-p-15 record_row">
                        <div class="form-group col-sm-12 col-xs-12 r-p-0">
                            <div class="input-group popovr">
                                <span class="input-group-addon cus-inpt-grp-addon"> <label> <input type="checkbox" value="'.$value->id.'"/> </label> </span>
                                <div class="col-sm-9 col-xs-12">
                                    <input type="text" name="edu_rec_desc[]" class="form-control edit_edu_rec_desc_'.$value->id.' edit_rcrd" disabled value="'.$value->description.'"/>
                                </div>
                                <div class="col-sm-3 col-xs-12">
                                    <input type="text" name="edu_rec_amount[]" class="form-control edit_edu_rec_desc_'.$value->id.' edit_rcrd" disabled value="'.$value->amount.'"/>
                                </div>
                                <input type="hidden" name="edu_rec_id[]" value="'.$value->id.'" disabled="disabled" class="edit_edu_rec_id_'.$value->id.'" />
                                <span class="input-group-addon cus-inpt-grp-addon '.$edu_set_btn_class.' settings">
                                    <i class="fa fa-cog"></i>
                                    <div class="pop-notifbox">
                                        <ul class="pop-notification" type="none">
                                            <li> <a href="#" edu_rec_id="'.$value->id.'" class="edit_edu_rec_btn"> <span> <i class="fa fa-pencil"></i> </span> Edit </a> </li>
                                            <li> <a href="delete" edu_rec_id="'.$value->id.'"  class="delete_edu_rec_btn"> <span class="color-red"> <i class="fa fa-exclamation-circle"></i> </span> Remove </a> </li>
                                            <li> <a href="#" edu_rec_id="'.$value->id.'" class="rec_status_chnge_btn" name="edu_rec_status"> <span class="color-yellow"> <i class="fa fa-bolt"></i> </span>Active/Inactive</a> </li>
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
        
        if($request->isMethod('post')){

            $data    = $request->all();
            $home_id = Auth::user()->home_id;
            $edu_rec              = new EducationRecord;
            $edu_rec->description = $data['edu_rec_desc'];
            $edu_rec->amount      = $data['edu_rec_amount'];
            $edu_rec->status      = 1;
            $edu_rec->home_id     = $home_id;

            if($edu_rec->save()){
               $res = $this->index();
               echo $res; die;
            }
            else{
                echo '0';
            }
            die;
        }
    }

    public function edit(Request $request){

        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        $edu_rec_ids = $data['edu_rec_id'];
        
        if(!empty($edu_rec_ids)){
            foreach ($edu_rec_ids as $key => $edu_rec_id) {
                
                $edu_rec = EducationRecord::where('id',$edu_rec_id)->where('home_id',Auth::user()->home_id)->first();
                // if(!empty($edu_rec)){
                //     $edu_rec->description  = $data['edu_rec_desc'][$key];
                //     $edu_rec->amount       = $data['edu_rec_amount'][$key];
                //     $edu_rec->save();
                // }
                if(!empty($edu_rec)){
                    $edu_rec_amount = preg_replace('/[^0-9]/', '', $data['edu_rec_amount'][$key]);
                    $edu_rec->description  = $data['edu_rec_desc'][$key];
                    $edu_rec->amount       = $edu_rec_amount;
                    $edu_rec->save();
                }
            }
        }
        $res = $this->index();
        echo $res;  die;
    }

    public function delete($edu_rec_id){

        if(!empty($edu_rec_id)){
            $res = EducationRecord::where('id', $edu_rec_id)->where('home_id',Auth::user()->home_id)->update(['is_deleted'=>'1']);
            echo $res;
        }
    }

    public function update_status($edu_rec_id = null){
        
        $edu_rec = EducationRecord::where('id', $edu_rec_id)->where('home_id',Auth::user()->home_id)->first();

        if(!empty($edu_rec)){
            if($edu_rec->status == '0'){
                $new_status = 1;
            } else{
                $new_status = 0;
            }
            //echo '$new_status='.$new_status;
            $edu_rec->status = $new_status;
           
            if($edu_rec->save()){
                echo true;
            } else{
                echo false;
            }
        } else{
            echo false;
        }
        die;
    }
    
    public function edu_record_delete(Request $request){
        // echo "<pre>"; print_r($request->edu_id); die;
        if(!empty($request->edu_id)){
            $res = EducationRecord::whereIn('id', $request->edu_id)->where('home_id',Auth::user()->home_id)->update(['is_deleted'=>'1']);
            if($res){
                echo '1';
            }else{
                echo "2";
            }
        }else{
            echo "2";
        }
    }
}