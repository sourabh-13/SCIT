<?php 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HomeCategory, App\ServiceUser, App\ServiceUserEarningStar;
use Auth, DB, Hash;
use DateTime, Carbon\Carbon;


class CategoryController extends Controller
{
    public function category($su_id = null) {
        $home_id = ServiceUser::where('id',$su_id)->value('home_id');
        // $categorys = HomeCategory::getCategorysName($home_id);
        $categorys = HomeCategory::getCategorys($home_id);
        $total_stars = ServiceUserEarningStar::where('service_user_id',$su_id)->value('star');
        $total_stars = (int)$total_stars;

        $location_get_interval = ServiceUser::getLocationInterval($su_id);
        if($categorys == null){
            $lebels = '';
        }
        return json_encode(array(
            'result' => array(
                'response' => true,
                'message' => "Categorys",
                'data' => $categorys,
                'stars' => $total_stars,
                'location_get_interval' => $location_get_interval
            )
        ));
    }
}