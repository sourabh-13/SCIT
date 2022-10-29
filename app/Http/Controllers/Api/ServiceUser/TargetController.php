<?php 
namespace App\Http\Controllers\Api\ServiceUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class TargetController extends Controller
{
    
    public function index($service_user_id) { //my targets
        $today = date('Y-m-d');
        
        $targets['completed'] = DB::table('su_placement_plan')
                                ->where('service_user_id',$service_user_id)
                                ->where('status','1')
                                ->get();

        $targets['active'] = DB::table('su_placement_plan')
                                ->where('service_user_id',$service_user_id)
                                ->whereDate('date', '>=', $today)
                                ->orderBy('date', 'asc')
                                ->where('status','0')
                                ->get();
        
        $targets['not_completed'] = DB::table('su_placement_plan')
                                ->where('service_user_id',$service_user_id)
                                ->whereDate('date', '<=', $today)
                                ->where('status','0')
                                ->get();
        
        //date format change                 
        foreach ($targets as $key => $target) {
            foreach ($target as $tar) {
                $tar->date = date('d/m/Y',strtotime($tar->date));
               // $new[] = $tar;
            }
        }
                                
        $targets = json_decode(json_encode($targets),true);
     // if(!empty($targets))
    //   if(!empty($targets['completed']) && !empty($targets['active']) && !empty($targets['not_completed']))
       // if( (!empty($targets['completed'])) && (!empty($targets['active'])) && (!empty($targets['not_completed'])) )
        if( (empty($targets['completed'])) && (empty($targets['active'])) && (empty($targets['not_completed'])) )
        {
            return json_encode(array(
                'result' =>array(
                    'response' => false,
                    'message' => 'No targets found.',
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' =>array(
                    'response' => true,
                    'message' => "List of targets.",
                    'data' => $targets
                )
            ));
        }
    }

}

?>