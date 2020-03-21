<?php

namespace App\Domain;

class TokyFunctions{
    function sms_send($to, $message){
        // create a new cURL resource
        $ch = curl_init();
        $api_key = '146c720219c08bf338cfd504bd13eff1b1d4c2efc6f665ed16dfa84f4e1c20e1';
        $headers = array();
        $headers[] = "X-Toky-Key: {$api_key}";
        //{"from":"+16282275444", "to": "+16282275222", "text": "Hello from Toky"}
        $data = array("from" => "+14089097717", "email" => "team@codewithus.com",
                    "to" => $to, 
                    "text" => $message);
       
        $json_data = json_encode($data);   
    
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, "https://api.toky.co/v1/sms/send");
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch,CURLOPT_POSTFIELDS, $json_data);
           
        $curl_response = curl_exec($ch); // Send request
        curl_close($ch); // close cURL resource 

        $decoded = json_decode($curl_response,true);
        var_export($decoded);
        return "Response:"; 
    }
}
