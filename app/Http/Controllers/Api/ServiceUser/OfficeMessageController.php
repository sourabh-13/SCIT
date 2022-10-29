<?php 
namespace App\Http\Controllers\Api\ServiceUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ServiceUser, App\OfficeMessage, App\ServiceUserNeedAssistance;

class OfficeMessageController extends Controller
{
    public function add(Request $r){
        $data = $r->input();
        //echo "<pre>"; print_r($data); die;
        if(!empty($data['service_user_id']) && !empty($data['message'])){
	        $home_id = ServiceUser::whereId($data['service_user_id'])->value('home_id');
	        if($home_id != ""){
	        	$om 		 		 = new OfficeMessage;
	        	$om->home_id 		 = $home_id;
	        	$om->service_user_id = $data['service_user_id'];
	        	$om->message 		 = $data['message'];
	        	if($om->save()){
	        		return json_encode(array(
		        		'result' => array(
		        			'response' => true,
		        			'message' => "Message has been sent successfully."
		        		)
		        	));
	        	}else{
	        		return json_encode(array(
		        		'result' => array(
		        			'response' => false,
		        			'message' => COMMON_EROR
		        		)
		        	));
	        	}
	        } else{
	        	return json_encode(array(
	        		'result' => array(
	        			'response' => false,
	        			'message' => "User not found."
	        		)
	        	));
	        }
  		}else{
  			return json_encode(array(
        		'result' => array(
        			'response' => false,
        			'message' => "Fill all fields."
        		)
        	));
  		}  
    }       

    public function need_assistance_add(Request $r){
        $data = $r->input();
        if(!empty($data['service_user_id']) && !empty($data['message'])){
	        $home_id = ServiceUser::whereId($data['service_user_id'])->value('home_id');
	        if($home_id != ""){
	        	$om 		 		 = new ServiceUserNeedAssistance;
	        	$om->home_id 		 = $home_id;
	        	$om->service_user_id = $data['service_user_id'];
	        	$om->message 		 = $data['message'];
	        	if($om->save()){
	        		return json_encode(array(
		        		'result' => array(
		        			'response' => true,
		        			'message' => "Message Added."
		        		)
		        	));
	        	}else{
	        		return json_encode(array(
		        		'result' => array(
		        			'response' => false,
		        			'message' => COMMON_EROR
		        		)
		        	));
	        	}
	        } else{
	        	return json_encode(array(
	        		'result' => array(
	        			'response' => false,
	        			'message' => "User not found."
	        		)
	        	));
	        }
  		}else{
  			return json_encode(array(
        		'result' => array(
        			'response' => false,
        			'message' => "Fill all fields."
        		)
        	));
  		}  
    }
    
}