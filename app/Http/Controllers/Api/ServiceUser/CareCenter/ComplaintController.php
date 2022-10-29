<?php

namespace App\Http\Controllers\Api\ServiceUser\CareCenter;
use App\Http\Controllers\Api\ServiceUser\CareCenterController;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use App\ServiceUser, App\ServiceUserCareCenter, App\User, App\CareTeam, App\ServiceUserExternalService;
use DB;

Class ComplaintController extends CareCenterController
{    
   /* public function add(Request $request) {
        $required_keys = array('service_user_id','user_type');
        $res = $this->checkKeys($required_keys,$request->input());
        if($res != true){ echo 'm'; die;
            return $res;
        }
        echo 'akhil';
        die;
    }*/

    public function add(Request $request) {

        $data = $request->input();

        if(!empty($data['service_user_id']) && !empty($data['user_type']) && !empty($data['message']) && !empty($data['user_id'])) {

            $service_user_name = ServiceUser::select('name')->where('id', $data['service_user_id'])->first(); 
            if($data['user_type'] == 'SOCIAL_WORKER') {
                                   
                $social_worker = CareTeam::select('name','email')->where('id',$data['user_id'])->first();
                if(!empty($social_worker)) {
                    $subject = "COMPLAINT";
                    $company_name = "SCITS";
                    $service_user_name = $service_user_name->name;
                    //echo $service_user_name;
                    $complaint_message = $data['message']; 
                    //echo $complaint_message;
                    $email = $social_worker->email;
                    //echo $email;
                    $user_name  = $social_worker->name;
                    //echo $user_name; die;
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) 
                    {  
                        Mail::send('emails.complaint_mail', ['service_user_name'=>$service_user_name,'user_name'=>$user_name, 'complaint_message' =>$complaint_message], function($message) use ($email,$company_name)
                        {
                            $message->to($email,$company_name)->subject('COMPLAINT');
                        });
                        //return true;
                    } 
                    return json_encode(array(
                        'result' => array(
                            'response' => true,
                            'message' => "Request has been saved."
                        )
                    ));
                } else {
                    return json_encode(array(
                        'result' => array(
                            'response' => false,
                            'message' => "No requests found."
                        )
                    ));
                }

            } else if($data['user_type'] == 'STAFF') {

                $staff = User::select('name','email')->where('id', $data['user_id'])->first();
                if(!empty($staff)) {
                    $subject = "COMPLAINT";
                    $company_name = "SCITS";
                    $service_user_name = $service_user_name->name;
                    // echo $service_user_name;
                    $complaint_message = $data['message'];
                    //  echo $complaint_message;
                    $email = $staff->email;
                    // echo $email;
                    $user_name  = $staff->name;
                    // echo $user_name; die;
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) 
                    {   
                        Mail::send('emails.complaint_mail', ['service_user_name'=>$service_user_name,'user_name'=>$user_name, 'complaint_message' =>$complaint_message], function($message) use ($email,$company_name)
                        {
                            $message->to($email,$company_name)->subject('COMPLAINT');
                        });
                        // return true;
                    } 
                    return json_encode(array(
                        'result' => array(
                            'response' => true,
                            'message' => "Request has been saved successfully."
                        )
                    ));
                } else {
                    return json_encode(array(
                        'result' => array(
                            'response' => false,
                            'message' => "User not found."
                        )
                    ));
                }

            } else if($data['user_type'] == 'EXTERNAL_SERVICE') {
                $su_external_service = ServiceUserExternalService::select('contact_name','email')->where('id', $data['user_id'])->first();
                // echo "<pre>"; print_r($su_external_service); die;
                if(!empty($su_external_service)) {
                    $subject = "COMPLAINT";
                    $company_name = "SCITS";
                    $service_user_name = $service_user_name->name;
                    $complaint_message = $data['message'];
                    $email = $su_external_service->email;
                    $user_name  = $su_external_service->contact_name;
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) 
                    {   
                        Mail::send('emails.complaint_mail', ['service_user_name'=>$service_user_name,'user_name'=>$user_name, 'complaint_message' =>$complaint_message], function($message) use ($email,$company_name)
                        {
                            $message->to($email,$company_name)->subject('COMPLAINT');
                        });
                        // return true;
                    } 
                    return json_encode(array(
                        'result' => array(
                            'response' => true,
                            'message' => "Request has been saved."
                        )
                    ));
                } else {
                    return json_encode(array(
                        'result' => array(
                            'response' => false,
                            'message' => "No complaint found."
                        )
                    ));
                }
            } else {
                    return json_encode(array(
                        'result' => array(
                            'response' => false,
                            'message' => "No record found."
                        )
                    ));
            }

            $result['result']['response'] = true;

        } else {
            $result['result']['response'] = false;
            $result['result']['message'] = "Fill all fields.";
        } 
        return json_encode($result);

    }
}