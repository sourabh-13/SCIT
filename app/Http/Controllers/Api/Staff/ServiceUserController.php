<?php 
namespace App\Http\Controllers\Api\Staff;
use App\Http\Controllers\frontEnd\StaffManagementController;

use Illuminate\Http\Request;
use App\User, App\ServiceUser, App\ServiceUserEarningStar;
use DateTime, Carbon\Carbon;
class ServiceUserController extends StaffManagementController
{
    public function listing_service_user($staff_id)
    {
        $staff_home_id = User::where('id',$staff_id)->value('home_id');
        $listing_service_users = ServiceUser::select('id','name','date_of_birth','admission_number','section','image')->where('home_id',$staff_home_id)->get();
        $listing_service_users = json_decode(json_encode($listing_service_users),true);

        foreach($listing_service_users as $key => $listing)
        {
            $age = $listing['date_of_birth'];
            $listing_service_users[$key]['age'] = Carbon::parse($age)->diff(Carbon::now())->format('%y years');

            $total_stars = ServiceUserEarningStar::where('service_user_id',$listing['id'])->value('star');
            $listing_service_users[$key]['earning_stars'] = (int)$total_stars;

        }
        if(!empty($listing_service_users)) {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'image_url' => serviceUserProfileImagePath,
                    'data' => $listing_service_users,
                    'message' => "Listing of Service Users."
                )
            ));
        } else {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "Data not found."
                )
            ));
        }    
    }
}