<?php

namespace App\Http\Controllers\Api\ServiceUser\CareCenter;
use App\Http\Controllers\Api\ServiceUser\CareCenterController;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use App\ServiceUser, App\ServiceUserCareCenter, App\User, App\LogBook, App\CareTeam, App\OfficeMessage;
use DB;

Class MessageOfficeController extends CareCenterController
{    
    
    public function index($service_user_id) {

        $office_message = OfficeMessage::select('message_office.id','message_office.message','message_office.created_at','message_office.order', 'user.name as staff_name','user.image as staff_image','su.name as su_name','su.image as su_image')
                            ->leftJoin('user', 'user.id','message_office.user_id')
                            ->join('service_user as su', 'su.id', 'message_office.service_user_id')
                            ->where('message_office.service_user_id', $service_user_id)
                            ->orderBy('id','asc')
                            ->get()
                            ->toArray();
        // echo "<prer>"; print_r($office_message); die;
         $office_message = $this->replace_null($office_message);
        foreach ($office_message as $key => $value) {

            $created_date = date('Y-m-d', strtotime($value['created_at']));
            $today_date = date('Y-m-d');
            if($created_date == $today_date) {
                $created_at = date('g:i a', strtotime($value['created_at']));
            } else{
                $created_at = date('d/m/Y g:i a', strtotime($value['created_at']));
            }
            $office_message[$key]['created_at'] = $created_at;
        }

        if(!empty($office_message)) {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message'  => "office messages list.",
                    'data'     => $office_message,
                    'staff_image_url' => userProfileImagePath,
                    'su_image_url' => serviceUserProfileImagePath,
                )
            ));
        } else {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message'  => "messages not found."
                )
            ));
        }

    }
    
    public function send_message(Request $req) {
        $data = $req->input();
        
        if(!empty($data['service_user_id']) && !empty($data['message'])) {
            $service_user_info = ServiceUser::select('home_id', 'name')->where('id',$data['service_user_id'])->first();

            $office = new OfficeMessage;
            $office->home_id = $service_user_info->home_id;
            $office->service_user_id = $data['service_user_id'];
            $office->message = $data['message'];
            if($office->save()) {
                return json_encode(array(
                    'result' => array(
                        'response' => true,
                        'message'  => "Message has been sent successfully"
                    )
                ));
            }
        } else {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "Fill all fields."

                )
            ));
        }
    }
}