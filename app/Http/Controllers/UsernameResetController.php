<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

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
        $users = User::where('phone_number', $request->phone_number)->get();
        $userNames = array();
        if(count($users)>0){
            foreach($users as $user){
                    $dataArray = ["username" => $user->user_name,
                                  "fullname" => $user->full_name,
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
}
