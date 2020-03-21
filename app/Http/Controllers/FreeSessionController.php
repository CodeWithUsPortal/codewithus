<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use App\Topic;
use App\FreeSessionTimeSlot;
use App\Day;
use App\Time;
use App\Role;
use App\User;
use App\FreeSessionBooking;


class FreeSessionController extends Controller
{
    public function formOptions(){
        return view('free_session.free_session_form_options');
    }
    public function signUpForm(){   
        return view('free_session.signup_form');
    }
    public function signInForm(){
        return view('free_session.signin_form');
    }
    public function findStudentRecordForFreeSession(Request $request){
        $freeSessionUserData = FreeSessionBooking::where(['student_name' => $request->student_name])->get();
        if(count($freeSessionUserData) >= 1){
            return response()->json(['response_msg'=>'Record found'],200); 
        }
        else{
            return response()->json(['response_msg'=>'Record not found'],200); 
        }
    }
    public function allLocationsForFreeSession(){
        $locations = Location::where(['is_deleted' => false,
                                      'show_free_session' => true])->get();
        return response()->json(['locations'=> $locations],200);
    }
    public function allTopics(){
        $topics = Topic::where(['is_deleted' => false])->get();
        return response()->json(['topics'=> $topics],200);
    }
    public function getAvailableTimeSlotsForALocation(Request $request){
        $timeSlots = FreeSessionTimeSlot::where(['location_id' => $request->location_id])->get();
        $availableTimeSlots = array();
        foreach($timeSlots as $timeSlot){
            $day = Day::where('id', $timeSlot->day_id)->value('name');
            $time = Time::where('id', $timeSlot->time_id)->value('time');
            $dateOfTheDay = "next ".$day;
            $date = date('M d, l', strtotime($dateOfTheDay));
            $dataArray = ["timeslot_id" => $timeSlot->id,
                          "timeslot_datetime" => $date." at ".$time,
            ];
            array_push($availableTimeSlots,$dataArray);
        }
        return response()->json(['availableTimeSlots'=> $availableTimeSlots],200);
    }
    public function addFreeSession(Request $request){
        $phoneNumberInput = str_replace(array('(',')'," ","-"), '',$request->phone_number);
        $phoneNumber = $phoneNumberInput;
        if($phoneNumber[0] != "+" && $phoneNumber[0] != 1){
            $phoneNumber = "+1".$phoneNumber;
        }
        elseif($phoneNumber[0] != "+" && $phoneNumber[0] == 1){
            $phoneNumber = "+".$phoneNumber;
        }

        $roleId = Role::where('role', "student")->value('id');

        $freeSessionBooking = new FreeSessionBooking();
        $freeSessionBooking->location_id = $request->location_id;
        $freeSessionBooking->student_name = $request->student_name;
        $freeSessionBooking->student_age = $request->student_age;
        $freeSessionBooking->phone_number = $phoneNumber;
        $freeSessionBooking->email = $request->email;
        $freeSessionBooking->topic_id = $request->topic_id;
        $freeSessionBooking->ad_source = $request->ad_source;
        $freeSessionBooking->free_session_time_slot_id = $request->time_slot_id;
        $freeSessionBooking->expectations = $request->expectations;
        $freeSessionBooking->save();

        $user = new User();
        $user->user_name = $request->student_name;
        $user->password = null;
        $user->full_name = $request->student_name;
        $user->email = $request->email;
        $user->phone_number = $phoneNumber;
        $user->role_id = $roleId;
        $user->topic_id = $request->topic_id;
        $user->is_free_session = true;
        $user->save();

        $location = Location::find($request->location_id);
        $location->users()->attach($user);

        return response()->json(['response_msg'=>'Data Saved'],200);
    }  

}
