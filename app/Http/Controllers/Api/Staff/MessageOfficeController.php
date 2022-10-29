<?php

namespace App\Http\Controllers\Api\Staff;
use App\Http\Controllers\Api\StaffManagementController;
use Illuminate\Http\Request;
use App\Http\Requests;
// use Illuminate\Support\Facades\Mail;
use App\User, App\OfficeMessage;
use Validator;

Class MessageOfficeController extends StaffManagementController
{        
    public function add_message(Request $req) {
        $data = $req->input();
        
        $validator = Validator::make($data,[
                'service_user_id' => 'required',
                'staff_id'        => 'required',
                'message'         => 'required'
            ]);

        if($validator->fails()) {
            return json_encode(array(
                    'result' => array(
                        'response' => false,
                        'message'  => FILL_FIELD_ERR,
                   )
            ));
        } else {
            
            //checking if this staff has access right to update request start
            $access_id = '237'; //from access right table
            $access = User::checkUserHasAccessRight($data['staff_id'],$access_id);
            if($access == false){
                return json_encode(array(
                        'result' => array(
                            'response' => true,
                            'message' => UNAUTHORIZE_ERR_APP,
                        )
                    ));  
            } 
            //checking if this staff has access right to update request end

            $home_id = User::whereId($data['staff_id'])->value('home_id');

            $msg_office                  = new OfficeMessage;
            $msg_office->home_id         = $home_id;
            $msg_office->service_user_id = $data['service_user_id'];
            $msg_office->user_id         = $data['staff_id'];
            $msg_office->message         = $data['message'];
            $msg_office->order           = '1';

            if($msg_office->save()) {
                return json_encode(array(
                    'result' => array(
                        'response' => true,
                        'message'  => "Message has been sent successfully"
                    )
                ));
            }  else {
                return json_encode(array(
                    'result' => array(
                        'response' => true,
                        'message' => "Fill all fields."
                    )
                ));
            }
        }
    }
    
}