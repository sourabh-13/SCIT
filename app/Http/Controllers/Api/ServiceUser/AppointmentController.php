<?php 

namespace App\Http\Controllers\Api\ServiceUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ServiceUser, App\PlanBuilder, App\ServiceUserCalendarEvent, App\User;

class AppointmentController extends Controller
{
    public function appointment_forms_list($service_user_id=null)
    {
        $home_id = ServiceUser::where('id',$service_user_id)->value('home_id');
        $appointment_list = PlanBuilder::select('id','title')->where('home_id',$home_id)->where('is_deleted',0)->get()->toArray();
        
        if(!empty($appointment_list))
        {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'data' => $appointment_list,
                    'message' => "Appointment Forms List."
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "No sppointments found."
                )
            ));
        }
    }
    
    public function view_add_appointment_form($form_id=null)
    {
        
        $appointment_detail = PlanBuilder::select('id','title','pattern')->where('id',$form_id)->where('is_deleted',0)->first();
        
        if(!empty($appointment_detail))
        {
            
            $deflt_patrn                   = array();
            $deflt_patrn[0]['label']       = 'Title';
            $deflt_patrn[0]['column_name'] = 'title';
            $deflt_patrn[0]['column_type'] = 'Textbox';
            
            $saved_patrn = json_decode($appointment_detail->pattern);
            
            if(!empty($saved_patrn)) {
                $pattern = array_merge($deflt_patrn,$saved_patrn);
            } else{
                $pattern = $deflt_patrn;
            }
            
            $appointment_detail->pattern = $pattern;
           
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'data' => $appointment_detail,
                    'message' => "Appointment detail."
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "Appointment detail not found."
                )
            ));   
        }
    }
    
    public function save_appointment(Request $r) {
        $data = $r->input();
        //echo "<pre>"; print_r($data); die;
        if(!empty($data['service_user_id'])&& !empty($data['form_id'])&& !empty($data['title']) && !empty($data['user_id']) )
        {
            $home_id = ServiceUser::where('id',$data['service_user_id'])->value('home_id');
            if(isset($data['formdata'])) {
                //$formdata = json_encode($data['formdata']);
                $formdata = $data['formdata'];
            } else {
                $formdata = '';
            }

            $su_calander_event                  = new ServiceUserCalendarEvent;
            $su_calander_event->service_user_id = $data['service_user_id'];
            $su_calander_event->home_id         = $home_id;
            $su_calander_event->user_id         = $data['user_id'];
            $su_calander_event->plan_builder_id = $data['form_id'];
            $su_calander_event->title           = $data['title'];
            $su_calander_event->formdata        = $formdata;
            $su_calander_event->save();
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "Appointment Added Successfully."
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "Fill all fields."
                )
            ));
        }
    }
    
    public function view_appointment_detail($su_calendar_event_id) {

        if(!empty($su_calendar_event_id)) {
            $form_pattern = PlanBuilder::getPlanPatternWithValue($su_calendar_event_id);
            // echo "<pre>"; print_r($form_pattern); die;
            
            $user_data = ServiceUserCalendarEvent::select('su_calendar_event.user_id','u.name')
                                                    ->leftJoin('user as u','u.id','su_calendar_event.user_id')
                                                    ->where('su_calendar_event.id', $su_calendar_event_id)
                                                    ->first();
            
            // $result['su_calendar_event_id'] = $su_calendar_event_id;
            // $result['form_pattern']         = $form_pattern;
            if(!empty($form_pattern)) {
                $result['result']['response'] = true;
                $result['result']['su_calendar_event_id'] = $su_calendar_event_id;
                $result['result']['form_pattern']         = $form_pattern;
                $result['result']['user_data']           = $user_data;
            } else {
                $result['result']['response'] = false;
                $result['result']['message']  = "Appointment detail not found.";
            }
        } else {
            $result['result']['response'] = false;
            $result['result']['message']  = "Appointment detail not found.";
        }
        return json_encode($result);   
        // echo "<pre>"; print_r($result); die;

    }
    
    public function appointments($service_user_id) {
        //$data = $r->input();
        //echo "<pre>"; print_r($data); die;
        
        //ServiceUserCalendarEvent

        $su_calander_events = ServiceUserCalendarEvent::select('id','title')
                                    ->where('service_user_id',$service_user_id)
                                    ->orderBy('id','desc')
                                    ->get()
                                    ->toArray();
    
        if(!empty($su_calander_events)){
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "Appointment list.",
                    'data' => $su_calander_events
                    
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "No appointments found."
                )
            ));
        }
    }
    
    /*public function add_appointment(Request $r)
    {
        $data = $r->input();
        if(!empty($data['service_user_id'])&& !empty($data['plan_builder_id'])&& !empty($data['title'])&& !empty($data['formdata']))
        {
            $home_id = ServiceUser::where('id',$data['service_user_id'])->value('home_id');
            $formdata = json_encode($data['formdata']);
            $su_calander_event                  = new ServiceUserCalendarEvent;
            $su_calander_event->service_user_id = $data['service_user_id'];
            $su_calander_event->home_id         = $home_id;
            $su_calander_event->plan_builder_id = $data['plan_builder_id'];
            $su_calander_event->title           = $data['title'];
            $su_calander_event->formdata        = $formdata;
            $su_calander_event->save();
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "Appointment Added."
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "Fill all fields."
                )
            ));
        }
    }*/
    
    /*-------Akhil June19,2018 ----*/
    public function staff_members($service_user_id = null) {
        
        
        $home_id = ServiceUser::where('id', $service_user_id)->value('home_id');
        
        if(!empty($home_id)) {
        
            $users  = User::select('id','name')
                            ->where('home_id', $home_id)
                            ->where('is_deleted','0')
                            ->get()
                            ->toArray();
            // echo "<pre>"; print_r($users); die;
            if(!empty($users)) {
                return json_encode(array(
                    'result' => array(
                        'response' => true,
                        'message'  => "Staff Member List.",
                        'data'     => $users
                    )
                ));
            } else {
                 return json_encode(array(
                    'result' => array(
                        'response' => false,
                        'message'  => "No user found.",
                        'data'     => $users
                    )
                ));
            }
        }  else {
             return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message'  => "No record found.",
                    'data'     => $users
                )
            ));
        }

    }
    
}