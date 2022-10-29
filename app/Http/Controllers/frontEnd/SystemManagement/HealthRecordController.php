<?php

namespace App\Http\Controllers\frontEnd\SystemManagement;
use App\Http\Controllers\frontEnd\SystemManagementController;
use Illuminate\Http\Request;
use App\ServiceUserHealthRecord;
use DB, Auth;

class HealthRecordController extends SystemManagementController
{

    public function index(){

    	$health_records = ServiceUserHealthRecord::select('su_health_record.*')
    							->join('service_user as su','su.id','=','su_health_record.service_user_id')
    							->where('su.home_id',Auth::user()->home_id)
    							->orderBy('su_health_record.id','desc')
    							->paginate(5);
        
        foreach($health_records as $key => $value) {

            echo '<a href="#" class="healthrcrd-li">'.$value->title.'</a>';
        }   
        echo $health_records->links();        
        die;
    }

}