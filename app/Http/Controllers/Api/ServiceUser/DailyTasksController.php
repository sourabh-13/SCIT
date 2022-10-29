<?php 
namespace App\Http\Controllers\Api\ServiceUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth, DB, Hash;
use DateTime, Carbon\Carbon;
use App\User, App\ServiceUserDailyRecord, App\ServiceUserEducationRecord, App\ServiceUserLivingSkill;

class DailyTasksController extends Controller
{
                /*Daily Tasks Full List*/
    
    public function daily_tasks($service_user_id)
    {
        $today = date('Y-m-d');
        
        $daily_record = ServiceUserDailyRecord:://with(['daily_record' =>function($query){ $query->where('is_deleted',0)->select(['id','description']); }])
                                with('daily_record')
                                ->has('daily_record')
                                ->where('service_user_id',$service_user_id)
                                ->where('is_deleted',0)
                                ->select('id','daily_record_id','scored','details','created_at')
                                ->orderBy('created_at','desc')
                                ->get();
                                
        $daily_record = json_decode(json_encode($daily_record),true);
        //echo '<prE>'; print_r($daily_record); die;
        $daily_records = array();
        
        if(!empty($daily_record)){
  
            $daily_record = $this->replace_null($daily_record);
            
            $pre_date = date('Y-m-d',strtotime($daily_record[0]['created_at']));
            $i = 0;
            
            foreach($daily_record as $key => $daily)
            {
                $current_date = date('Y-m-d',strtotime($daily['created_at']));
                if($pre_date == $current_date)
                {   
                    $daily_records[$i]['date']      = date('d F Y',strtotime($daily['created_at']));
                    $daily_records[$i]['records'][] = $daily;
     
                } else{
                    $i++;
                    $daily_records[$i]['date']      = date('d F Y',strtotime($daily['created_at']));
                    $daily_records[$i]['records'][] = $daily;
                    $pre_date                       = $current_date;

                }
            }
        }

        if(!empty($daily_records))
        {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "Daily tasks list.",
                    'data' => $daily_records
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "No daily task found.",
                    //'data' => $daily_records
                )
            ));
        }
    }
    
    public function living_skill($service_user_id)
    {
        $today = date('Y-m-d');
        $living_record = ServiceUserLivingSkill::/*with(
            ['living_skill' => function($query){$query->where('is_deleted',0)->select('id','description');}])->has('living_skill')*/
            with('living_skill')
            ->has('living_skill')
            ->where('service_user_id',$service_user_id)
            ->where('is_deleted',0)
            ->select('id','living_skill_id','scored','details','created_at')
            ->orderBy('created_at','desc')->get();
        
        $living_record = json_decode(json_encode($living_record),true);
        
        $living_records = array();
        if(!empty($living_record)) {

            $living_record = $this->replace_null($living_record);
            $pre_date = date('Y-m-d',strtotime($living_record[0]['created_at']));
            
            $i = 0;
                        
            foreach($living_record as $key => $daily)
            {
                $current_date = date('Y-m-d',strtotime($daily['created_at']));
                if($pre_date == $current_date)
                {   
                    $living_records[$i]['date']      = date('d F Y',strtotime($daily['created_at']));
                    $living_records[$i]['records'][] = $daily;
     
                } else{
                    $i++;
                    $living_records[$i]['date']      = date('d F Y',strtotime($daily['created_at']));
                    $living_records[$i]['records'][] = $daily;
                    $pre_date                       = $current_date;

                }
            }
        }

        if(!empty($living_records))
        {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "living skill list.",
                    'data' => $living_records
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "No living skill found.",
                )
            ));
        }
        
    }
    
    public function education_records($service_user_id)
    {
        $today = date('Y-m-d');
        $education_record = ServiceUserEducationRecord::/*with(
                                ['education_record' => function($query){$query->where('is_deleted',0)->select('id','description');}])*/
                                with('education_record')
                                ->has('education_record')
                                ->where('service_user_id',$service_user_id)
                                ->where('is_deleted',0)
                                ->select('id','education_record_id','scored','details','created_at')
                                ->orderBy('created_at','desc')
                                ->get()
                                ->toArray();
        
        foreach($education_record as $key => $value){
            if(empty($value['education_record'])) {
                unset($education_record[$key]);
            }
        }
        $education_record = array_values($education_record);

        $education_records = array();            
        
        if(!empty($education_record)){
    
            //echo '<pre>'; print_r($education_record); die;
            //$education_record = json_decode(json_encode($education_record),true);
            $education_record = $this->replace_null($education_record);
            $pre_date = date('Y-m-d',strtotime($education_record[0]['created_at']));
            $i = 0;
            
            foreach($education_record as $key => $daily)
            {
                $current_date = date('Y-m-d',strtotime($daily['created_at']));
                if($pre_date == $current_date)
                {   
                    $education_records[$i]['date']      = date('d F Y',strtotime($daily['created_at']));
                    $education_records[$i]['records'][] = $daily;
     
                } else{
                    $i++;
                    $education_records[$i]['date']      = date('d F Y',strtotime($daily['created_at']));
                    $education_records[$i]['records'][] = $daily;
                    $pre_date                       = $current_date;

                }
            }
        }

        if(!empty($education_records)) {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "education record list.",
                    'data' => $education_records
                )
            ));
        } else {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "No education record found.",
                )
            ));
        }
        
    }
    
    /*Daily Updated List*/
    
    public function earning_daily_tasks($service_user_id)
    {
        $today = date('Y-m-d');
        
        $daily_record = ServiceUserDailyRecord::/*with(['daily_record' =>function($query){ $query->where('is_deleted',0)->select(['id','description']); }])*/
            with('daily_record')
            ->has('daily_record')
            ->where('service_user_id',$service_user_id)
            ->where('is_deleted',0)->where('created_at','like',$today.'%')
            ->select('id','daily_record_id','scored','details','created_at')
            ->orderBy('created_at','desc')
            ->get();

        $daily_record = json_decode(json_encode($daily_record),true);
        if(!empty($daily_record))
        {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "Daily task list.",
                    'data' => $daily_record
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "No daily task found.",
                )
            ));
        }
    }
    
    public function earning_living_skill($service_user_id)
    {
        $today = date('Y-m-d');
        $living_record = ServiceUserLivingSkill::/*with(
            ['living_skill' => function($query){$query->where('is_deleted',0)->select('id','description');}])->has('living_skill')*/
            with('living_skill')
            ->has('living_skill')
            ->where('service_user_id',$service_user_id)->where('is_deleted',0)->where('created_at','like',$today.'%')
            ->select('id','living_skill_id','scored','details','created_at')
            ->orderBy('created_at','desc')->get();
        $earning_living_record = json_decode(json_encode($living_record),true);
        if(!empty($earning_living_record))
        {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "living skill list.",
                    'data' => $earning_living_record
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "No living skill found.",
                )
            ));
        }
    }
    
    public function earning_education_records($service_user_id)
    {
        $today = date('Y-m-d');
        $education_record = ServiceUserEducationRecord::/*with(
            ['education_record' => function($query){$query->where('is_deleted',0)->select('id','description');}])->has('education_record')*/
            with('education_record')
            ->has('education_record')
            ->where('service_user_id',$service_user_id)->where('is_deleted',0)->where('created_at','like',$today.'%')
            ->select('id','education_record_id','scored','details','created_at')
            ->orderBy('created_at','desc')->get();
        $education_record = json_decode(json_encode($education_record),true);
        if(!empty($education_record))
        {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "Education record list.",
                    'data' => $education_record
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "No education record found.",
                )
            ));
        }
        
    }
    
}