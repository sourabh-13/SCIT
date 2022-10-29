<?php 
namespace App\Http\Controllers\Api\ServiceUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth, DB, Hash;
use DateTime, Carbon\Carbon;
use App\ServiceUserCalendarNote;

class NoteController extends Controller
{
  /*  public function replace($array)
    {
        foreach ($array as $key => $value) 
        {

            if(is_array($value))
                $array[$key] = $this->replace($value);
            else
            {
     
                if (is_null($value) ){
                  $array[$key] = "N/A";
                }
                    
            }
        }
        return $array;
    }*/
    
                    /*------- Notes module-------*/
    public function index($service_user_id)
    {
        $notes = DB::table('su_calendar_note')->where('service_user_id',$service_user_id)->select('id','title','note','created_at')
            ->orderBy('id','desc')->get();
        $notes = json_decode(json_encode($notes),true);
        
        foreach($notes as $key => $value) {
            $created_at = date('d M, Y g:i A', strtotime($value['created_at']));
            $notes[$key]['created_at'] = $created_at;
        }
        
        if(!empty($notes))
        {
            return json_encode(array(
                'result' =>array(
                    'response' => true,
                    'message' => "Listing of notes.",
                    'data' => $notes
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' =>array(
                    'response' => false,
                    'message' => 'Notes not found.',
                )
            ));
        }
    }
                    
    public function add(Request $r)
    {
        $data = $r->input();
        if(!empty($data['service_user_id']) && !empty($data['title']) && !empty($data['note']))
        {
            $exist_user = DB::table('service_user')->where('id',$data['service_user_id'])->first();
            if(!empty($exist_user))
            {
                $home_id = $exist_user->home_id;
            }
            
            /*ServiceUserCalendarNote::create([
                'home_id' => $home_id,
                'service_user_id' =>$data['service_user_id'],
                'title' => $data['title'],
                'note' => $data['note']
            ]);*/
            
            $calendar_note = new ServiceUserCalendarNote;
            $calendar_note->home_id = $home_id;
            $calendar_note->service_user_id =$data['service_user_id'];
            $calendar_note->title = $data['title'];
            $calendar_note->note = $data['note'];
            $calendar_note->save();
            
            return json_encode(array(
                'result' => array(
                    'response' => true,
                    'message' => 'Your note has been saved successfully.'
                )
            ));
        }
        else
        {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => 'Fill all fields.'
                )
            ));
        }
    }
    
    public function edit(Request $req) {

        $note_id = $req->note_id;
        $title   = $req->title;
        $note    = $req->note;
        if(!empty($note_id) && !empty($title) && !empty($note)) {
            
            $update = ServiceUserCalendarNote::find($note_id);
            $update->title = $title;
            $update->note  = $note;
            if($update->save()) {

                $data = ServiceUserCalendarNote::where('id',$note_id)->first();

                return json_encode(array(
                    'result' => array(
                        'response' => true,
                        'message'  => 'Your note has been updated successfully.',
                        'date'     => $data
                    )
                ));

            } else {
                 return json_encode(array(
                    'result' => array(
                        'response' => false,
                        'message' => COMMON_ERROR
                    )
                ));
            }
        } else {
            return json_encode(array(
                'result' => array(
                    'response' => false,
                    'message' => FILL_FIELD_ERR
                )
            ));
        }
    }
    
}    