<?php 
namespace App\Http\Controllers\Api\ServiceUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ServiceUserEarningIncentive, App\Calendar, App\ServiceUserIncentiveSuspend, App\EarningScheme, App\Incentive, App\ServiceUser, App\ServiceUserEarningStar, App\Notification;
use Auth, DB, Hash;

class EarningSchemeController extends Controller
{   
    public function categories($service_user_id = null)
    {
        $service_user = ServiceUser::where('id',$service_user_id)->first();
        if(!empty($service_user)){
            $home_id = $service_user->home_id;

            $earn_cats = EarningScheme::select('earning_scheme_category.id','earning_scheme_category.title','earning_scheme_category.icon','ffi.icon_code')
                                    ->leftJoin('fa_fa_icon as ffi','ffi.icon_class','like','earning_scheme_category.icon')
                                    ->where('home_id',$home_id)
                                    ->where('status','1')
                                    ->where('is_deleted','0')
                                    ->get()
                                    ->toArray();
            //echo '<pre>'; print_r($earn_categories); die;

            foreach ($earn_cats as $key => $value) {
                $earn_cats[$key]['icon']            = $this->app_icon_format($value['icon']);
                $earn_cats[$key]['incentive_count'] = EarningScheme::incentivesCount($value['id']);
            }
        }

        if(!empty($earn_cats))
        {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "Earning Scheme Categories with count.",
                    'data' => $earn_cats
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "No earning scheme categories found.",
                    //'data' => $earning_categories
                )
            ));
        }
    }
    
    public function earning_incentives($earning_scheme_id)
    {
        $data = DB::table('earning_scheme_category')
            ->join('incentive','earning_scheme_category.id','=','incentive.earning_category_id')
            ->where('earning_scheme_category.id',$earning_scheme_id)
            ->select('incentive.id','incentive.name','incentive.stars','incentive.details','incentive.url')
            ->get();
            
        $earning_scheme_details = json_decode(json_encode($data),true);
        $earning_scheme_details = $this->replace_null($earning_scheme_details);
        if(!empty($earning_scheme_details))
        {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "Earning details.",
                    'data' => $earning_scheme_details
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "Earning scheme detail not found.",
                )
            ));
        }
    }
    
    public function user_incentives($service_user_id)
    {
       /* $calendar_incentive   = ServiceUserEarningIncentive::select('su_earning_incentive.id as su_ern_inc_id', 'su_earning_incentive.star_cost','incentive.name','incentive.earning_category_id','su_earning_incentive.time')
                                    ->join('incentive','incentive.id','su_earning_incentive.incentive_id')
                                    ->where('su_earning_incentive.service_user_id', $service_user_id)
                                    ->orderBy('su_earning_incentive.id','desc')
                                    ->get()
                                    ->toArray();

        foreach ($calendar_incentive as $key => $su_incentive) {
            
            //check if this incentive is booked in calendar
            $event_id   = $su_incentive['su_ern_inc_id'];
            $event_type = 'I';

            $booking_response    = Calendar::checkEventBooking($service_user_id,$event_id,$event_type);
            $calendar_incentive[$key] = array_merge($calendar_incentive[$key],$booking_response);

            //checking is the incentive is suspend 
            // if incentive will be suspend then its id will be present in ServiceUserIncentiveSuspend table
            $inc_suspend_id = ServiceUserIncentiveSuspend::where('su_earning_incentive_id',$event_id)
                                ->where('is_cancelled','0')   //not cancelled
                                ->orderBy('id','desc')
                                ->value('id');
            
            if(!empty($inc_suspend_id)) {
                $calendar_incentive[$key]['suspended_id'] = $inc_suspend_id;
            } else{
                $calendar_incentive[$key]['suspended_id'] = '';
            }
        }*/
        
        $booked_incentives = EarningScheme::bookedIncentives($service_user_id);
        foreach ($booked_incentives as $key => $value) {
            if(!empty($value['event_date'])){
                $booked_incentives[$key]['event_date'] = date('d M Y',strtotime($value['event_date']));
            }
        }
        
        $booked_incentives = $this->replace_null($booked_incentives);
        if(!empty($booked_incentives))
        {
            return json_encode(array(
                'result' =>array(
                    'response' =>true,
                    'data' => $booked_incentives,
                    //'stars' => $total_stars,
                    'message' => "Incentive details."
                )    
            ));
        }
        else
        {
            return json_encode(array(
                'result' =>array(
                    'response' =>false,
                    'message' => "Incentive details not found."
                )    
            ));
        }
    }
    
    public function earning_history($su_id=null)
    {
        //$home_id = DB::table('service_user')->where('id',$su_id)->value('home_id');
        $data = EarningScheme::earningHistory($su_id);
        if(!empty($data))
        {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'data' => $data,
                    'message' => "Earning history."
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "No earning history found."
                )
            ));
        }
    }
    
    public function add_to_calendar(Request $request) 
    {

        $data = $request->input();
        if(!empty($data['su_id']) && !empty($data['incentive_id']))
        {
            
            $su_home_id = ServiceUser::where('id',$data['su_id'])->value('home_id');
            /*if($su_home_id != Auth::user()->home_id){
                return redirect()->back()->with("error",UNAUTHORIZE_ERR);
            }*/
    
            $incentive = Incentive::find($data['incentive_id']);
            
            if(!empty($incentive)) {
    
                $su_star_info = ServiceUserEarningStar::where('service_user_id',$data['su_id'])->first();
                if(!empty($su_star_info)){
                    $availble_star = $su_star_info->star;
                } 
                else
                {
                    return json_encode(array(
                        'result' => array(
                            'response' => false,
                            'message' => "You don't have enough stars to avail this incentive."
                        )
                    ));
                }
               
                if($availble_star >= $incentive->stars){    
    
                    $clndr_incentive                  = new ServiceUserEarningIncentive;
                    $clndr_incentive->service_user_id = $data['su_id'];
                    $clndr_incentive->incentive_id    = $incentive->id;
                    $clndr_incentive->star_cost       = $incentive->stars;
                    $clndr_incentive->detail          = '';
                    if($clndr_incentive->save()){
    
                        $su_remaining_star = $availble_star - $clndr_incentive->star_cost;
                        $su_star_info->star= $su_remaining_star;
                        $su_star_info->save();
                        
                        
                        
                        //saving notification start
                        $notification                  = new Notification;
                        $notification->service_user_id            = $data['su_id'];
                        $notification->event_id                   = $clndr_incentive->id;
                        $notification->notification_event_type_id = 3;
                        $notification->event_action               = 'SPEND_STAR';     
                        $notification->home_id                    = $su_home_id;
                        //$notification->user_id                  = $data['su_id'];                 
                        $notification->save();
                        //saving notification end
                        return json_encode(array(
                            'result' => array(
                                'response' => true,
                                'message' => 'Incentive added successfully.'
                            )
                        ));
                    }
                    else
                    {
                        return json_encode(array(
                            'result' => array(
                                'response' => false,
                                'message' => COMMON_ERROR
                            )
                        ));
                    }
    
                } 
                else
                {
                    return json_encode(array(
                        'result' => array(
                            'response' => false,
                            'message' => "You don't have enough stars to avail this incentive."
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
    
}