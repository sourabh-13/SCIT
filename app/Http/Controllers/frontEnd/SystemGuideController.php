<?php
namespace App\Http\Controllers\frontEnd;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ServiceUserDailyRecord, App\DailyRecordScores, App\DailyRecord, App\Notification;
use App\SystemGuide;
use DB, Auth;

class SystemGuideController extends Controller
{
    public function index($sys_guide_tag=null)
    {  
       //echo $guide_tag;
        $system_guide_query = SystemGuide::select('system_guide.id','system_guide.system_guide_category_id','system_guide.question','system_guide.answer','system_guide_category.category_name','system_guide.created_at','system_guide_category.tag')
                            ->join('system_guide_category','system_guide.system_guide_category_id','system_guide_category.id')
                            ->where('system_guide.is_deleted','0')
                            ->where('system_guide_category.tag',$sys_guide_tag)
                            ->orderBy('system_guide.id','desc')
                            ->get();
                            //->toArray();
         //echo '<pre>'; print_r($system_guide_query); die; 
            $content = '';
            $category_name = '';

            if(!empty($system_guide_query)){
                
                foreach($system_guide_query as $guide){
                   $category_name = $guide->category_name;
                   $content .= '
                        <div class="rec-head">
                            <div class="col-md-12 col-sm-12 col-xs-12 cog-panel p-0 r-p-15 record_row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <a  class="date-tab">
                                        <span class="pull-left">
                                        '.$guide->question.'
                                        </span>
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="rec-content">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12 cog-panel p-0 r-p-15">
                                    <label class="col-md-1 col-sm-1 col-xs-12 p-t-7 r-p-0"> Ans: </label>

                                    <div class="form-group col-md-11 col-sm-11 col-xs-12 r-p-0">
                                        <div class="input-group popovr">
                                            <textarea class="form-control trans" rows="4" disabled="" >'.$guide->answer.'</textarea>                                
                                        </div>
                                    </div>
                                 </div>
                            </div>
                        </div>';
                }
            }

            $result['category_name'] = $category_name;
            $result['content']       = $content;
            return $result;
        //die;
    }

    public function category_logged(){
        $system_guide_category = DB::table('system_guide_category')
                                ->select('id','category_name','tag')
                                ->orderBy('category_name','asc')
                                ->get();

        if(!empty($system_guide_category)){
            foreach($system_guide_category as $category){
            echo '<div class="rec-head">
                    <div class="col-md-12 col-sm-12 col-xs-12 cog-panel p-0 r-p-15 " style="margin-bottom:10px;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <a class="form-control curs-point category_click_modal" category_id='.$category->tag.' data-dismiss="modal"> '.$category->category_name.' </a>
                        </div>
                    </div>
                </div>';
            }
        }
    }

    public function searched_ques($ques)
    {
        $search_query = SystemGuide::select('id','question','answer')
                            ->where('system_guide.is_deleted','0')
                            ->where('question','like','%'.$ques.'%')
                            ->orderBy('system_guide.id','desc')
                            ->get();
          
            if(!empty($search_query)){
                
                foreach($search_query as $guide){
                   echo '
                        <div class="rec-head">
                            <div class="col-md-12 col-sm-12 col-xs-12 cog-panel p-0 r-p-15 record_row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <a  class="date-tab">
                                        <span class="pull-left">
                                        '.$guide->question.'
                                        </span>
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="rec-content">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12 cog-panel p-0 r-p-15">
                                    <label class="col-md-1 col-sm-1 col-xs-12 p-t-7 r-p-0"> Ans: </label>

                                    <div class="form-group col-md-11 col-sm-11 col-xs-12 r-p-0">
                                        <div class="input-group popovr">
                                            <textarea class="form-control trans" rows="4"  disabled="">'.$guide->answer.'</textarea>                                
                                        </div>
                                    </div>
                                 </div>
                            </div>
                        </div>';
                }
            }       
    }

}









   