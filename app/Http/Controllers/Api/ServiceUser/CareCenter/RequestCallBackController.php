<?php

namespace App\Http\Controllers\Api\ServiceUser\CareCenter;
use App\Http\Controllers\Api\ServiceUser\CareCenterController;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use App\ServiceUser, App\ServiceUserCareCenter, App\User, App\LogBook, App\CareTeam, App\StickyNotification, App\Notification;
use DB;
use Auth;

Class RequestCallBackController extends CareCenterController
{    
    public function add(Request $r) {
        
        $data = $r->input();
        if(!empty($data['service_user_id']) && !empty($data['care_team_id'])){

            $service_user = ServiceUser::select('id','home_id', 'name')->where('id',$data['service_user_id'])->first()->toArray();
            $service_user_name = $service_user['name'];

           // $care_type = "D";
            $request = "has requested callback.";
            $detail  = "has clicked this request callback button and needs help.";
           
            $care_center = new ServiceUserCareCenter;
            $care_center->service_user_id = $data['service_user_id'];
            $care_center->care_type = 'R';
            $care_center->care_team_id = $data['care_team_id']; //to_inform_care_team_id
            
            if($care_center->save())
            {

                //saving in logbook
                $log_book_record          = new LogBook;
                $log_book_record->title   = $service_user_name.' '.$request;
                $log_book_record->date    = date('Y-m-d H:i:s');
                $log_book_record->details = $service_user_name.' '.$detail;
                $log_book_record->home_id = $service_user['home_id'];
                $log_book_record->save();

                /*$sticky_notif                  = new StickyNotification;
                $sticky_notif->home_id         = $service_user['home_id'];
                $sticky_notif->service_user_id = $service_user['id'];
                $sticky_notif->event_id        = $care_center->id;
                $sticky_notif->event_type_id   = 14;
                $sticky_notif->save();*/

                $notif                  = new Notification;
                $notif->home_id         = $service_user['home_id'];
                $notif->service_user_id = $service_user['id'];
                $notif->event_id        = $care_center->id;
                $notif->notification_event_type_id   = 15;
                $notif->event_action    = 'ADD';
                $notif->is_sticky       = '1';
                $notif->save();

                /*------ Important Email ----*/
                //email send functionality also use CareCenterController.php
                // $this->emailToCareTeamMember($data['care_team_id'],$data['service_user_id'],'REQ_CALLBACK');
                // $this->emailToAllStaff($data['service_user_id'],'REQ_CALLBACK');
                
                return json_encode(array(
                    'result' => array(
                        'response' => true,
                        'message' => "Callback request has been sent successfully."
                    )
                ));
            }
        } else{
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "Fill all fields."    
                )
            ));
        }    
    }
    
}