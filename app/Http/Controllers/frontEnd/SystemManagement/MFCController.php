<?php

namespace App\Http\Controllers\frontEnd\SystemManagement;
use App\Http\Controllers\frontEnd\SystemManagementController;
use Illuminate\Http\Request;
use App\DailyRecord, App\MFC;
use DB, Auth;

class MFCController extends SystemManagementController
{
    public function index() {

        $home_id = Auth::user()->home_id;
        $mfc = MFC::where('home_id',$home_id)->where('is_deleted','0')->orderBy('id','desc')->get();

        foreach($mfc as $key => $value) {
            
            if($value->status == 1){
                $mfc_set_btn_class = "clr-blue";
            } else {
                $mfc_set_btn_class = "clr-grey";
            }
                
    echo    '<div class="col-md-12 col-sm-12 col-xs-12 cog-panel p-0 r-p-15 mfc_row">

                    <div class="form-group col-md-9 col-sm-9 col-xs-12 r-p-0">
                        <div class="input-group popovr">
                            <input type="text" name="edit_mfc_desc[]" class="form-control edit_mfc_desc'.$value->id.' edit_mfc" disabled value="'.$value->description.'"/>
                            <input type="hidden" name="edit_mfc_id[]" value="'.$value->id.'" disabled="disabled" class="edit_mfc_id_'.$value->id.'" />

                            <span class="input-group-addon cus-inpt-grp-addon '.$mfc_set_btn_class.' settings">
                                <i class="fa fa-cog"></i>
                                <div class="pop-notifbox">
                                    <ul class="pop-notification" type="none">
                                        <li> <a href="#" mfc_id="'.$value->id.'" class="edit_mfc_btn"> <span> <i class="fa fa-pencil"></i> </span> Edit </a> </li>
                                        <li> <a href="delete" mfc_id="'.$value->id.'"  class="delete_mfc_btn"> <span class="color-red"> <i class="fa fa-exclamation-circle"></i> </span> Remove </a> </li>
                                        <li> <a href="#" mfc_id="'.$value->id.'" class="status_mfc_chnge_btn"> <span class="color-yellow"> <i class="fa fa-bolt"></i> </span> Active/Inactive </a> </li>
                                    </ul>
                                </div>
                            </span>
                        </div>
                    </div>

                </div>
            </div>';
        }
    }

    public function add(Request $request)   {
       
        if($request->isMethod('post'))  {

           $data = $request->all();
           $home_id = Auth::user()->home_id;

           $mfc                 = new MFC;
           $mfc->description    = $data['mfc_description']; 
           // $mfc->score          = $data['record_score'];
           $mfc->status         = 1;
           $mfc->home_id        = $home_id;

            if($mfc->save()){
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
        $edit_mfc_ids = $data['edit_mfc_id'];

        if(!empty($edit_mfc_ids)){
            foreach ($edit_mfc_ids as $key => $mfc_id) {

                $mfc = MFC::where('id',$mfc_id)->where('home_id', Auth::user()->home_id)->first();
                if(!empty($mfc)){
                    // $daily_record->score        = $data['edit_record_score'][$key];
                    $mfc->description  = $data['edit_mfc_desc'][$key];
                    $mfc->save();
                }
            }
        }
        $res = $this->index();
        echo $res;  die;
    }

    public function delete($mfc_id){

        if(!empty($mfc_id)){

            $res = MFC::where('id', $mfc_id)->where('home_id',Auth::user()->home_id)->update(['is_deleted'=>'1']);
            echo $res;
        }
    }

    public function update_status($mfc_id = null){
        
        $mfc = MFC::where('id', $mfc_id)->where('home_id',Auth::user()->home_id)->first();

        if(!empty($mfc)){

            if($mfc->status == '0'){
                $new_status = 1;
            } else{
                $new_status = 0;
            }
            //echo '$new_status='.$new_status;
            $mfc->status = $new_status;
           
            if($mfc->save()){
                echo true;
            } else{
                echo false;
            }
        } else {
            echo false;
        }
        die;
    }
}