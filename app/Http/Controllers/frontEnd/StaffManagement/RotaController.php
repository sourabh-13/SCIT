<?php
namespace App\Http\Controllers\frontEnd\StaffManagement;
use App\Http\Controllers\frontEnd\StaffManagementController;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\User, App\StaffRota, App\AccessLevel, App\RotaShift, App\Home;
use DB, Auth, Session;
use TCPDF;

class RotaController extends StaffManagementController
{
    public function index(Request $request, $print = false) {
        //  echo "<pre>"; print_r($request->input()); die;
        // dd($request);
        $home_id = Auth::user()->home_id;
        $rota_time_format = Home::where('id', $home_id)->value('rota_time_format');
        // echo $rota_time_format; die;
        // dd($request->Session()->all());
        $staff_query = User::select('id','name','image')
                            ->where('home_id',$home_id)
                            ->where('status','1')
                            ->where('is_deleted','0');
        //echo "<pre>"; print_r($staff_query); die;
        $user_acc_rights = User::where('id',Auth::user()->id)->value('access_rights'); 
        $user_acc_rights_ary = explode(',',$user_acc_rights);
        if(!in_array('256',$user_acc_rights_ary)){ //if user has no right to edit his own page
            $staff_query = $staff_query->where('id', '!=', Auth::user()->id);
        }

        if(!isset($request->rota_sel_access_level)) { //by default case

            if(Session::has('rota_access_level')){ //filter
                $rota_access_level = Session::get('rota_access_level');
            } else{ 
                ///when the page is open for the first time
                $rota_access_level = 0;
            }

        } else {  //on changing select box option 

            $rota_access_level = $request->rota_sel_access_level;
            Session::put('rota_access_level', $rota_access_level);
        }

        if($rota_access_level > 0){ //donot filter the staff when access level is set to all access level 
            $staff_query = $staff_query->where('access_level', $rota_access_level);
        }

        $staff = $staff_query->get()->toArray();
        //if(isset($request->start-date)){
        if(isset($_GET['start-date'])) { 
            $start_date = $_GET['start-date']; 
        }  else { 
            $start_date = date('Y-m-d'); //today
        }

        $startDate = Carbon::parse($start_date);
        
        $dayOfWeek = $startDate->dayOfWeek; 
        
        $dayOfWeek--;
        $start_day = date('Y-m-d',strtotime('-'.$dayOfWeek.' days', strtotime($start_date)));  

        $weeks = $this->_get_copy_weeks();

        // echo $start_day; die;
        foreach($staff as $key => $member) {
            $staff[$key]['rota'] = $this->getStaffWeeklyRota($member['id'],$start_day,$home_id);
        }
        //echo "<pre>"; print_r($staff); die;
        $shift_types = RotaShift::select('rota_shift.id as type_id','rota_shift.name','rota_shift.start_time','rota_shift.end_time','s_color.color')
                                    ->leftJoin('rota_shift_color as s_color','s_color.id','=','rota_shift.rota_shift_color_id')
                                    ->where('is_deleted','0')
                                    ->where('home_id', $home_id)
                                    ->get();
       //echo '<pre>'; print_r($shift_types); die;
 
        $access_level_name = AccessLevel::select('id','name')
                                        ->where('home_id',$home_id)
                                        ->where('is_deleted','0')
                                        ->get();
        if($print == false) {
            return view('frontEnd.staffManagement.rota',compact('staff','start_day','shift_types','access_level_name','rota_access_level','rota_time_format','start_date','weeks'));              
        } else {
            $res                      = [];
            $res['staff']             = $staff;
            $res['shift_types']       = $shift_types;
            $res['access_level_name'] = $access_level_name;
            $res['rota_time_format']  = $rota_time_format;
            $res['start_day']         = $start_day;
            $res['rota_access_level'] = $rota_access_level;
            $res['start_date']        = $start_date;
            return $res;
        }   
    }

    function _get_copy_weeks(){

        $today_date = date('Y-m-d'); //today
        $startDate  = Carbon::parse($today_date);
        $dayOfWeek  = $startDate->dayOfWeek; 
        
        $dayOfWeek--;
        $start_day = date('Y-m-d',strtotime('-'.$dayOfWeek.' days', strtotime($today_date)));  

        $weeks = [];
        $inc_date = $start_day;
        $f = 0;
        for($i=0; $i<=10; $i++){

            if($f == 0) { 
                $next_week_start = $start_day;  
                $next_week_end   = date('Y-m-d',strtotime('+6 days', strtotime($next_week_start)));
                $inc_date        = $next_week_end;

                $weeks[$i]['start_date'] = $next_week_start;
                $weeks[$i]['end_date']   = $next_week_end; 
                $f = 1;               
            } else {
                $next_week_start = date('Y-m-d',strtotime('+1 days', strtotime($inc_date)));  
                $next_week_end   = date('Y-m-d',strtotime('+6 days', strtotime($next_week_start)));
                $inc_date        = $next_week_end;

                $weeks[$i]['start_date'] = $next_week_start;
                $weeks[$i]['end_date']   = $next_week_end;                
            }

        }
        return $weeks;
    }

    public function copy_rota(Request $request){
        
        // echo $request->copy_start_date; die;
        $print = true;
        $data  = $this->index($request,$print);
        $staff = $data['staff'];

        if(empty($request->copy_start_date)){
            return redirect()->back()->with('error','No week selected');
        }
        /*echo "<pre>"; 
        print_r($request->input());
        print_r($data); die;*/
        
        foreach ($staff as $key => $staff_member) {
            // echo "<pre>"; print_r($data); die;
            $i = 0;
            foreach ($staff_member['rota'] as $key_date => $rota) {
                
                $week_day = date('Y-m-d',strtotime('+'.$i.' days', strtotime($request->copy_start_date)));  
                $i++;
                //echo $week_day.' '; 
                if(!empty($rota)){
                    $rota_id = $rota['id'];
                    $staff_id = $staff_member['id'];

                    $rota_info = StaffRota::where('id',$rota_id)->first();

                    $rota = StaffRota::where('date',$week_day)->where('user_id',$staff_id)->first();
                    if(empty($rota)){
                        $rota               = new StaffRota;
                    }
                    $rota->date             = $week_day;
                    $rota->home_id          = $rota_info->home_id;
                    $rota->user_id          = $rota_info->user_id;
                    $rota->shift_type_id    = $rota_info->shift_type_id;
                    $rota->start_time       = $rota_info->start_time;
                    $rota->end_time         = $rota_info->end_time;
                    $rota->save();

                }
            }

        } //die;
        return redirect('staff/rota/view?start-date='.$request->copy_start_date)->with('success','Rota has been copied successfully');

    }

    function getStaffWeeklyRota($staff_id,$start_day,$home_id) {

        for($i=0;$i<=6;$i++) {

            $date = date('Y-m-d',strtotime('+'.$i.' days', strtotime($start_day)));  
            
            $rota[$date] = StaffRota::select('staff_rota.id','staff_rota.start_time','staff_rota.end_time','rs_color.color','rs.name','rs.rota_shift_color_id')
                            ->where('staff_rota.user_id',$staff_id)
                            ->join('rota_shift as rs','rs.id','staff_rota.shift_type_id')
                            ->join('rota_shift_color as rs_color','rs_color.id','rs.rota_shift_color_id')
                            ->whereDate('staff_rota.date','=',$date)
                            ->where('staff_rota.home_id',$home_id)
                            ->where('rs.is_deleted','0')
                            ->first();

            if(!empty($rota[$date])) {
                $rota[$date] = $rota[$date]->toArray();
            } else {
                $rota[$date] = array();
            }

        }
        return $rota; 
    }
  
    public function print_rota(Request $request){
        
        $print = true;
        $data  = $this->index($request,$print);
        // echo '<pre>'; print_r($data); die;
    
        $curr  = date('d-m-y');
        include('vendor/tcpdf/tcpdf.php');

        $tcpdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $tcpdf->AddPage('L','A4');
        $tcpdf->SetCreator(PDF_CREATOR);
        $title ="SCITS";
        //$subtitle = (isset($orderdata['Order']['opt_out']) && $orderdata['Order']['opt_out'])? 'This is an Opt Out PO' : '';
        $subtitle = 'dsds';
        $tcpdf->SetTitle('Rota Shifts');
        $tcpdf->SetSubject('Subject');

        $tcpdf->setPrintHeader(true);
        $tcpdf->setPrintFooter(false);
        // $tcpdf->SetDefaultMonospacedFont('helvetica');
        $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $tcpdf->SetMargins(10, 10, 10, true);
        //$tcpdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $tcpdf->SetFont('helvetica', '', 9);
        
        $tcpdf->setFontSubsetting(false);

      
        $tcpdf->setFontSubsetting(false);
        $list = '';
        $i    = 1;
            
        // $url = url('/public/images/userProfileImages/1515228124.jpg');
        $name = '';
        $rota_time_format = $data['rota_time_format'];
        // echo $rota_time_format; die;
        $staff = $data['staff'];


        $start_day       = $data['start_day'];
        $range_startdate = date('d M Y', strtotime($start_day));
        $range_enddate   = date('d M Y',strtotime('+6 days', strtotime($start_day)));
        $back_date_val   = date('Y-m-d',strtotime('-7 days', strtotime($start_day)));
        $next_date_val   = date('Y-m-d',strtotime('+7 days', strtotime($start_day)));

        $rota_access_level = $data['rota_access_level'];
        if($rota_access_level == '0') {
            $access_level_name = 'All Access Levels';
        } else {
            $access_level_name = AccessLevel::where('id', $rota_access_level)
                                            ->value('name');
        }

        $date = '';
        $day  = '';

        $week_date = '';
        for($i=0; $i<=6; $i++) {
            $date = date('M d',strtotime('+'.$i.' days', strtotime($start_day)));
            $day  = date('D',strtotime('+'.$i.' days', strtotime($start_day)));
            
            $week_date .= '<th style="padding: 20px 0;border:1px solid #d9d9d9;">'.$date.' ('.$day.')</th>';
        }

        $level_name = '';
        
        foreach ($staff as $key => $staff_member) {
            // echo "<pre>"; print_r($data); die;
            if(empty($staff)){
                echo '<tr><td colspan="8"><p style="font-weight:500">No Records Found.</p></td></tr>';
            } else {
                    
                $duration = 0;
                $user_image = '';

                if(isset($staff_member['image'])) {
                    $user_image = $staff_member['image'];
                }
                if(empty($user_image)) {
                    $user_image = 'default_user.jpg';
                }

                $image_url  = url(userProfileImagePath.'/'.$user_image);
                $staff_name = $staff_member['name'];
                //echo $staff_name.', ';
                $list .='<tr>
                            <td align="center" style="border:1px solid #d9d9d9;">
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td><img src="'.$image_url.'" style="border-radius:50px; display: inline-block;margin:10px;" width="20"><br>'.$staff_name.' </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        ';

                 foreach ($staff_member['rota'] as $key => $rota) {
                     // echo "<pre>"; print_r($rota); //die;
                    if(!empty($rota)){
                        if($rota_time_format == '12') {  
                            $start_time = StaffRota::timeFormat($rota['start_time']);
                            $end_time   = StaffRota::timeFormat($rota['end_time']);
                        } else {
                            $start_time = $rota['start_time'];
                            $end_time   = $rota['end_time'];
                            if(strlen($rota['start_time']) == 1) {
                                $start_time = '0'.$start_time;
                            }
                            if(strlen($rota['end_time']) == 1) {
                                $end_time = '0'.$end_time;
                            }
                        }

                        $start_hr = $rota['start_time'];
                        $end_hr   = $rota['end_time'];
                        $duration += $end_hr - $start_hr;

                        if($rota['color'] == 'labels') {
                            $color_class = '#A1A1A1';
                        } else if($rota['color'] == 'primary'){
                            $color_class = '#59ACE2';
                        } else if($rota['color'] == 'success'){
                            $color_class = '#A9D86E';
                        } else if($rota['color'] == 'info'){
                            $color_class = '#8175C7';
                        } else if($rota['color'] == 'inverse'){
                            $color_class = '#344860';
                        } else if($rota['color'] == 'warning'){
                            $color_class = '#FCB322';
                        } else if($rota['color'] == 'danger'){
                            $color_class = '#FF6C60';
                        } else {
                            $color_class = 'red';

                        }

                        // $color_class = $rota['color'].'-div';

                        $list .='<td align="center" style="border:1px solid #d9d9d9;">
                                    <table style="width:100%;font-size:8pt;" cellpadding="4" cellspacing="0">
                                        <tbody>
                                            <tr valign="middle">
                                                <td align="center" style="background-color:'.$color_class.';border-radius:4px;"><p style="margin:0; text-align:center;color:#fff;">'.$rota['name'].'<br><span style="font-size:6pt;">'.$start_time.' - '.$end_time.'</span> </p></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>                                    
                                ';

                    }  else { 
                        //$list .='<td>1</td> <td>2</td> <td>3</td> <td>4</td> <td>5</td> <td>6</td> <td>7</td> <td>8</td>';
                        $list .='<td align="center" style="border:1px solid #d9d9d9;">
                                    <table style="width:100%; font-size:8pt; background:#fff;border:1px solid #ddd;" cellpadding="4" cellspacing="0">
                                        <tbody>
                                            <tr valign="middle">
                                                <td style="line-height:1.9; border: 1px solid #c4c4c4;border-radius: 4px;font-size: 10pt; color:#aeaeae;font-weight:bolder;">+</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>';
                    } 

                }
                //calculate total weekly hrs

                $list .=' <td align="center" style="line-height:2.4; border:1px solid #d9d9d9;"><p style="text-align:left;font-weight:bolder; color:#aeaeae;font-size:10pt;">'.$duration.' Hr</p></td>';
                $list .= '</tr>';
                $i++;
            }
        }

        $home = Home::select('home.title as home_name','a.company as company_name','a.image as company_logo')
                    ->where('home.id',Auth::user()->home_id)
                    ->join('admin as a','a.id','home.admin_id')
                    ->first();
        $home_name     = ucfirst($home->home_name);
        $company_name  = ucfirst($home->company_name);
        $company_logo  = $home->company_logo;
        if(!empty($home->company_logo)){
            $company_logo = adminImgPath.'/'.$home->company_logo; 
            $company_logo = '<img src="'.$company_logo.'" style="height:30px; width:auto; float;right;display:inline-block; padding:0px; margin:0px;">';
        } else{
            $company_logo = '';
        }
        
        
// print_r($list); die;
$html =<<<EOD

        <table style="width:100%; font-size:14pt;background:#fff;border:1px solid #ddd;" cellpadding="10" cellspacing="0">
            <tbody>
                <tr>
                    <td style="text-align:right; color:#1F88B5;font-weight:bolder;border:none;width:60%;">Staff Rota </td>
                    <td style="text-align:right; color:#1F88B5;font-weight:bolder;border:none;width:40%">
                        {$company_logo}
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="width:100%; font-size:11pt; background:#fff;border:1px solid #ddd;padding:30px 0;" cellpadding="10" cellspacing="0">
            <tbody>
                <tr style="line-height:0;">
                    <td> 
                        <span style="color:#767676;font-weight:bolder;font-size:9pt; ">Company :</span> <span style="font-size:10pt; font-weight:lighter;color:#767676;">{$company_name}</span><br /> 
                    </td>
                    <td> 
                        <span style="color:#767676;font-weight:bolder;font-size:9pt; ">Home :</span> <span style="font-size:10pt; font-weight:lighter;color:#767676;">{$home_name}</span> <br />
                    </td>
                </tr>
                <tr style="line-height:0;">
                    <td>
                        <span style="color:#767676;font-weight:bolder;font-size:9pt; ">Duration :</span> <span style="font-size:10pt; font-weight:lighter;color:#767676;">{$range_startdate} to {$range_enddate}</span> 
                    </td>
                    <td> 
                        <span style="color:#767676;font-weight:bolder;font-size:9pt; ">Access Level :</span> <span style="font-size:10pt; font-weight:lighter;color:#767676;">{$access_level_name}</span> 
                    </td>
                </tr>
            </tbody>
        </table>
        
        <table style="width:100%; font-size:8pt; background:#fff;border:1px solid #ddd;" cellpadding="4" cellspacing="0">
            <thead style="background:#ddd; color:#767676;border:1px solid #ddd;">
                <tr>
                    <th style="padding: 20px 0;border:1px solid #d9d9d9;">Dates </th>
                    {$week_date}
                    <th style="padding: 20px 0;border:1px solid #d9d9d9;">Weekly Hours </th>
                </tr>
            </thead>
            <tbody style="text-align:center;" valign="middle">
                {$list}
            </tbody>
        </table>
EOD;

    $tcpdf->writeHTML($html, true, false, true, false, '');

        // $url= asset('/');
        $url=    public_path();
        //$save=$tcpdf->Output('example_048.pdf', 'I');
        $tcpdf->Output('Rota.pdf','I');
        return Redirect::back();

    }

    public function add_shift(Request $request) {
        
        // echo "<pre>"; print_r($request->input()); die;
        $home_id        = Auth::user()->home_id; 
        $shift_type_id  = $request->shift_type_id;
        $shift_date     = $request->date;
        $staff_id       = $request->staff_id;
        
        $start_day     = $request->start_day; //just for calculating total Hrs
        
        $result['response'] = false;
        if(!empty($shift_type_id)) {

            // $shift_type = DB::table('rota_shift_type as rs_type')
            //         ->select('rs_type.id as type_id','rs_type.name','rs_type.tag','rs_time.start_time','rs_time.end_time')
            //         ->leftJoin('rota_shift_time as rs_time','rs_time.shift_type_id','rs_type.id')
            //         ->where('rs_time.home_id',$home_id)
            //         ->where('rs_time.shift_type_id',$shift_type_id)
            //         ->first();
           $shift_type = RotaShift::select('rota_shift.name','rota_shift.start_time','rota_shift.end_time','s_color.color')
                                    ->leftJoin('rota_shift_color as s_color','s_color.id','=','rota_shift.rota_shift_color_id')
                                    ->where('rota_shift.id', $shift_type_id)
                                    ->where('home_id', $home_id)
                                    ->first();

            if(!empty($shift_type)) {

                $rota = staffRota::where('user_id',$staff_id)
                                ->where('home_id',$home_id)
                                //->where('shift_type_id',$shift_type_id)
                                ->whereDate('date',$shift_date)
                                ->first(); 
                
                if(empty($rota)) {
                    $rota                = new staffRota;
                    $rota->home_id       = $home_id;
                    $rota->user_id       = $staff_id;
                    $rota->shift_type_id = $shift_type_id;
                    $rota->date          = $shift_date;
                    $rota->start_time    = $shift_type->start_time;
                    $rota->end_time      = $shift_type->end_time;
                    
                    if($rota->save()) {

                        // update hr start
                        $hrs = $this->calc_staff_weekly_hrs($rota->user_id,$start_day,$rota->home_id);
                        // update hr end

                        $start_time = StaffRota::timeFormat($rota->start_time);
                        $end_time   = StaffRota::timeFormat($rota->end_time);

                        $result['response']     = true;
                        $result['start_time']   = $start_time;
                        $result['end_time']     = $end_time;
                        $result['rota_id']      = $rota->id;
                        $result['date']         = $rota->date;
                        $result['hrs']          = $hrs;                        
                    } 
                } else {
                    $result['response']     = 'already_exists';
                }
            } else {
                    $result['response']     = 'not_exist_shift_time';
            }
        }
        return $result; 
    }

    function calc_staff_weekly_hrs($staff_id, $start_day, $home_id){

        $weekly_rota = $this->getStaffWeeklyRota($staff_id,$start_day,$home_id);
        $duration = 0;
        foreach($weekly_rota as $day_rota){    
            if(!empty($day_rota)){

                $start_hr = $day_rota['start_time'];
                $end_hr   = $day_rota['end_time'];
                $duration += $end_hr - $start_hr;            
            }
        }        
        return $duration;
    }

    public function delete($rota_id, Request $req) {
        $start_day = $req->start_day;
        // echo $start_day; die;
        if(!empty($rota_id)) {
            $staff_id = staffRota::where('id', $rota_id)->value('user_id');
            $home_id = Auth::user()->home_id;
            $res = staffRota::where('id', $rota_id)->where('home_id', $home_id)->delete();
            // update hr start
            $hrs = $this->calc_staff_weekly_hrs($staff_id,$start_day,$home_id);
            // update hr end
            
            $result['response']  = true;
            $result['del_res']   = $res;
            $result['staff_id']  = $staff_id;    
            $result['hrs']       = $hrs;    

            // echo $res;
        } else {
             $result['response'] = 'not_exist_shift_time';
        }
        return $result;
        // die;
    }

    public function add_rota(Request $request) {
        
        $data = $request->input();

        $rota_time = RotaShift::where('id',$data['shift_type_id'])->first();

        if(empty($rota_time)) {
            //echo 'Please set shift start and end time first';
            return redirect()->back()->with('error','Please set rota shift timing from Admin.');
        } else {
           
            foreach ($data['staff_id'] as  $staff_id) {
                //ECHO $staff_id.'<br>'; 
                $start_day = $data['from_date'];
                $from_date = date_create($data['from_date']);
                $to_date   = date_create($data['to_date']);

                $days_diff = date_diff($from_date,$to_date);
                $days_diff = $days_diff->d;
                
                for($i=0; $i<=$days_diff; $i++) {

                    $date = date('Y-m-d',strtotime('+'.$i.' days', strtotime($start_day))); 

                    $rota_date = staffRota::select('id','date')->whereDate('date',$date)->where('user_id',$staff_id)->first();
                
                    if(!empty($rota_date)) {
                        $update = staffRota::where('id', $rota_date->id)->update(['shift_type_id' => $data['shift_type_id']]);
                       
                    } else {
                        $rota                = new staffRota;
                        $rota->home_id       = Auth::user()->home_id;
                        $rota->user_id       = $staff_id;
                        $rota->shift_type_id = $data['shift_type_id'];                  
                        $rota->date          = $date;
                        $rota->start_time    = $rota_time['start_time'];
                        $rota->end_time      = $rota_time['end_time'];
                        $rota->save();
                    }
                }
            } 
        }
        return redirect()->back()->with('success', 'Rota added successfully.');
    }

    public function view_rota($rota_id = null) {
        
        $home_id  = Auth::user()->home_id;

        $rota_record = staffRota::select('staff_rota.id as rota_id','staff_rota.start_time','staff_rota.end_time','u.name as staff_name','rs.name as shift_name','staff_rota.user_id as user_id')
                            ->leftJoin('user as u','u.id','staff_rota.user_id')
                            ->leftJoin('rota_shift as rs','rs.id','staff_rota.shift_type_id')
                            //->where('u.is_deleted','0')
                            ->where('staff_rota.home_id', $home_id)
                            ->where('staff_rota.id', $rota_id)
                            ->first();
        
        if(!empty($rota_record)) {
            $result['response'] = true;
            $result['rota_id']    = $rota_record->rota_id;
            $result['user_id']    = $rota_record->user_id;
            $result['start_time'] = $rota_record->start_time;
            $result['end_time']   = $rota_record->end_time;
            $result['staff_name']  = $rota_record->staff_name;
            $result['shift_name'] = $rota_record->shift_name;
        } else {
            $result['response'] = false;
        }
        return $result;
    }

    public function edit_shift(Request $request) {

        $data = $request->all();

        $rota_id = $data['e_rota_id'];
    
        if($request->isMethod('post')) {
            $home_id  = Auth::user()->home_id;

            $edit_rota = staffRota::find($rota_id);
            if(!empty($edit_rota)) {
                $u_home_id = User::where('id', $edit_rota->user_id)->value('home_id');
                if($u_home_id == $home_id) {
                    $edit_rota->start_time = $data['e_start_time'];
                    $edit_rota->end_time   = $data['e_end_time'];
                    if($edit_rota->save()) {
                        return redirect()->back()->with('success','Shift time updated successfully.');
                    } else {
                        return redirect()->back()->with('error','Some error occured,Plese try again later.'); 
                    }
                }
            } else {
                return redirect()->back()->with('error','UNAUTHORIZE_ERR');
            }
        }
    }

    /*function getStaffWeeklyRota($staff_id,$start_day){

        for($i=0;$i<=6;$i++) {

            $date = date('Y-m-d',strtotime('+'.$i.' days', strtotime($start_day)));  
            $rota[$date] = StaffRota::select('staff_rota.id','rota_shift_info.title','rota_shift_info.tag')->where('user_id',$staff_id)
                    ->join('rota_shift_info','rota_shift_info.id','staff_rota.shift_id')
                    ->whereDate('staff_rota.date','=',$date)
                    ->first();

            if(!empty($rota[$date])){
                $rota[$date] = $rota[$date]->toArray();
            } else{
                $rota[$date] = array();
            }

        }
        return $rota;
    }*/
}
