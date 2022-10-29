<?php

namespace App\Http\Controllers\frontEnd\ServiceUserManagement;
use App\Http\Controllers\frontEnd\ServiceUserManagementController;
use Illuminate\Http\Request;
use App\ServiceUser, App\EventChangeRequest;
use DB,Auth;

class EventRequestController extends ServiceUserManagementController
{
    public function index($service_user_id = null) {
        
        /*$su_home_id = ServiceUser::where('id',$service_user_id)->value('home_id');
        if($su_home_id != Auth::user()->home_id){
            echo ''; die;
        }*/

        $su_event_req = EventChangeRequest::getChangeRequest($service_user_id);
        // echo "<pre>"; print_r($su_event_req); die;
        return $su_event_req;
        
    }

    public function view($req_id = null) {

        // $service_user_id = $req->service_user_id;
        // $su_event_req = EventChangeRequest::getChangeRequest($service_user_id,'VIEW');
        // return $su_event_req;

        $su_event_req = EventChangeRequest::select('event_change_request.id','event_change_request.new_date','event_change_request.date','event_change_request.reason','event_change_request.status')
                        ->where('event_change_request.id', $req_id)
                        ->first();


        $status = $su_event_req->status;
        if($status == 0){
            $status = '<select name="event_status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="2">Accept</option>
                            <option value="1">Reject</option> </select>';
        } elseif($status == 1) {
            $status = '<select name="event_status" class="form-control" disabled>
                            <option value="">Select Status</option>
                            <option value="2">Accept</option>
                            <option value="1" selected>Reject</option> </select>';   
        } elseif($status == 2){
            $status = '<select name="event_status" class="form-control" disabled>
                            <option value="">Select Status</option>
                            <option value="2" selected>Accept</option>
                            <option value="1">Reject</option> </select>';   
        }

        $date = date('d-m-Y', strtotime($su_event_req->date));
        $new_date = date('d-m-Y', strtotime($su_event_req->new_date));

        return $response = array('reason'=> $su_event_req->reason, 'date'=> $date, 'new_date'=>$new_date,'id'=>$su_event_req->id,'status'=>$status);

        // echo "<pre>"; print_r($status); die;

    }

    public function update(Request $request){

        $data = $request->input();
        $event_req_id = $data['event_req_id'];
        // echo "<pre>"; print_r($data); die;

        if(!empty($event_req_id)) {
            $event = EventChangeRequest::find($event_req_id);
            $event->status = $data['event_status'];
            if($event->save()) {
                echo "1";
            } else {
                echo "0";
            }
            die;
        } else {
            return redirect()->back()->with('error',UNAUTHORIZE_ERR);
        }

        // $res = EventChangeRequest::where('id',$data['event_req_id'])->update(['status'=>'1']);
        // echo "<pre>"; print_r($data); die;
        echo $res;

    }

}