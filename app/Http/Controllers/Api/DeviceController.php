<?php 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use App\UserDevice;


class DeviceController extends Controller
{

    public function add_su_device(Request $request) {
        
        $data = $request->input();

        $validator = Validator::make($data, [
            'service_user_id' => 'required',
            'device_token'    => 'required',
            'device_unique_id'=> 'required',
            'device_type'     => 'required',
        ]);

        if($validator->fails()) {

            $result['response'] = false;
            $result['message']  = FILL_FIELD_ERR;
            return json_encode($result);
        }

        $data['user_type'] = 0;        
        $data['user_id']   = $data['service_user_id'];        

        $res = $this->_save_device($data);

        if($res == true){
            $result['response'] = true;
            $result['message']  = 'Device added successfully';
        } else{
            $result['response'] = false;
            $result['message']  = COMMON_ERROR;
        }
        return json_encode($result);
    }

    public function add_user_device(Request $request){
        $data = $request->input();

        $validator = Validator::make($data, [
            'user_id' => 'required',
            'device_token'    => 'required',
            'device_unique_id'=> 'required',
            'device_type'     => 'required',
        ]);

        if($validator->fails()) {

            $result['response'] = false;
            $result['message']  = FILL_FIELD_ERR;
            return json_encode($result);
        }

        $data['user_type'] = 1;      
        $res = $this->_save_device($data);

        if($res == true){
            $result['response'] = true;
            $result['message']  = 'Device added successfully';
        } else{
            $result['response'] = false;
            $result['message']  = COMMON_ERROR;
        }
        return json_encode($result);

    }

    function _save_device($data){

        $user_device = UserDevice::where('device_unique_id',$data['device_unique_id'])->where('device_type',$data['device_type'])->first();

        if(empty($user_device)){
            $user_device                = new UserDevice;
        }
        $user_device->user_id           = $data['user_id'];
        $user_device->user_type         = $data['user_type'];
        $user_device->device_token      = $data['device_token'];
        $user_device->device_unique_id  = $data['device_unique_id'];
        $user_device->device_type       = $data['device_type'];

        if($user_device->save()){
            return true;
        } else{
            return false;
        }
    }
    
    
    public function remove_device(Request $request) {

        $data = $request->input();

        if( !empty($data['user_id']) && !empty($data['user_type']) ) {
            $delete = UserDevice::where(['id' => $user_id, 'user_type' => $user_type])->delete();
            if($delete) {
                $result['response'] = true;
                $result['message']  = 'Device remove successfully.';
            } else {
                $result['response'] = false;
                $result['message']  = COMMON_ERROR;
            }
        } else {
            $result['response'] = false;
            $result['message']  = "Please fill the required fields.";
        }
        return json_encode($result);
    }

   
}