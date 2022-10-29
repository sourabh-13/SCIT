<?php 
namespace App\Http\Controllers\Api\ServiceUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, DB, Hash;
use App\CareTeam;

class CareTeamController extends Controller
{
    public function care_team($id=null)
    {
        $care_team = CareTeam::with([
            'care_job_title'=>function($query){$query->select('id','title');}
            ])->where('service_user_id',$id)->where('is_deleted',0)->select('id','job_title_id','name','image')->get()->toArray();
            
        if(!empty($care_team))
        {
            return json_encode(array(
                'result' => array(
                    'response'=> true,
                    'data' => $care_team,
                    'message' => "Care team list.",
                    'image_url' => careTeam
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response'=> false,
                    'message' => "No Care team found."
                )
            ));
        }
    }
    
    public function care_team_view($id=null)
    {
        $care_team = CareTeam::with([
            'care_job_title'=>function($query){$query->select('id','title');}
            ])->where('id',$id)->where('is_deleted',0)->first();
            
        if(!empty($care_team)){
            $care_team = $care_team->toArray();
        }
       
        if(!empty($care_team))
        {
            return json_encode(array(
                'result' => array(
                    'response'=> true,
                    'message' => "Care team member detail.",
                    'data' => $care_team,
                    'image_url' => careTeam
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response'=> false,
                    'message' => "No care team found."
                )
            ));
        }
    }
}