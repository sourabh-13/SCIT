<?php

namespace App\Http\Controllers\Api\ServiceUser\CareCenter;
use App\Http\Controllers\Api\ServiceUser\CareCenterController;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use App\ServiceUser, App\ServiceUserCareCenter, App\User, App\LogBook, App\CareTeam, App\Notification;
use DB;

Class DangerController extends CareCenterController
{    
    public function add(Request $r) {
         
        $data = $r->input();
        if(!empty($data['service_user_id']) && !empty($data['care_team_id']) && !empty($data['latitude']) && !empty($data['longitude'])){

            $service_user = ServiceUser::select('id','home_id', 'name')->where('id',$data['service_user_id'])->first()->toArray();
            $service_user_name = $service_user['name'];

            $request = "is in danger.";
            $detail  = "has clicked this danger button and needs help.";
            
            $care_center = new ServiceUserCareCenter;
            $care_center->service_user_id = $data['service_user_id'];
            $care_center->care_type    = 'D';
            $care_center->care_team_id = $data['care_team_id']; //to_inform_care_team_id
            
            if($care_center->save())
            {   
                //save in log book
                $log_book_record          = new LogBook;
                $log_book_record->title   = $service_user_name.' '.$request;
                $log_book_record->date    = date('Y-m-d H:i:s');
                $log_book_record->details = $service_user_name.' '.$detail;
                $log_book_record->home_id = $service_user['home_id'];
                $log_book_record->save();

                //save sticky notification
                $notif                  = new Notification;
                $notif->home_id         = $service_user['home_id'];
                $notif->service_user_id = $service_user['id'];
                $notif->event_id        = $care_center->id;
                $notif->notification_event_type_id   = 14;
                $notif->event_action    = 'ADD';
                $notif->is_sticky       = '1';
                $notif->save();
                //master_ack
                
                /*$log_book_record          = new LogBook;
                $log_book_record->title   = $service_user_name.' '.$request;
                $log_book_record->date    = date('Y-m-d H:i:s');
                $log_book_record->details = $service_user_name.' '.$detail;
                $log_book_record->home_id = $service_user_detail['home_id'];
                $log_book_record->save();*/


                 /*------ Important Email ----*/
                //email send functionality also use CareCenterController.php
                $this->emailToCareTeamMember($data['care_team_id'],$data['service_user_id'],'IN_DANGER',$data['latitude'],$data['longitude']);
                $this->emailToAllStaff($data['service_user_id'],'IN_DANGER',$data['latitude'],$data['longitude']);
                
                return json_encode(array(
                    'result' => array(
                        'response' => true,
                        'message' => "Request has been sent."
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
    
    //  public function add(Request $r) {
    //     $data = $r->input();
    //     if(!empty($data['service_user_id']) && !empty($data['care_type']) && !empty($data['to_infom_user_type']) && !empty($data['to_inform_user_id'])){
    //         $service_user_detail = ServiceUser::select('home_id', 'name')->where('id',$data['service_user_id'])->first()->toArray();
    //         $service_user_name = $service_user_detail['name'];

    //         $care_type = "D";
    //         $request = "is in danger.";
    //         $detail  = "has clicked this danger button and needs help.";
    
            
    //         $staff_detail = array();
    //         if($data['to_infom_user_type'] == "Care Center"){
    //             $to_infom_user_type = "C";
    //             $staff_detail = CareTeam::select('email','name')->where('id',$data['to_inform_user_id'])->first();
    //         } else {
    //             $to_infom_user_type = "S";
    //             $staff_detail = User::select('email','name')->where('id',$data['to_inform_user_id'])->first();
    //         }
           
    //         $care_center = new ServiceUserCareCenter;
    //         $care_center->service_user_id = $data['service_user_id'];
    //         // $crae_center->care_type = $care_type;
    //         $care_center->care_type = 'D';
    //         $care_center->to_infom_user_type = $to_infom_user_type;
    //         $care_center->to_inform_user_id = $data['to_inform_user_id'];
            
            
    //         $log_book_record          = new LogBook;
    //         $log_book_record->title   = $service_user_name.' '.$request;
    //         $log_book_record->date    = date('Y-m-d H:i:s');
    //         $log_book_record->details = $service_user_name.' '.$detail;
    //         $log_book_record->home_id = $service_user_detail['home_id'];
            
    //         $userdata = array(
    //             'staff_user_name'=>$staff_detail['name'],
    //             'service_user_name' => $service_user_name,
    //             'request' => $request
    //         );
    //         $email = $staff_detail['email'];
    //         // echo $email; die;
    //         Mail::send('emails.danger', $userdata, function($message) use ($email,$service_user_name,$request)
    //         {
    //             // $message->to('promatics.akhilsharma@gmail.com')->subject($service_user_name." ".$request);
    //             $message->to($email)->subject($service_user_name." ".$request);
    //         });
    //         if($care_center->save() && $log_book_record->save())
    //         {
    //             return json_encode(array(
    //                 'result' => array(
    //                     'response' => true,
    //                     'message' => "Request has been saved."
    //                 )
    //             ));
    //         }
    //     } else{
    //         return json_encode(array(
    //             'result' => array(
    //                 'response' => false,
    //                 'message' => "Fill all fields."    
    //             )
    //         ));
    //     }    
    // }
    
}