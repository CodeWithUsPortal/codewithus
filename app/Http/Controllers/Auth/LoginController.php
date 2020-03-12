<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Role;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function parentsPhoneNumberForm(){
        return view('auth.parents_phone_no_form');
    }
    public function parentsLoginTokenForm(Request $request){

        $phoneNumberInput = str_replace(array('(',')'," ","-"), '',$request->phone_number);
        $phoneNumber = $phoneNumberInput;
        if($phoneNumber[0] != "+" && $phoneNumber[0] != 1){
            $phoneNumber = "+1".$phoneNumber;
        }
        elseif($phoneNumber[0] != "+" && $phoneNumber[0] == 1){
            $phoneNumber = "+".$phoneNumber;
        }

        $PhoneNumberMultipliedWithTwo =(string)((int)$request->phone_number) * 2;
        $last4Digits = substr($PhoneNumberMultipliedWithTwo, -4);
        $text = $last4Digits;
        Session::put($phoneNumber, $text);

        // //Start of SMS sending function
        $ch = curl_init();
        $api_key = '23480ecaa2d37d33905eae528df2d19e86c898c4653ec9e73b3d01ba96182f74';
        $headers = array();
        $headers[] = "X-Toky-Key: {$api_key}";
        //{"from":"+16282275444", "to": "+16282275222", "text": "Hello from Toky"}
        $data = array("from" => "+14089097717", "email" => "team@codewithus.com",
                    "to" => $phoneNumber, 
                    "text" => $text);
       
        $json_data = json_encode($data);   
    
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, "https://api.toky.co/v1/sms/send");
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch,CURLOPT_POSTFIELDS, $json_data);
           
        $curl_response = curl_exec($ch); // Send request
       
        curl_close($ch); // close cURL resource 
        //End of SMS sending function
        $decoded = json_decode($curl_response,true);
		
        return view('auth.parents_login_token_form')
                    ->withPhonenumber($phoneNumber);
    }
    public function parentsLogin(Request $request){
        
        $validToken = Session::get($request->user_name);
        if($request->code == $validToken) {
            $roleId = Role::where('role','parent')->value('id');

            $user = User::where('user_name',$request->user_name)->value('id');
            if($user == null){
                User::create([  
                    'user_name' => $request->user_name,
                    'password' =>  bcrypt("pass"),
                    'phone_number' => $request->user_name,
                    'role_id' => $roleId,
                ]);          
            }
            Session::forget($request->user_name);  
            return $this->login($request);      
        } else {
            return back()->with('wrong_security_code','The Security Code provided is wrong.');
        }
    }

    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
}
