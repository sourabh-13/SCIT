<?php
namespace App\Http\Controllers\Api\Staff;
use App\Http\Controllers\frontEnd\StaffManagementController;
use Illuminate\Http\Request;
use App\ServiceUserMoneyRequest, App\ServiceUserMoney, App\User;
use Validator;

class MoneyRequestController extends StaffManagementController
{

    public function index($staff_member_id) //mk
    {
        //staff_member_id
        $home_id = User::where('id',$staff_member_id)->value('home_id');

        $user_money_req_list = ServiceUserMoneyRequest::select('su_money_request.id','su_money_request.amount','su_money_request.description','su_money_request.provider_comment','su_money_request.status','su_money_request.created_at','su.name as service_user_name','su.image')
                                    ->where('su.home_id',$home_id)
                                    ->join('service_user as su','su.id','su_money_request.service_user_id')
                                    ->orderBy('su_money_request.id','desc')
                                    ->get()
                                    ->toArray();

        $req_money_list = json_decode(json_encode($user_money_req_list),true);
        $req_money_list = $this->replace_null($req_money_list);
        //echo '<pre>'; print_r($req_money_list); die;

        foreach ($req_money_list as $key => $value){

            if($value['status'] == '1'){
                $req_money_list[$key]['status'] = 'Rejected';
            } else if($value['status'] == '2'){
                $req_money_list[$key]['status'] = 'Accepted';
            } else{
                $req_money_list[$key]['status'] = 'Pending';
            }
            $req_money_list[$key]['created_at'] = date('d/m/Y g:i a', strtotime($value['created_at']));
        }

        if(!empty($req_money_list))
        {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'image_url' => serviceUserProfileImagePath,
                    'message' => "Money request list.",
                    'data' => $req_money_list
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "No money request found.",
                )
            ));
        }
    }

    public function update_request(Request $request) {

        $validator = Validator::make($request->all(), [
            'staff_id' => 'required',
            'request_id' => 'required',
            'status' => 'required',
            'description' => 'required'
        ]);

        if($validator->fails()) {
            return json_encode(array(
                    'result' => array(
                        'response' => false,
                        'message'  => FILL_FIELD_ERR,
                   )
            ));
        } else {
            $data = $request->input();

            //checking if this staff has access right to update request start
            $access_id = '193'; //from access right table
            $access = User::checkUserHasAccessRight($data['staff_id'],$access_id);
            if($access == false){
                return json_encode(array(
                        'result' => array(
                            'response' => true,
                            'message' => UNAUTHORIZE_ERR_APP,
                        )
                    ));  
            } 
            //checking if this staff has access right to update request end

            $money_request = ServiceUserMoneyRequest::where('id',$data['request_id'])->first();

            if(!empty($money_request)) {

                if($money_request->status != 0){
                    return json_encode(array(
                        'result' => array(
                            'response' => true,
                            'message' => "Money request status is already set.",
                        )
                    ));  
                }

                $su_money = ServiceUserMoney::where('service_user_id',$money_request->service_user_id)->first();

                if(!empty($su_money)) {  
                    $balance_amount   = $su_money->balance;
                    $requested_amount = $money_request->amount;
                    $new_status       = $data['status'];

                    if($new_status == 2){  //if accept status

                        if($requested_amount <= $balance_amount){

                            $money_request->provider_user_id = $data['staff_id'];                                        
                            $money_request->provider_comment = $data['description'];
                            $money_request->status           = $new_status;                                        
                            
                            if($money_request->save()){

                                if($money_request->status == 2){ 
                                    //Add entry to su_money table.
                                    $su_money                   = new ServiceUserMoney;
                                    $new_balance                = $balance_amount - $requested_amount;
                                    $su_money->balance          = $new_balance;
                                    $su_money->service_user_id  = $money_request->service_user_id; 
                                    //$su_money->user_id          = Auth::user()->id;
                                    $su_money->description      = "Amount Given.";
                                    $su_money->txn_type         = "W";
                                    $su_money->txn_amount       = $requested_amount;
                                    $su_money->su_money_request_id  = $money_request->id;
                                    if($su_money->save()){ } 
                                }

                                return json_encode(array(
                                    'result' => array(
                                        'response' => true,
                                        'message' => "Money request updated successfully.",
                                    )
                                ));  

                            } else{
                                return json_encode(array(
                                    'result' => array(
                                        'response' => false,
                                        'message' => COMMON_ERROR,
                                    )
                                ));
                            }
                        } else{
                            return json_encode(array(
                                'result' => array(
                                    'response' => false,
                                    'message' => "Insufficient balance.",
                                )
                            ));
                        }

                    } else if($new_status == 1){ //if reject

                        $money_request->provider_user_id = $data['staff_id'];                                        
                        $money_request->provider_comment = $data['description'];
                        $money_request->status           = $data['status'];                                        
                        
                        if($money_request->save()){
                            return json_encode(array(
                                'result' => array(
                                    'response' => true,
                                    'message' => "Money request updated successfully.",
                                )
                            ));
                            // return $resp = array('status'=>'ok');
                        } else {
                            return json_encode(array(
                                'result' => array(
                                    'response' => false,
                                    'message' => COMMON_ERROR,
                                )
                            ));
                        }

                    } 
                } else{
                    return json_encode(array(
                            'result' => array(
                                'response' => false,
                                'message' => "Insufficient balance.",
                            )
                        ));
                    //echo '2'; die;
                }

            } else{
                return json_encode(array(
                        'result' => array(
                            'response' => false,
                            'message' => 'Money Request not found',
                        )
                    ));
            }

        }
    }

}
