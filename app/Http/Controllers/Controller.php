<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\EarningScheme;
use illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	/*public function bug_report(Request $request){
        $bug_report = $request->input();
    	return view('frontEnd.error_500',compact('bug_report'));

    }*/
	public function app_icon_format($icon = null) {

		$icon_array = explode(' ', $icon);
		if(isset($icon_array['1'])) {
			$icon  = $icon_array['1'];
			$icon  = str_replace('-', '_', $icon);
		}
		return $icon;
    }
    
    public function replace_null($array) {
        foreach ($array as $key => $value) 
        {
            if(is_array($value)){
                $array[$key] = $this->replace_null($value);
            }
            else
            {
                if (is_null($value) ){
                  $array[$key] = "";
                }
            }
        }
        return $array;
    }

    public function fb_close(){   
        //window.opener.location.reload()
        echo "<script>
                self.close();
                window.opener.alert('Sharing with facebook has been done succesfully.');
            </script>";
    }

    public function get_location_from_lat_long($lat,$long){
        
        //8.407168
        //Samrala Chowk/Coordinates 30.9108° N, 75.8793° E
        $address        = "";
        $city           = "";
        $state          = "";
        $country        = "";

        $geoLocation    = array();
        $URL            = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&sensor=false';
        $data           = file_get_contents($URL);
        $geoAry         = json_decode($data,true);

        if(!empty($geoAry)) {
            for($i=0;$i<count($geoAry['results']);$i++){
                if($geoAry['results'][$i]['types'][0]=='sublocality_level_1'){
                    
                    $address = (isset($geoAry['results'][$i]['address_components'][0]['long_name'])) ? $geoAry['results'][$i]['address_components'][0]['long_name'] : '';

                    $city    = (isset($geoAry['results'][$i]['address_components'][1]['long_name'])) ? $geoAry['results'][$i]['address_components'][1]['long_name'] : '';

                    $state   = (isset($geoAry['results'][$i]['address_components'][3]['long_name'])) ? $geoAry['results'][$i]['address_components'][3]['long_name'] : '';
                    $country = (isset($geoAry['results'][$i]['address_components'][4]['long_name'])) ? $geoAry['results'][$i]['address_components'][4]['long_name'] : '';
                    break;
                }else{
                    $address = (isset($geoAry['results'][0]['address_components'][2]['long_name'])) ? $geoAry['results'][0]['address_components'][2]['long_name'] : '';
                    $city    = (isset($geoAry['results'][0]['address_components'][3]['long_name'])) ? $geoAry['results'][0]['address_components'][3]['long_name'] : '';
                    $state   = (isset($geoAry['results'][0]['address_components'][5]['long_name'])) ? $geoAry['results'][0]['address_components'][5]['long_name'] : '';
                    $country = (isset($geoAry['results'][0]['address_components'][6]['long_name'])) ? $geoAry['results'][0]['address_components'][6]['long_name'] : '';
                }
            }   
        }

        $geoLocation = array(
            'address'=>$address,
            'city'=>$city,
            'state'=>$state,
            'country'=>$country
        );
        
        $geoLocation = implode(', ', $geoLocation);
        return $geoLocation;
        //echo '<pre>'; print_r($geoAry); die;
    }

    function validateLatLong($lat, $long) {
        return preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $lat.','.$long);
    }

    /*public function sendIosNotification($device_id, $data){
        // Put your device token here (without spaces):
        $deviceToken = $device_id;
        // Put your private key's passphrase here:
        $passphrase = "1234";
        // Put your alert message here:
        $message = "Message";

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', pemFilePath);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp) {
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        }
        //echo 'Connected to APNS' . PHP_EOL;

        // Create the payload body
        $body['aps'] = array(
            'alert'         => array(
                'title-loc-key' => $data['title-loc-key'],
                'loc-key'       => $data['loc-key'],
            ),
            'sound'         => 'default',
            //"data"          => $data['more_data'],
        );

        // Encode the payload as JSON
        $payload = json_encode($body);
    
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        //pr($deviceToken); 
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        if (!$result) {
             //echo 'Message not delivered' . PHP_EOL;
            return 1;
        } else {
             //echo 'Message successfully delivered' . PHP_EOL;
            return 0;
        }

        // Close the connection to the server
        fclose($fp);
        //DIE;
    }*/
    

    /*public function checkKeys($required_keys, $available_keys) { 
     
        $error = false;
        foreach($required_keys as $key){
    
            if(!array_key_exists($key,$available_keys)){
                $error = true;
            }
        }
        
        if($error == true){ //echo 'm'; die;
            $result['result']['response'] = false;
            $result['result']['message'] = 'Required fields are missing.';
            return json_encode($result);
        } 
        return true;
    }*/

    /*public function app_icon_format() {

    	$earns = EarningScheme::where('is_deleted','0')->get()->toArray();
    	foreach ($earns as $value) {
    		$icon = $value['icon'];

    		$icon_array = explode(' ', $icon);
    		if(isset($icon_array)) {
    			$icon = $icon_array['1'];
    			$icon_show  =str_replace('-', '_', $icon);
    		}
    	}
    	echo "<pre>"; print_r($icon_show); die;

    }*/
    
    public function notifyFcm($device_id, $message) //For Android 
    {
        $fcm_key  = 'AIzaSyA9uQ4pTwwMBrxOmAIFlk5BgVm3I5QG624'; // FCM key(provided by android)
        $api_keys = [$fcm_key];     

        foreach($api_keys as $api_key){

            $url = 'https://fcm.googleapis.com/fcm/send';
            
            $msg = array(
                'registration_ids'  =>  $device_id,
                'data'              =>  $message,
            );
            //prx($message); die;  

            $headers = array(
                //'Authorization: key=' . API_ACCESS_KEY_AGRONXT,
                'Authorization: key=' . $api_key,
                'Content-Type: application/json'
            );
            // Open connection
            $ch = curl_init();

            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg));

            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            
            // Close connection
            curl_close($ch);
            //pr($result);die;
        }
        
        return  $result;
    }
}
