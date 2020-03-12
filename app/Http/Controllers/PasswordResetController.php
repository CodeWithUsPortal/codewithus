<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Location;
class PasswordResetController extends Controller
{
    public function showPasswordResetForm(){
        // Check if the entered username matches with a record
        return view('auth.custom_resets.forgot_password_form')
                ->withError("");
    }
    public function validateUsername(Request $request){
        $user = User::where('user_name', $request->user_name)->get();
        if(count($user) > 0){
            return view('auth.custom_resets.password_secret_code')
                  ->withUsername($request->user_name)->withPassword($request->password)->withError("");
        }
        else{
            return view('auth.custom_resets.forgot_password_form')
                        ->withError("No User record exists with this Username");
        }
    }

    public function validateSecretTokenAndUpdatePassword(Request $request){
        $userLocationId = User::where('user_name', $request->user_name)->value('location_id');
        $userLocationSecretCode = Location::where('id', $userLocationId)->value('secret_code');
        if($request->secret_code == $userLocationSecretCode){
            $data = User::where('user_name', $request->user_name)->first();
            $data->password =  bcrypt($request->password);        
            $data->save();
            return redirect('/');
        }
        else{
            return view('auth.custom_resets.password_secret_code')
                  ->withUsername($request->user_name)->withPassword($request->password)->withError("Incorrect Security Code");
        }
    }

}
