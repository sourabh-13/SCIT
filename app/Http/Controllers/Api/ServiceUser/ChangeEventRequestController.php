<?php

namespace App\Http\Controllers\Api\ServiceUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
/*use App\User, App\ServiceUser, App\ServiceUserHealthRecord, App\Calendar, App\PlanBuilder, App\ServiceUserCalendarNote, App\ServiceUserDailyRecord, App\ServiceUserCalendarEvent, App\ServiceUserEarningIncentive, App\StaffAnnualLeave, App\StaffSickLeave, App\StaffTaskAllocation, App\ServiceUserLivingSkill, App\ServiceUserEducationRecord;*/
use App\EventChangeRequest, App\ServiceUser, App\Notification;
use DB;

class ChangeEventRequestController extends Controller
{
	public function index(Request $request) {


		echo '      <div class="row">
		                <div class="col-md-12 col-sm-12 col-xs-12 cog-panel p-0 r-p-15">
		                    
		                    <div class="form-group col-xs-12 p-0">
		                        <label class="col-sm-1 col-xs-12 color-themecolor r-p-0"> Reason: </label>
		                        <div class="col-sm-11 r-p-0">
		                            <div class="input-group">
		                                <textarea rows="3" name="reason" required class="form-control txtarea edit_event" value=""></textarea>
		                            </div>
		                        </div>
		                    </div>

                        	<div class="form-group col-xs-12 cog-panel datepicker-sttng p-0">
                            	<label class="col-sm-1 col-xs-12 r-p-0">Current Date:</label>
                            	<div class="col-sm-11 r-p-0">
                                    <input name="current_date" disabled="disabled" class="form-control trans" type="text" value="" autocomplete="off" maxlength="10" />
                            	</div>
                        	</div>

                        	<div class="form-group col-xs-12 cog-panel datepicker-sttng p-0">
                            	<label class="col-sm-1 col-xs-12 r-p-0">New Date:</label>
                            	<div class="col-sm-11 r-p-0">
                                    <input name="new_date" required class="form-control datetime-picker trans" type="text" value="" autocomplete="off" maxlength="10" readonly="" />
                            	</div>
                        	</div>

		                    <input type="hidden" name="su_daily_record_id" type="text" value=""/>
		                    <input type="hidden" name="event_id" type="text" value=""/>
		                    <input type="hidden" name="event_type" type="text" value=""/>
		                </div>
		            </div>';
		
	}

	public function add(Request $request)
	{
		$data = $request->input();

		$su = ServiceUser::where('id', $data['service_user_id'])->first();

		if(!empty($su)){

			if($request->isMethod('post')) {

				$change_req  				= new EventChangeRequest;
				$change_req->service_user_id= $data['service_user_id'];
				$change_req->calendar_id  	= $data['calendar_id'];
				$change_req->reason    	  	= $data['reason'];
				$change_req->date  	   		= date('Y-m-d', strtotime($data['current_date']));
				$change_req->new_date  		= date('Y-m-d', strtotime($data['new_date']));

				if($change_req->save()) {
                    
                    //saving notification start
                    $notification                                  = new Notification;
                    $notification->service_user_id                 = $data['service_user_id'];
                    $notification->event_id                        = $change_req->id;
                    $notification->notification_event_type_id      = '20';
                    $notification->event_action                    = 'ADD';      
                    $notification->home_id                         = $su->home_id;        
                    $notification->save();
                    //saving notification end
                    
					$result['response'] = true;
				} else {

					$result['response'] = false;
				}
				return $result;
			}
		}

	}

	public function edit(Request $request) {

		if($request->isMethod('post')) {

		$data = $request->all();
		
			if($data['event_type'] == '2') {
				$su_daily_record_id 	= $data['su_daily_record_id'];
				$su_d_event 			= ServiceUserDailyRecord::find($su_daily_record_id);
				$su_d_event->scored 	= $data['su_record_score'];
				$su_d_event->details 	= $data['su_record_detail'];

				if($su_d_event->save()) {
					return redirect()->back()->with('success',' Daily Record Event Updated Successfully.');
				} else {
					return redirect()->back()->with('error','Some Error Occured Please Try Again Later.');
				}

			} else if($data['event_type'] == '1') {
				$su_health_record_id = $data['su_health_record_id'];
				$su_h_event  		 = ServiceUserHealthRecord::find($su_health_record_id);
				$su_h_event->details = $data['su_record_detail'];

				if($su_h_event->save()) {
					return redirect()->back()->with('success','Health Record Event Updated Successfully.');
				} else {
					return redirect()->back()->with('error','Some Error Occured Please Try Again Later.');
				}

			} else if($data['event_type'] == '5') {
				$su_note_id   	    = $data['su_note_id'];
				$su_n_event   	    = ServiceUserCalendarNote::find($su_note_id);
				$su_n_event->title  = $data['note_title'];					
				$su_n_event->note   = $data['su_note_title'];
				if($su_n_event->save()) {
					return redirect()->back()->with('success','Note Event Updated Successfully.');
				} else {
					return redirect()->back()->with('error','Some Error Occured Please Try Again Later.');
				}

			} else if($data['event_type'] == '4') {
				$su_calendar_event_id = $data['su_calendar_event_id'];
				if(isset($data['formdata'])){
					$formdata = json_encode($data['formdata']);
				} else{
					$formdata = '';
				}
				$su_c_event             =  ServiceUserCalendarEvent::find($su_calendar_event_id);
				$su_c_event->formdata   =  $formdata;

				if($su_c_event->save()) {
					return redirect()->back()->with('success','Event Updated successfully.');
				} else {
					return redirect()->back()->with('error','Some error occured, Please try again  after some time.');
				}

			}  else if($data['event_type'] == '3') {
					$su_incentive_id    =  $data['su_incentive_id'];
					$su_i_event         = ServiceUserEarningIncentive::find($su_incentive_id);
					//$su_i_event->star_cost = $data['su_incentive_star'];
					$su_i_event->detail = $data['su_incentive_detail'];
					$su_i_event->time   = $data['su_incentive_time'];
					if($su_i_event->save()) {
						return redirect()->back()->with('success','Incentive Event Updated Successfully.');
					} else {
						return redirect()->back()->with('error', COMMON_ERROR);
					}

			} else if($data['event_type'] == '6') {
					$staff_annual_leave_id =  $data['staff_annual_leave_id'];
					$staff_a_event             = StaffAnnualLeave::find($staff_annual_leave_id);
					$staff_a_event->title      = $data['annual_leave_title'];
					$staff_a_event->leave_date = date('Y-m-d', strtotime($data['annual_leave_leave_date']));
					$staff_a_event->reason     = $data['annual_leave_reason'];
					$staff_a_event->comments   = $data['annual_leave_comment'];
					if($staff_a_event->save()) {
						return redirect()->back()->with('success','Annual Leave Event Updated Successfully.');
					} else {
						return redirect()->back()->with('error', COMMON_ERROR);
					}
			} else if($data['event_type'] == '7') {
					$staff_sick_leave_id =  $data['staff_sick_leave_id'];
					$staff_s_event             = StaffSickLeave::find($staff_sick_leave_id);
					$staff_s_event->title      = $data['sick_leave_title'];
					$staff_s_event->leave_date = date('Y-m-d', strtotime($data['sick_leave_leave_date']));
					$staff_s_event->reason     = $data['sick_leave_reason'];
					$staff_s_event->comments   = $data['sick_leave_comment'];
					if($staff_s_event->save()) {
						return redirect()->back()->with('success','Sick Leave Event Updated Successfully.');
					} else {
						return redirect()->back()->with('error', COMMON_ERROR);
					}
			} else if($data['event_type'] == '8') {
					$staff_task_id =  $data['staff_task_id'];
					$staff_t_event             = StaffTaskAllocation::find($staff_task_id);
					$staff_t_event->title     = $data['task_title'];
					$staff_t_event->details   = $data['task_detail'];
					if($staff_t_event->save()) {
						return redirect()->back()->with('success','Task Allocation Event Updated Successfully.');
					} else {
						return redirect()->back()->with('error', COMMON_ERROR);
					}
			} else if($data['event_type'] == '9') { 
					$su_living_skill_id    = $data['su_living_skill_id'];
					$su_l_event 		   = ServiceUserLivingSkill::find($su_living_skill_id);
					$su_l_event->scored    = $data['su_skill_score'];
					$su_l_event->details   = $data['su_skill_detail'];

					if($su_l_event->save()) {
						return redirect()->back()->with('success','Living Skill Event Updated Successfully.');
					} else {
						return redirect()->back()->with('error','Some Error Occured Please Try Again Later.');
					}
			}  else if($data['event_type'] == '10') { 
					$su_education_record_id = $data['su_education_record_id'];
					$su_e_event 			= ServiceUserEducationRecord::find($su_education_record_id);
					$su_e_event->scored 	= $data['su_education_score'];
					$su_e_event->details 	= $data['su_education_detail'];

					if($su_e_event->save()) {
						return redirect()->back()->with('success','Education Record Event Updated Successfully.');
					} else {
						return redirect()->back()->with('error','Some Error Occured Please Try Again Later.');
					}
			}
			 else {

			}
		}
	}
	/*Note: About this controller
		This controller is used for viewing the detail of calendar events which has been booked into the calendar.
		Edit booked Event
		delete booked Event (move back to event list)
	*/
}