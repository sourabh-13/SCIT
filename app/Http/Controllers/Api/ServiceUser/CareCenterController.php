<?php

namespace App\Http\Controllers\Api\ServiceUser;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\ServiceUser, App\ServiceUserCareCenter, App\User, App\LogBook, App\CareTeam, App\ServiceUserExternalService;
use DB;
use Auth;

Class CareCenterController extends Controller
{
    public function staff_list($service_user_id) {
        $service_user_home_id = ServiceUser::where('id',$service_user_id)->value('home_id');
        $staff_list = DB::table('user')
                     ->join('access_level' , 'user.access_level' , '=' , 'access_level.id')
                     ->select('user.id','user.name as staff_name','access_level.name as access_level')
                     ->where('user.is_deleted','0')
                     ->where('access_level.is_deleted','0')
                     ->get();
        if(!empty($staff_list)){
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "Service User's Staff List",
                    'data' => $staff_list
                )
            ));
        } else{
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "Data not found."
                )
            ));
        }
        
    }
    
    public function social_worker_list($service_user_id){
        
        $find_job_title = 'Social worker';
        
        $social_workers = CareTeam::select('su_care_team.id','su_care_team.name','su_care_team.email','su_care_team.phone_no','ct_jt.title as job_title')
                            ->join('care_team_job_title as ct_jt', 'ct_jt.id','=','su_care_team.job_title_id')
                            ->where('ct_jt.title','=',$find_job_title)
                            ->where('su_care_team.is_deleted','0')
                            ->where('ct_jt.is_deleted','0')
                            ->get()
                            ->toArray();
                            
        if(!empty($social_workers)){
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "Social Worker List.",
                    'data' => $social_workers
                )
            ));
        } else {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "Data not found.",
                )
            ));
        }
    }
    
    public function external_service_list($service_user_id) {

        $su_external_service = ServiceUserExternalService::select('id','contact_name','email','company_name','phone_no')
                                ->where('service_user_id', $service_user_id)
                                ->where('is_deleted','0')
                                ->get()
                                ->toArray();
        // echo "<pre>"; print_r($su_external_service); die;
        if(!empty($su_external_service)) {
            // $result = array();
            $result['result']['response'] = true;
            $result['result']['message'] = "External Advocacy Service";
            $result['result']['data'] = $su_external_service;
        } else {
            $result['result']['response']= false;
            $result['result']['message'] = "No record found.";
        }
        return json_encode($result);

        // if(!empty($su_external_service)) {
        //     return json_encode(array(
        //         'result' => array(
        //             'response' => true,
        //             'message' => "External Advocacy Service",
        //             'data' => $su_external_service
        //         )
        //     ));
        // } else {
        //     return json_encode(array(
        //         'result' => array(
        //             'response' => false,
        //             'message' => "Data not found.",
        //         )
        //     ));

        // }


    }
    
   /* public function add_danger(Request $r){
        $data = $r->input();
        if(!empty($data['service_user_id']) && !empty($data['care_type']) && !empty($data['to_infom_user_type']) && !empty($data['to_inform_user_id'])){
            $service_user_detail = ServiceUser::select('home_id', 'name')->where('id',$data['service_user_id'])->first()->toArray();
            $service_user_name = $service_user_detail['name'];
            if($data['care_type'] == 'In danger'){
                $care_type = "D";
                $request = "is in danger.";
                $detail  = "has clicked this danger button and needs help.";
            } elseif($data['care_type'] == 'Requested Callback') {
                $care_type = "R";
                $request = "has requested callback.";
                $detail  = "has clicked this request callback button and needs help.";
            } else {
                $care_type = "A";
                $request = "has assistance request.";
                $detail  = "has clicked this assistance request button and needs help.";
            }
            
            $staff_detail = array();
            if($data['to_infom_user_type'] == "Care Center"){
                $to_infom_user_type = "C";
                $staff_detail = CareTeam::select('email','name')->where('id',$data['to_inform_user_id'])->first();
            } else {
                $to_infom_user_type = "S";
                $staff_detail = User::select('email','name')->where('id',$data['to_inform_user_id'])->first();
            }
           
            $care_center = new ServiceUserCareCenter;
            $care_center->service_user_id = $data['service_user_id'];
            $care_center->care_type = $care_type;
            $care_center->to_infom_user_type = $to_infom_user_type;
            $care_center->to_inform_user_id = $data['to_inform_user_id'];
            
            
            $log_book_record          = new LogBook;
            $log_book_record->title   = $service_user_name.' '.$request;
            $log_book_record->date    = date('Y-m-d H:i:s');
            $log_book_record->details = $service_user_name.' '.$detail;
            $log_book_record->home_id = $service_user_detail['home_id'];
            
            $userdata = array(
                'staff_user_name'=>$staff_detail->name,
                'service_user_name' => $service_user_name,
                'request' => $request
            );
            $email = $staff_detail['email'];
            Mail::send('emails.danger', $userdata, function($message) use ($email,$service_user_name,$request)
            {
                $message->to('promatics.karanmahajan@gmail.com')->subject($service_user_name." ".$request);
            });
            if($care_center->save() && $log_book_record->save())
            {
                return json_encode(array(
                    'result' => array(
                        'response' => true,
                        'message' => "Request has been saved."
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
    }*/
    
   public function emailToAllStaff($service_user_id = null, $email_type = null,$latitude = null, $longitude = null) {
        
        $service_user_info = ServiceUser::select('home_id', 'name')->where('id',$service_user_id)->first();
        $staff_details = User::select('email','name')->where('home_id',$service_user_info->home_id)->get();
           
        // $detail  = "has clicked this danger button and needs help.";
        $subject='';
        $company_name = "SCITS";
        foreach ($staff_details as $key => $value) {
            $email = $value->email;
            $name  = $value->name;
            if($email_type == 'IN_DANGER'){
                $subject = ucfirst($service_user_info->name).' '." is in danger!";
                $request = "has pressed In danger button and need help.";
                // $detail  = "has clicked this danger button and needs help.";
            } else if($email_type == 'REQ_CALLBACK'){ 
                
                $subject = ucfirst($service_user_info->name).' '." callback request!";
                $request = " has requested to callback and need help.";
                //$request = "requesting for callback";
            } else {
                $subject = '';
                $request = '';
            }
            $location_url = 'https://www.google.com/maps/place/'.$latitude.','.$longitude;
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) 
            {   
                Mail::send('emails.danger', ['service_user_name'=>$service_user_info->name,'request'=>$request,'staff_user_name'=>$name,'email_type'=>$email_type, 'location_url'=> $location_url], function($message) use ($email,$company_name,$subject)
                {
                    $message->to($email,$company_name)->subject($subject);
                });
            } 

        }
        return $staff_details;
    }

   public function emailToCareTeamMember($care_team_member_id = null, $service_user_id = null, $email_type = null, $latitude = null, $longitude = null) {
        
        $service_user_info = ServiceUser::select('home_id', 'name')->where('id',$service_user_id)->first();
        $care_team = CareTeam::select('email','name')->where('id',$care_team_member_id)->first();

        if(!empty($care_team)){
            // $detail  = "has clicked this danger button and needs help.";
            $subject='';
            $company_name = "SCITS";
            $email = $care_team->email;
            $name  = $care_team->name;
            if($email_type == 'IN_DANGER'){
                $subject = ucfirst($service_user_info->name).' '." is in danger!";
                $request = "has pressed In danger button and need help.";
                //$request = "is in danger";
                // $detail  = "has clicked this danger button and needs help.";
            } else if($email_type == 'REQ_CALLBACK'){
                $subject = ucfirst($service_user_info->name).' '." callback request!";
                $request = " has requested to callback and need help.";
                //$request = "requesting for callback";
            } else {
                $subject = '';
                $request = '';
            }
            $location_url = 'https://www.google.com/maps/place/'.$latitude.','.$longitude;
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) 
            {   
                Mail::send('emails.danger', ['service_user_name'=>$service_user_info->name,'request'=>$request,'staff_user_name'=>$name,'email_type'=>$email_type, 'location_url'=> $location_url], function($message) use ($email,$company_name,$subject)
                {
                    $message->to($email,$company_name)->subject($subject);
                });
            } 
        }

        return true;

    }
}