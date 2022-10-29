<?php

namespace App\Http\Controllers\frontEnd\SystemManagement;
use App\Http\Controllers\frontEnd\SystemManagementController;
use Illuminate\Http\Request;
use App\DailyRecord,App\EarningSchemeLabelRecord;
use DB, Auth;
use Carbon\Carbon;

class EarningSchemeTaskController extends SystemManagementController
{
    public function index($label_id){

       $home_id = Auth::user()->home_id;
       $daily_records = DB::table('earning_scheme_label_records')
                            ->where('home_id',$home_id)
                            ->where('earning_scheme_label_records.earning_scheme_label_id',$label_id)
                            ->where('deleted_at',null)
                            ->orderBy('id','desc')
                            ->get();
        $earning_scheme_label = DB::table('earning_scheme_label') 
                                    ->select('name')
                                    ->where('earning_scheme_label.id',$label_id)
                                    ->first();
        $daily_rec = $daily_records->toArray();
                            //echo "<pre>"; print_r($daily_rec); 
        if(!empty($daily_rec)){ //echo "string"; die; 
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
                    </div>
                    <input type="hidden" id="new_label_name" name="label_name" value="'.$earning_scheme_label->name.'">
                    <input type="hidden" id="new_label_id" name="label_id" value="'.$label_id.'">';
            }
        }
        else{ //echo "string123"; die;
            echo '<div class="text-center p-b-20" style="width:100%"> No Records found.</div>
                    <input type="hidden" name="label_id" id="new_label_id" value="'.$label_id.'">
                 <input type="hidden" name="label_name" id="new_label_name" value="'.$earning_scheme_label->name.'">';
        }
            // $resp = $earning_scheme_label_name->name;
            // return $resp;
        }

    public function add(Request $request,$label_id){
       
        if($request->isMethod('post'))
        {
           $data = $request->all();
           $home_id = Auth::user()->home_id;
          // echo '<pre>'; print_r($data); die;
           $record  = new EarningSchemeLabelRecord;
           $record->earning_scheme_label_id  = $label_id ;
           $record->description  = $data['record_description'];
           $record->status  = 1;
           if(isset($data['record_score'])){
               $record->amount   = $data['record_score'];
           } 
           $record->home_id = $home_id;

           if($record->save()){ //echo "string"; die;
            $res = $this->index($label_id);
            echo $res;
           }
           else{
            echo '0';
           }
            die;
        }
    }

    public function edit(Request $request,$label_id){
      
        $data = $request->all();
        $record_ids = $data['edit_record_id'];
        
        if(!empty($record_ids)){
            foreach($record_ids as $key => $record_id) {
                
                $daily_record = EarningSchemeLabelRecord::where('id',$record_id)
                                            ->where('earning_scheme_label_id',$label_id)
                                            ->where('home_id',Auth::user()->home_id)
                                            ->first();
                if(!empty($daily_record)){
                    if(isset($data['edit_record_score'][$key])){
                        $daily_record->amount = $data['edit_record_score'][$key];
                    }
                    $daily_record->description  = $data['edit_record_desc'][$key];
                    $daily_record->save();
                }
            }
        }

        $res = $this->index($label_id);
        echo $res;  die;
    }

    public function delete(Request $request, $daily_record_id){ 

        $data = $request->all();
        if(!empty($daily_record_id)){
            $res = EarningSchemeLabelRecord::where('id', $daily_record_id)
                                            ->where('earning_scheme_label_id',$data['label_id'])
                                            ->where('home_id',Auth::user()->home_id)
                                            ->update(['deleted_at'=>Carbon::now()]);
            echo $res;
        }
    }

    public function update_status(Request $request, $daily_record_id = null){
        
        $data = $request->input();
        $daily_record = EarningSchemeLabelRecord::where('id', $daily_record_id)
                                                 ->where('earning_scheme_label_id',$data['new_label_id'])
                                                 ->where('home_id',Auth::user()->home_id)
                                                 ->first();
        // echo "<pre>"; print_r($data); die;

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

        $data = $request->all();
        $record_del = EarningSchemeLabelRecord::whereIn('id', $request->record_id)
                                               ->where('earning_scheme_label_id',$data['label_id'])
                                               ->where('home_id',Auth::user()->home_id)
                                               ->update(['deleted_at'=>Carbon::now()]);
        if($record_del){
            echo '1';
        }else{
            echo "2";
        }
    }
}