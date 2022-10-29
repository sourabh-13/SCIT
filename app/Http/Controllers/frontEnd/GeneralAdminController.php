<?php

namespace App\Http\Controllers\frontEnd;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\ServiceUser, App\User, App\HomeLabel, App\Home;
use DB;
use Auth;

class GeneralAdminController extends Controller
{
	  
	public function index()
    {
        //$labels = HomeLabel::getLabels();
        $home_id = Auth::user()->home_id;
       	$users = User::select('id','name')->where('home_id',$home_id)->where('is_deleted','0')->get()->toArray();
        $service_users = ServiceUser::select('id','name','image')->where('home_id',$home_id)->where('is_deleted','0')->get()->toArray();
       	$guide_tag = 'general_admin';
      
       	$home = Home::where('id',Auth::user()->home_id)->select('weekly_allowance')->first();
        
       	return view('frontEnd.generalAdmin.index',compact('users','guide_tag','home','service_users'));
	}


 
}