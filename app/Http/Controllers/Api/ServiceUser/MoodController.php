<?php 
namespace App\Http\Controllers\Api\ServiceUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, DB, Hash;
use DateTime, Carbon\Carbon;
use App\ServiceUserMood, App\ServiceUser, App\Notification, App\User;

class MoodController extends Controller
{
    /*public function replace($array)
    {
        foreach ($array as $key => $value) 
        {

            if(is_array($value))
                $array[$key] = $this->replace($value);
            else
            {
     
                if (is_null($value) ){
                  $array[$key] = "N/A";
                }
                    
            }
        }
        return $array;
    }*/
    
    public function moods($service_user_id=null)
    {
        $home_id = DB::table('service_user')->where('id',$service_user_id)->value('home_id');
        $moods = DB::table('mood')->select('id','name','image')->where('home_id',$home_id)->get();
        $moods = json_decode(json_encode($moods),true);
        if(!empty($moods))
        {
            return json_encode(array(
                'result' =>array(
                    'response' => true,
                    'message' => 'Mood List.',
                    'data' => $moods,
                    'mood_url' => MoodImgPath
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' =>array(
                    'response' => false,
                    'message' => 'Moods not found.',
                )
            ));
        }
    }
    
    public function add_mood(Request $r)
    {
        $data = $r->input();
        // echo "<pre>"; print_r($data); die;
        if(!empty($data['service_user_id']) && !empty($data['mood_id']) && !empty($data['description']))
        {
            $su_info = ServiceUser::select('id','home_id','name')->where('id',$data['service_user_id'])->first()->toArray();
            /*echo $su_info['home_id'];
            die;*/
            if(!empty($su_info))
            {   

                $su_feeling = new ServiceUserMood;
                $su_feeling->service_user_id = $data['service_user_id'];
                $su_feeling->mood_id = $data['mood_id'];
                $su_feeling->description = $data['description'];
                $su_feeling->home_id = $su_info['home_id'];
                $su_feeling->save();
                
                //saving notification start
                $notification                             = new Notification;
                $notification->service_user_id            = $data['service_user_id'];
                $notification->event_id                   = $su_feeling->id;
                $notification->notification_event_type_id = '21';
                $notification->event_action               = 'ADD';      
                $notification->home_id                    = $su_info['home_id'];        
                $notification->save();
                //saving notification end
                
               $users = User::select('user.id','user.name','ud.device_token','ud.user_type','ud.device_type')
                                ->join('user_device as ud','ud.user_id', 'user.id')
                                ->where([ 'user.home_id' => $su_info['home_id'], 'user.is_deleted' => '0', 'ud.user_type' => '1'
                                        ])
                                ->get()
                                ->toArray();
                // echo "<pre>"; print_r($users); die;
                $notify = [];
                foreach ($users as $key => $user) {
                    
                    $message     = $su_info['name'].' add a new mood.';
                    
                    $token       = array();
                    $token[]     = $user['device_token'];
                    $device_type = $user['device_type'];
                    $user_name   = $user['name'];

                    //     //Android notification
                    $messageAndroid                      = array();
                    $messageAndroid['title']             = PROJECT_NAME;
                    $messageAndroid['message']           = $message;
                    $messageAndroid['service_user_id']   = $data['service_user_id'];
                    $messageAndroid['subject']           = 'Notification';
                    $messageAndroid['notification_type'] = 'add_mood';
                    $notify[] =  $this->notifyFcm($token,$messageAndroid);
                }
                
                return json_encode(array(
                    'result' => array(
                        'response' => 'true',
                        'message' => 'Mood added successfully.'
                    )
                ));
            }
            else
            {
                return json_encode(array(
                    'result' => array(
                        'response' => 'false',
                        'message' => 'User not found'
                    )
                ));
            }
            
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => 'false',
                    'message' => 'Fill all fields.'
                )
            ));
        }
        /*print_r($data);
        die;*/
    }
    
    public function listing_mood($id)
    {
        $feelings = DB::table('su_mood')
            ->join('mood','su_mood.mood_id','=','mood.id')
            ->select('su_mood.id','mood.image','mood.name','su_mood.description','su_mood.suggestions','su_mood.created_at')->where('su_mood.service_user_id',$id)->orderBy('id','desc')->get();
        $feelings = json_decode(json_encode($feelings),true);
        foreach ($feelings as $key => $value) {
            $created_at = date('d/m/Y g:i A', strtotime($value['created_at']));
            $feelings[$key]['created_at'] = $created_at;
        }
        $feelings = $this->replace_null($feelings);
        if(!empty($feelings))
        {
            return json_encode(array(
                'result' =>array(
                    'response' => true,
                    'message' => 'Listing of User mood',
                    'data' => $feelings,
                    'mood_url' => MoodImgPath
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' =>array(
                    'response' => false,
                    'message' => 'Users mood history not found.',
                )
            ));
        }
    }
    
}