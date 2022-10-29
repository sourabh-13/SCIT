<?php
namespace App\Http\Controllers\backEnd\user;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session; 
use App\User, App\StaffAnnualLeave;  
use DB; 
use Hash;

class AnnualLeaveController extends Controller
{
    public function index(Request $request, $user_id) {   
        //compare with su home_id
        $u_home_id = User::where('id',$user_id)->value('home_id');
        $home_id = Session::get('scitsAdminSession')->home_id;
        if($home_id == $u_home_id) {

            $u_annual = StaffAnnualLeave::where('staff_member_id', $user_id)->where('is_deleted','0')->select('id','title', 'leave_date','staff_member_id');
            $search = '';

            if(isset($request->limit))
            {
                $limit = $request->limit;
                Session::put('page_record_limit',$limit);
            } else{

                if(Session::has('page_record_limit')){
                    $limit = Session::get('page_record_limit');
                } else{
                    $limit = 25;
                }
            }

            if(isset($request->search))
            {
                $search = trim($request->search);
                $u_annual = $u_annual->where('title','like','%'.$search.'%');             //search by date or title
            }

            $u_annual_leave = $u_annual->paginate(25);
        } else {
            return redirect('admin/')->with('error',UNAUTHORIZE_ERR);
        }
        $page = 'user-annual-leave';
        return view('backEnd.user.annualLeave.annual_leaves', compact('page','limit', 'user_id','u_annual_leave','search')); 
    }

    public function add(Request $request, $user_id) {   
        
        if($request->isMethod('post')) { 
            $data = $request->input();

            //compare with su home_id
            $u_home_id = User::where('id',$user_id)->value('home_id');
            $home_id = Session::get('scitsAdminSession')->home_id;

            if($home_id == $u_home_id) {
                $u_annual_leave                  =  new StaffAnnualLeave;
                $u_annual_leave->title           =  $data['title'];
                $u_annual_leave->staff_member_id =  $user_id;
                $u_annual_leave->home_id         =  $home_id;
                $u_annual_leave->leave_date      =  date('Y-m-d', strtotime($data['leave_date']));
                $u_annual_leave->reason          =  $data['reason'];
                $u_annual_leave->comments        =  $data['comment'];

                if($u_annual_leave->save()) {
                        // return redirect('admin/service-users/care-history/'.$service_user_id)->with('success', 'New Care Timeline added successfully.');
                        return redirect('admin/user/annual-leaves/'.$user_id)->with('success', 'Annual Leave added successfully.');
                    }  else  {
                        return redirect()->back()->with('error', 'Some error occurred. Please try after sometime.');
                    }
            } else {
                return redirect('admin/')->with('error',UNAUTHORIZE_ERR);
            }
        }
        $page = 'user-annual-leave';
        return view('backEnd.user.annualLeave.annual_leave_form', compact('page', 'user_id'));
    }
            
    public function edit(Request $request, $u_annual_leave_id) {   

        $u_annual_leave    =  StaffAnnualLeave::find($u_annual_leave_id);
        if(!empty($u_annual_leave)) {
            $user_id    = $u_annual_leave->staff_member_id;

             //comparing u home_id
            $u_home_id = User::where('id',$user_id)->value('home_id');
            $home_id    = Session::get('scitsAdminSession')->home_id;
            if($home_id != $u_home_id) {
                return redirect('admin/')->with('error',UNAUTHORIZE_ERR);
            }

            if($request->isMethod('post')) {   
                $data = $request->input();
                // echo "<pre>"; print_r($data); die;
                $u_annual_leave->title           =  $data['title'];
                $u_annual_leave->leave_date      =  date('Y-m-d', strtotime($data['leave_date']));
                $u_annual_leave->reason          =  $data['reason'];
                $u_annual_leave->comments        =  $data['comment'];             
        
               if($u_annual_leave->save()) {
                   return redirect('admin/user/annual-leaves/'.$user_id)->with('success','Annual Leave Updated Successfully.'); 
                } else {
                   return redirect()->back()->with('error','Annual Leave could not be Updated Successfully.'); 
                }  
            }
        } else {
                return redirect('admin/')->with('error','Sorry,Annual Leave does not exists');
        }

        $u_annual_leave = StaffAnnualLeave::where('id', $u_annual_leave_id)
                        ->first();

        if(!empty($u_annual_leave)) {
            //compare with su home_id
            $u_home_id = User::where('id',$u_annual_leave->staff_member_id)->value('home_id');
            $home_id    = Session::get('scitsAdminSession')->home_id;
            if($home_id != $u_home_id) { 
                return redirect('admin/')->with('error',UNAUTHORIZE_ERR);
            } 
        } else {
            return redirect('admin/')->with('error','Sorry, Annual Leave does not exists');
        }

        $page = 'user-annual-leave';
        return view('backEnd.user.annualLeave.annual_leave_form', compact('u_annual_leave','page','user_id'));
    }
        
    public function delete($u_annual_leave_id) {   

        if(!empty($u_annual_leave_id)) {
           $u_annual_leave =  StaffAnnualLeave::where('id', $u_annual_leave_id)->first();
           
            if(!empty($u_annual_leave)) {
                $u_home_id = User::where('id',$u_annual_leave->staff_member_id)->value('home_id');
                $home_id = Session::get('scitsAdminSession')->home_id;
                
                //compare with su home_id
                if($home_id != $u_home_id) {
                    return redirect('admin/')->with('error',UNAUTHORIZE_ERR);
                }

                StaffAnnualLeave::where('id', $u_annual_leave_id)->update(['is_deleted'=>'1']);
                return redirect()->back()->with('success','Annual Leave deleted Successfully.'); 
            } else  {
                return redirect('admin/')->with('error','Sorry,Annual Leave does not exists'); 
            }
        } else {
            return redirect('admin/')->with('error',UNAUTHORIZE_ERR);
        }
    }
}