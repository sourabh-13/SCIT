<?php
namespace App\Http\Controllers\Api\Staff;
use App\Http\Controllers\Api\StaffManagementController;
use Illuminate\Http\Request;
use App\StaffTaskAllocation;

class TaskAllocationController extends StaffManagementController
{

    public function index($staff_member_id) //mk
    {
        $today = date('Y-m-d');
        
        $tasks = StaffTaskAllocation::select('id','title','details','created_at','updated_at')
                                        ->where('is_deleted','0')
                                        ->where('staff_member_id', $staff_member_id)
                                        ->orderBy('staff_task_allocation.id','desc')
                                        ->orderBy('staff_task_allocation.created_at','desc')
                                        ->get();

        $tasks = json_decode(json_encode($tasks),true);
        //echo '<pre>'; print_r($tasks); die;

        $daily_tasks = array();
        if(!empty($tasks)) {
            $tasks = $this->replace_null($tasks);
            $pre_date = date('Y-m-d',strtotime($tasks[0]['created_at']));
            $i = 0;
            
            foreach($tasks as $key => $value)
            {
                $current_date = date('Y-m-d',strtotime($value['created_at']));
                if($pre_date == $current_date)
                {   
                    $daily_tasks[$i]['date']      = date('d F Y',strtotime($value['created_at']));
                    $daily_tasks[$i]['records'][] = $value;
     
                } else{
                    $i++;
                    $daily_tasks[$i]['date']      = date('d F Y',strtotime($value['created_at']));
                    $daily_tasks[$i]['records'][] = $value;
                    $pre_date                     = $current_date;

                }
            }
        }

        if(!empty($daily_tasks))
        {
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => "Daily tasks list.",
                    'data' => $daily_tasks
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => "No daily tasks found.",
                    //'data' => $daily_records
                )
            ));
        }
    }

}
