<?php 
namespace App\Http\Controllers\Api\ServiceUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth, DB, Validator;
use DateTime, Carbon\Carbon;
use App\User, App\ServiceUser, App\ServiceUserLocationHistory, App\ServiceUserSpecifiedLocation, App\ServiceUserLocationNotification, App\Notification, App\ServiceUserCareTeam;

class LocationController extends Controller
{
    
    public function add(Request $request) {
         //return json_encode(true); 

        $validator = Validator::make($request->all(), [
            'service_user_id' => 'required',
            'latitude'  => 'required',
            'longitude' => 'required', 
            // 'name'      => 'required',           
        ]);
        if($validator->fails()) {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message'  => FILL_FIELD_ERR,
               )
            ));
        } else {

            $data = $request->input();

            $previous_location   = ServiceUserLocationHistory::where('service_user_id',$data['service_user_id'])
                                                            ->whereDate('timestamp',date('Y-m-d'))
                                                            // ->orderBy('id','desc')
                                                            ->delete();
                                                        
            // if($previous_location){
                // return json_encode(array(
                //     'result' => array(
                //         'response' => true,
                //         'message'  => "Location added successfully."
                //     )
                // ));


                $location                  = new ServiceUserLocationHistory;
                $location->service_user_id = $data['service_user_id'];
                $location->latitude        = $data['latitude'];
                $location->longitude       = $data['longitude'];
                $location->timestamp       = date('Y-m-d H:i:s');
                $location->name            = !empty($data['name']) ? trim($data['name']) : NULL;
                $location->submission_type = '0'; 
                    
                if($location->save()) {
                    // echo "1"; die;
                    //send yp location notification in case of location change & save last location type
                    $this->NotifyLocationAlert($location->id);
                    
                    return json_encode(array(
                        'result' => array(
                            'response' => true,
                            'message'  => "Location added successfully."
                        )
                    ));
                } else {
                    return json_encode(array(
                        'result' => array(
                            'response' => false,
                            'message'  => "Some Error Occured.",
                       )
                    ));
                }
            // }else {
            //     return json_encode(array(
            //         'result' => array(
            //             'response' => false,
            //             'message'  => "Some Error Occured delete.",
            //        )
            //     ));
            // }
            // echo "<pre>"; print_r($data); die;
        }
    }

    /*public function add_missing_locations(Request $request) {
        
        return json_encode(array(
                    'result' => array(
                        'response' => true,
                        'message'  => "Location added successfully."
                    )
                ));
    }*/

    public function add_missing_locations(Request $request) {

        // $location   = ServiceUserLocationHistory::where('id',1297)->value('name');
        // $data       = json_decode($location, true);
        // $location_history = $data['locations'];
        //echo '<pre>'; print_r($location_history); die;   
      
        

        /*$location                  = new ServiceUserLocationHistory;
        $location->service_user_id = 1;
        $location->latitude        = 12;
        $location->longitude       = 34;
        $location->timestamp       = date('Y-m-d H:i:s');
        $location->submission_type = 1; 
        $location->name            = json_encode($data);
        if($location->save()){
            // $p = json_decode($location->name, true);
            // echo '<pre>'; print_r($p); die;
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message'  => "Location history added successfully."
                )
            ));
        } else{
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'error'  => "Location history mot added successfully."
                )
            ));
        }*/
        
        $data             = $request->input();

        $location_history = $data['locations'];
        //echo $location_history; die;
        
        //by default device type is set to android 
        $device_type      = (isset($data['device_type'])) ? $data['device_type'] : 'A';
         
        //$location_history = ' mk (';
        //$location_history = (string)$location_history;
        //  $location_history = str_replace('(','[',$location_history);
        //  $location_history = str_replace(')',']',$location_history);
        //echo $location_history; die;
         $location_history = json_decode($location_history,true);
        
        // echo '<pre>'; print_r($location_history); die;
         return json_encode(array(
                    'result' => array(
                        'response' => true,
                        'message'  => "Location added successfully testing.",
                        'my_data' => $location_history
                        
                    )
                ));
                
        $location_history = json_decode($location_history, true);

        $s = 0;
        $e = 0;

        if(is_array($location_history)) {
           
                
            foreach($location_history as $key => $value) {

                if($device_type == 'I'){
                    $value = json_decode($value, true);
                    // $result = $this->validateLatLong($value['latitude'],$value['longitude']);
                    // if($result == false){
                    //     unset($locations[$key]);    
                    // }
                }

                $location                  = new ServiceUserLocationHistory;
                $location->service_user_id = $data['service_user_id'];
                $location->latitude        = $value['latitude'];
                $location->longitude       = $value['longitude'];
                $location->timestamp       = date('Y-m-d H:i:s');
                $location->submission_type = 1; 
                if($location->save()) { //echo '1'; die;
                    $s++; 
                    //send yp location notification in case of location change & save last location type
                    $this->NotifyLocationAlert($location->id);
                } else{
                    $e++; 
                   // echo '2'; die;
                }
            }
        }
        //return 'true';

        /*return json_encode(array(
            'result' => array(
                'response' => true,
                'message'  => "success = ".$s.", error = ".$e.", Location history added successfully."
            )
        ));*/

        return json_encode(array(
            'result' => array(
                'response' => true,
                'message'  => "Location added successfully."
            )
        ));
        
        //Notes

        //android locations (one time json encoded)
        //example given below
        //[{"latitude": "123","longitude": "456","timestamp": "2017-09-27 12:00:00"},{"latitude": "123","longitude": "456","timestamp": "2017-09-27 12:00:00"}]

        //ios locations (two time json encoded) 
        //example given below
        //["{\"latitude\":\"30.9544706069558\",\"longitude\":\"75.8483777490239\",\"timestamp\":\"2017-10-27 06:12:55 +0000\"}", "{\"latitude\":\"30.9546524046306\",\"longitude\":\"75.8482433610304\",\"timestamp\":\"2017-10-27 06:12:59 +0000\"}", "{\"latitude\":\"30.9544950778263\",\"longitude\":\"75.8483723738906\",\"timestamp\":\"2017-10-27 06:13:25 +0000\"}"]

    }

    function NotifyLocationAlert($su_location_history_id){

        $location = ServiceUserLocationHistory::find($su_location_history_id);

        $location_name = $location->name;
        $location_latitude = $location->latitude;
        $location_longitude = $location->longitude;
        if(empty($location_name)) { //if location name is empty then get it using lat long
            $location_name = $this->get_location_from_lat_long($location->latitude,$location->longitude);
        }
   
        $service_user  = ServiceUser::select('home_id','id','name','last_loc_area_type')
                            ->where('id',$location->service_user_id)
                            ->first();

        $loc_match = ServiceUserSpecifiedLocation::where('location','LIKE','%'.$location_name.'%')
                            ->where('service_user_id', $service_user->id)
                            ->first();
        
        $notify_new_loc = '';
        $old_location_type = $service_user->last_loc_area_type;

        if(!empty($loc_match)){ //if location exist in our db

            if($loc_match->location_type == 'A'){
                if($service_user->last_loc_area_type != 'A'){
                    //send notification - entered into allowed
                    $notify_new_loc = 'A';
                    $this->updateSuAreaType($service_user->id,'A');
                    //echo 'entered into allowed'; die;
                }
            } else{ //if location type is restricted

                if($service_user->last_loc_area_type != 'R'){
                    //send notification - enter into restricted
                    $notify_new_loc = 'R';
                    $this->updateSuAreaType($service_user->id,'R');
                    //echo 'entered into restricted'; die;
                } 
            }                        
        } else{ //if loc is not specified by staff
            
            if($service_user->last_loc_area_type != 'G'){
                //send notification - entered into grey area
                $notify_new_loc = 'G';
                $this->updateSuAreaType($service_user->id,'G');
                //echo 'entered into grey'; die;
            }
        }
        // echo $notify_new_loc; die;
        if(!empty($notify_new_loc)){

            $location                    = ServiceUserLocationHistory::find($location->id);
            $location->location_type     = $notify_new_loc;
            $location->old_location_type = $old_location_type;
            $location->save();

            //saving su location notification start
            $loc_notification                    = new ServiceUserLocationNotification;
            $loc_notification->service_user_id   = $service_user->id;
            $loc_notification->home_id           = $service_user->home_id;
            $loc_notification->location_name     = $location_name;
            $loc_notification->latitude          = $location->latitude;
            $loc_notification->longitude         = $location->longitude;
            $loc_notification->old_location_type = $old_location_type;
            $loc_notification->location_type     = $notify_new_loc;
            $loc_notification->save();
            //save su location notification end
            
            //save sticky notification
            $notif                  = new Notification;
            $notif->home_id         = $service_user->home_id;
            $notif->service_user_id = $service_user->id;
            $notif->event_id        = $loc_notification->id;
            $notif->notification_event_type_id = 17;
            $notif->event_action    = 'ADD';
            $notif->is_sticky       = 1;
            $notif->save();
            //saving sticky notification end

            // send stiky notification to staffs
            // send email to staff members
            $emails = User::getStaffList($service_user->home_id);
            $company_name = PROJECT_NAME;
            
            $location_url = 'https://www.google.com/maps/place/'.$location_latitude.','.$location_longitude;
            foreach($emails as $value){
                //echo '<pre>'; print_r($value); die;
                $staff_email        = $value['email'];
                $staff_name         = $value['name'];

                if (!filter_var($staff_email, FILTER_VALIDATE_EMAIL) === false) 
                {   
                    Mail::send('emails.su_location_alert_to_staff', [
                            'staff_name' => $staff_name, 
                            'service_user_name' => $service_user->name,
                            'location_name' => $location_name,
                            'location_url' => $location_url,

                        ], function($message) use ($staff_email,$company_name)
                    {
                        $message->to($staff_email,$company_name)->subject('SCITS Location alert');
                    });
                } 
                //echo '1'; die;                            
            }   
            $care_team = ServiceUserCareTeam::select('id','email','name')
                                            ->where('service_user_id',$service_user->id)
                                            ->where('home_id',$service_user->home_id)
                                            ->where('is_deleted','0')
                                            ->get()
                                            ->toArray();
            foreach($care_team as $care_member){
                //echo '<pre>'; print_r($value); die;
                $care_member_email        = $care_member['email'];
                $care_member_name         = $care_member['name'];

                if (!filter_var($care_member_email, FILTER_VALIDATE_EMAIL) === false) 
                {   
                    Mail::send('emails.su_location_alert_to_care_team', [
                            'care_member_name' => $care_member_name, 
                            'service_user_name' => $service_user->name,
                            'location_name' => $location_name,
                            'location_url' => $location_url,
                            
                        ], function($message) use ($care_member_email,$company_name)
                    {
                        $message->to($care_member_email,$company_name)->subject('SCITS Location alert');
                    });
                } 
                //echo '1'; die;                            
            } 

        }  
        return true;                   
    }

    function updateSuAreaType($service_user_id,$last_loc_area_type){
        ServiceUser::where('id',$service_user_id)
                ->update([
                    'last_loc_area_type' => $last_loc_area_type
                ]);
    }

    /*public function getLocation(){ //for testing purpose only
        $lat = '30.9108';
        $long = '75.8793';
        $location_name = $this->get_location_from_lat_long($lat,$long);
        echo $location_name;
    }*/

    /*$last_loc_area_type= $service_user->last_loc_area_type;
                    $su_home_id        = $service_user->home_id;
                    $service_user_name = $service_user->name;

                    $need_notify = 'NO';
                    if($location_type == 'R'){
                        
                                        
                        if(!empty($spc_location)) {
                            $need_notify = 'YES';
                        }
                    } else{ //allowed then location should be same
                        $spc_location = ServiceUserSpecifiedLocation::where('location','LIKE','%'.$location_name.'%')
                                        ->where('service_user_id', $service_user->id)
                                        ->first();
                    
                        if(empty($spc_location)) {
                            $need_notify = 'YES';
                        }
                    }
                    
                    //Means if a location is found which is saved in our db
                    //if(!empty($spc_location)) {
                    
                    if($need_notify == 'YES') {
                        
                        // send stiky notification to staffs

                        //saving sticky notification start
                        $loc_notification                    = new ServiceUserLocationNotification;
                        $loc_notification->service_user_id   = $service_user->id;
                        $loc_notification->home_id           = $su_home_id;
                        $loc_notification->location_name     = $location_name;
                        $loc_notification->latitude          = $location->latitude;
                        $loc_notification->longitude         = $location->longitude;
                        $loc_notification->location_restriction_type = $location_type;
                        $loc_notification->save();
                       
                        // send email to staff members
                        $emails = User::select('email','name')
                                    ->where('home_id',$su_home_id)
                                    ->where('status','1')
                                    ->where('is_deleted','0')
                                    ->get()
                                    ->toArray();
                                    
                        $company_name        = PROJECT_NAME;
                        
                        foreach($emails as $value){
                            //echo '<pre>'; print_r($value); die;
                            $staff_email        = $value['email'];
                            $staff_name         = $value['name'];

                            if (!filter_var($staff_email, FILTER_VALIDATE_EMAIL) === false) 
                            {   
                                Mail::send('emails.su_location_alert_to_staff', [
                                        'staff_name' => $staff_name, 
                                        'service_user_name' => $service_user_name,
                                        'location_name' => $location_name
                                    ], function($message) use ($staff_email,$company_name)
                                {
                                    $message->to($staff_email,$company_name)->subject('SCITS Location alert');
                                });
                            }                             
                        }
                        
                    }*/
    
    public function notify_location_alert_node($su_location_history_id){

        $location = ServiceUserLocationHistory::find($su_location_history_id);

        $location_name = $location->name;
        $location_latitude = $location->latitude;
        $location_longitude = $location->longitude;
        if(empty($location_name)) { //if location name is empty then get it using lat long
            $location_name = $this->get_location_from_lat_long($location->latitude,$location->longitude);
        }
   
        $service_user  = ServiceUser::select('home_id','id','name','last_loc_area_type')
                            ->where('id',$location->service_user_id)
                            ->first();

        $loc_match = ServiceUserSpecifiedLocation::where('location','LIKE','%'.$location_name.'%')
                            ->where('service_user_id', $service_user->id)
                            ->first();
        
        $notify_new_loc = '';
        $old_location_type = $service_user->last_loc_area_type;

        if(!empty($loc_match)){ //if location exist in our db

            if($loc_match->location_type == 'A'){
                if($service_user->last_loc_area_type != 'A'){
                    //send notification - entered into allowed
                    $notify_new_loc = 'A';
                    $this->updateSuAreaType($service_user->id,'A');
                    //echo 'entered into allowed'; die;
                }
            } else{ //if location type is restricted

                if($service_user->last_loc_area_type != 'R'){
                    //send notification - enter into restricted
                    $notify_new_loc = 'R';
                    $this->updateSuAreaType($service_user->id,'R');
                    //echo 'entered into restricted'; die;
                } 
            }                        
        } else{ //if loc is not specified by staff
            
            if($service_user->last_loc_area_type != 'G'){
                //send notification - entered into grey area
                $notify_new_loc = 'G';
                $this->updateSuAreaType($service_user->id,'G');
                //echo 'entered into grey'; die;
            }
        }
        // echo $notify_new_loc; die;
        if(!empty($notify_new_loc)){

            $location                    = ServiceUserLocationHistory::find($location->id);
            $location->location_type     = $notify_new_loc;
            $location->old_location_type = $old_location_type;
            $location->save();

            //saving su location notification start
            $loc_notification                    = new ServiceUserLocationNotification;
            $loc_notification->service_user_id   = $service_user->id;
            $loc_notification->home_id           = $service_user->home_id;
            $loc_notification->location_name     = $location_name;
            $loc_notification->latitude          = $location->latitude;
            $loc_notification->longitude         = $location->longitude;
            $loc_notification->old_location_type = $old_location_type;
            $loc_notification->location_type     = $notify_new_loc;
            $loc_notification->save();
            //save su location notification end
            
            //save sticky notification
            $notif                  = new Notification;
            $notif->home_id         = $service_user->home_id;
            $notif->service_user_id = $service_user->id;
            $notif->event_id        = $loc_notification->id;
            $notif->notification_event_type_id = 17;
            $notif->event_action    = 'ADD';
            $notif->is_sticky       = 1;
            $notif->save();
            //saving sticky notification end

            // send stiky notification to staffs
            // send email to staff members
            $emails = User::getStaffList($service_user->home_id);
            $company_name = PROJECT_NAME;
            
            $location_url = 'https://www.google.com/maps/place/'.$location_latitude.','.$location_longitude;
            foreach($emails as $value){
                //echo '<pre>'; print_r($value); die;
                $staff_email        = $value['email'];
                $staff_name         = $value['name'];

                if (!filter_var($staff_email, FILTER_VALIDATE_EMAIL) === false) 
                {   
                    Mail::send('emails.su_location_alert_to_staff', [
                            'staff_name' => $staff_name, 
                            'service_user_name' => $service_user->name,
                            'location_name' => $location_name,
                            'location_url' => $location_url,

                        ], function($message) use ($staff_email,$company_name)
                    {
                        $message->to($staff_email,$company_name)->subject('SCITS Location alert');
                    });
                } 
                //echo '1'; die;                            
            }   
            $care_team = ServiceUserCareTeam::select('id','email','name')
                                            ->where('service_user_id',$service_user->id)
                                            ->where('home_id',$service_user->home_id)
                                            ->where('is_deleted','0')
                                            ->get()
                                            ->toArray();
            foreach($care_team as $care_member){
                //echo '<pre>'; print_r($value); die;
                $care_member_email        = $care_member['email'];
                $care_member_name         = $care_member['name'];

                if (!filter_var($care_member_email, FILTER_VALIDATE_EMAIL) === false) 
                {   
                    Mail::send('emails.su_location_alert_to_care_team', [
                            'care_member_name' => $care_member_name, 
                            'service_user_name' => $service_user->name,
                            'location_name' => $location_name,
                            'location_url' => $location_url,
                            
                        ], function($message) use ($care_member_email,$company_name)
                    {
                        $message->to($care_member_email,$company_name)->subject('SCITS Location alert');
                    });
                } 
                //echo '1'; die;                            
            } 

        }  
        return true;                  
    }

    public function lat_long_update_logout_tym(Request $request){
        // echo "<pre>"; print_r($request->input()); die;
        $validator = Validator::make($request->all(), [
            'service_user_id' => 'required',
            'latitude'  => 'required',
            'longitude' => 'required', 
            'name'      => 'required',           
        ]);
        if($validator->fails()) {
            return json_encode(array(
                    'result' => array(
                        'response' => false,
                        'message'  => FILL_FIELD_ERR,
                   )
            ));
        } else {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message'  => "Location updated successfully."
                )
            ));
            // $data = $request->input();

            
            // $location   = ServiceUserLocationHistory::where('service_user_id',$data['service_user_id'])
            //                                         ->where('location_source','L')
            //                                         ->whereDate('timestamp',date('Y-m-d'))
            //                                         ->orderBy('id','desc')
            //                                         ->first();
            // // echo "<pre>"; print_r($location); die;
            // if(!empty($location)){

            //     $location->latitude        = $data['latitude'];
            //     $location->longitude       = $data['longitude'];
            //     $location->name            = $data['name'];
                
            //     if($location->save()) {
            //         // echo "1"; die;

            //         //send yp location notification in case of location change & save last location type
            //         $this->NotifyLocationAlert($location->id);
            //         $remove_running_location   = ServiceUserLocationHistory::where('service_user_id',$data['service_user_id'])
            //                                         ->where('location_source','R')
            //                                         ->whereDate('timestamp',date('Y-m-d'))
            //                                         ->orderBy('id','desc')
            //                                         ->delete();
            //         if($remove_running_location){
                        
            //             return json_encode(array(
            //                 'result' => array(
            //                     'response' => true,
            //                     'message'  => "Location updated successfully."
            //                 )
            //             ));
            //         }else {
            //             return json_encode(array(
            //                 'result' => array(
            //                     'response' => false,
            //                     'message'  => "Some Error Occured.",
            //                )
            //             ));
            //         }
                    
            //     } else {
            //         return json_encode(array(
            //             'result' => array(
            //                 'response' => false,
            //                 'message'  => "Some Error Occured.",
            //            )
            //         ));
            //     }
            // } else {
            //     return json_encode(array(
            //         'result' => array(
            //             'response' => false,
            //             'message'  => "No Record Found.",
            //        )
            //     ));
            // }

        }

    }

}




?>