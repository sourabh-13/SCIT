<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use illuminate\Http\Request;
use App\ServiceUserMoney, App\Home, App\ServiceUserLocationHistory, App\ServiceUser, App\WeeklyAllowance,App\UserDevice, App\Policies, App\User, App\DynamicFormBuilder,App\AdminCardDetail, App\CompanyCharges, App\CompanyPaymentInformation, App\CompanyPayment;
use Illuminate\Support\Facades\Mail;
use DB;
use Carbon\Carbon;
    
class CronController extends Controller
{   
    public function every_day(){ 
        //$this->delete_location_history();
        $this->remind_staff_to_accept();
        $this->remind_staff_for_dynamic_form();
        $this->recurring_home_package_fee();
    }

    public function every_week(){
        $this->add_weekly_allowance();
    }

    public function every_minute(){
        //now not required
        //$this->_send_location_notification();
    }
    
    function add_weekly_allowance(){ //assign weekly allowance money to every user of the home

        $homes = Home::select('home.id','home.weekly_allowance_service_users')
                    ->join('admin','admin.id','home.admin_id')
                    ->where('home.is_deleted',0)
                    ->where('admin.is_deleted',0)
                    ->get()
                    ->toArray();
        //echo '<pre>'; print_r($homes); die;
        foreach($homes as $home) {

            $allowance = WeeklyAllowance::select('weekly_allowance.service_user_id','weekly_allowance.amount')
                            ->join('service_user as su','su.id','=','weekly_allowance.service_user_id')
                            ->where('su.home_id',$home['id'])
                            ->where('weekly_allowance.status','A')
                            ->get()
                            ->toArray();
            //echo '<pre>'; print_r($allowance); die;

            foreach ($allowance as $key => $value) {

                $balance = ServiceUserMoney::where('service_user_id',$value['service_user_id'])
                                    ->orderBy('id','desc')
                                    ->value('balance');

                $outstanding_balance = ServiceUserMoney::where('service_user_id',$value['service_user_id'])
                                        ->orderBy('id','desc')
                                        ->value('outstanding_balance');


                if($balance == ""){
                    $balance = 0;
                }

                $new_balance                = $value['amount'] + $balance;
                
                $su_money                   = new ServiceUserMoney;
                $su_money->service_user_id  = $value['service_user_id'];
                //$su_money->user_id          = 10;
                $su_money->description      = 'Weekly Allowance';
                $su_money->txn_type         = 'D';
                $su_money->txn_amount       = $value['amount'];

                if ($new_balance <= floatval(-0.01)) {
                        
                        $outstanding_balance           = $new_balance - $outstanding_balance;
                        $su_money->outstanding_balance = abs($outstanding_balance);
                        $su_money->balance             = 0;

                    } else if ($new_balance == 0) {

                        $outstanding_balance           =  abs($outstanding_balance);
                        $su_money->outstanding_balance = 0;
                        $su_money->balance             = 0;
                    } else {

                       $outstanding_balance           =  abs($outstanding_balance);
                       $su_money->balance             =  $new_balance - $outstanding_balance;
                       $su_money->outstanding_balance = 0; 
                    }

                    if (!empty($outstanding_balance)) {
                        if ($outstanding_balance > 0) {
                            if ($outstanding_balance > $new_balance) {

                                $outstanding_balance           =  abs($outstanding_balance);
                                $su_money->outstanding_debit   =  $value['amount'];
                                $outstanding_balance           =  $outstanding_balance - $new_balance;
                                $su_money->outstanding_balance =  $outstanding_balance;
                                $su_money->balance             =  0;
                            // echo "<pre>";print_r($outstanding_debit);die;
                            } else {

                                $outstanding_balance           =  abs($outstanding_balance);
                                $su_money->outstanding_debit   =  $outstanding_balance;
                                $su_money->balance             =  $new_balance - $outstanding_balance;
                            }
                        }
                    }
                // $su_money->balance          = $new_balance;
                $su_money->save();   
            }
        }
    }

    function remind_staff_to_accept(){

        //get all homes
        $homes = Home::select('home.id')
                    ->join('admin','admin.id','home.admin_id')
                    ->where('home.is_deleted',0)
                    ->where('admin.is_deleted',0)
                    ->get()
                    ->toArray();
        //echo '<pre>'; print_r($homes); die;
        foreach($homes as $home) {

            //get users of a home
            $home_id = $home['id'];
            $users   = User::getStaffList($home_id);
            //echo '<pre>'; print_r($users); die;
            foreach ($users as $key => $user) {
                
                $user_id = $user['id'];
                $before_7day = date('Y-m-d 00:00:00', strtotime('-7 days'));

                $policies = Policies::select('polices.id','polices.file','polices.updated_at','user_accepted_policy.id as user_accepted_id')
                                    ->where('polices.home_id',$home_id)
                                    ->leftJoin('user_accepted_policy', function($join) use ($user_id)
                                        {
                                            $join->on('user_accepted_policy.policy_id','=','polices.id');
                                            $join->on('user_accepted_policy.user_id','=',DB::raw("'$user_id'"));
                                            $join->on('user_accepted_policy.created_at','>','polices.updated_at');
                                        })
                                    ->where('user_accepted_policy.id',null) 
                                    ->where('polices.updated_at','<',$before_7day) 
                                    ->orderBy('polices.id','asc')
                                    ->get()
                                    ->toArray();

                if(!empty($policies)) {
                    //echo '<pre>'; print_r($policies); die;
                    $email        = $user['email'];
                    $name         = $user['name'];
                    $user_name    = $user['user_name'];
                    $company_name = PROJECT_NAME;

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) 
                    {   
                        Mail::send('emails.policy.accept_policy_reminder', ['name'=>$name, 'user_name'=>$user_name], function($message) use ($email,$company_name)
                        {
                            $message->to($email,$company_name)->subject('SCITS Policy reminder');
                        });                
                    } 
                }
                /*// strategy
                1. get all homes
                2. foreach homes -> get users of a home
                3. foreach users -> get policies pending for approval
                4. send notifications to user*/
            }
        }
    }

    function delete_location_history(){ 

        $homes = Home::select('id','location_history_duration')->get()->toArray();
        $loc_history = ServiceUserLocationHistory::get()->toArray();
        $loc_duration = 0;

        foreach($loc_history as $value){
            //echo '<pre>'; print_r($value);
            $loc_duration_info = ServiceUser:://select('service_user.home_id','home.location_history_duration')
                                    where('service_user.id', $value['service_user_id'])
                                    ->join('home','home.id','service_user.home_id')
                                    ->first();

            if(!empty($loc_duration_info)){
                $loc_duration = $loc_duration_info->location_history_duration;

            } 
            
            $last_date = date('Y-m-d',strtotime('-'.$loc_duration.' days'));

            $loc_date = strrpos($value['timestamp'],0,9);

            if($loc_date < $last_date){ 
                ServiceUserLocationHistory::destroy($value['id']);
            }             
        }
    }

    function remind_staff_for_dynamic_form() { //alert email to every user for filling dynamic form of the home

        //get all homes
        $homes = Home::select('home.id','home.title')
                    ->join('admin','admin.id','home.admin_id')
                    ->where('home.is_deleted',0)
                    ->where('admin.is_deleted',0)
                    ->get()
                    ->toArray();
        // echo '<pre>'; print_r($homes); die;
        $today = date('Y-m-d');
        // echo $today; die;
        foreach ($homes as $key => $home) {
            
             //get users of a home
            $home_id = $home['id'];

            // $users   = User::getStaffList($home_id);
            $d_forms   = DynamicFormBuilder::getReminderDayFormList($home_id);
            // echo '<pre>'; print_r($d_forms); die;            
            foreach ($d_forms as $key => $d_form) {
                
                $form_id      = $d_form['id'];
                $form_title   = $d_form['title'];
                $reminder_day = $d_form['reminder_day'];  //(int)

                $alert_notification_date = date('Y-m-d', strtotime('+'.$reminder_day.' days'));
                // echo $alert_notification_date.'<br>';
                if($today == $alert_notification_date) {    
                    
                    $users = User::getStaffList($home_id);
                    // echo "<pre>"; print_r($users);
                    // echo $users.'<br>';

                    foreach ($users as $key => $user) {
                        
                        $email        = $user['email'];
                        $name         = $user['name'];
                        $user_name    = $user['user_name'];
                        $company_name = PROJECT_NAME;

                        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) 
                        {   
                            Mail::send('emails.form_reminder', ['name'=>$name, 'user_name'=>$user_name,'form_title'=>$form_title], function($message) use ($email,$company_name)
                            {
                                $message->to($email,$company_name)->subject('SCITS Form reminder');
                            });                
                        }

                    }

                }
            }
        }
    }

    public function recurring_home_package_fee(){ //deduct charges from customer account
        
        $username   = env('EMAIL');
        $password   = env('PASSWORD');
        $signature  = env('SIGNATURE');

        $admin_card_details = AdminCardDetail::select('admin_card_detail.*','cp.expiry_date','cp.package_duration','cp.company_charges_id')
                                            ->join('company_payment as cp','cp.admin_id','admin_card_detail.admin_id')
                                            ->get()->toArray();
        // echo "<pre>"; print_r($admin_card_details); die;
       


        
        //echo '<pre>'; print_r($admin_card_details); die; 
        /*$api_username    = 'promatics.hashishgarg-facilitator_api1.gmail.com';
        $api_password    = '4DMJ26SNXMD6RVCL';
        $api_signature   = 'Ao68DNqlX5gVaPZlGVYk.BmBnaqJAKRQa8wtH5yrtwKwLKkCuupfUpHQ';*/
        if(!empty($admin_card_details)){
            foreach($admin_card_details as $admin) {
                $expdate = explode('/', $admin['mm_yy']);
                $month  = $expdate['0'];
                $year   = '20'.$expdate['1'];

                $pkg_dtl = CompanyCharges::where('id',$admin['company_charges_id'])
                                        ->first();
                if(!empty($pkg_dtl)){
                    $current_date = date('Y-m-d H:i:s');

                    if($admin['package_duration'] == 'M'){
                        $amount = $pkg_dtl->price_monthly;
                        $exp_dte = Carbon::now()->addMonths(1);
                    }else{
                        $amount = $pkg_dtl->price_yearly;
                        $exp_dte = Carbon::now()->addMonths(12);
                    }
                    
                    
                    if($current_date == $admin['expiry_date']){
                        // echo "1"; die;
                        $payment_params = [ 
                            "s" => '-s',
                            "insecure"  =>'--insecure',
                            "VERSION"   =>'56.0',
                            // "SIGNATURE" => 'Ao68DNqlX5gVaPZlGVYk.BmBnaqJAKRQa8wtH5yrtwKwLKkCuupfUpHQ',
                            "SIGNATURE" =>$signature,
                            // "USER"  =>'promatics.hashishgarg-facilitator_api1.gmail.com',
                            "USER"  =>$username,
                            // "PWD"   =>'4DMJ26SNXMD6RVCL',
                            "PWD"   =>$password,
                            "METHOD"=>'DoDirectPayment',
                            "PAYMENTACTION"=> 'Sale',
                            "IPADDRESS" =>'192.168.1.12',
                            // "AMT" =>'8.88',
                            "AMT" =>$amount,
                            // "ACCT" =>'4242424242424242',
                            "ACCT" =>$admin['card_number'],
                            // "EXPDATE" =>'012022',
                            "EXPDATE" =>$month.''.$year,
                            // "CVV2" =>'123',
                            "CVV2" =>$admin['cvv'],
                            // "FIRSTNAME" =>'John',
                            // "FIRSTNAME" =>$admin['first_name'],
                            // "LASTNAME" =>'Smith',
                            // "LASTNAME" =>$admin['last_name'],
                            // "STREET" =>'1 Main St.',
                            // "STREET" =>$admin['street'],
                            // "CITY" =>'San Jose',
                            // "CITY" =>$admin['city_name'],
                            // "STATE" =>'CA',
                            // "STATE" =>$admin['state_code'],
                            // "ZIP" =>'95131',
                            // "ZIP" =>$admin['zip_code'],
                            "COUNTRYCODE" =>'GBP'
                        ];
                        // echo "<pre>"; print_r($payment_params); die;
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp/');
                        // curl_setopt($curl, CURLOPT_URL, 'http://www.trip.ae/flights/meta/landing');
                        // curl_setopt($curl, CURLOPT_USERPWD, CHECKR_API_KEY . ":");
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_POST, false);
                        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payment_params));
                        $httpResponse = curl_exec($curl);
                        $test = array_values(explode("&",$httpResponse));
                        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                        curl_close($curl);
                        //echo "status:" . $http_status . "\n" . $httpResponse; die;
                        $response = json_decode($httpResponse);
                        //echo "<pre>"; print_r($response); die;  

                        if(!empty($test[0])){
                            // echo "<pre>"; print_r($test); die;    
                            $test_re                            = explode("=", $test[2]);
                            $paid_amount                        = explode("=",@$test[5]);
                            $sender_transaction_id              = explode("=",@$test[9]);
                            
                            
                            $data['paid_amount']                = @$paid_amount[1];
                            $data['receiver_email']             = @$receiver_email[1];
                            $data['sender_transaction_id']      = @$sender_transaction_id[1];

                            if($test_re[1]== "Success"){
                    
                                $paymentInfo                            = new CompanyPaymentInformation;
                                $paymentInfo->admin_id                  = $admin['admin_id'];
                                $paymentInfo->paid_amount               = $data['paid_amount'];
                                $paymentInfo->sender_transaction_id     = $data['sender_transaction_id'];
                                $paymentInfo->company_charges_id        = $admin['company_charges_id'];
                                $paymentInfo->expiry_date               = $exp_dte;
                                if($paymentInfo->save()){
                                    //update status
                                    $company_payment_dtl = CompanyPayment::where('admin_id',$admin['admin_id'])
                                                                            ->first();

                                    $package_info = CompanyCharges::where('id',$admin['company_charges_id'])
                                                                    ->first();

                                    if(!empty($company_payment_dtl)){
                                        if(!empty($package_info)){
                                            
                                            
                                            
                                            $company_payment_dtl->company_charges_id    = $package_info->id;
                                            $company_payment_dtl->package_duration      = $admin['package_duration'];
                                            $company_payment_dtl->expiry_date           = $exp_dte;
                                            $company_payment_dtl->status                = '1';
                                            $company_payment_dtl->pay_key               = '';
                                            $company_payment_dtl->save();
                                        }
                                        
                                    }else{
                                        if(!empty($package_info)){
                                            $company_payment                        = new CompanyPayment;
                                            $company_payment->admin_id              = $admin['admin_id'];
                                            $company_payment->company_charges_id    = $package_info->id;
                                            $company_payment->package_duration      =  $admin['package_duration'];
                                            $company_payment->homes_added           = '0';
                                            $company_payment->expiry_date           = $exp_dte;
                                            $company_payment->status                = '1';
                                            $company_payment->pay_key               = '';
                                            $company_payment->save();
                                        }
                                    }                                   

                                } 
                            } 



                            // if($test_re[1]== "Success"){
                                
                            //     $paymentInfo                             = new CompanyPaymentInformation;
                            //     $paymentInfo->company_payment_id         = $package_info->id;
                            //     $paymentInfo->paid_amount                = $data['paid_amount'];
                            //     $paymentInfo->receiver_email             = $data['receiver_email'];
                            //     $paymentInfo->sender_transaction_id      = $data['sender_transaction_id'];
                            //     $paymentInfo->sender_email               = $data['sender_email'];
                            //     $paymentInfo->final_pay_key              = $data['final_pay_key'];
                            //     $paymentInfo->sender_account_id          = $data['sender_account_id'];
                                
                            //     if($paymentInfo->save()){
                            //         //update status
                            //         $update_company_payment = CompanyPayment::where('admin_id',$system_admin_id)
                            //                                                 ->update(['status'=>'1']);
                            //         if($update_company_payment){
                            //             return redirect('admin/system-admin/homes/add/'.$system_admin_id)->with('success','Your payment paid successfully.');
                            //         }else{
                            //             return redirect()->back()->with('error',COMMON_ERROR);
                            //         }
                            //     }else{
                            //         return redirect()->back()->with('error',COMMON_ERROR);
                            //     }
                            // }else{
                            //     return redirect()->back()->with('error',COMMON_ERROR);
                            // }


                        }
                    }
                    
                }
            } 
        }       
    }
}