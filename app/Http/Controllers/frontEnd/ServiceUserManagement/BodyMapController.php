<?php

namespace App\Http\Controllers\frontEnd\ServiceUserManagement;
use App\Http\Controllers\frontEnd\ServiceUserManagementController;
use Illuminate\Http\Request;
use App\BodyMap, App\ServiceUserRisk;
use Auth;
//created on 22  june (neha)

class BodyMapController extends ServiceUserManagementController
{   
    //for body map
    public function index($su_risk_id = null) {

        // echo $su_risk_id; die;
        $staff_id         = Auth::user()->id;

        $service_user_id  = ServiceUserRisk::where('id', $su_risk_id)->value('service_user_id');
        // echo $service_user_id; die;
        $sel_injury_parts = BodyMap::select('id','sel_body_map_id','service_user_id','staff_id','su_risk_id')
                                    ->where('service_user_id',$service_user_id)
                                    ->where('staff_id',$staff_id)
                                    ->where('su_risk_id',$su_risk_id)
                                    ->where('is_deleted','0')
                                    ->get()
                                    ->toArray();
                                    
        // echo "<pre>";print_r($sel_injury_parts);die;

        return view('frontEnd.serviceUserManagement.elements.risk_change.body_map',compact('su_risk_id','sel_injury_parts','service_user_id'));
    }
    
    //to add injury point in bodymap
    public function addInjury(Request $request) {

        $data     = $request->input();
        // echo "<pre>"; print_r($data); die;
        $staff_id = Auth::user()->id;

        if (!empty($data)) {
            
            $body_map                  = new BodyMap;
            $body_map->service_user_id = $data['service_user_id'];
            $body_map->staff_id        = $staff_id;
            $body_map->sel_body_map_id = $data['sel_body_map_id'];
            $body_map->su_risk_id      = $data['su_risk_id'];
            $body_map->save();
        }

        echo"1";die;
        
    }

    //to remove injury point in bodymap
    public function removeInjury(Request $request,$service_user_id = null){

        $data        = $request->input();
        $selected_id = $data['sel_body_map_id'];
        $staff_id    = Auth::user()->id;

        if (!empty($data)) {

            $details = BodyMap::where('sel_body_map_id',$selected_id)->update(['is_deleted'=>'1']);
            if ($details) {

                echo "1";die;
            }  
        }

    }
}