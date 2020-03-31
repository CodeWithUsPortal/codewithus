<?php


namespace App\Helper;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Helper
{
    public static function sendSMS($text, $number)
    {
        $ch = curl_init();
        $api_key = Config::get('settings.sms.api_key');
        $headers = array();
        $headers[] = "X-Toky-Key: {$api_key}";

        $data = array
        (
            "from" => Config::get('settings.sms.from'),
            "email" => Config::get('settings.sms.email'),
            "to" => $number,
            "text" => $text
        );

        $json_data = json_encode($data);

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, Config::get('settings.sms.url'));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, Config::get('settings.sms.method'));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $json_data);

        $curl_response = curl_exec($ch); // Send request

        // close cURL resource
        curl_close($ch);
    }

    public static function generateUniqueValue($table, $column)
    {
        $random = Str::random(4);

        $isDuplicate = DB::table($table)->where($column, $random)->count();

        while ($isDuplicate > 0)
        {
            $random = Str::random(4);
            $isDuplicate = DB::table($table)->where($column, $random)->count();
        }

        return Str::lower($random);
    }

    public static function isStudentAuthorized($id)
    {
        
    }
}