<?php
namespace App\Http\Controllers\backEnd\serviceUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HomeLabel, App\ServiceUser, App\ServiceUserEarningTarget, App\ServiceUserEarningStar, App\EarningScheme, App\Incentive, App\EarningAreaPercentage, App\ServiceUserDailyRecord, App\ServiceUserLivingSkill, App\ServiceUserEducationRecord, App\DynamicFormLocation, App\DynamicForm;
use DB, Session;

class EarningSchemeController extends Controller
{
    public function index($service_user_id, Request $request) {   

        $home_id = Session::get('scitsAdminSession')->home_id;
        // echo $home_id; die;

        $labels         = HomeLabel::getLabels($home_id);             
        $earning_target = ServiceUserEarningTarget::getEarningTarget($service_user_id);   

        $total_stars    = ServiceUserEarningStar::where('service_user_id',$service_user_id)->value('star');
        $total_stars    = (int)$total_stars; 

        $earning_scheme = EarningScheme::where('home_id', $home_id)
                                        ->where('status', '1')
                                        ->orderBy('id','desc')
                                        ->get()
                                        ->toArray();

        foreach ($earning_scheme as $key => $value) {
            $incentives_count = Incentive::where('earning_category_id',$value['id'])
                                    ->where('status','1')
                                    ->count();
            $earning_scheme[$key]['incentives_count'] = $incentives_count;
        }

        $record_score      = EarningScheme::getRecordsScore($service_user_id);
        $earn_area_percent = EarningAreaPercentage::select('daily_record','education_record','living_skill','mfc')->first();
        $earn_history      = EarningScheme::earningHistory($service_user_id);

        $page = 'service-users-earn-schm';
        return view('backEnd.serviceUser.earningScheme.earning_scheme', compact('page','service_user_id','labels','earning_target','total_stars','earning_scheme','booked_incentives','record_score','earn_area_percent','earn_history')); 
    }


    /*------ Incentives Start ------*/
    public function view_incentive($earning_category_id) { 

        $incentive = Incentive::select('incentive.id','name','stars','details','url','earning_scheme_category.title')
                                ->leftJoin('earning_scheme_category','earning_scheme_category.id','incentive.earning_category_id')
                                ->where('earning_category_id', $earning_category_id)
                                ->get()
                                ->toArray();
     
        foreach ($incentive as $key => $value) {
    
        echo   '<div class="incen-main">
                    <div class="">
                        <h4> '.$value['name'].' 
                            <a href="'.url('/service/earning-scheme/incentive/add?incentive_id='.$value['id']).'"  class="confirm_to_clndr" stars_need="'.$value['stars'].'">
                            </a>
                        </h4>
                    </div>
                    <p>'.$value['details'].'</p>
                    <a href="'.$value['url'].'" target="_blank" class="incen-link">'.$value['url'].'</a>
                    <div class="stars-sec">
                        <span class="star-head"> Stars : </span>';
                        for($i=1; $i <= $value['stars']; $i++) {
                            echo '<span><i class="fa fa-star"></i></span>';
                        }
                    echo '</div>
                </div>';
        } 
        die;
    }
    /*------ Incentives End ------*/

    /*------- Daily Record Start --------*/
    public function daily_record($service_user_id, Request $request) {   

        $su_daily_record_query = ServiceUserDailyRecord::select('su_daily_record.*','dr.description')
                                    ->join('daily_record as dr','su_daily_record.daily_record_id','=','dr.id')
                                    ->where('dr.status','1')
                                    ->where('su_daily_record.is_deleted','0')
                                    ->where('su_daily_record.service_user_id',$service_user_id)
                                    ->orderBy('su_daily_record.id','desc')
                                    ->orderBy('su_daily_record.created_at','desc');
                                    // ->get()
                                    // ->toArray();
        // echo "<pre>"; print_r($su_daily_record_query); die;
        
        $search = '';

        if(isset($request->limit)) {
            $limit = $request->limit;
            Session::put('page_record_limit',$limit);
        } else{

            if(Session::has('page_record_limit')){
                $limit = Session::get('page_record_limit');
            } else{
                $limit = 10;
            }
        }    

        if(isset($request->search)) {
            
            $search = trim($request->search);
            $su_daily_record_query = $su_daily_record_query->where('dr.description','like','%'.$search.'%');             //search by date or title
            //echo $search; die;
        }  

        $su_daily_record_query = $su_daily_record_query->paginate($limit);                      
        
        $page = 'service-users-earn-schm';
        return view('backEnd.serviceUser.earningScheme.daily_record', compact('page','limit', 'service_user_id','su_daily_record_query','search')); 
    }

    public function daily_record_view($daily_record_id = null) {

        $dr_info = ServiceUserDailyRecord::select('su_daily_record.*','dr.description')
                            ->join('daily_record as dr','su_daily_record.daily_record_id','=','dr.id')
                            ->where('su_daily_record.id',$daily_record_id)
                            ->first();

        $page = 'service-users-earn-schm';
        return view('backEnd.serviceUser.earningScheme.daily_record_form', compact('page','dr_info','search')); 
    }
    /*------- Daily Record End --------*/


    /*------- Living Skill Start --------*/
    public function living_skill($service_user_id, Request $request) {

        $su_living_skills = ServiceUserLivingSkill::select('su_living_skill.*','ls.description')
                                    ->join('living_skill as ls','su_living_skill.living_skill_id','=','ls.id')
                                    ->where('ls.status','1')
                                    ->where('su_living_skill.is_deleted','0')
                                    ->where('su_living_skill.service_user_id',$service_user_id)
                                    ->orderBy('su_living_skill.id','desc')
                                    ->orderBy('su_living_skill.created_at','desc');
                                    // ->get()
                                    // ->toArray();
        // echo "<pre>"; print_r($su_daily_record_query); die;
        
        $search = '';

        if(isset($request->limit)) {
            $limit = $request->limit;
            Session::put('page_record_limit',$limit);
        } else{

            if(Session::has('page_record_limit')){
                $limit = Session::get('page_record_limit');
            } else{
                $limit = 10;
            }
        }    

        if(isset($request->search)) {
            
            $search = trim($request->search);
            $su_living_skills = $su_living_skills->where('ls.description','like','%'.$search.'%');             //search by date or title
            // echo $search; die;
        }  

        $su_living_skills = $su_living_skills->paginate($limit);                      
        
        $page = 'service-users-earn-schm';
        return view('backEnd.serviceUser.earningScheme.living_skill', compact('page','limit', 'service_user_id','su_living_skills','search')); 
    }  

    public function living_skill_view($living_skill_id = null) {

        $ls_info = ServiceUserLivingSkill::select('su_living_skill.*','ls.description')
                            ->join('living_skill as ls','su_living_skill.living_skill_id','=','ls.id')
                            ->where('su_living_skill.id',$living_skill_id)
                            ->first();
        
        $page = 'service-users-earn-schm';
        return view('backEnd.serviceUser.earningScheme.living_skill_form', compact('page','ls_info','search'));
    } 
    /*------- Living Skill End --------*/


    /*------- Education Records Start --------*/
    public function education_record($service_user_id, Request $request) {   

        $su_edu_records = ServiceUserEducationRecord::select('su_education_record.*','er.description')
                                    ->join('education_record as er','su_education_record.education_record_id','=','er.id')
                                    ->where('er.status','1')
                                    ->where('su_education_record.is_deleted','0')
                                    ->where('su_education_record.service_user_id',$service_user_id)
                                    ->orderBy('su_education_record.id','desc')
                                    ->orderBy('su_education_record.created_at','desc');
                                    // ->get()
                                    // ->toArray();
        // echo "<pre>"; print_r($su_daily_record_query); die;
        
        $search = '';

        if(isset($request->limit)) {
            $limit = $request->limit;
            Session::put('page_record_limit',$limit);
        } else{

            if(Session::has('page_record_limit')){
                $limit = Session::get('page_record_limit');
            } else{
                $limit = 10;
            }
        }    

        if(isset($request->search)) {
            
            $search = trim($request->search);
            // echo $search; die;
            $su_edu_records = $su_edu_records->where('er.description','like','%'.$search.'%');             //search by date or title
        }  

        $su_edu_records = $su_edu_records->paginate($limit);                      
        
        $page = 'service-users-earn-schm';
        return view('backEnd.serviceUser.earningScheme.education_record', compact('page','limit', 'service_user_id','su_edu_records','search')); 
    }

    public function education_record_view($education_record_id = null) {

        $ed_info =  ServiceUserEducationRecord::select('su_education_record.*','er.description')
                                ->join('education_record as er','su_education_record.education_record_id','=','er.id')
                                ->where('su_education_record.id',$education_record_id)
                                ->first();
        
        $page = 'service-users-earn-schm';
        return view('backEnd.serviceUser.earningScheme.education_record_form', compact('page','ed_info','search'));
    }
    /*------- Education Records End --------*/


    /*------- MFC Records --------*/
    public function mfc($service_user_id = null, Request $request) {

        $this_location_id  = DynamicFormLocation::getLocationIdByTag('mfc');

        $mfc_records       = DynamicForm::select('dynamic_form.id','dynamic_form.user_id','dynamic_form.title','dynamic_form.date','u.name')
                                    ->join('user as u','u.id','dynamic_form.user_id')
                                    ->where('dynamic_form.location_id',$this_location_id)
                                    ->where('dynamic_form.service_user_id',$service_user_id)
                                    ->where('dynamic_form.is_deleted','0')
                                    ->orderBy('dynamic_form.id','desc');
                                    // ->get()
                                    // ->toArray();

        
        $search = '';

        if(isset($request->limit)) {
            $limit = $request->limit;
            Session::put('page_record_limit',$limit);
        } else{

            if(Session::has('page_record_limit')){
                $limit = Session::get('page_record_limit');
            } else{
                $limit = 10;
            }
        }    

        if(isset($request->search)) {
            
            $search = trim($request->search);
            $mfc_records = $mfc_records->where('dynamic_form.title','like','%'.$search.'%');            
        }  

        $mfc_records = $mfc_records->paginate($limit);                      
        
        $page = 'service-users-earn-schm';
        return view('backEnd.serviceUser.earningScheme.mfcs', compact('page','limit', 'service_user_id','mfc_records','search'));   
    }

    public function mfc_view($d_mfc_id = null) {

        $result = DynamicForm::showFormWithValue($d_mfc_id, false);
        // echo "<pre>"; print_r($result); die;
        $service_user_id = $result['service_user_id'];
        $result = $result['form_data'];
        // echo $service_user_id; die;
        // return $result;

        $page = 'service-users-earn-schm';
        return view('backEnd.serviceUser.earningScheme.mfc_form', compact('result','page','service_user_id', 'd_mfc_id')); 
    }
    /*------- MFC Records End --------*/

}