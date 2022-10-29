<?php 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HomeLabel, App\ServiceUser, App\ServiceUserEarningStar;
use Auth, DB, Hash;
use DateTime, Carbon\Carbon;


class LabelController extends Controller
{
    public function label($su_id = null) {
        $home_id = ServiceUser::where('id',$su_id)->value('home_id');
        // $labels = HomeLabel::getLabelsName($home_id);
        $labels = HomeLabel::getLabels($home_id);
        $total_stars = ServiceUserEarningStar::where('service_user_id',$su_id)->value('star');
        $total_stars = (int)$total_stars;

        $location_get_interval = ServiceUser::getLocationInterval($su_id);
        if($labels == null){
            $lebels = '';
        }
        return json_encode(array(
            'result' => array(
                'response' => true,
                'message' => "Labels",
                'data' => $labels,
                'stars' => $total_stars,
                'location_get_interval' => $location_get_interval
            )
        ));
    }
}