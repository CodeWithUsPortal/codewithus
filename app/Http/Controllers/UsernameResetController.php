<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use phpDocumentor\Reflection\Types\Null_;

class UsernameResetController extends Controller
{
    public function forgotUserNameForm(){
        return view('auth.custom_resets.forgot_username');
    }
    public function getUserNames(Request $request){
        $users = User::where('phone_number', $request->phone_number)->get();
        $userNames = array();
        if(count($users)>0){
            foreach($users as $user){
                $condition = stripos($user->full_name, $request->name) ;
                if($condition !== false){
                    $dataArray = ["username" => $user->user_name,
                                  "fullname" => $user->full_name,
                    ];
                    array_push($userNames, $dataArray);   
                }
            }
        }
        if(count($userNames) > 0){
            return $userNames; 
        } 
        else{
            return response()->json(['response_msg'=>'No Usernames exists with this information'],200);
        }                   
    }
    public function getUserNamesUsingPhoneNumbers(Request $request){
        $phoneNumberInput = str_replace(array('(',')'," ","-"), '',$request->phone_number);
        $phoneNumber = $phoneNumberInput;
        if($phoneNumber[0] != "+" && $phoneNumber[0] != 1){
            $phoneNumber = "+1".$phoneNumber;
        }
        elseif($phoneNumber[0] != "+" && $phoneNumber[0] == 1){
            $phoneNumber = "+".$phoneNumber;
        }

        $users = User::where('phone_number', $phoneNumber)->get();
        $userNames = array();
        if(count($users)>0){
            foreach($users as $user){
                    $role = Role::where('id', $user->role_id)->value('role');
                    if($user->password == null || $user->password ==""){
                        $isPasswordAvailable = "No";
                    }
                    else{
                        $isPasswordAvailable = "Yes";
                    }
                    if($role == "teacher"){
                        $user_full_name = $user->full_name." (Teacher)";
                    }
                    else if($role == "admin"){
                        $user_full_name = $user->full_name." (Admin)";
                    }
                    else if($role == "student"){
                        $user_full_name = $user->full_name;
                    }
                    $dataArray = ["username" => $user->user_name,
                                  "fullname" => $user_full_name,
                                  "phone_number" => $user->phone_number,
                                  "is_password_available" => $isPasswordAvailable,
                    ];
                    array_push($userNames, $dataArray);   
            }
        }
        if(count($userNames) > 0){
            return $userNames; 
        } 
        else{
            return response()->json(['response_msg'=>'No Usernames exists with this information'],200);
        }                   
    }

    public function setUserPassword(Request $request){
        $user = User::where(['user_name' => $request->user_name,
                             'full_name' => $request->full_name,
                             'phone_number' => $request->phone_number])->first();
        $user->password=bcrypt($request->password);
        $user->update();
        return response()->json(['response_msg'=>'Password Saved'],200);
    }
}
