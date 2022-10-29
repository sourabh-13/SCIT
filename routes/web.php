<?php

Route::match(['get','post'],'/change-design-layout/{design_layout_id}', 'frontEnd\DashboardController@change_layout');
                         /*-------Api Routes-------*/
Route::group(['prefix' => 'api/service'], function () {
    Route::post('/contact-us', 'Api\ContactUsController@add_contact_us');
    Route::post('/login', 'Api\ServiceUser\UserController@login');
    Route::get('/personal-detail/{service_user_id}','Api\ServiceUser\UserController@personal_details');
    
                            /*-------NoteController--------*/
    Route::get('/notes/{service_user_id}','Api\ServiceUser\NoteController@index');
    Route::post('/note/add','Api\ServiceUser\NoteController@add');
    Route::post('/note/edit','Api\ServiceUser\NoteController@edit');
    
                        /*-------UserController---------*/
    Route::get('/targets/{service_user_id}','Api\ServiceUser\TargetController@index');
                        /*DailyTask Controller*/
    Route::get('/daily-tasks/{su_id}','Api\ServiceUser\DailyTasksController@daily_tasks');
    Route::get('/living-skill/{su_id}','Api\ServiceUser\DailyTasksController@living_skill');
    Route::get('/education-records/{su_id}','Api\ServiceUser\DailyTasksController@education_records');
    Route::get('/earning/daily-tasks/{su_id}','Api\ServiceUser\DailyTasksController@earning_daily_tasks');
    Route::get('/earning/living-skill/{su_id}','Api\ServiceUser\DailyTasksController@earning_living_skill');
    Route::get('/earning/education-records/{su_id}','Api\ServiceUser\DailyTasksController@earning_education_records');
    
                    /*-------EarningSchemeController-------*/
    //view earning categories
    Route::get('/earning-scheme-categories/{su_id}','Api\ServiceUser\EarningSchemeController@categories');
    //view incentives of a earning category
    Route::get('/earning-scheme-details/{earning_scheme_id}','Api\ServiceUser\EarningSchemeController@earning_incentives');
    //earning history of su
    Route::get('/earning-schemes/{service_user_id}','Api\ServiceUser\EarningSchemeController@earning_history');
    //booked incentives of a user
    Route::get('/earning/user-incentives/{service_user_id}','Api\ServiceUser\EarningSchemeController@user_incentives');
    
    Route::post('/earning/incentive/add','Api\ServiceUser\EarningSchemeController@add_to_calendar');
    
                        /*-------MoodController-------*/
    Route::get('/moods/{su_id}','Api\ServiceUser\MoodController@moods');
    Route::post('/mood/add','Api\ServiceUser\MoodController@add_mood');
    Route::get('/mood/user/{id}','Api\ServiceUser\MoodController@listing_mood');
    
                        /*-------MoneyController-------*/
    Route::get('/money/{service_user_id}','Api\ServiceUser\MoneyController@index');
    Route::post('money/request/add','Api\ServiceUser\MoneyController@add_money_request');
    Route::get('money/history/{su_id}','Api\ServiceUser\MoneyController@history');
    Route::get('money/request/view/{money_request_id}','Api\ServiceUser\MoneyController@request_detail');
    
                        /*-------LabelController-------*/
    Route::match(['get','post'],'labels/{service_user_id}','Api\LabelController@label');
    
                        /*-------CareTeamController-------*/
    Route::match(['get','post'],'/care-team/{service_user_id}','Api\ServiceUser\CareTeamController@care_team');
    Route::match(['get','post'],'/care-team/view/{service_user_id}','Api\ServiceUser\CareTeamController@care_team_view');
    
                        /*-----AppointmentController-------*/
    Route::get('/appointments/{su_id}','Api\ServiceUser\AppointmentController@appointments');
    Route::get('/appointment/forms/{su_id}','Api\ServiceUser\AppointmentController@appointment_forms_list');
    Route::get('/appointment/form/{form_id}','Api\ServiceUser\AppointmentController@view_add_appointment_form');
    Route::post('/appointment/save','Api\ServiceUser\AppointmentController@save_appointment');
    Route::get('/appointment/view/{su_calendar_event_id}','Api\ServiceUser\AppointmentController@view_appointment_detail');
    Route::get('/staff/members/{service_user_id}','Api\ServiceUser\AppointmentController@staff_members');

    
                        /*-------CareCenterController-------*/
    Route::get('care-center/staff-list/{service_user_id}','Api\ServiceUser\CareCenterController@staff_list');
    // Route::post('/care-center/in-danger','Api\ServiceUser\CareCenterController@add_danger');
    Route::get('/care-center/social-worker/{service_user_id}' , 'Api\ServiceUser\CareCenterController@social_worker_list');
    Route::get('/care-center/external-service-list/{service_user_id}','Api\ServiceUser\CareCenterController@external_service_list');
    
    Route::post('/care-center/in-danger','Api\ServiceUser\CareCenter\DangerController@add');
    
    //Request callback
    Route::post('/care-center/request-callback','Api\ServiceUser\CareCenter\RequestCallBackController@add');
    
    //Need assistance
    Route::get('/care-center/need-assistance/{service_user_id}','Api\ServiceUser\CareCenter\NeedAssistanceController@index');
    Route::post('/care-center/need-assistance/send-message','Api\ServiceUser\CareCenter\NeedAssistanceController@send_message');
    
    //Message office
    Route::get('/care-center/message-office/{service_user_id}','Api\ServiceUser\CareCenter\MessageOfficeController@index');
    Route::post('/care-center/message-office/send-message','Api\ServiceUser\CareCenter\MessageOfficeController@send_message');
    
    //Make complaint
    Route::post('/care-center/complaint/add','Api\ServiceUser\CareCenter\ComplaintController@add');

    //yp Calendar 
    Route::get('/calendar/{service_user_id}','Api\ServiceUser\CalendarController@index');
    Route::match(['get','post'], '/calendar/event/view', 'Api\ServiceUser\CalendarEventController@index');
    Route::match(['get','post'], '/calendar/change-event-req/{su_id}','Api\ServiceUser\ChangeEventRequestController@index');
    Route::match(['get','post'], '/calendar/change-event-req/add/{su_id}','Api\ServiceUser\ChangeEventRequestController@add');
    
    //yp Location tracking
    Route::match(['get','post'],'/location/add', 'Api\ServiceUser\LocationController@add');
    Route::match(['get','post'],'/location/add-missing', 'Api\ServiceUser\LocationController@add_missing_locations');
    Route::match(['get','post'],'/location/alert/{su_location_history_id}', 'Api\ServiceUser\LocationController@notify_location_alert_node');
    Route::match(['get','post'],'/logout/location', 'Api\ServiceUser\LocationController@lat_long_update_logout_tym');
    //save device id
    Route::post('/device/add', 'Api\DeviceController@add_su_device');

});
Route::group(['prefix' => 'api/staff'], function () {
    Route::get('/service-users/{staff_id}','Api\Staff\ServiceUserController@listing_service_user');
    Route::get('/daily-tasks/{staff_id}','Api\Staff\TaskAllocationController@index');
    Route::get('/money-requests/{staff_id}','Api\Staff\MoneyRequestController@index');
    Route::post('/money-request/update','Api\Staff\MoneyRequestController@update_request');

    Route::get('/trainings/{staff_id}','Api\Staff\TrainingController@index');

    Route::post('/mood/add-suggestion','Api\Staff\MoodController@give_suggestion');

    Route::post('/message-office/add-message','Api\Staff\MessageOfficeController@add_message');

    Route::get('/care-center/in-danger/{staff_id}','Api\Staff\CareCenterController@in_danger_requests');
    Route::get('/care-center/request-callbacks/{staff_id}','Api\Staff\CareCenterController@request_callbacks');


    Route::post('/device/add', 'Api\DeviceController@add_user_device');

});
						/*Change Password*/
Route::post('api/change-password','Api\ServiceUser\UserController@change_password');
Route::post('api/forgot-password','Api\ServiceUser\UserController@forgot_password');
Route::get('/reset-password/{user_name}/{security_code}', 'Api\ServiceUser\UserController@show_forget_password_form');
Route::post('/reset-password/save', 'Api\ServiceUser\UserController@set_forget_password');
Route::post('api/remove-device', 'Api\DeviceController@remove_device');
                    /*------Api Routes End here-------*/
     

// Route::match(['get','post'], '/login', 'frontEnd\UserController@login');
Route::match(['get','post'], '/login', 'frontEnd\UserController@login')->middleware('PreventBack');

Route::get('/logout', 'frontEnd\UserController@logout');
Route::post('/forgot-password', 'frontEnd\ForgotPasswordController@send_forgot_pass_link_mail');
Route::match(['get','post'], '/check-email-exists', 'frontEnd\ForgotPasswordController@check_email_exists');

Route::get('/fb_close', 'Controller@fb_close');

//Only Use for Agent
Route::match(['get','post'], 'agent/welcome', 'frontEnd\AgentController@welcome_page');


Route::match(['get','post'], '/delete/calendar/event/{event_id}', 'frontEnd\SystemManagement\CalendarController@del_calender_event');
Route::match(['get','post'], '/select/social/work/send/mail/{srvc_usr_id}', 'frontEnd\ServiceUserManagement\ReportController@social_worker');
Route::match(['get','post'], '/send/mail/social/worker', 'frontEnd\ServiceUserManagement\ReportController@send_mail_social_work');

Route::group(['middleware'=>['checkUserAuth','lock']],function(){
	
	Route::match(['get','post'], '/', 'frontEnd\DashboardController@dashboard');
	Route::post('/add-incident-report', 'frontEnd\DashboardController@add_incident_report');

	// ------------- Personal Management - My profile ---------------------// 
	Route::get('/my-profile/{user_id}', 'frontEnd\PersonalManagement\ProfileController@index');
	Route::post('/my-profile/edit', 'frontEnd\PersonalManagement\ProfileController@edit_profile_setting');
	
	Route::match(['get','post'], '/profile/change-password', 'frontEnd\PersonalManagement\ChangePasswordController@change_password');

	// Weekly money module
	Route::post('weekly-allowance/update','frontEnd\ServiceUserManagement\MoneyController@update_home_weekly_allowance');
	
	//----12 jun 2018----
	Route::post('shopping_budget/add','frontEnd\ServiceUserManagement\MoneyController@add_shopping_bugdet');
	
	//add petty cash for home 
	Route::post('/profile/petty_cash/add-cash','frontEnd\PersonalManagement\PettyCashController@add_petty_cash');
	Route::get('/profile/petty-cash/view/{petty_cash_id}', 'frontEnd\PersonalManagement\PettyCashController@view');
	//Route::post('/profile/petty-cash/edit', 'frontEnd\PersonalManagement\PettyCashController@view');
	Route::match(['get','post'],'/profile/petty_cash/check-balance','frontEnd\PersonalManagement\PettyCashController@get_petty_balance');
	Route::match(['get','post'],'/profile/petty-cashes', 'frontEnd\PersonalManagement\PettyCashController@index');

	// location history
	Route::match(['get','post'], '/service/location-history/{service_user_id}', 'frontEnd\ServiceUserManagement\LocationHistoryController@index');
	Route::post('/service/location-history/location/add', 'frontEnd\ServiceUserManagement\LocationHistoryController@add_location');

    Route::match(['get', 'post'],'/service/location-history/location/edit/{location_history_id}', 'frontEnd\ServiceUserManagement\LocationHistoryController@edit_location');

	Route::get('/service/location-history/location/delete/{location_history_id}', 'frontEnd\ServiceUserManagement\LocationHistoryController@delete_location');
	Route::post('/service/location-history/restriction-type/change', 'frontEnd\ServiceUserManagement\LocationHistoryController@change_location_restriction_type');
	//not
	/*Route::get('/service/location-history/notif/acnowldg/master/{location_history_id}', 'frontEnd\ServiceUserManagement\LocationHistoryController@acknowldg_loc_notif_master');
	Route::get('/service/location-history/notif/acnowldg/personal/{location_history_id}', 'frontEnd\ServiceUserManagement\LocationHistoryController@acknowldg_loc_notif_personal');*/


	//Sick Leave
	Route::match(['get', 'post'], '/my-profile/sick-leaves/view/{manager_id}', 'frontEnd\PersonalManagement\SickLeaveController@index');
	//Route::match(['get', 'post'], '/my-profile/sick-leaves/delete/{sick_leave_id}', 'frontEnd\PersonalManagement\SickLeaveController@delete');
	Route::match(['get', 'post'], '/my-profile/sick-leaves/view-record/{sick_leave_id}', 'frontEnd\PersonalManagement\SickLeaveController@view_sick_record');
	
	//Annual Leave
	Route::match(['get', 'post'], '/my-profile/annual-leaves/view/{manager_id}', 'frontEnd\PersonalManagement\AnnualLeaveController@index');
	Route::match(['get', 'post'], '/my-profile/annual-leaves/view-record/{annual_leave_id}', 'frontEnd\PersonalManagement\AnnualLeaveController@view_annual_record');
	//Task Allocation
	Route::match(['get', 'post'], '/my-profile/task-allocation/view/{manager_id}', 'frontEnd\PersonalManagement\TaskAllocationController@index');

	// -------- Header ------------------------//
	//Dynamic forms
	//Route::match(['get','post'], '/system/plans/', 'frontEnd\SystemManagement\PlanBuilderController@index');
	
	//not
	Route::match(['get', 'post'],'/service/dynamic-forms', 'frontEnd\ServiceUserManagement\DynamicFormController@index');
	
	Route::post('/service/dynamic-form/save', 'frontEnd\ServiceUserManagement\DynamicFormController@save_form');
	Route::post('/service/dynamic-form/edit', 'frontEnd\ServiceUserManagement\DynamicFormController@edit_form');
	Route::post('/service/dynamic-form/view/pattern', 'frontEnd\ServiceUserManagement\DynamicFormController@view_form_pattern');
	Route::post('/service/patterndataformio', 'frontEnd\ServiceUserManagement\DynamicFormController@patterndataformio');
	Route::post('/service/patterndataformiovaule', 'frontEnd\ServiceUserManagement\DynamicFormController@patterndataformiovalue');
	Route::get('/service/dynamic-form/view/data/{dynamic_form_id}', 'frontEnd\ServiceUserManagement\DynamicFormController@view_form_data');
	Route::get('/service/dynamic-form/delete/{dynamic_form_id}', 'frontEnd\ServiceUserManagement\DynamicFormController@delete_form');
	Route::post('/service/dynamic-form/edit-details', 'frontEnd\ServiceUserManagement\DynamicFormController@edit_details');
	Route::post('/service/dynamic-form/daily-log', 'frontEnd\ServiceUserManagement\DynamicFormController@su_daily_log_add');

	// Route::match(['get','post'], '/system/plans/edit', 'frontEnd\SystemManagement\PlanBuilderController@edit');
	// Route::match(['get','post'], '/system/plans/delete/{plan_id}', 'frontEnd\SystemManagement\PlanBuilderController@delete');

	// -------- Service Management ------------------------//

	Route::match(['get','post'], '/service-user-management', 'frontEnd\ServiceUserManagementController@service_users');
	Route::match(['get','post'], '/service/user-profile/{service_user_id}', 'frontEnd\ServiceUserManagement\ProfileController@index');

	Route::get('/service/user/afc-status/{service_user_id}','frontEnd\ServiceUserManagement\ProfileController@get_afc_status');
	
	// status change of su_profile pic
	Route::match(['get','post'],'/service/user-profile/afc-status/update/{service_user_id}', 'frontEnd\ServiceUserManagement\ProfileController@update_afc_status');

	//notifications
	Route::match(['get','post'], '/service/notifications/', 'frontEnd\ServiceUserManagement\ProfileController@show_notifications');

	Route::match(['get','post'], '/system-management', 'frontEnd\SystemManagementController@system_management');
 
	Route::match(['get','post'], '/add-service-user', 'frontEnd\SystemManagementController@add_service_user');
	Route::match(['get','post'], '/add-staff-user', 'frontEnd\SystemManagementController@add_staff_user');
	Route::get('user/qualification/delete/{id}','frontEnd\SystemManagementController@delete_certificate');

	//Daily Record in ServiceUserManagement
	Route::match(['get','post'], '/service/daily-records/{service_user_id}', 'frontEnd\ServiceUserManagement\DailyRecordController@index');
	Route::match(['get','post'], '/service/daily-record/add', 'frontEnd\ServiceUserManagement\DailyRecordController@add');
	Route::match(['get','post'], '/service/daily-record/edit', 'frontEnd\ServiceUserManagement\DailyRecordController@edit');
	Route::match(['get','post'], '/service/daily-record/delete/{su_daily_record_id}', 'frontEnd\ServiceUserManagement\DailyRecordController@delete');
	Route::match(['get','post'], '/service/daily-record/calendar/add/{su_daily_record_id}', 'frontEnd\ServiceUserManagement\DailyRecordController@add_to_calendar');
	/*Route::match(['get','post'], '/service/daily-record/status/{daily_record_id}', 'frontEnd\ServiceUserManagement\DailyRecordController@update_status');*/

	//Daily Logs in ServiceUserManagement
	Route::match(['get','post'], '/service/daily-logs', 'frontEnd\ServiceUserManagement\DailyLogsController@index');

	//Backend Logs Download
	Route::get('/service/logbook/download', 'frontEnd\ServiceUserManagement\PDFLogsController@download');

	//health record
	Route::get('/service/health-records/{service_user_id}', 'frontEnd\ServiceUserManagement\HealthRecordController@index');
	Route::post('/service/health-record/add', 'frontEnd\ServiceUserManagement\HealthRecordController@add');
	Route::post('/service/health-record/edit', 'frontEnd\ServiceUserManagement\HealthRecordController@edit');
	Route::get('/service/health-record/delete/{su_health_record_id}', 'frontEnd\ServiceUserManagement\HealthRecordController@delete');

	//risks
	Route::post('/service/risk/status/change', 'frontEnd\ServiceUserManagement\RiskController@change_risk_status');
	Route::get('/service/risks/{su_id}', 'frontEnd\ServiceUserManagement\RiskController@index');
	Route::get('/service/risk/view/{risk_id}', 'frontEnd\ServiceUserManagement\RiskController@view');
	Route::get('/service/risk/risksfilter', 'frontEnd\ServiceUserManagement\RiskController@risksfilter');
	//risk RMP
	Route::post('/service/risk/rmp/add', 'frontEnd\ServiceUserManagement\RiskController@add_rmp_risk');
	Route::get('/service/risk/rmp/view/{su_risk_id}', 'frontEnd\ServiceUserManagement\RiskController@view_rmp_risk');
	//edit only a single records info
	Route::post('/service/risk/rmp/edit/{su_rmp_id}', 'frontEnd\ServiceUserManagement\RiskController@edit_rmp_risk');
	
	Route::get('/service/risk/inc-rec/view/{su_risk_id}', 'frontEnd\ServiceUserManagement\RiskController@view_inc_rec_risk');
	//edit multiple records at a time - details, review etc. 

	//risk Incident Report
	Route::post('/service/risk/inc-rep/add', 'frontEnd\ServiceUserManagement\RiskController@add_inc_rep');
	Route::post('/service/risk/inc-rep/edit', 'frontEnd\ServiceUserManagement\RiskController@edit_inc_rep');

	//File Manager
	Route::match(['get','post'], '/service/file-managers/{service_user_id}', 'frontEnd\ServiceUserManagement\FileController@index');
	Route::match(['get','post'], '/service/file-manager/add', 'frontEnd\ServiceUserManagement\FileController@add_files');
	Route::get('/service/file-manager/delete/{file_id}', 'frontEnd\ServiceUserManagement\FileController@delete');
	Route::post('/service/file-manager/upload/add', 'frontEnd\ServiceUserManagement\FileController@add_file');
	Route::post('/service/file-manager/email', 'frontEnd\ServiceUserManagement\FileController@file_email');

	//care team serviceUserManagement
	Route::match(['get','post'], '/service/care_team/add/{service_user_id}', 'frontEnd\ServiceUserManagement\ProfileController@add_care_team');
	Route::match(['get','post'], '/service/care_team/edit', 'frontEnd\ServiceUserManagement\ProfileController@edit_care_team');
	Route::match(['get','post'], '/service/care_team/delete/{care_team_id}', 'frontEnd\ServiceUserManagement\ProfileController@delete_care_team');

	//careHistory serviceUserManagement
	Route::match(['get','post'], '/service/care_history/add/{service_user_id}', 'frontEnd\ServiceUserManagement\ProfileController@add_care_history');
	Route::match(['get','post'], '/service/care_history/edit', 'frontEnd\ServiceUserManagement\ProfileController@edit_care_history');
	Route::match(['get','post'], '/service/care_history/delete/{care_history_id}', 'frontEnd\ServiceUserManagement\ProfileController@delete_care_history');
	
	Route::match(['get','post'], '/service/care-history/delete-file/{su_care_history_id}', 'frontEnd\ServiceUserManagement\ProfileController@delete_hist_file');
	
	//location info
	Route::post('/service/user/edit-location-details', 'frontEnd\ServiceUserManagement\ProfileController@edit_location_info');
	Route::post('/service/user/edit-contact-details', 'frontEnd\ServiceUserManagement\ProfileController@edit_contact_info');
	
	//contact_us
	Route::post('/service/user/contact-person/edit', 'frontEnd\ServiceUserManagement\ProfileController@edit_contact_person');
	Route::get('/service/user/contact-person/delete/{contact_us_id}', 'frontEnd\ServiceUserManagement\ProfileController@delete_contact_person');

	//earning scheme	
	Route::match(['get','post'], '/service/earning-scheme/{service_user_id}', 'frontEnd\ServiceUserManagement\EarningSchemeController@index');
	Route::match(['get','post'], '/service/earning-scheme/view_incentive/{earning_category_id}', 'frontEnd\ServiceUserManagement\EarningSchemeController@view_incentive');
	Route::match(['get','post'], '/service/earning-scheme/incentive/add', 'frontEnd\ServiceUserManagement\EarningSchemeController@add_to_calendar');
	Route::post('/service/earning-scheme/star/remove/{service_user_id}', 'frontEnd\ServiceUserManagement\EarningSchemeController@remove_star');
	
	Route::post('/service/earning/set-target', 'frontEnd\ServiceUserManagement\EarningSchemeController@set_su_earning_target');

	//suspend incentive
	Route::post('/service/earning-scheme/incentive/suspend', 'frontEnd\ServiceUserManagement\EarningSchemeController@incentive_suspend');
	Route::get('/service/earning-scheme/incentive/suspend/view/{suspended_id}', 'frontEnd\ServiceUserManagement\EarningSchemeController@view_suspension');
	/*Route::post('/service/earning-scheme/incentive/suspend/edit', 'frontEnd\ServiceUserManagement\EarningSchemeController@edit_suspension');*/
	Route::get('/service/earning-scheme/incentive/suspend/delete', 'frontEnd\ServiceUserManagement\EarningSchemeController@remove_suspension');

	//Living Skill in ServiceUserManagement
	Route::match(['get', 'post'], '/service/living-skills/{service_user_id}', 'frontEnd\ServiceUserManagement\LivingSkillController@index');
	Route::match(['get','post'], '/service/living-skill/add', 'frontEnd\ServiceUserManagement\LivingSkillController@add');
	Route::match(['get','post'], '/service/living-skill/edit', 'frontEnd\ServiceUserManagement\LivingSkillController@edit');
	Route::match(['get','post'], '/service/living-skill/delete/{su_living_skill_id}', 'frontEnd\ServiceUserManagement\LivingSkillController@delete');
	Route::match(['get','post'], '/service/living-skill/calendar/add/{su_living_skill_id}', 'frontEnd\ServiceUserManagement\LivingSkillController@add_to_calendar');
	
	//Education Record in ServiceUserManagement
	Route::match(['get','post'], '/service/education-records/{service_user_id}', 'frontEnd\ServiceUserManagement\EducationRecordController@index');
	Route::match(['get','post'], '/service/education-record/add', 'frontEnd\ServiceUserManagement\EducationRecordController@add');
	Route::match(['get','post'], '/service/education-record/edit', 'frontEnd\ServiceUserManagement\EducationRecordController@edit');
	Route::match(['get','post'], '/service/education-record/delete/{su_edu_record_id}', 'frontEnd\ServiceUserManagement\EducationRecordController@delete');
	Route::match(['get','post'], '/service/education-record/calendar/add/{su_edu_record_id}', 'frontEnd\ServiceUserManagement\EducationRecordController@add_to_calendar');

	//MFC Records in ServiceUserManagement
	Route::match(['get','post'], '/service/mfc-records/{service_user_id}', 'frontEnd\ServiceUserManagement\MFCController@index');
	Route::get('/service/mfc/view/{su_mfc_id}', 'frontEnd\ServiceUserManagement\MFCController@view_mfc_rcrd');
	Route::post('/service/mfc/add', 'frontEnd\ServiceUserManagement\MFCController@add');
	Route::match(['get', 'post'], '/service/mfc/edit/{su_mfc_id}', 'frontEnd\ServiceUserManagement\MFCController@edit');
	Route::match(['get','post'], '/service/mfc/delete/{su_mfc_id}', 'frontEnd\ServiceUserManagement\MFCController@delete');

	//BMP_RMP in  Daily Record ServiceUserManagement
	Route::match(['get','post'], '/service/bmp-rmps/{service_user_id}', 'frontEnd\ServiceUserManagement\BmpRmpController@index'); 
	Route::post('/service/bmp-rmp/add', 'frontEnd\ServiceUserManagement\BmpRmpController@add'); 
	Route::post('/service/bmp-rmp/edit', 'frontEnd\ServiceUserManagement\BmpRmpController@edit');
	Route::get('/service/bmp-rmp/delete/{bmp_rmp_id}', 'frontEnd\ServiceUserManagement\BmpRmpController@delete');

	Route::match(['get','post'], '/service/bmp-rmp/view/{bmp_rmp_risk_id}', 'frontEnd\ServiceUserManagement\BmpRmpController@view_detail');

	Route::post('/service/user/edit-details', 'frontEnd\ServiceUserManagement\ProfileController@edit_detail_info');
	/*Route::match(['get','post'], '/service/daily-records-bmp-rmp/{service_user_id}', 'frontEnd\ServiceUserManagement\BmpRmpController@service_users_list');*/ 

    //Body Map

	Route::match(['get','post'],'/service/body-map/{risk_id}','frontEnd\ServiceUserManagement\BodyMapController@index');
	Route::match(['get','post'],'/service/body-map/injury/add','frontEnd\ServiceUserManagement\BodyMapController@addInjury');
	Route::match(['get','post'],'/service/body-map/injury/remove/{service_user_id}','frontEnd\ServiceUserManagement\BodyMapController@removeInjury');

	//calender paths
	Route::match(['get','post'], '/service/calendar/{service_user_id}', 'frontEnd\CalendarController@index');
	Route::match(['get','post'], '/service/calendar/daily-records/{serv_usr_id}', 'frontEnd\CalendarController@daily_records');
	Route::match(['get','post'], '/service/calendar/health-records/{serv_usr_id}', 'frontEnd\CalendarController@health_records');
	Route::match(['get','post'], '/service/calendar/daily-records/delete/{daily_record_id}', 'frontEnd\CalendarController@delete_daily_record');

	//calendar drag & drop events
	Route::match(['get','post'],'/service/calendar/event/add', 'frontEnd\CalendarController@add_event');
	Route::match(['get','post'], '/service/calendar/event/move', 'frontEnd\CalendarController@move_event');
	

	//calendar entries
	Route::get('/service/calendar/entry/display-form/{plan_bulider_id}', 'frontEnd\CalendarEntryController@display_form');
	Route::post('/service/calendar/entry/add', 'frontEnd\CalendarEntryController@add');
	
	// calendar add notes
	Route::post('/service/calendar/note/add', 'frontEnd\CalendarEntryController@add_note');

	// calendar view event details
	Route::match(['get','post'], '/service/calendar/event/view', 'frontEnd\CalendarEventController@index');
	Route::match(['get','post'], '/service/calendar/event/edit', 'frontEnd\CalendarEventController@edit');
	Route::match(['get','post'], '/service/calendar/event/remove/{calendar_id}', 'frontEnd\CalendarEventController@delete');

    //Weekly and Monthly Report
    Route::match(['get','post'], '/select/report', 'frontEnd\ServiceUserManagement\ReportController@index');
    Route::match(['get','post'], '/monthly/report/detail/{log_book_id}', 'frontEnd\ServiceUserManagement\ReportController@monthly_report_detail');
    Route::match(['get','post'], '/edit/report/detail', 'frontEnd\ServiceUserManagement\ReportController@edit_report_detail');
    Route::match(['get','post'], '/send/mail/careteam/{log_book_id}', 'frontEnd\ServiceUserManagement\ReportController@send_mail_to_careteam');

	//EventChange Request
	Route::get('/service/event-requests/{service_user_id}', 'frontEnd\ServiceUserManagement\EventRequestController@index');
	Route::get('/service/event-request/{req_id}', 'frontEnd\ServiceUserManagement\EventRequestController@view');
	Route::post('/service/event-request/update', 'frontEnd\ServiceUserManagement\EventRequestController@update');

	//placement plan - service usr mngment
	Route::match(['get','post'], '/service/placement-plans/{su_id}', 'frontEnd\ServiceUserManagement\PlacementPlanController@index');
	Route::match(['get','post'], '/service/placement-plan/add', 'frontEnd\ServiceUserManagement\PlacementPlanController@add');
	Route::match(['get','post'], '/service/placement-plan/completed-targets/{su_id}', 'frontEnd\ServiceUserManagement\PlacementPlanController@completed_targets');
	Route::match(['get','post'], '/service/placement-plan/active-targets/{su_id}', 'frontEnd\ServiceUserManagement\PlacementPlanController@active_targets');
	Route::match(['get','post'], '/service/placement-plan/pending-targets/{su_id}', 'frontEnd\ServiceUserManagement\PlacementPlanController@pending_targets');
	Route::match(['get','post'], '/service/placement-plan/mark-complete/{target_id}', 'frontEnd\ServiceUserManagement\PlacementPlanController@mark_complete');
	Route::match(['get','post'], '/service/placement-plan/mark-active/{target_id}', 'frontEnd\ServiceUserManagement\PlacementPlanController@mark_active');

	Route::match(['get','post'], '/service/placement-plan/target/view/{act_tar_id}', 'frontEnd\ServiceUserManagement\PlacementPlanController@view_target');
	Route::post('/service/placement-plan/edit', 'frontEnd\ServiceUserManagement\PlacementPlanController@edit');
	
	Route::post('/service/placement-plan/add-qqa-review', 'frontEnd\ServiceUserManagement\PlacementPlanController@add_qqa_review');

	//RMP
	Route::match(['get','post'],'/service/rmp/view/{service_user_id}', 'frontEnd\ServiceUserManagement\RmpController@index');
	Route::get('/service/rmp/view_rmp/{su_rmp_id}', 'frontEnd\ServiceUserManagement\RmpController@view_rmp');
	Route::post('/service/rmp/add', 'frontEnd\ServiceUserManagement\RmpController@add_rmp');
	Route::match(['get','post'],'/service/rmp/edit_rmp/{su_rmp_id}', 'frontEnd\ServiceUserManagement\RmpController@edit_rmp');
	Route::get('/service/rmp/delete/{su_rmp_id}', 'frontEnd\ServiceUserManagement\RmpController@delete');
	Route::match(['get','post'], '/service/rmp/edit', 'frontEnd\ServiceUserManagement\RmpController@edit');

	//BMP
	Route::match(['get','post'], '/service/bmp/view/{service_user_id}', 'frontEnd\ServiceUserManagement\BmpController@index');
	Route::post('/service/bmp/add', 'frontEnd\ServiceUserManagement\BmpController@add_bmp');
	Route::match(['get','post'],'/service/bmp/edit', 'frontEnd\ServiceUserManagement\BmpController@edit');
	Route::get('/service/bmp/delete/{su_bmp_id}', 'frontEnd\ServiceUserManagement\BmpController@delete');
	Route::get('/service/bmp/view_bmp/{su_bmp_id}', 'frontEnd\ServiceUserManagement\BmpController@view_bmp');
	Route::post('/service/rmp/edit_bmp/{su_bmp_id}', 'frontEnd\ServiceUserManagement\BmpController@edit_bmp');

	//IncidentReport
	Route::match(['get','post'], '/service/incident-report/views/{service_user_id}', 'frontEnd\ServiceUserManagement\IncidentController@index');
	Route::get('/service/incident-report/view_incident/{su_incident_id}', 'frontEnd\ServiceUserManagement\IncidentController@view_incident');
	Route::post('/service/incident-report/add', 'frontEnd\ServiceUserManagement\IncidentController@add_incident');
	Route::post('/service/incident-report/edit_incident/{su_incident_id}', 'frontEnd\ServiceUserManagement\IncidentController@edit_incident');
	Route::get('/service/incident-report/delete/{su_incident_id}', 'frontEnd\ServiceUserManagement\IncidentController@delete');

	//ServiceUser LogBook
	Route::match(['get','post'], '/service/logsbook/{service_user_id}', 'frontEnd\ServiceUserManagement\LogBookController@index');
	Route::post('/service/logbook/add', 'frontEnd\ServiceUserManagement\LogBookController@add');
	Route::get('/service/logbook/view/{log_book_id}', 'frontEnd\ServiceUserManagement\LogBookController@view');

	/**
	 * BaseUrl: 
	 */
	$LogBookCommentsController = 'frontEnd\ServiceUserManagement\LogBookComments\LogBookCommentsController@';
	Route::get('/service/logbook/comments', $LogBookCommentsController.'index');
	Route::post('/service/logbook/comments', $LogBookCommentsController.'store');

	// Route::get('/service/logbook/view/{service_user_id}/{log_book_id}', 'frontEnd\ServiceUserManagement\LogBookController@view');
	Route::get('/service/logbook/Calendar/add', 'frontEnd\ServiceUserManagement\LogBookController@add_to_calendar');

	//handover point
	Route::get('/staff-user-list', 'frontEnd\ServiceUserManagement\LogBookController@staffuserlist');
	Route::post('/handover/service/log', 'frontEnd\ServiceUserManagement\LogBookController@log_handover_to_staff_user');
	Route::match(['get','post'], '/handover/daily/log', 'frontEnd\HandoverController@index');
	Route::match(['get','post'], '/handover/daily/log/edit', 'frontEnd\HandoverController@handover_log_edit');

    //add to weekly
    Route::match(['get','post'], '/weekly/report/{log_id}', 'frontEnd\ServiceUserManagement\LogBookController@weekly_report');
    Route::match(['get','post'], '/weekly/rprt/edit/{service_user_id}', 'frontEnd\ServiceUserManagement\LogBookController@weekly_report_edit');
    
	// su Moods listing
	Route::get('/service/moods/{service_user_id}', 'frontEnd\ServiceUserManagement\MoodController@index');
	Route::post('/service/mood/suggestion/add', 'frontEnd\ServiceUserManagement\MoodController@add');

	// my money requests of user
	Route::get('service/money-requests/{service_user_id}','frontEnd\ServiceUserManagement\MoneyController@index');
	Route::get('service/money-request/{money_request_id}','frontEnd\ServiceUserManagement\MoneyController@view_detail');
	Route::post('service/money-request/update','frontEnd\ServiceUserManagement\MoneyController@update');	

    Route::match(['get','post'],'notif/response','frontEnd\ServiceUserManagementController@notif_response');


	// -------- System Management ------------------------//
    //-------------shalinder----------------------------------------------------------------------//
    Route::match(['get','post'], '/system/earning-scheme/tasks/{label_id}','frontEnd\SystemManagement\EarningSchemeTaskController@index');
    Route::match(['get','post'],'/system/earning-scheme/task/add/{label_id}','frontEnd\SystemManagement\EarningSchemeTaskController@add');
    Route::match(['get','post'], '/system/earning-scheme/task/edit/{label_id}', 'frontEnd\SystemManagement\EarningSchemeTaskController@edit');
    Route::match(['get','post'],'/system/earning-scheme/task/delete/{daily_record_id}', 'frontEnd\SystemManagement\EarningSchemeTaskController@delete');
    Route::match(['get','post'],'/system/earning-scheme/task/status/{daily_record_id}', 'frontEnd\SystemManagement\EarningSchemeTaskController@update_status');
    Route::match(['get','post'],'/system/earning-scheme/del-daily-records', 'frontEnd\SystemManagement\EarningSchemeTaskController@delete_daily_records');
    //---------------------------------------------------------------------------------------------//

	//Daily Records in SystemManagement
	Route::match(['get','post'], '/system/daily-records/', 'frontEnd\SystemManagement\DailyRecordController@index');
	Route::match(['get','post'], '/system/daily-records/add', 'frontEnd\SystemManagement\DailyRecordController@add');
	Route::match(['get','post'], '/system/daily-records/delete/{daily_record_id}', 'frontEnd\SystemManagement\DailyRecordController@delete');
	Route::match(['get','post'], '/system/daily-records/status/{daily_record_id}', 'frontEnd\SystemManagement\DailyRecordController@update_status');
	Route::match(['get','post'], '/system/daily-records/edit', 'frontEnd\SystemManagement\DailyRecordController@edit');
	Route::match(['get','post'], '/system/del-daily_records', 'frontEnd\SystemManagement\DailyRecordController@delete_daily_records');
	// Daily Records in SystemManagement end
	
	//MFC in SystemManagement May19
	Route::match(['get','post'], '/system/mfc/', 'frontEnd\SystemManagement\MFCController@index');
	Route::match(['get','post'], '/system/mfc/add', 'frontEnd\SystemManagement\MFCController@add');
	Route::match(['get','post'], '/system/mfc/edit', 'frontEnd\SystemManagement\MFCController@edit');
	Route::match(['get','post'], '/system/mfc/delete/{mfc_id}', 'frontEnd\SystemManagement\MFCController@delete');
	Route::match(['get','post'], '/system/mfc/status/{mfc_id}', 'frontEnd\SystemManagement\MFCController@update_status');

	//Risk Controller in SystemManagement
	Route::match(['get','post'], '/system/risk/add', 'frontEnd\SystemManagement\RiskController@add');
	Route::match(['get','post'], '/system/risk/index', 'frontEnd\SystemManagement\RiskController@index');
	Route::match(['get','post'], '/system/risk/delete/{risk_id}', 'frontEnd\SystemManagement\RiskController@delete');
	Route::match(['get','post'], '/system/risk/status/{risk_id}', 'frontEnd\SystemManagement\RiskController@update_status');
	Route::match(['get','post'], '/system/risk/edit', 'frontEnd\SystemManagement\RiskController@edit');
	Route::match(['get','post'], '/system/del-risk', 'frontEnd\SystemManagement\RiskController@risk_delete');
	//Risk Controller in SystemManagement End

	//Earning Scheme in SystemManagement
	Route::match(['get','post'], '/system/earning/index', 'frontEnd\SystemManagement\EarningSchemeController@index');
	Route::match(['get','post'], '/system/earning/add', 'frontEnd\SystemManagement\EarningSchemeController@add');
	Route::match(['get','post'], '/system/earning/edit', 'frontEnd\SystemManagement\EarningSchemeController@edit');
	Route::match(['get','post'], '/system/earning/delete/{earn_id}', 'frontEnd\SystemManagement\EarningSchemeController@delete');
	Route::match(['get','post'], '/system/earning/status/{earn_id}', 'frontEnd\SystemManagement\EarningSchemeController@update_status');
	//Multidelete earning schemes (Not Use)
	Route::match(['get','post'], '/system/del-earn', 'frontEnd\SystemManagement\EarningSchemeController@earning_scheme_delete');
	Route::match(['get','post'], '/system/earning/add_incentive', 'frontEnd\SystemManagement\EarningSchemeController@add_incentive');
	Route::match(['get','post'], '/system/earning/delete_incentive/{incentive_id}', 'frontEnd\SystemManagement\EarningSchemeController@delete_incentive');
	Route::match(['get','post'], '/system/earning/update_incentive_status/{incentive_id}', 'frontEnd\SystemManagement\EarningSchemeController@update_incentive_status');
	
	//Incentive View in Earning Scheme
	Route::match(['get','post'], '/system/earning/view_incentive/{incentive_id}', 'frontEnd\SystemManagement\EarningSchemeController@view_incentive');
	Route::match(['get','post'], '/system/earning/edit_incentive', 'frontEnd\SystemManagement\EarningSchemeController@edit_incentive');

	//Health Records in SystemManagement
	Route::match(['get','post'], '/system/health-records/', 'frontEnd\SystemManagement\HealthRecordController@index');
	/*Route::match(['get','post'], '/system/health-records/pagination', 'frontEnd\SystemManagement\HealthRecordController@pagination');*/

	//SupportTicket in SystemManagement
	Route::match(['get','post'], '/system/support-ticket', 'frontEnd\SystemManagement\SupportTicketController@index');
	Route::match(['get','post'], '/system/support-ticket/add', 'frontEnd\SystemManagement\SupportTicketController@add');
	Route::match(['get','post'], '/system/support-ticket/view/{ticket_id}', 'frontEnd\SystemManagement\SupportTicketController@view_ticket');
	Route::match(['get','post'], '/system/support-ticket/view-msg/add', 'frontEnd\SystemManagement\SupportTicketController@add_ticket_mesg');
	Route::match(['get','post'], '/system/support-ticket/ticket_status/{support_id}', 'frontEnd\SystemManagement\SupportTicketController@ticket_status');
	
	//Appointments / Plans in SystemManagement
	Route::match(['get','post'], '/system/plans/', 'frontEnd\SystemManagement\PlanBuilderController@index');
	Route::match(['get','post'], '/system/plans/add', 'frontEnd\SystemManagement\PlanBuilderController@add');
	Route::match(['get','post'], '/system/plans/view/{plan_builder_id}', 'frontEnd\SystemManagement\PlanBuilderController@view');
	Route::match(['get','post'], '/system/plans/edit', 'frontEnd\SystemManagement\PlanBuilderController@edit');
	Route::match(['get','post'], '/system/plans/delete/{plan_id}', 'frontEnd\SystemManagement\PlanBuilderController@delete');
	Route::match(['get','post'], '/system/del/plans', 'frontEnd\SystemManagement\PlanBuilderController@delete_plan');

	//Calendar in SystemManagement
		Route::match(['get','post'], '/system/calendar', 'frontEnd\SystemManagement\CalendarController@index');

		//calendar drag & drop events
		Route::match(['get','post'],'/system/calendar/event/add', 'frontEnd\SystemManagement\CalendarController@add_event');
		Route::match(['get','post'], '/system/calendar/event/move', 'frontEnd\SystemManagement\CalendarController@move_event');

		//calendar entries
		Route::get('/system/calendar/entry/display-form/{plan_bulider_id}', 'frontEnd\CalendarEntryController@display_form');
		Route::post('/system/calendar/entry/add', 'frontEnd\CalendarEntryController@add');

		// calendar add notes
		Route::post('/system/calendar/note/add', 'frontEnd\CalendarEntryController@add_note');

		// calendar view event details
		Route::match(['get','post'], '/system/calendar/event/view', 'frontEnd\CalendarEventController@index');
		Route::match(['get','post'], '/system/calendar/event/edit', 'frontEnd\CalendarEventController@edit');
		Route::match(['get','post'], '/system/calendar/event/remove/{calendar_id}', 'frontEnd\CalendarEventController@delete');
		Route::post('/system/calendar/select/member', 'frontEnd\SystemManagement\CalendarController@select_member');
	
		//Living Skills in SystemManagement
		Route::match(['get','post'], '/system/living-skills/', 'frontEnd\SystemManagement\LivingSkillController@index');
		Route::match(['get','post'], '/system/living-skill/add', 'frontEnd\SystemManagement\LivingSkillController@add');
		Route::match(['get','post'], '/system/living-skill/edit', 'frontEnd\SystemManagement\LivingSkillController@edit');
		Route::match(['get','post'], '/system/living-skill/delete/{living_skill_id}', 'frontEnd\SystemManagement\LivingSkillController@delete');
		Route::match(['get','post'], '/system/living-skill/status/{living_skill_id}', 'frontEnd\SystemManagement\LivingSkillController@update_status');
		Route::match(['get','post'], '/system/del/living-skill/', 'frontEnd\SystemManagement\LivingSkillController@living_skill_delete');
		//Living Skills in SystemManagement end
		
		//Education Training in SystemManagement 
		Route::match(['get','post'], '/system/education-records/', 'frontEnd\SystemManagement\EducationRecordController@index');
		Route::match(['get','post'], '/system/education-record/add', 'frontEnd\SystemManagement\EducationRecordController@add');
		Route::match(['get','post'], '/system/education-record/edit', 'frontEnd\SystemManagement\EducationRecordController@edit');
		Route::match(['get','post'], '/system/education-record/delete/{edu_rec_id}', 'frontEnd\SystemManagement\EducationRecordController@delete');
		Route::match(['get','post'], '/system/education-record/status/{edu_rec_id}', 'frontEnd\SystemManagement\EducationRecordController@update_status');
		Route::match(['get','post'], '/system/del/education-record', 'frontEnd\SystemManagement\EducationRecordController@edu_record_delete');
		//Education Training in SystemManagement May15 end

	//if user is not autorized to anything then send request to admin
	Route::match('post', '/send-modify-request', 'frontEnd\DashboardController@send_modify_request');

	//Route::match(['post','get'], '/bug-report', 'Controller@bug_report');
	Route::match(['post','get'], '/bug-report', 'frontEnd\BugReportController@index');
	Route::match(['post','get'], '/bug-report/add', 'frontEnd\BugReportController@add');

	// -------- Staff Management ------------------------//

    Route::match(['get', 'post'], '/staff-management', 'frontEnd\StaffManagementController@staff_member');
    Route::match(['get', 'post'], '/staff/profile/{staff_id}', 'frontEnd\StaffManagement\ProfileController@index');
    Route::match(['get', 'post'], '/staff/member/edit-settings', 'frontEnd\StaffManagement\ProfileController@edit_staff_setting');

	/*Route::post('/staff/member/edit-profile', 'frontEnd\StaffManagement\ProfileController@edit_staff_detail_info');
    Route::post('/staff/member/edit-location', 'frontEnd\StaffManagement\ProfileController@edit_staff_location_info');
    Route::post('/staff/member/edit-contact', 'frontEnd\StaffManagement\ProfileController@edit_staff_contact_info');*/
    
	//TaskAllocation
	Route::match(['get', 'post'], '/staff/member/task-allocation/add', 'frontEnd\StaffManagement\TaskAllocationController@add');
	Route::match(['get', 'post'], '/staff/member/task-allocation/view/{staff_member_id}', 'frontEnd\StaffManagement\TaskAllocationController@index');
	Route::match(['get','post'], '/staff/member/task-allocation/delete/{task_id}', 'frontEnd\StaffManagement\TaskAllocationController@delete');
	Route::match(['get', 'post'], '/staff/member/task-allocation/edit', 'frontEnd\StaffManagement\TaskAllocationController@edit');
	Route::match(['get','post'], '/staff/member/task-allocation/status-update/{task_id}', 'frontEnd\StaffManagement\TaskAllocationController@update_status');

	//Manage SickLeave
	Route::match(['get','post'], '/staff/member/sick-leave/view/{staff_member_id}', 'frontEnd\StaffManagement\SickLeaveController@index');
	Route::match(['get','post'], '/staff/member/sick-leave/view-record/{sick_leave_id}', 'frontEnd\StaffManagement\SickLeaveController@view_sick_record');
	Route::post('/staff/member/sick-leave/add', 'frontEnd\StaffManagement\SickLeaveController@add');
	Route::post('/staff/member/sick-leave/edit', 'frontEnd\StaffManagement\SickLeaveController@edit');	
	Route::get('/staff/member/sick-leave/delete/{sick_leave_id}', 'frontEnd\StaffManagement\SickLeaveController@delete');

	//Manage AnnualLeave
	Route::match(['get', 'post'], '/staff/annual-leaves/{staff_member_id}','frontEnd\StaffManagement\AnnualLeaveController@index');
	Route::match(['get','post'], '/staff/annual-leave/view-annual/{annual_leave_id}', 'frontEnd\StaffManagement\AnnualLeaveController@view_annual_record');
	Route::post('/staff/annual-leave/add', 'frontEnd\StaffManagement\AnnualLeaveController@add');
	Route::post('/staff/annual-leave/edit', 'frontEnd\StaffManagement\AnnualLeaveController@edit');
	Route::get('/staff/annual-leave/delete/{annual_leave_id}', 'frontEnd\StaffManagement\AnnualLeaveController@delete');

	//Staff Rota
	Route::match(['get','post'], '/staff/rota/view', 'frontEnd\StaffManagement\RotaController@index');
	Route::post('/staff/rota/add-shift', 'frontEnd\StaffManagement\RotaController@add_shift');
	Route::get('/staff/rota/delete-shift/{rota_id}', 'frontEnd\StaffManagement\RotaController@delete');
	Route::post('/staff/rota/add-rota', 'frontEnd\StaffManagement\RotaController@add_rota');

	Route::get('/staff/rota/shift/view/{rota_id}', 'frontEnd\StaffManagement\RotaController@view_rota');
	Route::post('/staff/rota/shift/edit', 'frontEnd\StaffManagement\RotaController@edit_shift');
	
	Route::get('/staff/rota/print', 'frontEnd\StaffManagement\RotaController@print_rota');
	Route::get('/staff/rota/copy', 'frontEnd\StaffManagement\RotaController@copy_rota');

	/*------- staff Training ------- */
	Route::get('/staff/trainings', 'frontEnd\StaffManagement\TrainingController@index');
	Route::post('/staff/training/add','frontEnd\StaffManagement\TrainingController@add');
	Route::get('/staff/training/view/{id}','frontEnd\StaffManagement\TrainingController@view');
	Route::get('/staff/training/completed/view/{id}','frontEnd\StaffManagement\TrainingController@completed_training');
	Route::get('/staff/training/not-completed/view/{id}','frontEnd\StaffManagement\TrainingController@not_completed_training');
	Route::get('/staff/training/active/view/{id}','frontEnd\StaffManagement\TrainingController@active_training');
	Route::get('/staff/training/status/update/{training_id}','frontEnd\StaffManagement\TrainingController@status_update');
	//Route::get('/staff/training/status/update/{training_id}/{status}','frontEnd\StaffManagement\TrainingController@status_update');
	Route::post('/staff/training/staff/add','frontEnd\StaffManagement\TrainingController@add_user_training');
	Route::get('/staff/training/view_fields/{traini_id}','frontEnd\StaffManagement\TrainingController@view_fields');
	Route::post('/staff/training/edit_fields','frontEnd\StaffManagement\TrainingController@edit_fields');
	Route::get('/staff/training/delete/{training_id}', 'frontEnd\StaffManagement\TrainingController@delete');

	// -------- general admin ------------------------//
	Route::match(['get','post'], '/general-admin', 'frontEnd\GeneralAdminController@index');

	//LogBook
	Route::match(['get', 'post'], '/general/logsbook', 'frontEnd\GeneralAdmin\LogBookController@index');
	Route::match(['get', 'post'], '/general/logbook/add', 'frontEnd\GeneralAdmin\LogBookController@add');
	Route::get('/service-user-list', 'frontEnd\GeneralAdmin\LogBookController@serviceuserlist');
	Route::post('/service-user-add-log', 'frontEnd\GeneralAdmin\LogBookController@service_user_add_log');
	Route::match(['get','post'],'/general/logbook/calendar/add', 'frontEnd\GeneralAdmin\LogBookController@add_to_calendar');
	
		
	//PettyCash Report
	Route::match(['get', 'post'], '/general/petty-cashes', 'frontEnd\GeneralAdmin\PettyCashController@index');
	Route::get('/general/petty-cash/view/{petty_cash_id}', 'frontEnd\GeneralAdmin\PettyCashController@view');
	Route::post('/general/petty-cash/add', 'frontEnd\GeneralAdmin\PettyCashController@add');
	Route::post('/general/petty-cash/edit', 'frontEnd\GeneralAdmin\PettyCashController@edit');
	Route::match(['get','post'],'/general/petty_cash/check-balance','frontEnd\GeneralAdmin\PettyCashController@get_petty_balance');

	//Policies & Procedures
	Route::match(['get','post'], '/policies', 'frontEnd\PoliciesController@index');
	Route::match(['get','post'], '/policies/add-multiple', 'frontEnd\PoliciesController@add_multiple');
	Route::post('/policies/add-single', 'frontEnd\PoliciesController@add_single');
	Route::get('/policies/delete/{policy_id}', 'frontEnd\PoliciesController@delete'); //delete new added
	Route::get('/policy/delete/{policy_id}','frontEnd\PoliciesController@delete_policy'); //delete from logged
	Route::get('/policy/accept/{policy_id}','frontEnd\PoliciesController@accept_policy');
	Route::post('/policy/update', 'frontEnd\PoliciesController@update_policy');

	//AgendaMeetingController
	Route::match(['get','post'], '/staff/meetings', 'frontEnd\GeneralAdmin\AgendaMeetingController@index');
	Route::get('/staff/meeting/view/{meeting_id}', 'frontEnd\GeneralAdmin\AgendaMeetingController@view');
	Route::post('staff/meeting/add', 'frontEnd\GeneralAdmin\AgendaMeetingController@add');
	Route::post('staff/meeting/edit', 'frontEnd\GeneralAdmin\AgendaMeetingController@edit');
	Route::get('staff/meeting/delete/{meeting_id}', 'frontEnd\GeneralAdmin\AgendaMeetingController@delete');

	//------------- View Reports ---------------//
	Route::match(['get','post'], '/view-reports', 'frontEnd\ViewReportController@index');
    // 	Route::get('/users/{user_type_id}', 'frontEnd\ViewReportController@get_user');
    Route::get('/users', 'frontEnd\ViewReportController@get_user');
	Route::match(['get','post'], '/user/record','frontEnd\ViewReportController@record');
	
});
	
	// System guide 
	Route::get('/system-guide/faq/view/{guide_tag}','frontEnd\SystemGuideController@index');
	Route::get('/system-guide/category/logged','frontEnd\SystemGuideController@category_logged');
	Route::get('/system-guide/search/{ques}','frontEnd\SystemGuideController@searched_ques');

	//Routes that does not need permissions - Auth and access

	// Frontend unique SU username
	// Route::match(['get','post'], 'user/check-su-username-exists', 'frontEnd\UserController@check_su_username_exists');
	// Route::match(['get','post'], 'staff/check-staff-username-exists', 'frontEnd\UserController@check_staff_username_exists');
	
	Route::match(['get','post'], '/check-username-exists', 'frontEnd\UserController@check_username_exists');

	
	Route::match('get', '/set-password/{user_id}/{security_code}', 'frontEnd\UserController@show_set_password_form');
	Route::match('post', 'users/set-password', 'frontEnd\UserController@set_password');

	Route::get('get-homes/{company_name}', 'frontEnd\UserController@get_homes');

	//lockscreen (not required to saved in db)
	Route::get('lock', 'frontEnd\LockAccountController@lock');
	Route::get('lockscreen', 'frontEnd\LockAccountController@lockscreen');
	Route::post('lockscreen', 'frontEnd\LockAccountController@unlock');

    //care center message module (not saved)
	Route::get('/service/care-center/message-office/{service_user_id}','frontEnd\ServiceUserManagement\CareCenterController@office_messages');
	Route::post('/service/care-center/message-office/add','frontEnd\ServiceUserManagement\CareCenterController@add_office_message');
	Route::get('/service/care-center/message/need_assistance/{service_user_id}','frontEnd\ServiceUserManagement\CareCenterController@need_assistance_messages');
	
	Route::match(['get','post'], '/service/care-center/req-callback/{service_user_id}', 'frontEnd\ServiceUserManagement\CareCenterController@request_callback');
	Route::match(['get','post'], '/service/care-center/in-danger/{service_user_id}', 'frontEnd\ServiceUserManagement\CareCenterController@in_danger');


	//sticky notification
	//not
	Route::get('/notification/ack/master/{notification_id}','frontEnd\StickyNotificationController@ack_master');
	Route::get('/notification/ack/indiv/{notification_id}','frontEnd\StickyNotificationController@ack_individual');

	/*-------In Danger -------*/

	/*Route::get('/care-center/notification/update/{care_center_id}','frontEnd\ServiceUserCareCenterController@update_view_status');*/
	
	//Cron jobs
	//Route::get('cron/weekly-allowance/add','CronController@add_weekly_allowance');
	Route::get('cron/every-day','CronController@every_day');
	Route::get('cron/every-week','CronController@every_week');
    Route::get('cron/every-minute','CronController@every_minute');
	Route::get('user/payments','CronController@recurring_home_package_fee');


	//Route::get('cron-jobs','CronController@index');


//________________BACKEND_ROUTES_START___________________________________________________//

Route::match(['get','post'],'admin/login', 'backEnd\AdminController@login');
Route::match(['get','post'], 'admin/logout', 'backEnd\AdminController@logout');
Route::match(['get','post'], 'admin/check-email-exists', 'backEnd\ForgotPasswordController@check_admin_email_exists');
Route::match(['get','post'], 'admin/forgot-password', 'backEnd\ForgotPasswordController@send_forgot_pass_link_mail');

Route::get('admin/get-homes/{company_name}', 'backEnd\WelcomeController@get_homes');

//show admin set password page
Route::match('get', 'admin/set-password/{system_admin_id}/{security_code}', 'backEnd\superAdmin\AdminController@show_set_password_form_system_admin');

//save admin new password after set password page
Route::match(['get','post'], 'admin/system-admin/set-password', 'backEnd\superAdmin\AdminController@set_password_system_admin');
//paypal
Route::match(['get','post'],'/system-admin/home/payment/success/{system_admin_id}', 'backEnd\superAdmin\HomeController@success');
//paypal
Route::group(['prefix' => 'admin', 'middleware'=>'CheckAdminAuth'], function(){
	//download form  As PDF 
	Route::match(['get','post'],'/DownloadFormpdf/{id}','backEnd\superAdmin\UserController@DownloadFormpdf');

	Route::get('/', 'backEnd\AdminController@dashboard');
// 	Route::get('/dashboard', 'backEnd\AdminController@dashboard');
    Route::match(['get','post'],'/dashboard', 'backEnd\AdminController@dashboard');

	
	//personal Mangement(profile)
	Route::get('/profile', 'backEnd\myProfile\ProfileController@profile');
	Route::match(['get', 'post'], '/profile/edit', 'backEnd\myProfile\ProfileController@edit');
	Route::match(['get','post'], '/profile/change-password', 'backEnd\myProfile\ProfileController@change_password');

	Route::match(['get','post'], '/welcome', 'backEnd\WelcomeController@welcome');
	Route::get('/welcome/get-homes/{company_name}', 'backEnd\WelcomeController@welcome_get_homes');
	// Route::match(['get','post'], '/welcome', 'backEnd\HomeController@welcome_admin');
	// Route::match(['get','post'], '/welcome/homes', 'backEnd\HomeController@welcome_super_admin');

    //backEnd Manager in SuperAdmin
    Route::match(['get','post'],'/company-managers', 'backEnd\superAdmin\companyManager\ManagerController@index');
    Route::match(['get','post'], '/company-manager/add', 'backEnd\superAdmin\companyManager\ManagerController@add');
    Route::match(['get','post'], '/company-manager/edit/{id}', 'backEnd\superAdmin\companyManager\ManagerController@edit');
    Route::match(['get','post'], '/company-manager/delete/{id}', 'backEnd\superAdmin\companyManager\ManagerController@delete');
    Route::match(['get','post'], '/company-manager/send-set-pass-link/{user_id}', 'backEnd\superAdmin\companyManager\ManagerController@send_user_set_pass_link_mail'); 
    Route::match(['get','post'], '/company-manager/check_username_unique', 'backEnd\UserController@check_username_exist');
	
    //backEnd SystemAdmin in SuperAdmin 
	Route::match(['get','post'],'/system-admins', 'backEnd\superAdmin\AdminController@system_admins');
	Route::match(['get','post'],'/system-admin/add', 'backEnd\superAdmin\AdminController@add');
	Route::match(['get','post'],'/system-admin/edit/{sa_id}', 'backEnd\superAdmin\AdminController@edit');
	Route::match(['get','post'],'/system-admin/delete/{sa_id}', 'backEnd\superAdmin\AdminController@delete');
	Route::match(['get','post'], '/system-admin/send-set-pass-link/{system_admin_id}', 'backEnd\superAdmin\AdminController@send_system_admin_set_pass_link_mail');
    Route::match(['get','post'], '/system-admin/package/detail/{sa_id}', 'backEnd\superAdmin\AdminController@package_detail');
    
	//backEnd company charges in SuperAdmin
	Route::match(['get','post'],'/company-charges', 'backEnd\superAdmin\CompanyChargesController@index');
	Route::match(['get','post'],'/company-charge/edit/{package_id}', 'backEnd\superAdmin\CompanyChargesController@edit');
	Route::match(['get','post'],'/company-charge/validate-home-range', 'backEnd\superAdmin\CompanyChargesController@validate_home_range');
	Route::match(['get','post'],'/company-charge/validate-range-gap', 'backEnd\superAdmin\CompanyChargesController@validate_range_gap');

	//backEnd SystemAdmin in SuperAdmin Home
	Route::match(['get','post'],'/system-admin/homes/{system_admin_id}', 'backEnd\superAdmin\HomeController@index');
	Route::match(['get','post'],'/system-admin/homes/add/{system_admin_id}', 'backEnd\superAdmin\HomeController@add');
	Route::match(['get','post'],'/system-admin/home/edit/{home_id}', 'backEnd\superAdmin\HomeController@edit');
	Route::match(['get','post'],'/system-admin/home/delete/{home_id}', 'backEnd\superAdmin\HomeController@delete');
	Route::match(['get','post'],'/system-admin/home/undo-delete/{home_id}', 'backEnd\superAdmin\HomeController@undo_delete');
	Route::match(['get','post'],'/system-admin/home/company-package-type', 'backEnd\superAdmin\HomeController@company_package_type');
	Route::match(['get','post'],'/system-admin/home/card-detail', 'backEnd\superAdmin\HomeController@card_detail_save');
	

	Route::match(['get','post'],'/users', 'backEnd\UserController@users');
	Route::match(['get','post'], '/users/add', 'backEnd\UserController@add');
	Route::match(['get','post'], '/users/edit/{id}', 'backEnd\UserController@edit');
	Route::match(['get','post'], '/users/delete/{id}', 'backEnd\UserController@delete');
	Route::get('/users/certificates/delete/{id}', 'backEnd\UserController@delete_certificates');
	Route::match(['get','post'], '/users/send-set-pass-link/{user_id}', 'backEnd\UserController@send_user_set_pass_link_mail');

	//User TaskAllocation
	Route::match(['get', 'post'], '/user/task-allocations/{user_id}', 'backEnd\user\TaskAllocationController@index');
	Route::match(['get', 'post'], '/user/task-allocation/add/{user_id}', 'backEnd\user\TaskAllocationController@add');
	Route::match(['get', 'post'], '/user/task-allocation/edit/{u_task_alloc_id}', 'backEnd\user\TaskAllocationController@edit');
	Route::match(['get','post'], '/user/task-allocation/delete/{u_task_alloc_id}', 'backEnd\user\TaskAllocationController@delete');

	//User SickLeave
	Route::match(['get','post'], '/user/sick-leaves/{staff_member_id}', 'backEnd\user\SickLeaveController@index');
	Route::match(['get','post'], '/user/sick-leave/add/{staff_member_id}', 'backEnd\user\SickLeaveController@add');
	Route::match(['get','post'], '/user/sick-leave/edit/{u_sick_leave_id}', 'backEnd\user\SickLeaveController@edit');	
    Route::get('/user/sick-leave/delete/{u_sick_leave_id}', 'backEnd\user\SickLeaveController@delete');
    Route::match(['get','post'], '/user/sick-leave/sanction/{u_sick_leave_id}', 'backEnd\user\SickLeaveController@sanction_leave');
    Route::match(['get','post'], '/user/sick-leave/user-list/{home_id}/{user_id}', 'backEnd\user\SickLeaveController@staff_user_list');
	Route::match(['get','post'], '/user/rota', 'backEnd\user\SickLeaveController@get_staff_rota');
	
	//User Annual Leave
	Route::match(['get','post'], '/user/annual-leaves/{staff_member_id}', 'backEnd\user\AnnualLeaveController@index');
	Route::match(['get','post'], '/user/annual-leave/add/{staff_member_id}', 'backEnd\user\AnnualLeaveController@add');
	Route::match(['get','post'], '/user/annual-leave/edit/{u_annual_leave_id}', 'backEnd\user\AnnualLeaveController@edit');
	Route::get('/user/annual-leave/delete/{u_sick_leave_id}', 'backEnd\user\AnnualLeaveController@delete');	

	//backEnd ServiceUserController
	Route::match(['get','post'],'/service-users', 'backEnd\serviceUser\ServiceUserController@index');
	Route::match(['get','post'], '/service-users/add', 'backEnd\serviceUser\ServiceUserController@add');
	Route::match(['get','post'], '/service-users/edit/{su_id}', 'backEnd\serviceUser\ServiceUserController@edit');
	Route::match(['get','post'], '/service-users/delete/{su_id}', 'backEnd\serviceUser\ServiceUserController@delete');
	Route::get('/service-users/send-set-pass-link/{su_id}', 'backEnd\serviceUser\ServiceUserController@send_set_pass_link_mail');

	//backEnd Service Users Care History
	Route::match(['get','post'],'/service-users/care-history/{su_id}', 'backEnd\serviceUser\CareHistoryController@index');
	Route::match(['get','post'],'/service-users/care-history/add/{su_id}', 'backEnd\serviceUser\CareHistoryController@add');
	Route::match(['get','post'],'/service-users/care-history/edit/{care_id}', 'backEnd\serviceUser\CareHistoryController@edit');
	Route::match(['get','post'],'/service-users/care-history/delete/{care_id}', 'backEnd\serviceUser\CareHistoryController@delete');
	Route::match(['get','post'], '/service-users/care-history/delete-file/{su_care_history_id}', 'backEnd\serviceUser\CareHistoryController@delete_hist_file');

	//backEnd careTeam in serviceUser
	Route::match(['get','post'], '/service-user/careteam/{su_id}', 'backEnd\serviceUser\CareTeamController@team_list');
	Route::match(['get','post'], '/service-user/careteam/add/{su_id}', 'backEnd\serviceUser\CareTeamController@add');
	Route::match(['get','post'], '/service-user/careteam/delete/{team_member_id}', 'backEnd\serviceUser\CareTeamController@delete');
	Route::match(['get','post'], '/service-user/careteam/edit/{team_member_id}', 'backEnd\serviceUser\CareTeamController@edit');

	//backEnd serviceUser contacts
	Route::match(['get','post'],'/service-users/contacts/{su_id}','backEnd\serviceUser\ContactsController@team_list');
	Route::match(['get','post'], '/service-users/contacts/add/{su_id}', 'backEnd\serviceUser\ContactsController@add');
	Route::match(['get','post'], '/service-users/contacts/delete/{team_member_id}', 'backEnd\serviceUser\ContactsController@delete');
	Route::match(['get','post'], '/service-users/contacts/edit/{team_member_id}', 'backEnd\serviceUser\ContactsController@edit');

	//backEnd ServiceUser Moods
	Route::match(['get', 'post'], '/service-user/moods/{su_id}', 'backEnd\serviceUser\MoodController@index');
	Route::match(['get', 'post'], '/service-user/mood/add/{su_id}', 'backEnd\serviceUser\MoodController@add');
	Route::match(['get', 'post'], '/service-user/mood/edit/{su_mood_id}', 'backEnd\serviceUser\MoodController@edit');
	Route::match(['get', 'post'], '/service-user/mood/delete/{su_mood_id}', 'backEnd\serviceUser\MoodController@delete');

	//backEnd ServiceUser External Service
	Route::match(['get', 'post'], '/service-user/external-service/{su_id}', 'backEnd\serviceUser\ExternalServiceController@index');
	Route::match(['get', 'post'], '/service-user/external-service/add/{su_id}', 'backEnd\serviceUser\ExternalServiceController@add');
	Route::match(['get', 'post'], '/service-user/external-service/edit/{ext_service_id}', 'backEnd\serviceUser\ExternalServiceController@edit');
	Route::match(['get', 'post'], '/service-user/external-service/delete/{ext_service_id}', 'backEnd\serviceUser\ExternalServiceController@delete');
	
	
	//backEnd ServiceUser DailyLog

	Route::match(['get', 'post'], '/service-user/logbooks/{su_id}', 'backEnd\serviceUser\LogBookController@index');
	Route::get('/service-user/logbook/view/{log_book_id}', 'backEnd\serviceUser\LogBookController@view');
	Route::get('/service-user/logbook/download', 'backEnd\serviceUser\LogBookController@download');
	
	//backEnd ServiceUser Independent LivingSkills
	Route::match(['get', 'post'], '/service-user/living-skills/{su_id}', 'backEnd\serviceUser\LivingSkillController@index');
	Route::get('/service-user/living-skill/view/{su_living_skill_id}', 'backEnd\serviceUser\LivingSkillController@view');

	//backEnd ServiceUser Calendar
	Route::match(['get', 'post'], '/service-user/calendar/{su_id}', 'backEnd\serviceUser\CalendarController@index');
    Route::match(['get','post'], '/service-user/calendar/event/view', 'backEnd\serviceUser\CalendarController@event_detail');
    
    //backEnd ServiceUser RMP
    Route::match(['get', 'post'], '/service-user/rmps/{su_id}', 'backEnd\serviceUser\RmpController@index');
    Route::match(['get', 'post'], '/service-user/rmp/view/{d_rmp_form_id}', 'backEnd\serviceUser\RmpController@view');

     //backEnd ServiceUser BMP
    Route::match(['get', 'post'], '/service-user/bmps/{su_id}', 'backEnd\serviceUser\BmpController@index');
    Route::match(['get', 'post'], '/service-user/bmp/view/{d_bmp_form_id}', 'backEnd\serviceUser\BmpController@view');

    //backEnd ServiceUser Risk
    Route::match(['get', 'post'], '/service-user/risks/{su_id}', 'backEnd\serviceUser\RiskController@index');
    Route::match(['get', 'post'], '/service-user/risk/view/{su_risk_id}', 'backEnd\serviceUser\RiskController@view');

    //backEnd ServiceUser Earning Scheme
    Route::match(['get', 'post'], '/service-user/earning-schemes/{su_id}', 'backEnd\serviceUser\EarningSchemeController@index');
    Route::match(['get','post'], '/service/earning-scheme/view_incentive/{earning_category_id}', 'backEnd\serviceUser\EarningSchemeController@view_incentive');
    Route::match(['get','post'], '/service/earning-scheme/daily-records/{su_id}/{label_id}', 'backEnd\serviceUser\EarningSchemeController@daily_record');
    Route::match(['get','post'], '/service/earning-scheme/daily-record/view/{daily_record_id}/{label_type}', 'backEnd\serviceUser\EarningSchemeController@daily_record_view');
    Route::match(['get','post'], '/service/earning-scheme/living-skills/{su_id}', 'backEnd\serviceUser\EarningSchemeController@living_skill');
    Route::match(['get','post'], '/service/earning-scheme/living_skill/view/{daily_record_id}', 'backEnd\serviceUser\EarningSchemeController@living_skill_view');
    Route::match(['get','post'], '/service/earning-scheme/education-records/{su_id}', 'backEnd\serviceUser\EarningSchemeController@education_record');
    Route::match(['get','post'], '/service/earning-scheme/education-record/view/{education_record_id}', 'backEnd\serviceUser\EarningSchemeController@education_record_view');
    Route::match(['get','post'], '/service/earning-scheme/mfcs/{su_id}', 'backEnd\serviceUser\EarningSchemeController@mfc');
    Route::match(['get','post'], '/service/earning-scheme/mfc/view/{d_mfc_id}', 'backEnd\serviceUser\EarningSchemeController@mfc_view');
    Route::match(['get','post'], '/service-user/earning-scheme-label/add/{service_user_id}/{earning_scheme_label_id}','backEnd\serviceUser\EarningSchemeController@add_earning_scheme_label');
    Route::match(['get','post'], '/service-user/earning-scheme-label/delete/{service_user_id}/{earning_scheme_label_id}','backEnd\serviceUser\EarningSchemeController@delete_earning_scheme_label');

	
	//backEnd incidentReport in serviceUser service-users/incident-reports (not)
	Route::match(['get','post'], '/service-user/incident-reports/{su_id}', 'backEnd\serviceUser\IncidentReportController@index');
	Route::match(['get','post'], '/service-user/incident/add/{su_id}', 'backEnd\serviceUser\IncidentReportController@add');
	Route::match(['get','post'], '/service-user/incident/edit/{inc_rep_id}', 'backEnd\serviceUser\IncidentReportController@edit');
	Route::get('/service-user/incident/delete/{inc_rep_id}', 'backEnd\serviceUser\IncidentReportController@delete');

	//backEnd Agent
	Route::match(['get','post'],'/agents', 'backEnd\AgentController@agents');
	Route::match(['get','post'],'/agents/add', 'backEnd\AgentController@add');
	Route::match(['get','post'],'/agents/edit/{agent_id}', 'backEnd\AgentController@edit');
	Route::match(['get','post'],'/agents/delete/{agent_id}', 'backEnd\AgentController@delete');
	Route::match(['get','post'],'/agents/send-set-pass-link/{agent_id}', 'backEnd\AgentController@send_user_set_pass_link_mail');
	//agent access rights
	Route::get('agents/access-rights/{agent_id}', 'backEnd\AccessRightController@agent_index');
	Route::match(['get','post'], 'agents/access-right/update', 'backEnd\AccessRightController@agent_update');



	//backEnd DailyRecord
	Route::match(['get','post'], '/daily-record', 'backEnd\DailyRecordController@index');
	Route::match(['get','post'], '/daily-record/add', 'backEnd\DailyRecordController@add');
	Route::match(['get','post'], '/daily-record/edit/{id}', 'backEnd\DailyRecordController@edit');
	Route::match(['get','post'], '/daily-record/delete/{id}', 'backEnd\DailyRecordController@delete');

	//backEnd Daily Record Score
	Route::match(['get','post'], '/daily-record-scores', 'backEnd\DailyRecordScoreController@index');
	Route::match(['get','post'], '/daily-record-score/edit/{dr_score_id}', 'backEnd\DailyRecordScoreController@edit');

	//backEnd Risks
	Route::match(['get','post'], '/risk', 'backEnd\RiskController@risk');
	Route::match(['get','post'], '/risk/add', 'backEnd\RiskController@add');
	Route::match(['get','post'], '/risk/edit/{id}', 'backEnd\RiskController@edit');
	Route::match(['get','post'], '/risk/delete/{id}', 'backEnd\RiskController@delete');

	//backEnd EarningScheme 
	Route::match(['get','post'], '/earning-scheme', 'backEnd\EarningSchemeController@earning_scheme');
	Route::match(['get','post'], '/earning-scheme/add', 'backEnd\EarningSchemeController@add');
	Route::match(['get','post'], '/earning-scheme/edit/{id}', 'backEnd\EarningSchemeController@edit');
	Route::match(['get','post'], '/earning-scheme/delete/{id}', 'backEnd\EarningSchemeController@delete');

	//backEnd EarningScheme -- Incentive
	Route::match(['get','post'], '/earning-scheme/incentive/{id}', 'backEnd\IncentiveController@incentive');
	Route::match(['get','post'], '/earning-scheme/incentive/add/{id}', 'backEnd\IncentiveController@add');
	Route::match(['get','post'], '/earning-scheme/incentive/edit/{id}', 'backEnd\IncentiveController@edit');
	Route::match(['get','post'], '/earning-scheme/incentive/delete/{id}', 'backEnd\IncentiveController@delete');

	//backEnd Incentive
	Route::match(['get','post'], '/earning-scheme/incentive/{id}', 'backEnd\IncentiveController@incentive');

    //Earning scheme labels
    Route::any('earning-scheme-labels','backEnd\EarningSchemeLabelController@index');
    Route::any('/earning-scheme-label/add','backEnd\EarningSchemeLabelController@add');
    Route::any('/earning-scheme-label/edit/{label_id}','backEnd\EarningSchemeLabelController@edit');
    Route::any('/earning-scheme-label/delete/{label_id}','backEnd\EarningSchemeLabelController@delete');

	//backEnd Homelist
	Route::match(['get','post'],'/homelist', 'backEnd\homeManage\HomeController@index');
	Route::match(['get','post'],'/homelist/add', 'backEnd\homeManage\HomeController@add');
	Route::match(['get','post'],'/homelist/edit/{home_id}', 'backEnd\homeManage\HomeController@edit');
	Route::match(['get','post'],'/homelist/delete/{home_id}', 'backEnd\homeManage\HomeController@delete');
	Route::match(['get','post'],'/homelist/undo-delete/{home_id}', 'backEnd\homeManage\HomeController@undo_delete');
	Route::match(['get','post'],'/homelist/company-package-type', 'backEnd\homeManage\HomeController@company_package_type');
	Route::match(['get','post'],'/homelist/payment/success/{admin_id}', 'backEnd\homeManage\HomeController@success');

	//backend homelist-admins		
	Route::match(['get','post'],'/homelist/home-admin/{home_id}','backEnd\homeManage\AdminController@index');
	Route::match(['get','post'],'/homelist/home-admin/add/{home_id}','backEnd\homeManage\AdminController@add');
	Route::match(['get','post'],'/homelist/home-admin/edit/{home_admin_id}','backEnd\homeManage\AdminController@edit');
	Route::match(['get','post'],'/homelist/home-admin/delete/{home_admin_id}','backEnd\homeManage\AdminController@delete');
	Route::match(['get','post'],'/homelist/home-admin/send-set-pass-link/{home_admin_id}','backEnd\homeManage\AdminController@send_set_password_link_mail');

	//access rights
	Route::get('users/access-rights/{user_id}', 'backEnd\AccessRightController@index');
	Route::match(['get','post'], 'users/access-right/update', 'backEnd\AccessRightController@update');

	//support ticket
	Route::match(['get','post'], '/support-ticket', 'backEnd\SupportTicketController@index');
	Route::match(['get','post'], '/support-ticket/add/msg', 'backEnd\SupportTicketController@add_ticket_mesg');
	Route::match(['get','post'], '/support-ticket/view/{ticket_id}', 'backEnd\SupportTicketController@view_ticket');
	// Route::match(['get','post'], '/support-ticket/edit/{user_id}', 'backEnd\SupportTicketController@edit');
	Route::match(['get','post'], '/support-ticket/delete/{user_id}', 'backEnd\SupportTicketController@delete');
	
	//backEnd Placement Plan	
	Route::match(['get','post'], '/placement-plan', 'backEnd\PlacementPlanController@index');
	Route::match(['get','post'], '/placement-plan/add', 'backEnd\PlacementPlanController@add');
	Route::match(['get','post'], '/placement-plan/edit/{target_id}', 'backEnd\PlacementPlanController@edit');
	Route::match(['get','post'], '/placement-plan/delete/{target_id}', 'backEnd\PlacementPlanController@delete');	
	
	//form-builder
	Route::match(['get','post'], '/form-builder', 'backEnd\systemManage\FormBuilderController@index');	
	Route::match(['get','post'], '/form-builder/add', 'backEnd\systemManage\FormBuilderController@add');	
	Route::match(['get','post'], '/form-builder/edit/{form_id}', 'backEnd\systemManage\FormBuilderController@edit');	
	Route::match(['get','post'], '/form-builder/delete/{form_id}', 'backEnd\systemManage\FormBuilderController@delete');	

	/*//form-builder
	Route::match(['get','post'], '/form-builder', 'backEnd\FormBuilderController@index');	
	Route::match(['get','post'], '/form-builder/add', 'backEnd\FormBuilderController@add');	
	Route::match(['get','post'], '/form-builder/view/{form_id}', 'backEnd\FormBuilderController@view');	
	Route::match(['get','post'], '/form-builder/edit/{form_id}', 'backEnd\FormBuilderController@edit');	
	Route::match(['get','post'], '/form-builder/delete/{form_id}', 'backEnd\FormBuilderController@delete');*/	

	// labels
	Route::get('/labels', 'backEnd\HomeLabelController@index');	
	Route::get('/label/view/{label_tag}', 'backEnd\HomeLabelController@view');	
	Route::post('/label/edit', 'backEnd\HomeLabelController@edit');	

	// categories
	Route::get('/categories', 'backEnd\HomeCategoriesController@index');	
	Route::get('/categories/view/{category_tag}', 'backEnd\HomeCategoriesController@view');	
	Route::post('/categories/edit', 'backEnd\HomeCategoriesController@edit');
	Route::match(['get','post'], '/categories/add', 'backEnd\HomeCategoriesController@add');	
	// Route::get('/categories/add', 'backEnd\HomeCategoriesController@add');	

	// Backend unique username for user,service user,agent & admin
	Route::match(['get','post'], '/users/check_username_unique', 'backEnd\UserController@check_username_exist');
	Route::match(['get','post'], '/service-users/check_username_exists', 'backEnd\serviceUser\ServiceUserController@check_username_exist');
	Route::match(['get','post'], '/system-admin/check_user_username_exists', 'backEnd\superAdmin\AdminController@check_username_exist');
	Route::match(['get','post'], '/agents/check_username_unique', 'backEnd\AgentController@check_username_exist');

	// migrations 
	Route::match(['get','post'],'/service-users/migrations/{service_user_id}', 'backEnd\serviceUser\MigrationController@index');	
	Route::get('/service-users/migration/send-request/{service_user_id}', 'backEnd\serviceUser\MigrationController@show_form');	
	Route::post('/service-users/migration/send-request', 'backEnd\serviceUser\MigrationController@save_request');	

	Route::get('/service-users/migration/view/{su_migration_id}', 'backEnd\serviceUser\MigrationController@view');	
	Route::get('/service-users/migration/cancel-request/{su_migration_id}', 'backEnd\serviceUser\MigrationController@cancel_request');	
	//get homes by id
	Route::get('/migration/get-company/{admin_id}', 'backEnd\serviceUser\MigrationController@get_homes');	
	
	//backEnd ModifyRequest
	Route::match(['get','post'], '/modification-requests', 'backEnd\ModificationRequestController@index');
	Route::match(['get','post'], '/modification-request/edit/{request_id}', 'backEnd\ModificationRequestController@edit');
	Route::match(['get','post'], '/modification-request/delete/{request_id}', 'backEnd\ModificationRequestController@delete');

	//backEnd LivingSkills 
	Route::match(['get', 'post'], '/living-skill', 'backEnd\LivingSkillController@index');
	Route::match(['get', 'post'], '/living-skill/add', 'backEnd\LivingSkillController@add');
	Route::match(['get', 'post'], '/living-skill/edit/{skill_id}', 'backEnd\LivingSkillController@edit');
	Route::match(['get','post'],  '/living-skill/delete/{skill_id}', 'backEnd\LivingSkillController@delete');

	//backEnd MFC 
	Route::match(['get', 'post'], '/mfc-records', 'backEnd\MFCController@index');
	Route::match(['get', 'post'], '/mfc/add', 'backEnd\MFCController@add');
	Route::match(['get', 'post'], '/mfc/edit/{mfc_id}', 'backEnd\MFCController@edit');
	Route::get('/mfc/delete/{mfc_id}', 'backEnd\MFCController@delete');	

	//backEnd Education Training
	Route::match(['get', 'post'], '/education-trainings', 'backEnd\EducationTrainingController@index');
	Route::match(['get', 'post'], '/education-training/add', 'backEnd\EducationTrainingController@add');
	Route::match(['get', 'post'], '/education-training/edit/{edu_tr_id}', 'backEnd\EducationTrainingController@edit');
	Route::match(['get', 'post'], '/education-training/delete/{edu_tr_id}', 'backEnd\EducationTrainingController@delete');

	//backEnd CareTeam-JobTitle
	Route::match(['get', 'post'], '/care-team-job-titles', 'backEnd\homeManage\CareTeamJobTitleController@index');
	Route::match(['get', 'post'], '/care-team-job-title/add', 'backEnd\homeManage\CareTeamJobTitleController@add');
	Route::match(['get', 'post'], '/care-team-job-title/edit/{job_title_id}', 'backEnd\homeManage\CareTeamJobTitleController@edit');
	Route::match(['get', 'post'], '/care-team-job-title/delete/{job_title_id}', 'backEnd\homeManage\CareTeamJobTitleController@delete');

	//backEnd Moods
	Route::match(['get', 'post'], '/moods', 'backEnd\homeManage\MoodController@index');
	Route::match(['get', 'post'], '/mood/add', 'backEnd\homeManage\MoodController@add');
	Route::match(['get', 'post'], '/mood/edit/{mood_id}', 'backEnd\homeManage\MoodController@edit');
	Route::match(['get', 'post'], '/mood/delete/{mood_id}', 'backEnd\homeManage\MoodController@delete');

	//Access levels
	Route::get('/home/access-levels', 'backEnd\homeManage\AccessLevelController@index');
	
	Route::match(['get', 'post'],'/home/access-level/add', 'backEnd\homeManage\AccessLevelController@add');
	Route::match(['get','post'], '/home/access-level/edit/{access_level_id}', 'backEnd\homeManage\AccessLevelController@edit');
	Route::match(['get', 'post'],'/home/access-level/delete/{access_level_id}', 'backEnd\homeManage\AccessLevelController@delete');
	Route::get('/home/access-level/rights/view/{access_level_id}', 'backEnd\homeManage\AccessLevelController@view_rights');
	Route::post('/home/access-level/rights/update', 'backEnd\homeManage\AccessLevelController@update_rights');

	//Staff Rota
	// Route::get('/home/rota-shift', 'backEnd\homeManage\RotaShiftController@index');
	// Route::match(['get', 'post'], '/home/rota-shift/view/{shift_id}', 'backEnd\homeManage\RotaShiftController@view');
	// Route::match(['get', 'post'], '/home/rota-shift/edit', 'backEnd\homeManage\RotaShiftController@edit');
	
		//Staff Rota
	Route::match(['get', 'post'], '/home/rota-shift', 'backEnd\homeManage\RotaShiftController@index');
	//Route::match(['get', 'post'], '/home/rota-shift/view/{shift_id}', 'backEnd\homeManage\RotaShiftController@view');
	Route::match(['get', 'post'], '/home/rota-shift/add', 'backEnd\homeManage\RotaShiftController@add');
	//(not saved)
	Route::match(['get', 'post'], '/home/rota-shift/edit/{shift_id}', 'backEnd\homeManage\RotaShiftController@edit');
	Route::match(['get', 'post'], '/home/rota-shift/delete/{shift_id}', 'backEnd\homeManage\RotaShiftController@delete');

	//backEnd ContactUs
	// Route::match(['get', 'post'], '/contact-us', 'backEnd\ContactUsController@index');
	// Route::match(['get', 'post'], '/contact/reply/{contact_id}', 'backEnd\ContactUsController@reply');
	// Route::match(['get', 'post'], '/contact/delete/{contact_id}', 'backEnd\ContactUsController@delete');

	//backEnd System Guide Category
	Route::match(['get', 'post'], '/system-guide-category', 'backEnd\systemGuide\SystemGuideCategoryController@index');
	Route::match(['get', 'post'], '/system-guide/view/{sg_ctgry_id}', 'backEnd\systemGuide\SystemGuideController@index');
	
	//backEnd System Guide 
	Route::match(['get','post'],'/system-guide/add/{sg_ctgry_id}', 'backEnd\systemGuide\SystemGuideController@add');
	Route::match(['get','post'],'/system-guide/edit/{sys_guide_id}', 'backEnd\systemGuide\SystemGuideController@edit');
	Route::match(['get','post'],'/system-guide/delete/{sys_guide_id}', 'backEnd\systemGuide\SystemGuideController@delete');

	// backEnd managers
	Route::match(['get', 'post'], '/managers', 'backEnd\ManagersController@index');
	Route::match(['get', 'post'], '/managers/add', 'backEnd\ManagersController@add');
	Route::match(['get', 'post'], '/managers/edit/{manager_id}', 'backEnd\ManagersController@edit');
	Route::match(['get', 'post'], '/managers/delete/{manager_id}', 'backEnd\ManagersController@delete');
	Route::match(['get', 'post'], '/manager/change-status', 'backEnd\ManagersController@change_status');
	Route::match(['get', 'post'], '/manager/check-email-exists', 'backEnd\ManagersController@check_email_exists');
	Route::match(['get', 'post'], '/manager/check-contact-no-exists', 'backEnd\ManagersController@check_contact_no_exists');

	//backEnd Service User Dynamic Forms
	Route::match(['get', 'post'], '/service-user/dynamic-forms/{su_id}', 'backEnd\serviceUser\DynamicFormController@index');
	Route::match(['get', 'post'], '/service-user/dynamic-forms/view/{d_form_id}', 'backEnd\serviceUser\DynamicFormController@view');
	Route::post('/service-user/dynamic-form/edit', 'backEnd\serviceUser\DynamicFormController@edit');
	Route::get('/service-user/dynamic-form/delete/{d_form_id}', 'backEnd\serviceUser\DynamicFormController@delete');

	//backEnd Service User File Manager
	Route::match(['get', 'post'], '/service-user/file-managers/{su_id}', 'backEnd\serviceUser\FileManagerController@index');
	Route::match(['get','post'], '/service-user/file-manager/add/{service_user_id}', 'backEnd\serviceUser\FileManagerController@add');
	Route::get('/service-user/file-manager/delete/{file_id}','backEnd\serviceUser\FileManagerController@delete');

	//backEnd Service User My Money History
	Route::match(['get','post'], '/service-user/my-money/history/{su_id}', 'backEnd\serviceUser\MyMoneyHistoryController@index');

	//backEnd Service User My Money Request
	Route::match(['get','post'], '/service-user/my-money/request/{su_id}', 'backEnd\serviceUser\MyMoneyRequestController@index');
	Route::match(['get','post'], '/service-user/my-money/request-view/{money_request_id}', 'backEnd\serviceUser\MyMoneyRequestController@view');

	//backEnd Policies&Procedure
	Route::match(['get', 'post'], '/home/policies', 'backEnd\homeManage\PoliciesController@index');
	Route::match(['get','post'], '/home/policies/add', 'backEnd\homeManage\PoliciesController@add');
	Route::get('/home/policies/delete/{policy_id}', 'backEnd\homeManage\PoliciesController@delete');
	Route::match(['get','post'],'/home/policies/staff/accepted/{policy_id}', 'backEnd\homeManage\PoliciesController@policy_accepted_staff');
	Route::match(['get','post'],'/home/policies/staff/to-agree/{policy_id}', 'backEnd\homeManage\PoliciesController@to_agree_policy');

	//------ backEnd General Admin ---------- //
	//Agenda Meetings
	Route::match(['get', 'post'], '/general-admin/agenda/meetings', 'backEnd\generalAdmin\AgendaMeetingController@index');
	Route::match(['get', 'post'], '/general-admin/agenda/meeting-view/{meeting_id}', 'backEnd\generalAdmin\AgendaMeetingController@view');
	//Petty Cash
	Route::match(['get','post'], '/general-admin/petty/cash', 'backEnd\generalAdmin\PettyCashController@index');
	Route::match(['get','post'], '/general-admin/petty/cash-view/{petty_id}', 'backEnd\generalAdmin\PettyCashController@view');
	//Log Book
	Route::match(['get','post'], '/general-admin/log/book', 'backEnd\generalAdmin\LogBookController@index');
	Route::match(['get','post'], '/general-admin/log/book-view/{log_book_id}', 'backEnd\generalAdmin\LogBookController@view');
	//Weekly Allowance 
	Route::match(['get','post'], '/general-admin/allowance/weekly', 'backEnd\generalAdmin\WeeklyAllowanceController@index');
	//Staff Training
	Route::match(['get','post'], '/general-admin/staff/training', 'backEnd\generalAdmin\StaffTrainingController@index');
	Route::match(['get','post'], '/general-admin/staff/training-view/{training_id}', 'backEnd\generalAdmin\StaffTrainingController@view');
});

//super admin path
Route::group(['prefix' => 'super-admin', 'middleware'=>'CheckAdminAuth'], function(){
	
	//service user migration
	Route::get('/migrations', 'backEnd\superAdmin\MigrationController@index');	
	Route::get('/migration/view/{migration_id}', 'backEnd\superAdmin\MigrationController@view');	
	Route::post('/migration/update', 'backEnd\superAdmin\MigrationController@update');	

	//super user admin
	Route::match(['get','post'],'/user/add','backEnd\superAdmin\UserController@add');
	Route::match(['get','post'],'/user/edit/{user_id}','backEnd\superAdmin\UserController@edit');
	Route::match(['get','post'],'/user/delete/{user_id}','backEnd\superAdmin\UserController@delete');
	Route::match(['get','post'],'/users','backEnd\superAdmin\UserController@index');

	Route::match(['get','post'], '/user/send-set-pass-link/{user_id}', 'backEnd\superAdmin\UserController@send_set_password_link_mail');

	Route::match('get', 'user/set-password/{super_admin_id}/{security_code}', 'backEnd\superAdmin\UserController@show_set_password_form_super_admin');

	Route::match(['get','post'], 'user/super-admin/set-password', 'backEnd\superAdmin\UserController@set_password_super_admin');

	//home-admin
	Route::match(['get','post'],'/home-admin/{home_id}','backEnd\superAdmin\HomeAdminController@index');
	Route::match(['get','post'],'/home-admin/add/{home_id}','backEnd\superAdmin\HomeAdminController@add');
	Route::match(['get','post'],'/home-admin/edit/{home_admin_id}','backEnd\superAdmin\HomeAdminController@edit');
	Route::match(['get','post'],'/home-admin/delete/{home_admin_id}','backEnd\superAdmin\HomeAdminController@delete');

	//FileManager Categories
	Route::match(['get','post'], '/filemanager-categories', 'backEnd\superAdmin\FileManagerCategoryController@index');
	Route::match(['get','post'], '/filemanager-category/add', 'backEnd\superAdmin\FileManagerCategoryController@add');
	Route::match(['get','post'], '/filemanager-category/edit/{category_id}', 'backEnd\superAdmin\FileManagerCategoryController@edit');
	Route::match(['get','post'], '/filemanager-category/delete/{category_id}', 'backEnd\superAdmin\FileManagerCategoryController@delete');

	Route::match(['get','post'],'/home-admin/send-set-pass-link/{home_admin_id}','backEnd\superAdmin\HomeAdminController@send_set_password_link_mail');

	//SocailApp
	Route::match(['get','post'], '/social-apps', 'backEnd\superAdmin\SocialAppController@index');
	Route::match(['get','post'], '/social-app/add', 'backEnd\superAdmin\SocialAppController@add');
	Route::match(['get','post'], '/social-app/edit/{social_app_id}', 'backEnd\superAdmin\SocialAppController@edit');
	Route::get('/social-app/delete/{social_app_id}', 'backEnd\superAdmin\SocialAppController@delete');

	//Ethnicity
	Route::match(['get','post'],'/ethnicities', 'backEnd\superAdmin\EthnicityController@index');
	Route::match(['get','post'],'/ethnicity/add', 'backEnd\superAdmin\EthnicityController@add');
	Route::match(['get','post'],'/ethnicity/edit/{ethnicity_id}', 'backEnd\superAdmin\EthnicityController@edit');
	Route::match(['get','post'],'/ethnicity/delete/{ethnicity_id}', 'backEnd\superAdmin\EthnicityController@delete');
	


});
	// not used now
	// Route::match(['get','post'], 'admin/home', 'backEnd\HomeController@index');
	// Route::match(['get','post'], 'admin/home/{home_id}', 'backEnd\HomeController@set_home');
	
	//backend unique email for user,service user & admin (now not used)
	/*Route::get('admin/users/check-user-email-exists', 'backEnd\UserController@check_user_email_exists');
	Route::get('admin/users/check-user-edit-email-exists', 'backEnd\UserController@check_user_edit_email_exists');
	Route::get('admin/service-users/check-serviceuser-email-exists', 'backEnd\ServiceUserController@check_serviceuser_email_exists');*/

//testing routes               
/*Route::get('/test/get_location','Api\ServiceUser\LocationController@getLocation');*/


//constants 
//image paths
if (!defined('userProfileImagePath'))define('userProfileImagePath', asset('public/images/userProfileImages'));
if (!defined('serviceUserProfileImagePath'))define('serviceUserProfileImagePath', asset('public/images/serviceUserProfileImages'));
if (!defined('careTeam'))define('careTeam', asset('public/images/careTeam'));
if (!defined('home'))define('home', asset('public/images/home'));
if (!defined('adminImgPath'))define('adminImgPath', asset('public/images/admin'));
if (!defined('ServiceUserFilePath'))define('ServiceUserFilePath', asset('public/images/serviceUserFiles'));
if (!defined('pettyCashReceiptPath'))define('pettyCashReceiptPath', asset('/public/images/pettyCash'));
if (!defined('MoodImgPath'))define('MoodImgPath', asset('/public/images/mood'));
if (!defined('userQualificationImgPath'))define('userQualificationImgPath', asset('/public/images/userQualification'));
if (!defined('PoliciesFilePath'))define('PoliciesFilePath', asset('public/images/policies'));
if (!defined('contactsPath'))define('contactsPath',asset('/public/images/contacts'));
if (!defined('pemFilePath'))define('pemFilePath','/home/mercury/public_html/scits/public/notify.pem');
if (!defined('SecurityPolicyFilePath'))define('SecurityPolicyFilePath', asset('public/images/securityPolicy'));
if (!defined('suCareHistoryFilePath'))define('suCareHistoryFilePath', asset('public/images/suCareHistoryFiles'));
if (!defined('agentProfileImagePath'))define('agentProfileImagePath', asset('public/images/agentProfileImages'));
if (!defined('managerImagePath'))define('managerImagePath', asset('public/images/managerProfileImages'));

//used in controller
if (!defined('userProfileImageBasePath'))define('userProfileImageBasePath', '/public/images/userProfileImages');
if (!defined('serviceUserProfileImageBasePath'))define('serviceUserProfileImageBasePath', '/public/images/serviceUserProfileImages');
if (!defined('careTeamPath'))define('careTeamPath', '/public/images/careTeam');
if (!defined('homebasePath'))define('homebasePath', '/public/images/home');
if (!defined('adminbasePath'))define('adminbasePath', '/public/images/admin');
if (!defined('ServiceUserFileBasePath'))define('ServiceUserFileBasePath', '/public/images/serviceUserFiles');
if (!defined('pettyCashFilesBasePath'))define('pettyCashFilesBasePath', '/public/images/pettyCash');
if (!defined('MoodImgBasePath'))define('MoodImgBasePath', '/public/images/mood');
if (!defined('userQualificationBaseImgPath'))define('userQualificationBaseImgPath', '/public/images/userQualification');
if (!defined('PoliciesFileBasePath'))define('PoliciesFileBasePath', '/public/images/policies');
if (!defined('contactsBasePath'))define('contactsBasePath','/public/images/contacts');
if (!defined('SecurityPolicyFileBasePath'))define('SecurityPolicyFileBasePath', '/public/images/securityPolicy');
if (!defined('suCareHistoryFileBasePath'))define('suCareHistoryFileBasePath', '/public/images/suCareHistoryFiles');
if (!defined('agentProfileImageBasePath'))define('agentProfileImageBasePath', '/public/images/agentProfileImages');
if (!defined('managerImageBasePath'))define('managerImageBasePath', '/public/images/managerProfileImages');

//Messages 
if (!defined('UNAUTHORIZE_ERR'))define("UNAUTHORIZE_ERR", "Sorry, You are not authorized to access this page.");
if (!defined('UNAUTHORIZE_ERR_APP'))define("UNAUTHORIZE_ERR_APP", "Sorry, You are not authorized.");
if (!defined('COMMON_ERROR'))define("COMMON_ERROR", 	  "Some error occured, Please try again after sometime.");
if (!defined('NO_HOME_ERR'))define("NO_HOME_ERR", 	  "Please first select a home from welcome page"); 
if (!defined('FILL_FIELD_ERR'))define("FILL_FIELD_ERR",  "Fill all the required fields.");

//	You have not selected any home, Please select a home from welcome page.");
if (!defined('NO_RECORD'))define('NO_RECORD', 	'No Record Found.');
if (!defined('ADD_RCD_MSG'))define('ADD_RCD_MSG', 	'Record has been added successfully.');
if (!defined('EDIT_RCD_MSG'))define('EDIT_RCD_MSG', 	'Record has been updated successfully.');
if (!defined('DEL_RECORD'))define('DEL_RECORD', 	'Record has been deleted successfully.');
if (!defined('CAl_ADD_RECORD'))define('CAl_ADD_RECORD','Record has been add to calendar successfully.');
if (!defined('DEL_CONFIRM'))define('DEL_CONFIRM', 	'Are you sure to delete this ?');

//other constants
if (!defined('PROJECT_NAME'))define('PROJECT_NAME', 'SCITS');
if (!defined('LOCK_TIME'))define('LOCK_TIME', '3000'); //in seconds i.e. 60 sec = 1 min, 300 = 5 mint, 3600 sec = 1 hr
if (!defined('DEFAULT_SU_EARN_TARGET'))define('DEFAULT_SU_EARN_TARGET','70'); //if no target will be defined for a su
if (!defined('SESSION_TIMEOUT'))define('SESSION_TIMEOUT', '20'); //in minutes
if (!defined('DEFAULT_LOCATION_RECALL_TIME'))define('DEFAULT_LOCATION_RECALL_TIME', '15'); //in minutes
if (!defined('MIN_PETTY_CASH_BALANCE'))define('MIN_PETTY_CASH_BALANCE', '1000'); //in minutes



