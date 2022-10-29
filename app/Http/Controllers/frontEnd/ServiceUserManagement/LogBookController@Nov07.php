<?php
namespace App\Http\Controllers\frontEnd\ServiceUserManagement;
use App\Http\Controllers\frontEnd\ServiceUserManagementController;
use Illuminate\Http\Request;
use App\LogBook, App\ServiceUser, App\ServiceUserLogBook;
use DB, Auth;

class LogBookController extends ServiceUserManagementController
{
    public function index($service_user_id) {   

        $su_log_book_records =  ServiceUserLogBook::select('su_log_book.id as su_log_book_id','log_book.title','log_book.details','log_book.id','log_book.date','d.title as d_title', 'd.date as d_date','su_log_book.created_at','d.details as d_details')
                                    ->leftJoin('log_book','log_book.id','su_log_book.log_book_id')
                                    ->leftJoin('dynamic_form as d','d.id', 'su_log_book.dynamic_form_id')
                                    ->where('su_log_book.service_user_id', $service_user_id)
                                    // ->where('log_book.home_id', Auth::user()->home_id)
                                    // ->where('log_book.is_deleted','0')
                                    ->orderBy('su_log_book.id','desc');

                                    // ->get()->toArray();
        // echo "<pre>"; print_r($su_log_book_records); die;

        $today = date('Y-m-d 00:0:00');
        
        $pagination  = '';
        if(isset($_GET['search'])) {

            // echo "<pre>"; print_r($_GET['search']); die;
            $log_book_search_type = $_GET['log_book_search_type'];
            
            if($log_book_search_type == 'log_title'){
               // if (!empty($su_log_book_records->title)) {
                    $su_log_book_records = $su_log_book_records->where('log_book.title','like','%'.$_GET['search'].'%');
              //  } else {
                    $su_log_book_records = $su_log_book_records->where('dynamic_form.title','like','%'.$_GET['search'].'%');
                //}

            } else{

                $search_date = date('Y-m-d',strtotime($_GET['log_book_date_search'])).' 00:00:00';
                $search_date_next = date('Y-m-d',strtotime('+1 day', strtotime($_GET['log_book_date_search']))).' 00:00:00';

                $su_log_book_records = $su_log_book_records->where('log_book.date','>',$search_date)
                                                     ->where('log_book.date','<',$search_date_next);
            }
        }

        $su_log_book_records  = $su_log_book_records->orderBy('log_book.id','desc')
                                              ->orderBy('log_book.date','desc');
                                              //->paginate(50);
        
        if(isset($_GET['search'])) {

            $su_log_book_records = $su_log_book_records->get();
        } else {
                $su_log_book_records = $su_log_book_records->paginate(50);

                if($su_log_book_records->links() != '')  {
                    $pagination .= '</div><div class="log_records_paginate m-l-15 position-botm">';
                    $pagination .= $su_log_book_records->links();
                    $pagination .= '</div>';
            }
        }
        
        if(!$su_log_book_records->isEmpty()){
            $pre_date = date('y-m-d',strtotime($su_log_book_records['0']->date));
        }
                
        foreach ($su_log_book_records as $key => $value) {

            $check = (!empty($value->details)) ? '<i class="fa fa-check"></i>' : '';
            
            $first = 0;

            $title = '';
           

            if(isset($_GET['logged']) ||  isset($_GET['search']) ){ 

                if(!empty($value->title)) {
                    $title = $value->title;
                    $details = $value->details;
                    $record_date = date('Y-m-d',strtotime($value->date));
                } else if(!empty($value->d_title)) {
                    $title = $value->d_title;
                    $details = $value->d_details;
                    $record_date = date('Y-m-d',strtotime($value->created_at));
                } else {
                    $title = '';
                    $record_date = '';
                    $details = '';
                }


                if($record_date != $pre_date){
                    $pre_date = $record_date; 
               
                    echo '</div>
                    <div class="daily-rcd-head">
                        <div class="col-md-12 col-sm-12 col-xs-12 cog-panel p-0 r-p-15 record_row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <a  class="date-tab">
                                    <span class="pull-left">
                                        '.date('d F Y',strtotime($record_date)).'
                                    </span>
                                    <i class="fa fa-angle-right pull-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="daily-rcd-content">';
                }
                else{}
            } 

            echo '
                <div class="col-md-12 col-sm-12 col-xs-12 cog-panel p-0 r-p-15 record_row">
                    <div class="form-group col-md-11 col-sm-11 col-xs-12 r-p-0 pull-center">
                        <div class="input-group popovr">
                            <input type="text" name="edit_su_record_desc[]"  style="text-align: center" class="form-control cus-control edit_record_desc_'.$value->id.' edit_rcrd"  disabled  value="'.$title.'" />';
                             
                            if(!empty($details)){
                                echo '<div class="input-plus color-green"> <i class="fa fa-plus"></i> </div>';
                            }
                              echo '<input type="hidden" name="edit_su_record_id[]" value="'.$value->id.'" disabled="disabled" class="edit_record_id_'.$value->id.'" />
                                <span class="input-group-addon cus-inpt-grp-addon clr-blue settings">
                                <i class="fa fa-cog"></i>
                                <div class="pop-notifbox">
                                    <ul class="pop-notification" type="none">';
                                        //if(isset($add_new_case)) { 
                                        echo '<li> <a href="#" log_book_id="'.$value->id.'" class="view-su-log-book"> <span> <i class="fa fa-eye"></i> </span> View </a> </li>';
                                        /*echo '<li> <a href="#" log_book_id="'.$value->su_log_book_id.'" class="view-su-log-book"> <span> <i class="fa fa-eye"></i> </span> View </a> </li>';*/
                                        //}
                                        /*<li> <a href="#" log_book_id="'.$value->id.'" class="delete-log-record"> <span class="color-red"> <i class="fa fa-exclamation-circle"></i> </span> Remove </a> </li>*/
                                        echo '<li> <a href="#" log_book_id="'.$value->id.'" class="log-record-detail"> <span class="color-red"> <i class="fa fa-plus color-green"></i> </span> Details </a> </li>
                                        <li><a href="'.url('/service/logbook/Calendar/add?log_book_id='.$value->id.'&service_user_id='.$service_user_id).'" log_book_id="'.$value->id.'" class="add_to_clndr"> <span class="color-green"> <i class="fa fa-plus-circle"></i> </span> Add to Calendar</a> </li>
                                    </ul>
                                </div>
                            </span>
                        </div>
                    </div>

                    <div class="input-plusbox form-group col-xs-11 p-0 detail">
                        <label class="cus-label color-themecolor"> Details: </label>
                        <div class="cus-input p-r-10">
                            <div class="input-group">
                                <textarea rows="5" name="edit_su_record_detail[]" disabled class="form-control tick_text txtarea edit_detail_'.$value->id.' edit_rcrd " value="">'.$details.'</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                ';
                
        }
        
        echo $pagination;
    }

    public function add(Request $request) {

        if($request->isMethod('post'))
        {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            /*$su_home_id = ServiceUser::where('id',$data['service_user_id'])->value('home_id');
            if(Auth::user()->home_id != $su_home_id){
                echo '0'; die; 
            }*/

            $log_book_record          = new LogBook;
            // echo "<pre>"; print_r($log_book_record); die;
            
            $log_book_record->title   = $data['log_title'];
            $log_book_record->date    = date('Y-m-d H:i:s', strtotime($data['log_date']));
            $log_book_record->details = $data['log_detail'];
            $log_book_record->home_id = Auth::user()->home_id;
            $log_book_record->user_id = Auth::user()->id;

            $log_book_record->save();


            if($log_book_record->save()) {
                
                $su_log_book_record                     =   new ServiceUserLogBook;
                $su_log_book_record->service_user_id    =   $data['service_user_id'];
                $su_log_book_record->log_book_id        =   $log_book_record->id;
                $su_log_book_record->user_id            =   Auth::user()->id;
                //$su_log_book_record->category_id        =   $data['category_id'];

                if($su_log_book_record->save()) {

                    $result['response'] = true;
                }  else {
                    $result['response'] = false;  
                }
            }   
            else {
                
                $result['response'] = false;
            }
                // if($log_book_record->save()){

                //saving notification start

                /*$notification                  = new Notification;
                $notification->service_user_id = $data['service_user_id'];
                $notification->event_id        = $records->id;
                $notification->event_type      = 'SU_DR';
                $notification->event_action    = 'ADD';      
                $notification->home_id         = Auth::user()->home_id;
                $notification->user_id         = Auth::user()->id;        
                $notification->save();*/

                //saving notification end

                /*$res = $this->index();
                echo $res; die;*/ 

                /*return redirect()->back()->with('success','Request submitted successfully.');

            }
            else { 
                return redirect()->back()->with('error',COMMON_ERROR);
            }*/
            return $result;
        }
    }

    public function view($log_book_id = null) {

        $home_id = Auth::user()->home_id;
    
        $su_log_book_record = LogBook::select('log_book.*', 'su_lb.service_user_id', 'su_lb.log_book_id','su_lb.user_id','u.name as staff_name')
                                        ->where('log_book.home_id', $home_id)
                                        ->join('su_log_book as su_lb', 'su_lb.log_book_id', 'log_book.id')
                                        ->join('user as u','u.id','su_lb.user_id')
                                        ->where('log_book.id', $log_book_id)
                                        ->first();
        // echo "<pre>"; print_r($su_log_book_record); die;
        if(!empty($su_log_book_record)) {

            $result['response'] = true;
            //$result['category_id'] = $su_log_book_record->category_id;
            $result['title']        = $su_log_book_record->title;
            $result['details']      = $su_log_book_record->details;
            $result['date']         = $su_log_book_record->date;
            $result['staff_name']   = $su_log_book_record->staff_name;
            
        }  
        else {
            $result['response'] = false;
        }

        return $result;
    }
     
    public function serviceuserlist(Request $request) {
        $serviceuserlist = ServiceUser::where('is_deleted','0')
                                ->where('home_id', Auth::user()->home_id)
                                ->select('id', 'name', 'user_name')->get();

        echo '<label class="col-md-4 col-sm-2 col-xs-12 p-t-7 text-right"> Select Service User: </label>
                    <div class="col-md-6 col-sm-9 col-xs-10">
                        <div class="select-bi" style="width:100%;float:left;">
                            
                            <select class="js-example-placeholder-single select-field form-control" required id="records_list" style="width:100%;" name="service_user_id">
                                <option value=""></option>';

                                foreach($serviceuserlist as $value){
                                echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                                }
                                
                        echo '</select>
                        </div>
                       
                    </div>';
                    die;
    }

    public function service_user_add_log(Request $request) {
        if ($request->isMethod('post'))   {
            $data = $request->all();

            $su_log_record = new ServiceUserLogBook;
            $su_log_record->log_id          = $data['log_id'];
            $su_log_record->service_user_id = $data['service_user_id'];
            //$su_log_record->category_id     = $data['category_id'];

            if($su_log_record->save())  {

                $result['response'] = '1';
            }   else   {
                $result['response'] = '0';
            }
        } else {
            echo '0'; die;
        }
        return $result;
    }

    public function add_to_calendar(Request $request) {
        
        $data = $request->input();
        $service_user_id = $data['service_user_id'];
        $log_book_id = $data['log_book_id'];

        $clndr_add = LogBook::where('id', $log_book_id)->where('added_to_calendar','0')->update(['added_to_calendar'=> '1']);
        if($clndr_add) {
            return redirect('/service/calendar/'.$service_user_id)->with('success', 'Log has been add to calendar successfully');
        } else {
            return redirect()->back()->with('error', 'Log record already in calendar');
        }

    }

}