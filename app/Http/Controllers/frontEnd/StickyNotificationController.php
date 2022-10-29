<?php 

namespace App\Http\Controllers\frontEnd;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Notification;

class StickyNotificationController extends Controller
{
	public function ack_master($notif_id = null){ //acknowledge for all then notifi. will not be disappeared for all

		$update = Notification::where('id',$notif_id)->update([
					'sticky_master_ack' => Auth::user()->id,
					'sticky_master_ack_timestamp' => date('Y-m-d H:i:s')
				]);

		if($update){
			return 'true';
		} else{
			return 'false';
		}

	}

	public function ack_individual($notifi_id = null){ 
		//acknowledge for self then notifi. will be shown to everybody else

		$notifi = Notification::where('id',$notifi_id)->first();
		$individual_ack = $notifi->sticky_individual_ack;
		$user_id = Auth::user()->id;
		
		if(!empty($individual_ack)){
			
			//converting object into array
			$individual_ack = json_decode($individual_ack,true);

			//if this is already acknowleged by this user then do not save this again.
			if(is_array($individual_ack)){
				if(array_key_exists($user_id,$individual_ack)){ 
					return 'true';
				} else{
					$individual_ack[$user_id] = date('Y-m-d H:i:s');
				}					
			} else{
				$individual_ack[$user_id] = date('Y-m-d H:i:s');
			}

		} else{
			$individual_ack = array();
			$individual_ack[$user_id] = date('Y-m-d H:i:s');
		}

		ksort($individual_ack);

		$notifi->sticky_individual_ack = json_encode($individual_ack);

		if($notifi->save()){
			return 'true';
		} else{
			return 'false';
		}			
	}

	/*public function update_view_status($id) {
		$danger_data = ServiceUserCareCenter::where('id',$id)->update([
			'acknowledged_by' => Auth::user()->id
			]);
		if($danger_data){
			echo true;
		} else{
			echo false;
		}
		die;
	}*/
}