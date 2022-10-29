<?php

namespace App\Http\Controllers\backEnd\PersonalManagement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin, App\Home;
use Session;
use DB;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $admin_id = Session::get('scitsAdminSession')->id;
        
        $profile = DB::table('admin')->where('is_deleted',0)->where('id', $admin_id)->select('id', 'name', 'user_name', 'email', 'company', 'image')->first();
        // echo "<pre>"; print_r($profile); die;
        $page = 'dashboard';
        return view('backEnd.common.profile_form', compact('profile', 'page'));

    }

    public function edit(Request $request)
    {
        $admin_id = Session::get('scitsAdminSession')->id;
        
        if($request->isMethod('post')) {

            $profile  = Admin::find($admin_id);
            $profile->name      = $request->name; 
            $profile->user_name = $request->user_name; 
            $profile->email     = $request->email; 
            $profile->company   = $request->company; 
            $admin_old_image    = $profile->image;

            if(!empty($_FILES['image']['name']))    {

                $tmp_image  =   $_FILES['image']['tmp_name'];
                $image_info =   pathinfo($_FILES['image']['name']);
                $ext        =   strtolower($image_info['extension']);
                $new_name   =   time().'.'.$ext; 
               
                if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png')
                {
                    $destination =   base_path().adminbasePath; 
                    if(move_uploaded_file($tmp_image, $destination.'/'.$new_name))
                    {
                        if(!empty($admin_old_image)){
                            if(file_exists($destination.'/'.$admin_old_image))
                            {
                                unlink($destination.'/'.$admin_old_image);
                            }
                        }
                        $profile->image = $new_name;
                    }
                }
            }
            if($profile->save()) {
                
                return redirect('admin/dashboard')->with('success, Profile editted successfully');
            } else {
	    		return redirect()->back()->with('error, Some error occured');
            }
    	}
    }

    public function check_admin_username(Request $request) {
    
        $data = $request->input();
        $user_name = '';

        if(is_array($data)) {

            $user_name_arr = array_values($data);
            $user_name = $user_name_arr[0];
        }

        $response = Home::userNameUnique($user_name);

        if($response){
            echo '{"valid": true}';
        } else{
            echo '{"valid": false}'; 
        }
        die;
    }
}
