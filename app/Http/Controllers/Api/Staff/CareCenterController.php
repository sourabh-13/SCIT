<?php

namespace App\Http\Controllers\Api\Staff;
use App\Http\Controllers\Api\StaffManagementController;
use Illuminate\Http\Request;
use App\Http\Requests;
// use Illuminate\Support\Facades\Mail;
use App\User, App\ServiceUserCareCenter;
use Validator;

Class CareCenterController extends StaffManagementController
{        
    public function in_danger_requests($staff_id = null){

        $home_id = User::where('id',$staff_id)->value('home_id');

        $in_danger_reqs = ServiceUserCareCenter::select('su_care_center.id','su_care_center.service_user_id','su_care_center.created_at')
                            ->join('service_user as su','su.id','su_care_center.service_user_id')
                            ->where('su.home_id',$home_id)
                            ->where('su_care_center.care_type','D')
                            ->count();
        $res['response'] = true;
        $res['message'] = 'Danger requests count';
        $res['total'] = $in_danger_reqs;
        return $res;        
    }

    public function request_callbacks($staff_id = null){

        $home_id = User::where('id',$staff_id)->value('home_id');

        $request_callbacks = ServiceUserCareCenter::select('su_care_center.id','su_care_center.service_user_id','su_care_center.created_at')
                            ->join('service_user as su','su.id','su_care_center.service_user_id')
                            ->where('su.home_id',$home_id)
                            ->where('su_care_center.care_type','R')
                            ->count();
        $res['response'] = true;
        $res['message'] = 'Request callbacks count';
        $res['total'] = $request_callbacks;
        return $res;
    }
}