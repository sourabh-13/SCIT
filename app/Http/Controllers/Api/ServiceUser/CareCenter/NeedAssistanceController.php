<?php

namespace App\Http\Controllers\Api\ServiceUser\CareCenter;
use App\Http\Controllers\Api\ServiceUser\CareCenterController;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use App\ServiceUser, App\ServiceUserCareCenter, App\User, App\LogBook, App\CareTeam, App\ServiceUserNeedAssistance, App\Notification;
use DB;

Class NeedAssistanceController extends CareCenterController
{    
    public function index($service_user_id) {

        // $service_user_info = ServiceUser::select('home_id', 'name')->where('id',$service_user_id)->first()->toArray();
        $need_assistance = ServiceUserNeedAssistance::select('message','created_at')
                                ->where('service_user_id', $service_user_id)
                                ->orderBy('id','asc')
                                ->get()
                                ->toArray();
        
        foreach ($need_assistance as $key => $value) {
            $created_at = date('d/m/Y g:i a', strtotime($value['created_at']));
            $need_assistance[$key]['created_at'] = $created_at;
        }
        
        if(!empty($need_assistance)) {
            return json_encode(array(  
                'result' => array( 
                    'response' => true,
                    'message'  => "need assistance listing",
                    'data'     => $need_assistance
                )
            ));
        } else {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message'  => "Messages not found."
                )
            ));
        }

    }
    
    public function send_message(Request $req) {
        $data = $req->input();
        if(!empty($data['service_user_id']) && !empty($data['message'])) {
            $service_user = ServiceUser::select('id','home_id','name')->where('id',$data['service_user_id'])->first()->toArray();

            $assistance                  = new ServiceUserNeedAssistance;
            $assistance->home_id         = $service_user['home_id'];
            $assistance->service_user_id = $data['service_user_id'];
            $assistance->message         = $data['message'];
            if($assistance->save()) {

                $notif                  = new Notification;
                $notif->home_id         = $service_user['home_id'];
                $notif->service_user_id = $service_user['id'];
                $notif->event_id        = $assistance->id;
                $notif->notification_event_type_id   = 16;
                $notif->event_action    = 'ADD';
                $notif->is_sticky       = '1';
                $notif->save();

                return json_encode(array(
                    'result' => array(
                        'response' => true,
                        'message'  => "Message has been sent successfully."
                    )
                ));
            } 
        } else {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "Fill all fields."
                )
            ));
        }
    }
}