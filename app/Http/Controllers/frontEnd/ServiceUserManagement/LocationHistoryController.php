<?php
namespace App\Http\Controllers\frontEnd\ServiceUserManagement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, Session;
use App\ServiceUser, App\ServiceUserSpecifiedLocation, App\ServiceUserLocationHistory, App\Home, App\ServiceUserLocationNotification;

class LocationHistoryController extends Controller
{
   	public function index(Request $request, $service_user_id = null){

   		$specified_locations =  ServiceUserSpecifiedLocation::select('id','location','location_type','latitude','longitude','radius','timestamp')
						   									->where('service_user_id',$service_user_id)
						   									->where('is_deleted','0')
						   									->get()
						   									->toArray();

   		$location_history_duration = Home::where('id',Auth::user()->home_id)
   										->value('location_history_duration');

   		$service_user = ServiceUser::select('name','location_get_interval')->where('id',$service_user_id)->first();
   		$location_get_interval	   = $service_user->location_get_interval;
   		$service_user_name 		   = $service_user->name;
   		$data = $request->input();
   		$locations    = array();
   		
   		if(isset($data['date'])){

   			$search_date = $data['date'];
   			if(!empty($search_date)){
	   			$search_date_reformat = date('Y-m-d',strtotime($search_date));
   			} else {
				$search_date          = date('d-m-Y');
				$search_date_reformat = date('Y-m-d');
			}
		} else {
			$search_date = date('d-m-Y');
			$search_date_reformat = date('Y-m-d');
		}
		 //echo '<prE>'; print_r($service_user_id); 
		 // echo '<prE>'; print_r($search_date_reformat); die;

		$locations   = ServiceUserLocationHistory::select('id','latitude','longitude','timestamp')
						->where('service_user_id',$service_user_id)
						->where('timestamp','LIKE',$search_date_reformat.'%')
						->where('location_source','L')
						//->orWhere('timestamp','LIKE',$search_date_reformat.'%')
						->orderBy('id','asc')
						->limit('10')
						->get()->toArray();
						// ->orWhere('timestamp',$search_date_reformat.'00:00:00')
				// echo '<pre>'; print_r($locations); die;
		//validating lat long
		foreach ($locations as $key => $value) {
			//$locations[$key]['latitude'] = $this->
			$result = $this->validateLatLong($value['latitude'],$value['longitude']);
			if($result == false){
				unset($locations[$key]);	
			}
		}
		$locations = array_values($locations);
		
 		$latest_loc = array();
	    if(!empty($locations)) {

	        $arr_length = count($locations);
	        $last_key   = $arr_length - 1;
	        
	        $latest_loc['latitude']  = $locations[$last_key]['latitude'];
	        $latest_loc['longitude'] = $locations[$last_key]['longitude'];
	        $latest_loc['timestamp'] = $locations[$last_key]['timestamp'];
	        // echo "<pre>"; print_r($latest_loc); die;
	        unset($locations[$last_key]);
	    }
	     // echo "<pre>"; print_r($latest_loc); die;
	    //default lot long set to london coordinates
	    $focus_loc['latitude']  = (isset($latest_loc['latitude'])) ? $latest_loc['latitude'] : '51.509865';
	    $focus_loc['longitude'] = (isset($latest_loc['longitude'])) ? $latest_loc['longitude'] : '-0.118092';
	    
	    $noti_data = array();
        if(Session::has('noti_data')){
            $noti_data = Session::get('noti_data');
            Session::forget('noti_data');
        }

        //service user today login location
        $su_locations   = ServiceUserLocationHistory::select('id','latitude','longitude','timestamp')
													->where('service_user_id',$service_user_id)
													->whereDate('timestamp',date('Y-m-d'))
													->where('location_source','L')
													->orderBy('id','desc')
													->first();
		// echo '<prE>'; print_r($su_locations); die;
		//service user today last logout location
		$su_last_running_locations   = ServiceUserLocationHistory::select('id','latitude','longitude','timestamp')
													->where('service_user_id',$service_user_id)
													->whereDate('timestamp',date('Y-m-d'))
													->where('location_source','R')
													->orderBy('id','desc')
													->first();
		//service user today all locations
		$su_all_running_locations   = ServiceUserLocationHistory::select('id','latitude','longitude','timestamp')
													->where('service_user_id',$service_user_id)
													// ->whereDate('timestamp',date('Y-m-d'))
													->whereDate('timestamp',$search_date_reformat)
													->where('location_source','R')
													->orderBy('id','asc')
													->get()->toArray();
	    // echo '<prE>'; print_r($su_all_running_locations); die;
   		return view('frontEnd.serviceUserManagement.location_history', compact('service_user_id','specified_locations','location_history_duration','locations','search_date','service_user_name','location_get_interval','latest_loc','focus_loc','noti_data','su_locations','su_last_running_locations','su_all_running_locations'));
   	}

   	public function add_location(Request $request) {

		$data   = $request->input();
		$miles  = $data['miles'];
		$radius = $miles * 1609.34;
		//echo "<pre>"; print_r($data); die;
		$location  				   = new ServiceUserSpecifiedLocation;
		$location->home_id         = Auth::user()->home_id;
		$location->service_user_id = $data['service_user_id'];
		$location->location        = $data['location'];
		$location->location_type   = $data['location_type'];
		$location->latitude		   = $data['latitude'];
		$location->longitude       = $data['longitude'];
		$location->radius          = $radius;
		//$location->location_type   = $data['location_type'];;
		$location->timestamp       = date('Y-m-d H:i:s');
		if($location->save()) {
			return redirect()->back()->with('success','Location has been added successfully.');
		} else {
			return redirect()->back()->with('error',COMMON_ERROR);
		}
	}

	public function edit_location(Request $request, $location_id = null){
		// echo "<pre>"; print_r($request->input()); die;
		$location = ServiceUserSpecifiedLocation::find($location_id);

		if($request->isMethod('post')){
			$radius = $request->miles * 1609.34;
			$location->radius = $radius;

			if($location->save()) {
				return redirect()->back()->with('success','Location has been updated successfully.');
			}else{
				return redirect()->back()->with('error',COMMON_ERROR);
			}
		}else{
			return redirect()->back()->with('error',COMMON_ERROR);
		}
	}

   	public function delete_location($location_id = null) {
		$location = ServiceUserSpecifiedLocation::where('id',$location_id)->update(['is_deleted' => '1']);

		if($location) {
			return redirect()->back()->with('success','Location has been deleted successfully.');
		} else {
			return redirect()->back()->with('error',COMMON_ERROR);
		}
	}

	public function change_location_restriction_type(Request $request){
		
		$data = $request->input();
		//$location_type 		    			 = $data['location_type'];
		$service_user 							 = ServiceUser::where('id',$data['service_user_id'])->first();
		$service_user->location_restriction_type = $data['location_type'];
		$service_user->location_get_interval     = (int)$data['location_get_interval'];

		if($service_user->save()){
			return redirect('/service/location-history/'.$service_user->id)->with('success','Location type has been updated successfully.');
		} else{
			return redirect()->back()->with('error',COMMON_ERROR);
		}
	}

	/*public function acknowldg_loc_notif_master($location_notif_id = null){ //acknowledge for all then notifi. will not be disappeared for all

		$update = ServiceUserLocationNotification::where('id',$location_notif_id)->update([
					'master_acknowledgement' => Auth::user()->id
					]);
		if($update){
			return 'true';
		} else{
			return 'false';
		}
	}

	public function acknowldg_loc_notif_personal($location_notif_id = null){ 
		//acknowledge for self then notifi. will be shown to everybody else

		$loc_notify = ServiceUserLocationNotification::where('id',$location_notif_id)->first();
		
		if(!empty($loc_notify)){
			
			$user_id = Auth::user()->id;
			
			if(!empty($loc_notify->individual_acknowledgement)){
				$indivdl_ack = json_decode($loc_notify->individual_acknowledgement,true);
				
				//if this is already acknowleged by this user then do not save this again.
				if(is_array($indivdl_ack)){
					if(array_key_exists($user_id,$indivdl_ack)){ 
						return 'true';
					} else{
						$indivdl_ack[$user_id] = date('Y-m-d H:i:s');
					}					
				} else{
					$indivdl_ack[$user_id] = date('Y-m-d H:i:s');
				}

			} else{
				$indivdl_ack = array();
				$indivdl_ack[$user_id] = date('Y-m-d H:i:s');
			}
			ksort($indivdl_ack);
			$update = ServiceUserLocationNotification::where('id',$location_notif_id)->update([
						'individual_acknowledgement' => json_encode($indivdl_ack)
					]);
			
			if($update){
				return 'true';
			} else{
				return 'false';
			}			

		}
	}*/
}