<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Role;
use App\Domain\TokyFunctions;

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
    public function loginFormForUsersExceptParents(){
        return view('auth.users_login');
    }
    public function parentsPhoneNumberForm(){
        return view('auth.parents_phone_no_form');
    }
    public function parentsLoginTokenForm(Request $request,TokyFunctions $toky){

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

		$toky->sms_send($phoneNumber, $text);
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
