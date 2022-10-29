<?php 
namespace App\Http\Controllers\Api\ServiceUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ServiceUserMoneyRequest, App\ServiceUserMoney, App\ServiceUser, App\Home, App\Notification;

class MoneyController extends Controller
{
    public function index($service_user_id)
    {
        $home_id = ServiceUser::whereId($service_user_id)->value('home_id');
        $balance = ServiceUserMoney::where('service_user_id',$service_user_id)->orderBy('id','desc')->value('balance');
        $balance = (float)$balance;
        $allocated_money = Home::whereId($home_id)->value('weekly_allowance');
        $allocated_money = (float)$allocated_money;
        $money['allocated_money'] = (string)$allocated_money; 
        $money['balance'] = (string)$balance; 
        
        //$money  =array('allocated_money' => $allocated_money,'balance' => $balance ); 
    
        $money = json_decode(json_encode($money),true);
        $money = $this->replace_null($money);
        if(!empty($money))
        {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => 'My Money',
                    'data' => $money
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => 'No data found.',
                )
            ));
        }
        
    }
    
    public function add_money_request(Request $r) { //made request

        $data = $r->input();
       
        if(!empty($data['su_id']) && !empty($data['amount']) && !empty($data['description']))
        {
            $service_user = ServiceUser::select('id','home_id')->where('id',$data['su_id'])->first();
            if(!empty($service_user)){

                $bal = ServiceUserMoney::select('balance')->where('service_user_id',$service_user->id)->first();
                //   echo"<pre>"; print_r($bal); die;
                
                $available_balance = isset($bal->balance) ? $bal->balance : 0;
                
                if($available_balance >= $data['amount'])
                {   
                    $money                  = new ServiceUserMoneyRequest;
                    $money->service_user_id = $service_user->id;
                    $money->amount          = $data['amount'];
                    $money->description     = $data['description'];
                    $money->status          = 0;

                    if($money->save()){    
                        
                        //save sticky notification
                        $notif                  = new Notification;
                        $notif->home_id         = $service_user->home_id;
                        $notif->service_user_id = $service_user->id;
                        $notif->event_id        = $money->id;
                        $notif->notification_event_type_id = 18;
                        $notif->event_action    = 'ADD';
                        $notif->is_sticky       = 1;
                        $notif->save();

                        return json_encode(array(
                            'result' => array(
                                'response' => true,
                                'message' => "Your request for money has been received successfully. You will get money once this will be approved from staff."
                            )
                        ));
                    } else{

                        return json_encode(array(
                            'result' => array(
                                'response' => false,
                                'message' => COMMON_EROR
                            )
                        ));
                    }
                }
                else
                {
                    return json_encode(array(
                        'result' => array(
                            'response' => false,
                            'message' => "Insufficient balance."
                        )
                    ));
                }
            }
        }    
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "Fill all fields."
                )
            ));
        }
    }
    
    public function history($service_user_id=null) {
       
        $money_history = ServiceUserMoneyRequest::where('service_user_id',$service_user_id)->orderBy('id','desc')->get()->toArray();

        if(!empty($money_history))
        {
            foreach ($money_history as $key => $value) {
                 if($value['status']==0) {
                    $status = 'Pending';
                }elseif($value['status']==1) {
                    $status = 'Rejected';
                }else {
                    $status = 'Accepted';
                }
                $created_date = date('d/m/Y g:i A', strtotime($value['created_at']));
                $money_history[$key]['status'] = $status;
                $money_history[$key]['created_at'] = $created_date;
            }
            $money_history = $this->replace_null($money_history);

            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'data' => $money_history,
                    'message' => "Money Request history."
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "Money request history not found."
                )
            ));
        }
    }

    public function request_detail($money_request_id = null) {
       
        $money_request = ServiceUserMoneyRequest::select('su_money_request.id','su_money_request.amount','su_money_request.description','su_money_request.provider_comment','su_money_request.status','su_money_request.created_at')
                                            ->where('su_money_request.id',$money_request_id)
                                            ->leftJoin('su_money as sm','sm.su_money_request_id','su_money_request.id')
                                            ->first();
    
        if(!empty($money_request)){
            $money_request = $money_request->toArray();

            if($money_request['status']==0) {
                $status = 'Pending';
            }elseif($money_request['status']==1) {
                $status = 'Rejected';
            }else {
                $status = 'Accepted';
            }
            $created_date                = date('d/m/Y g:i A', strtotime($money_request['created_at']));
            $money_request['status']     = $status;
            $money_request['created_at'] = $created_date;
            $money_request = $this->replace_null($money_request);

            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'data'     => $money_request,
                    'message'  => "Money Request detail."
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message'  => "Money Request not found."
                )
            ));
        }
    }

    // public function history($service_user_id=null)
    // {
    //     $money_history = ServiceUserMoneyRequest::where('service_user_id',$service_user_id)->orderBy('id','desc')->get()->toArray();
    //     if(!empty($money_history))
    //     {
    //         return json_encode(array(
    //             'result' => array(
    //                 'response' => true,
    //                 'data' => $money_history,
    //                 'message' => "Money Request history."
    //             )
    //         ));
    //     }
    //     else
    //     {
    //         return json_encode(array(
    //             'result' => array(
    //                 'response' => false,
    //                 'message' => "Data not found."
    //             )
    //         ));
    //     }
    // }
    
}