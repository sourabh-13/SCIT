<?php
namespace App\Http\Controllers\frontEnd\ServiceUserManagement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\OfficeMessage, App\ServiceUser, App\ServiceUserNeedAssistance, App\ServiceUserCareCenter;


class CareCenterController extends Controller
{
    public function office_messages($service_user_id){
        $messages = OfficeMessage::where('service_user_id',$service_user_id)->orderBy('id','asc')->get();
        //echo "<pre>"; print_r($messages); die;
        echo '<div class="chat-conversation">
                <ul class="conversation-list">';
        foreach($messages as $key => $value) {
            
            if($value->order == 0){ //Yp message from App
                $div_clear = "clearfix even";
            }
            else{
                $div_clear = "clearfix odd";  //Staff message from website          
            }  
        
            echo   '<li class="'.$div_clear.'">
                        <div class="conversation-text">
                            <div class="ctext-wrap">
                                <p>'.$value->message.'
                                <i>'.date('g:i a d-M-Y', strtotime($value->created_at)).'</i>
                                </p>
                            </div>    
                        </div>
                    </li>';
        }
        echo '</ul></div>';
    }

    public function add_office_message(Request $r){
        $data = $r->input();
        //echo "<pre>"; print_r($data); die;
        $home_id             = ServiceUser::whereId($data['service_user_id'])->value('home_id');
        $om                  = new OfficeMessage;
        $om->home_id         = $home_id;
        $om->service_user_id = $data['service_user_id'];
        $om->user_id         = Auth::user()->id;
        $om->message         = $data['chat_input'];
        $om->order           = '1';
        if($om->save()){
            $messages = OfficeMessage::where('service_user_id',$data['service_user_id'])->get();
            //echo "<pre>"; print_r($messages); die;
            foreach($messages as $key => $value) {
                
                if($value->order == 0){ //Yp message from App
                    $div_clear = "clearfix even";
                }
                else{ 
                    $div_clear = "clearfix odd";   //Staff message from website                   
                }  
            
                echo   '<div class="chat-conversation">
                            <ul class="conversation-list">
                                <li class="'.$div_clear.'">
                                    <div class="conversation-text">
                                        <div class="ctext-wrap">
                                            <p>'.$value->message.'
                                            <i>'.date('g:i a d-M-Y', strtotime($value->created_at)).'</i>
                                            </p>    
                                    </div>
                                </li>
                            </ul>
                        </div>';
            }
        }
    }

    public function need_assistance_messages($service_user_id) {
        
        $need = ServiceUserNeedAssistance::where('service_user_id',$service_user_id)->get();
        foreach($need as $key => $value) {
            echo   '<div class="chat-conversation">
                        <ul class="conversation-list">
                            <li class="clearfix even">
                                <div class="conversation-text">
                                    <div class="ctext-wrap">
                                        <p>'.$value->message.'
                                        <i>'.date('g:i a d-M-Y', strtotime($value->created_at)).'</i>
                                        </p>    
                                </div>
                            </li>
                        </ul>
                    </div>';
        }
    }

    public function request_callback($service_user_id) {

        $request_callback  = ServiceUserCareCenter::select('su_care_center.id','su_care_center.created_at', 's.name')
                                                    ->join('service_user as s','s.id','su_care_center.service_user_id')
                                                    ->where('su_care_center.service_user_id', $service_user_id)
                                                    ->where('su_care_center.care_type', 'R')
                                                    ->orderBy('su_care_center.id','desc')
                                                    ->paginate(10);

        // echo "<pre>"; print_r($request_callback); die;
        // $req_callback = json_decode(json_encode($request_callback),true);                                                

        foreach ($request_callback as $key => $req_list) {
        
            echo '<a href="javascript:;" class="req_call_listing-li">'.$req_list->name.' has pressed request callback button | '.date('d M Y h:i a',strtotime($req_list->created_at)).'</a>';
        }
        echo $request_callback->links();
        die;
    }


    public function in_danger($service_user_id) {

        $in_danger  = ServiceUserCareCenter::select('su_care_center.id','su_care_center.created_at', 's.name')
                                                    ->join('service_user as s','s.id','su_care_center.service_user_id')
                                                    ->where('su_care_center.service_user_id', $service_user_id)
                                                    ->where('su_care_center.care_type', 'D')
                                                    ->orderBy('su_care_center.id','desc')
                                                    ->paginate(10);

        // echo "<pre>"; print_r($in_danger); die;
        // $req_callback = json_decode(json_encode($in_danger),true);                                                

        foreach ($in_danger as $key => $danger_list) {
        
            echo '<a href="javascript:;" class="req_call_listing-li">'.$danger_list->name.' has pressed in danger button | '.date('d M Y h:i a',strtotime($danger_list->created_at)).'</a>';
        }
        echo $in_danger->links();
        die;
    }

}