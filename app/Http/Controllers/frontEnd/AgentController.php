<?php

namespace App\Http\Controllers\frontEnd;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, DB;
use App\User, App\ServiceUser, App\Admin, App\Home, App\LogBook;
use Hash, Session;
use Carbon\Carbon;

class AgentController extends Controller
{
	  
	public function welcome_page(Request $request){

    	$all_companies = Admin::select('id','company')
                                ->where('access_type','O')
                                ->where('is_deleted','0')
                                ->get()
                                ->toArray();

    	$company_id 		= Auth::user()->admn_id;
    	$agent_home_ids 	= Auth::user()->home_id;
    	$agent_home_ids 	= explode(',', $agent_home_ids);

    	$homes = Home::select('id','admin_id','title')
    					->where('admin_id',$company_id)
    					->whereIn('id',$agent_home_ids)
    					->where('is_deleted','0')
    					->get()
    					->toArray();

    	
    	if($request->isMethod('post')){

    		
    		$all_home_ids 	= Auth::user()->home_id;
    		$user_name  	= Auth::user()->user_name; 

    		$hme_id 		= $request->home_id;
    		
    		$new_home_ids 	= $hme_id.','.$all_home_ids;
	    	$new_home_ids 	= implode(',',array_unique(explode(',', $new_home_ids)));
	    	$update_home_id = User::where('user_name',$user_name)
		    						->update(['home_id'=> $new_home_ids]);
		    if($update_home_id){
		    	return redirect('/')->with('success','your home change successfully.');
		    }else{
		    	return redirect()->back()->with('error',COMMON_ERROR);
		    }
    	}
    	return view('frontEnd.welcome',compact('all_companies','company_id','homes'));
    }

    /*public function check_staff_username_exists(Request $request){
    	
    	$count = User::where('user_name',$request->staff_user_name)->count();

        if($count > 0)  {
          	echo json_encode(false);	 //  for jquery validations
        } else {
            echo json_encode(true);      //  for jquery validations
        }    
    }

    public function check_su_username_exists(Request $request){
    	
    	$count = ServiceUser::where('user_name',$request->su_user_name)->count();

        if($count > 0) {
           echo json_encode(false);	  	 //  for jquery validations
        } else {
            echo json_encode(true);      //  for jquery validations
        }  
    }*/ 

    
}
