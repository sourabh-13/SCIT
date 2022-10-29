<?php
namespace App\Http\Controllers\frontEnd\ServiceUserManagement;
use App\Http\Controllers\frontEnd\ServiceUserManagementController;
use Illuminate\Http\Request;
use App\ServiceUserDailyRecord, App\DailyRecordScores, App\DailyRecord, App\Notification;
use App\ServiceUserEarningDailyPoints, App\ServiceUserEarningStar, App\ServiceUser, App\EarningScheme;
use DB, Auth;

class DailyRecordController extends ServiceUserManagementController
{
	  
    public function index($service_user_id = null)
    {   
        $su_home_id = ServiceUser::where('id',$service_user_id)->value('home_id');
        if(Auth::user()->home_id != $su_home_id){
            die; 
        }
        
        //in search case editing start
        if(isset($_POST)) {
            $data = $_POST;
            // echo "<pre>"; print_r($data); die;
            $this->_edit($data);
        }

        $su_daily_record_query = ServiceUserDailyRecord::select('su_daily_record.*','eslr.description')
                                    ->join('earning_scheme_label_records as eslr','su_daily_record.daily_record_id','=','eslr.id')
                                    ->where('eslr.status','1')
                                    ->where('su_daily_record.is_deleted','0')
                                    ->where('su_daily_record.service_user_id',$service_user_id)
                                    ->orderBy('su_daily_record.id','desc')
                                    ->orderBy('su_daily_record.created_at','desc');

        $today = date('Y-m-d 00:0:00');
        $tick_btn_class = ""; 
        //$yesterday = date('Y-m-d 00:0:00',strtotime('-1 day'));
        
        if(isset($_GET['logged'])) {

            $tick_btn_class = "logged_daily_record_btn";
            $su_daily_record_query = $su_daily_record_query->where('su_daily_record.created_at','<',$today)->paginate(50);

            if($su_daily_record_query->links() != '') {
                    echo '</div><div class="m-l-15 position-botm">';
                    echo $su_daily_record_query->links();
                    echo '</div>';       
                }

        }
        elseif(isset($_GET['search'])) {

            $tick_btn_class = "search_record_btn";
            $dr_search_type = $_GET['dr_search_type'];
            if($dr_search_type == 'title'){
            
                $su_daily_record_query = $su_daily_record_query->where('dr.description','like','%'.$_GET['search'].'%')->get();
            
            } else{

                $search_date = date('Y-m-d',strtotime($_GET['dr_date'])).' 00:00:00';
                $search_date_next = date('Y-m-d',strtotime('+1 day', strtotime($_GET['dr_date']))).' 00:00:00';

                $su_daily_record_query = $su_daily_record_query->where('su_daily_record.created_at','>',$search_date)
                                                            ->where('su_daily_record.created_at','<',$search_date_next)->get();
            }
        }
        else {
            $su_daily_record_query = $su_daily_record_query->where('su_daily_record.created_at','>',$today)->get();
            $tick_btn_class = "submit-edit-daily-record"; 
            //$add_new_case = '';
        }
        
        $service_user_daily_record  = $su_daily_record_query;

        // $service_user_daily_record  = $su_daily_record_query->orderBy('su_daily_record.id','desc')
        //                                                     ->orderBy('su_daily_record.created_at','desc')
        //                                                     ->paginate(50);

        if(!$service_user_daily_record->isEmpty()){
            $pre_date = date('y-m-d',strtotime($service_user_daily_record['0']->created_at));
        }

        foreach ($service_user_daily_record as $key => $value) {

            if($value->status == 1){
                $record_set_btn_class = "clr-blue";
            }
            else{
                $record_set_btn_class = "clr_grey";
            }

            if($value->am_pm == 'A'){
                $am_pm = 'Am';
            }else{
                $am_pm = 'Pm';
            }

            $check = (!empty($value->details)) ? '<i class="fa fa-check"></i>' : '';
            
            $first = 0;

            if(isset($_GET['logged']) ||  isset($_GET['search']) ){ 
                $record_date = date('Y-m-d',strtotime($value->created_at));

                if($record_date != $pre_date){
                    $pre_date = $record_date; 
               
                    echo '</div>
                    <div class="daily-rcd-head">
                        <div class="col-md-12 col-sm-12 col-xs-12 cog-panel p-0 r-p-15 record_row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <a  class="date-tab">
                                    <span class="pull-left">
                                        '.date('d F Y',strtotime($record_date)).'
                                    </span>
                                    <i class="fa fa-angle-right pull-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="daily-rcd-content">';
                }
                else{}
            } 

            echo '
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-12 col-sm-12 col-xs-12 cog-panel p-0 r-p-15 record_row">
                        <div class="form-group col-md-3 col-sm-3 col-xs-6 p-0">
                            <label class="col-md-6 col-sm-6 col-xs-6 p-t-7 r-p-0"> Score: </label>
                            <div class="col-md-6 col-sm-6 col-xs-6 p-0">
                                <div class="select-style small-select">
                                    <select name="edit_su_record_score[]" disabled  class="edit_record_score_'.$value->id.' edit_rcrd sel">'; 

                                        for($i=0; $i<=5; $i++){
                                            $select         = ($i == $value->scored) ? 'selected' : '';
                                            //$disable_option = ( ($i > $value->scored) && ($value->scored != 0) ) ? 'disabled' : '';
                                            /*if($select && $disable_option){
                                                $disable_option = '';
                                            }*/
                                            echo '<option value="'.$i.'"'. $select .' >'.$i.'</option>';
                                        }
                                  echo '</select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-9 col-sm-9 col-xs-12 r-p-0">
                            <div class="input-group popovr">
                                <input type="text" name="edit_su_record_desc[]" class="form-control cus-control edit_record_desc_'.$value->id.' edit_rcrd"  disabled  value="'.$value->description.'" maxlength="255"/>
                                <input type="text" name="edit_su_am_pm[]" class="form-control cus-control edit_record_desc_'.$value->id.' edit_rcrd"  disabled  value="'.$am_pm.'" maxlength="255"/>';
                                 
                                if(!empty($value->details)){
                                    echo '<div class="input-plus color-green"> <i class="fa fa-plus"></i> </div>';
                                }
                                  echo '<input type="hidden" name="edit_su_record_id[]" value="'.$value->id.'" disabled="disabled" class="edit_record_id_'.$value->id.'" />
                                    <span class="input-group-addon cus-inpt-grp-addon '.$record_set_btn_class.' settings">
                                    <i class="fa fa-cog"></i>
                                    <div class="pop-notifbox">
                                        <ul class="pop-notification" type="none">';
                                            //if(isset($add_new_case)) { 
                                            echo '<li> <a href="#" service_user_daily_record_id="'.$value->id.'" class="edit_record_btn"> <span> <i class="fa fa-pencil"></i> </span> Edit </a> </li>';
                                            //}
                                            echo '<li> <a href="#" service_user_daily_record_id="'.$value->id.'" class="delete-record"> <span class="color-red"> <i class="fa fa-exclamation-circle"></i> </span> Remove </a> </li>
                                            <li> <a href="#" service_user_daily_record_id="'.$value->id.'" class="record-detail"> <span class="color-red"> <i class="fa fa-plus color-green"></i> </span> Details </a> </li>
                                            <li>  <a href="'.url('/service/daily-record/calendar/add/'.$value->id).'"> <span class="color-green"> <i class="fa fa-plus-circle"></i> </span> Add to calendar </a> </li>
                                            <li> <a data-toggle="modal" data-dismiss="modal" service_user_daily_record_id="'.$value->id.'" class="bmp-rmp_record_btn" > <span class="color-yellow"> <i class="fa fa-bolt"></i> </span> BMP/RMP </a>
                                            </li>
                                        </ul>
                                    </div>
                                </span>
                            </div>
                        </div>

                        <div class="input-plusbox form-group col-xs-12 p-0 detail">
                            <label class="cus-label color-themecolor"> Details: </label>
                            <div class="cus-input">
                                <div class="input-group">
                                    <textarea rows="5" name="edit_su_record_detail[]" disabled class="form-control tick_text txtarea edit_detail_'.$value->id.' edit_rcrd " value="" maxlength="1000">'.$value->details.'</textarea>
                                    <div class="input-group-addon cus-inpt-grp-addon sbt_tick_area"">
                                        <div class="tick_show sbt_btn_tick_div '.$tick_btn_class.'">'.$check.'</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> ';    
        }
    }

    public function add(Request $request){

        if($request->isMethod('get'))
        {
            $data = $request->all();
            
            $su_home_id = ServiceUser::where('id',$data['service_user_id'])->value('home_id');
            if(Auth::user()->home_id != $su_home_id){
                echo '0'; die; 
            }

            // echo '<pre>'; print_r($data); die;
            $records = new ServiceUserDailyRecord;
            $records->service_user_id = $data['service_user_id'];
            $records->daily_record_id = $data['daily_record_id'];
            $records->am_pm           = $data['am_pm'];
            $records->details         = '';
            $records->status          = 1;
            $records->home_id         = Auth::user()->home_id;
            
            if($records->save()){

                //saving notification start
                $notification                             = new Notification;
                $notification->service_user_id            = $data['service_user_id'];
                $notification->event_id                   = $records->id;
                // $notification->event_type      = 'SU_DR';
                $notification->notification_event_type_id = '2';
                $notification->event_action               = 'ADD';      
                $notification->home_id                    = Auth::user()->home_id;
                $notification->user_id                    = Auth::user()->id;        
                $notification->save();
                //saving notification end

                $res = $this->index($data['service_user_id']);
                echo $res; die; 
            }
            else{ 
                echo '0';
            }
            die;
        }

    }

    public function delete($service_user_daily_record_id){

        if(!empty($service_user_daily_record_id)){

            $su_dr = ServiceUserDailyRecord::where('id', $service_user_daily_record_id)->first();            
            if(!empty($su_dr)){
             
                $su_home_id = ServiceUser::where('id',$su_dr->service_user_id)->value('home_id');
                if(Auth::user()->home_id != $su_home_id){
                    echo '0'; die; 
                }

                $su_dr->is_deleted = 1;
                if($su_dr->save()){
                    echo '1';
                } else{
                    echo '0';
                }
            }
        }
        die;
    }

    public function edit(Request $request){

        $data            = $request->all();
        $service_user_id = $this->_edit($data);
        $res             = $this->index($service_user_id);
        echo $res;  
        die;
    }

    public function _edit($data = array()){

        $service_user_id = '';

        if(isset($data['edit_su_record_id'])){ 

            $su_daily_record_ids = $data['edit_su_record_id'];
            
            if(!empty($su_daily_record_ids)){

                foreach ($su_daily_record_ids as $key => $record_id) {

                    $record     = ServiceUserDailyRecord::find($record_id);
                    
                    if(!empty($record)) {

                        $service_user_id = $record->service_user_id;

                        $su_home_id = ServiceUser::where('id',$service_user_id)->value('home_id');
                        if(Auth::user()->home_id == $su_home_id){
                            
                            $record->scored  = $data['edit_su_record_score'][$key];
                            $record->details = $data['edit_su_record_detail'][$key];
                            $record->save();

                            if($record->save()){

                                //update earning of su for that dates
                                $updated_earning_star_id = EarningScheme::updateEarning($service_user_id,$record->created_at);
                            }
                        }
                    }
                }
            }
 
            if(!empty($updated_earning_star_id)) { //if a new star has been added
                
                //saving notification start
                $notification                            = new Notification;
                $notification->service_user_id           = $service_user_id;
                $notification->event_id                  = $updated_earning_star_id;
                //$notification->event_type      = 'SU_ER';
                $notification->notification_event_type_id = '3';
                $notification->event_action               = 'ADD_STAR';   
                $notification->home_id                    = Auth::user()->home_id;
                $notification->user_id                    = Auth::user()->id;             
                $notification->save();
                //saving notification end
            
            } else{
                //saving notification start
                $notification                  = new Notification;
                $notification->service_user_id = $service_user_id;
                $notification->event_id        = $record->id;
               // $notification->event_type      = 'SU_DR';
                $notification->notification_event_type_id = '2';
                $notification->event_action    = 'EDIT'; 
                $notification->home_id         = Auth::user()->home_id;
                $notification->user_id         = Auth::user()->id;               
                $notification->save();
                //saving notification end
            }
        }
        return $service_user_id;
    }
     
    public function add_to_calendar($su_daily_record_id = null) {
        
        $clndr_add = ServiceUserDailyRecord::where('id', $su_daily_record_id)->update(['added_to_calendar'=> '1']);
        if($clndr_add) {
            return redirect()->back()->with('success', CAl_ADD_RECORD);
        } else {
            return redirect()->back()->with('error', COMMON_ERROR);
        }
    }

    /*public function edit(Request $request){

        $data = $request->all();
        //echo '<pre>'; print_r($data); die;
        if(isset($data['edit_su_record_id'])){ 
            $su_daily_record_ids = $data['edit_su_record_id'];
            
            if(!empty($su_daily_record_ids)){

                foreach ($su_daily_record_ids as $key => $record_id) {

                    $record     = ServiceUserDailyRecord::find($record_id);
                    $su_home_id = ServiceUser::where('id',$record->service_user_id)->value('home_id');
                    if(Auth::user()->home_id == $su_home_id){
                        $record->scored  = $data['edit_su_record_score'][$key];
                        $record->details = $data['edit_su_record_detail'][$key];
                        $record->save();

                        EarningScheme::updateEarning($record->service_user_id,$record->created_at);
                    }
                }
            }
        }
        $service_user_id = $record->service_user_id;

        //$updated_earning_star_id = $this->update_earning($service_user_id);
        $updated_earning_star_id = EarningScheme::updateEarning($service_user_id);


        if(!empty($updated_earning_star_id)) { //if a new star has been added
            
            //saving notification start
            $notification                  = new Notification;
            $notification->service_user_id = $service_user_id;
            $notification->event_id        = $updated_earning_star_id;
            $notification->event_type      = 'SU_ER';
            $notification->event_action    = 'ADD_STAR';   
            $notification->home_id         = Auth::user()->home_id;
            $notification->user_id         = Auth::user()->id;             
            $notification->save();
            //saving notification end
        
        } else{
            //saving notification start
            $notification                  = new Notification;
            $notification->service_user_id = $service_user_id;
            $notification->event_id        = $record->id;
            $notification->event_type      = 'SU_DR';
            $notification->event_action    = 'EDIT'; 
            $notification->home_id         = Auth::user()->home_id;
            $notification->user_id         = Auth::user()->id;               
            $notification->save();
            //saving notification end
        }

        $res = $this->index($service_user_id);
        echo $res;  
        die;
    }*/

    /*function update_earning($service_user_id = null){
        
        $su_home_id = ServiceUser::where('id',$service_user_id)->value('home_id');
        if(Auth::user()->home_id != $su_home_id){
            return false;
        }

        $today = date('Y-m-d').' 00:00:00';

        $su_dly_records = ServiceUserDailyRecord::select('su_daily_record.scored', 'su_daily_record.id')
                                    ->where('su_daily_record.service_user_id',$service_user_id)
                                    ->whereDate('su_daily_record.created_at','>=',$today)
                                    ->where('su_daily_record.is_deleted','0')
                                    ->where('service_user.home_id',Auth::user()->home_id)
                                    ->join('service_user','service_user.id','=','su_daily_record.service_user_id') 
                                    ->get()
                                    ->toArray();

        $su_today_daily_point = 0; 
        $du_dly_record_ids = array();
        foreach($su_dly_records as $value){
            
            $su_record_score = $value['scored'];
            if( ($su_record_score > 0) && ($su_record_score <= 3) ){
                $su_today_daily_point++;
                $du_dly_record_ids[] = $value['id'];
            }
        }

        if($su_today_daily_point > 0){

            //points can not be more then 5 in a day.
            if($su_today_daily_point > 5){
                $su_today_daily_point = 5;
            }

            $today_date = date('Y-m-d');
            sort($du_dly_record_ids);
            $du_dly_record_id_str = implode(',',$du_dly_record_ids);

            $su_daily_point = ServiceUserEarningDailyPoints::where('service_user_id',$service_user_id)
                                    ->whereDate('date','=',$today_date)
                                    ->first();
            
            if(!empty($su_daily_point)){

                $su_daily_point->point           = $su_today_daily_point;
                $su_daily_point->daily_record_ids= $du_dly_record_id_str;
                
            } else{

                $su_daily_point                  = new ServiceUserEarningDailyPoints;
                $su_daily_point->service_user_id = $service_user_id;
                $su_daily_point->point           = $su_today_daily_point;
                $su_daily_point->daily_record_ids= $du_dly_record_id_str;
                $su_daily_point->date            = $today_date;
            }

            //update stars
            if($su_daily_point->save()){
                
                if( ($su_daily_point->point == 5) && ($su_daily_point->star_given == 0) ){

                    $su_star = ServiceUserEarningStar::where('service_user_id',$service_user_id)->first();

                    if(!empty($su_star)){
                                        
                        $pre_star      = $su_star->star;
                        $su_star->star = $pre_star + 1;                            

                    } else{
                        $su_star = new ServiceUserEarningStar;
                        $su_star->star = 1;                        
                        $su_star->service_user_id = $service_user_id;                        
                    }

                    if($su_star->save()){
                        
                        //updating daily point table that its points has been converted to stars
                        $su_daily_point->star_given= 1;
                        $su_daily_point->save();
                        
                        return $su_star->id;
                    }

                }
            }

        }
        return false;
    }*/

}

    /* Note: About update_earning
    When a manager updates daily record task
    Then this function is executed to assign the points to the su according to the scores obtained by su

    This function is made by taking following points into consideration:

    1. The main task was to score 3 and above in daily record tasks
    2. Each day the YP can earn points (up to a maximum of 5 per shift) 

        Following point is to be done with cron job
    3. and these points are collected over a weekly period and if the YP reaches their target for the week they can earn stars towards their overall incentive 
    
    Weekly points collection

        So the first gauge in earning scheme page shows daily points.
        And the second graph says weekly points.
        the poins which were not able to turn in to stars. will be collected on weekly basis.
        And so in the week Monday to sunday. on the sunday night these remaining points of each day of this week will be collected and devided by 5 to make them stars and the remaining points will become useless.
        and   

    e.g. If a su has got 2 point on monday 
        then these points will not turn into star on monday
        and suppose he also got 4 points on  tuesday then 
        then these points will not turn into star on tuesday
        but on the weekend all these points will be collected and will be devided by 5 to get as more star from the point.
        so in this case su will get 1 more star and 1 point will become useless.
        as    2 + 4 = 6
                6/5 = 1 star
                6%5 = 1 point useless
        -------------------------------------------------
        The above functionality has been totally changed and now the curret functionality is :
        ------------------------------------------------
        In here I talk % again this was written last week but it has all the info in their for you to take from

        General Behaviour (Daily Record)
        Independent Living Skills
        Missing/Absent from Care
        Education / Training

        These are parts which are going to be in the YPs profile each section will have a score behind it, for example if there is no record of missing/absent from care then that is scored as 25%, all education training is complete 25%, independent life skills is complete 25% score but on general behaviour has been bad and only 1 point out of 5 is done then this is 5% complete.
        The scoring system works on if you get 80% or more then that gets a point for the day.
    */