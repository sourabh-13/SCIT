<?php

namespace App\Http\Controllers\frontEnd\SystemManagement;
use App\Http\Controllers\frontEnd\SystemManagementController;
use Illuminate\Http\Request;
use App\DailyRecord;
use DB, Auth;

class DailyRecordController extends SystemManagementController
{
    public function index(){

       $home_id = Auth::user()->home_id;
       $daily_records = DB::table('daily_record')->where('is_deleted','0')->where('home_id',$home_id)->orderBy('id','desc')->get();
       
        foreach($daily_records as $key => $value) {
            
            if($value->status == 1){
                $record_set_btn_class = "clr-blue";
            } else{
                $record_set_btn_class = "clr-grey";
            }
        echo '<div class="col-md-12 col-sm-12 col-xs-12 cog-panel p-0 r-p-15 record_row resp-padd-rmv">
                        <div class="form-group col-md-9 col-sm-9 col-xs-12 r-p-0">
                            <div class="input-group popovr">
                                <span class="input-group-addon cus-inpt-grp-addon"> <label> <input type="checkbox" value="'.$value->id.'"/> </label> </span>
                                <input type="text" name="edit_record_desc[]" class="form-control edit_record_desc_'.$value->id.' edit_rcrd" disabled value="'.$value->description.'"/>
                                <input type="hidden" name="edit_record_id[]" value="'.$value->id.'" disabled="disabled" class="edit_record_id_'.$value->id.'" />

                                <span class="input-group-addon cus-inpt-grp-addon '.$record_set_btn_class.' settings">
                                    <i class="fa fa-cog"></i>
                                    <div class="pop-notifbox">
                                        <ul class="pop-notification" type="none">
                                            <li> <a href="#" daily_record_id="'.$value->id.'" class="edit_record_btn"> <span> <i class="fa fa-pencil"></i> </span> Edit </a> </li>
                                            <li> <a href="delete" daily_record_id="'.$value->id.'"  class="delete_record_btn"> <span class="color-red"> <i class="fa fa-exclamation-circle"></i> </span> Remove </a> </li>
                                            <li> <a href="#" daily_record_id="'.$value->id.'" class="status_record_change_btn" name="status_daily_record"> <span class="color-yellow"> <i class="fa fa-bolt"></i> </span> Active/Inactive </a> </li>
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
          // echo '<pre>'; print_r($data); die;
           $record                 = new DailyRecord;
           $record->description    = $data['record_description']; 
           //$record->score          = $data['record_score'];
           $record->status         = 1;
           $record->home_id        = $home_id;

           if($record->save()){
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
        $record_ids = $data['edit_record_id'];
        
        if(!empty($record_ids)){
            foreach ($record_ids as $key => $record_id) {
                
                $daily_record = DailyRecord::where('id',$record_id)->where('home_id',Auth::user()->home_id)->first();
                if(!empty($daily_record)){
                    //$daily_record->score        = $data['edit_record_score'][$key];
                    $daily_record->description  = $data['edit_record_desc'][$key];
                    $daily_record->save();
                }
            }
        }

        $res = $this->index();
        echo $res;  die;
    }

    public function delete($daily_record_id){

        if(!empty($daily_record_id)){
            $res = DailyRecord::where('id', $daily_record_id)->where('home_id',Auth::user()->home_id)->update(['is_deleted'=>'1']);
            echo $res;
        }
  
    }

    public function update_status($daily_record_id = null){
        
        $daily_record = DailyRecord::where('id', $daily_record_id)->where('home_id',Auth::user()->home_id)->first();

        if(!empty($daily_record)){
            if($daily_record->status == '0'){
                $new_status = 1;
            } else{
                $new_status = 0;
            }
            //echo '$new_status='.$new_status;
            $daily_record->status = $new_status;
           
            if($daily_record->save()){
                echo true;
            } else{
                echo false;
            }
        } else{
            echo false;
        }
        die;
    }
    
    public function delete_daily_records(Request $request){
        // echo "<pre>"; print_r($request->input()); die;
        $record_del = DailyRecord::whereIn('id', $request->record_id)->where('home_id',Auth::user()->home_id)->update(['is_deleted'=>'1']);
        if($record_del){
            echo '1';
        }else{
            echo "2";
        }
    }

}
/* unused daily records score
<div class="form-group col-md-3 col-sm-3 col-xs-12 p-0">
    <label class="col-md-5 col-sm-5 col-xs-12 p-t-7 r-p-0"> Score: </label>
    <div class="col-md-7 col-sm-7 col-xs-12 p-0">
        <div class="select-style small-select ">
            <select name="edit_record_score[]" disabled class="edit_record_score_'.$value->id.' edit_rcrd sel" >';
            for($i=0;$i<=5;$i++){
                //$select = ($i == $value->score) ? 'selected' : '';
                echo '<option value="'.$i.'" >'.$i.'
                </option>';
            }
               
            echo '</select>
        </div>
    </div>
</div>*/