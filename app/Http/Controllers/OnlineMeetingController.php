<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Configuration;

class OnlineMeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher|admin|parent|student');
    }

    public function onlineMeetingRoom(){
        $user = Auth::user();
        $role = $user->role->role;
        switch ($role) {
            case 'admin':
                return view('online_meeting.admin_username_online_meeting');
                break;
            case 'teacher':
                return view('online_meeting.teacher_username_online_meeting');
                break; 
            case 'parent':
                return view('online_meeting.parent_username_online_meeting');
                break; 
            case 'student':
                return view('online_meeting.student_username_online_meeting');
                break; 
        }
    }
    
    public function getUsernameForOnlineMeeting(Request $request){
        $user = Auth::user();
        $role = $user->role->role;
        $userNames = array();

        if($role == "parent"){
            $phoneNumber = $user->phone_number;
            $users = User::where('phone_number',$phoneNumber)->get();
            foreach($users as $user){
                $dataArray = [
                    "user_id" =>  $user->id,
                    "user_name" => $user->full_name,
                    "link_to_meeting" => "/join_online_meeting_room/".$user->full_name,
                ];
                array_push($userNames,$dataArray);
            }
        }
        else{
            $dataArray = [
                "user_id" =>  $user->id,
                "user_name" => $user->full_name,
                "link_to_meeting" => "/join_online_meeting_room/".$user->full_name,
            ];
            array_push($userNames,$dataArray);
        }
        return response()->json(['userNames'=> $userNames],200);
    }

    public function joinOnlineMeetingRoom($userName){
        $meetingId = Configuration::where('key', 'zoom_meeting_id')->value('value');
        $name = base64_encode($userName);
        $meetingLink = "https://zoom.us/wc/".$meetingId."/join?prefer=1&un=".$name;
        
        $user = Auth::user();
        $role = $user->role->role;
        switch ($role) {
            case 'admin':
                return view('online_meeting.admin_online_meeting')->withMeeting($meetingLink)->withUsername($userName);
                break;
            case 'teacher':
                return view('online_meeting.teacher_online_meeting')->withMeeting($meetingLink)->withUsername($userName);
                break; 
            case 'parent':
                return view('online_meeting.parent_online_meeting')->withMeeting($meetingLink)->withUsername($userName);
                break; 
            case 'student':
                return view('online_meeting.student_online_meeting')->withMeeting($meetingLink)->withUsername($userName);
                break; 
        }
    }
}
