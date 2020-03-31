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
use App\Domain\EmailFunctions;
use App\Domain\TokyFunctions;
use App\Domain\MailFunctions;
use Carbon\Carbon;
use App\TaskClass;
use Illuminate\Support\Facades\DB;

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
        
        $thisMondayAvailableTimeSlots = array();
        $thisTuesdayAvailableTimeSlots = array();
        $thisWednesdayAvailableTimeSlots = array();
        $thisThursdayAvailableTimeSlots = array();
        $thisFridayAvailableTimeSlots = array();
        $thisSaturdayAvailableTimeSlots = array();
        $thisSundayAvailableTimeSlots = array();

        $nextMondayAvailableTimeSlots = array();
        $nextTuesdayAvailableTimeSlots = array();
        $nextWednesdayAvailableTimeSlots = array();
        $nextThursdayAvailableTimeSlots = array();
        $nextFridayAvailableTimeSlots = array();
        $nextSaturdayAvailableTimeSlots = array();
        $nextSundayAvailableTimeSlots = array();

        $mondayAvailabilityTitle = "";
        $tuesdayAvailabilityTitle = "";
        $wednesdayAvailabilityTitle = "";
        $thursdayAvailabilityTitle = "";
        $fridayAvailabilityTitle = "";
        $saturdayAvailabilityTitle = "";
        $sundayAvailabilityTitle = "";

        $thisMondayWeekDate = "";
        $thisTuesdayWeekDate = "";
        $thisWednesdayWeekDate = "";
        $thisThursdayWeekDate = "";
        $thisFridayWeekDate = "";
        $thisSaturdayWeekDate = "";
        $thisSundayWeekDate = "";

        $nextMondayWeekDate = "";
        $nextTuesdayWeekDate = "";
        $nextWednesdayWeekDate = "";
        $nextThursdayWeekDate = "";
        $nextFridayWeekDate = "";
        $nextSaturdayWeekDate = "";
        $nextSundayWeekDate = "";

        $todaysWeekDay = $this->getTodaysWeekDay();
        $tomorrowsWeekDay = $this->getTomorrowsWeekDay();
        $dayAfterTomorrowsWeekDay = $this->getDayAfterTomorrowsWeekDay();

        foreach($timeSlots as $timeSlot){
            $day = Day::where('id', $timeSlot->day_id)->value('name');
            $time = Time::where('id', $timeSlot->time_id)->value('time');
            $dateOfTheDay = "next ".$day;
            $date = date('Y-m-d', strtotime($dateOfTheDay));
            $date = Carbon::createFromFormat('Y-m-d', $date);
            $daysToAdd = 7;
            $date = $date->addDays($daysToAdd);
            $date = date('Y-m-d', strtotime($date));
            $date = date('M d, l', strtotime($date));

            $dateTitle = date('M d', strtotime($date));
            $dateLabel = date('F d Y', strtotime($date));
            $dataArray = ["timeslot_id" => $timeSlot->id,
                          "timeslot_time" => $time,
                          "timeslot_datetime" => $date." at ".$time,
            ];
            switch ($day) {
                case 'Monday':
                    if($tomorrowsWeekDay != "Monday" && $dayAfterTomorrowsWeekDay != "Monday"){
                        $mondayAvailabilityTitle = $dateTitle;
                        $nextMondayWeekDate = $dateLabel;
                        array_push($nextMondayAvailableTimeSlots,$dataArray);
                    }else{
                        $dateOfTheDay = "next ".$day;
                        $date = date('Y-m-d', strtotime($dateOfTheDay));
                        $date = Carbon::createFromFormat('Y-m-d', $date);
                        $daysToAdd = 14;
                        $date = $date->addDays($daysToAdd);
                        $date = date('Y-m-d', strtotime($date));
                        $date = date('M d, l', strtotime($date));

                        $dateTitle = date('M d', strtotime($date));
                        $dateLabel = date('F d Y', strtotime($date));
                        $dataArray = ["timeslot_id" => $timeSlot->id,
                                    "timeslot_time" => $time,
                                    "timeslot_datetime" => $date." at ".$time,
                        ];
                        $mondayAvailabilityTitle = $dateTitle;
                        $nextMondayWeekDate = $dateLabel;
                        array_push($nextMondayAvailableTimeSlots,$dataArray);
                    }
                    break;
                case 'Tuesday':
                    if($tomorrowsWeekDay != "Tuesday" && $dayAfterTomorrowsWeekDay != "Tuesday"){
                        $tuesdayAvailabilityTitle = $dateTitle;
                        $nextTuesdayWeekDate = $dateLabel;
                        array_push($nextTuesdayAvailableTimeSlots,$dataArray);  
                    }
                    else{
                        $dateOfTheDay = "next ".$day;
                        $date = date('Y-m-d', strtotime($dateOfTheDay));
                        $date = Carbon::createFromFormat('Y-m-d', $date);
                        $daysToAdd = 14;
                        $date = $date->addDays($daysToAdd);
                        $date = date('Y-m-d', strtotime($date));
                        $date = date('M d, l', strtotime($date));

                        $dateTitle = date('M d', strtotime($date));
                        $dateLabel = date('F d Y', strtotime($date));
                        $dataArray = ["timeslot_id" => $timeSlot->id,
                                    "timeslot_time" => $time,
                                    "timeslot_datetime" => $date." at ".$time,
                        ];
                        $tuesdayAvailabilityTitle = $dateTitle;
                        $nextTuesdayWeekDate = $dateLabel;
                        array_push($nextTuesdayAvailableTimeSlots,$dataArray);  
                    }
                    break; 
                case 'Wednesday':
                    if($tomorrowsWeekDay != "Wednesday" && $dayAfterTomorrowsWeekDay != "Wednesday"){
                        $wednesdayAvailabilityTitle = $dateTitle;
                        $nextWednesdayWeekDate = $dateLabel;
                        array_push($nextWednesdayAvailableTimeSlots,$dataArray);
                    }else{
                        $dateOfTheDay = "next ".$day;
                        $date = date('Y-m-d', strtotime($dateOfTheDay));
                        $date = Carbon::createFromFormat('Y-m-d', $date);
                        $daysToAdd = 14;
                        $date = $date->addDays($daysToAdd);
                        $date = date('Y-m-d', strtotime($date));
                        $date = date('M d, l', strtotime($date));

                        $dateTitle = date('M d', strtotime($date));
                        $dateLabel = date('F d Y', strtotime($date));
                        $dataArray = ["timeslot_id" => $timeSlot->id,
                                    "timeslot_time" => $time,
                                    "timeslot_datetime" => $date." at ".$time,
                        ];
                        $wednesdayAvailabilityTitle = $dateTitle;
                        $nextWednesdayWeekDate = $dateLabel;
                        array_push($nextWednesdayAvailableTimeSlots,$dataArray);
                    }
                    break; 
                case 'Thursday':
                    if($tomorrowsWeekDay != "Thursday" && $dayAfterTomorrowsWeekDay != "Thursday"){
                        $thursdayAvailabilityTitle = $dateTitle;
                        $nextThursdayWeekDate = $dateLabel;
                        array_push($nextThursdayAvailableTimeSlots,$dataArray);
                    }else{
                        $dateOfTheDay = "next ".$day;
                        $date = date('Y-m-d', strtotime($dateOfTheDay));
                        $date = Carbon::createFromFormat('Y-m-d', $date);
                        $daysToAdd = 14;
                        $date = $date->addDays($daysToAdd);
                        $date = date('Y-m-d', strtotime($date));
                        $date = date('M d, l', strtotime($date));

                        $dateTitle = date('M d', strtotime($date));
                        $dateLabel = date('F d Y', strtotime($date));
                        $dataArray = ["timeslot_id" => $timeSlot->id,
                                    "timeslot_time" => $time,
                                    "timeslot_datetime" => $date." at ".$time,
                        ];
                        $thursdayAvailabilityTitle = $dateTitle;
                        $nextThursdayWeekDate = $dateLabel;
                        array_push($nextThursdayAvailableTimeSlots,$dataArray);
                    }
                    break;
                case 'Friday':
                    if($tomorrowsWeekDay != "Friday" && $dayAfterTomorrowsWeekDay != "Friday"){
                        $fridayAvailabilityTitle = $dateTitle;
                        $nextFridayWeekDate = $dateLabel;
                        array_push($nextFridayAvailableTimeSlots,$dataArray);
                    }else{
                        $dateOfTheDay = "next ".$day;
                        $date = date('Y-m-d', strtotime($dateOfTheDay));
                        $date = Carbon::createFromFormat('Y-m-d', $date);
                        $daysToAdd = 14;
                        $date = $date->addDays($daysToAdd);
                        $date = date('Y-m-d', strtotime($date));
                        $date = date('M d, l', strtotime($date));

                        $dateTitle = date('M d', strtotime($date));
                        $dateLabel = date('F d Y', strtotime($date));
                        $dataArray = ["timeslot_id" => $timeSlot->id,
                                    "timeslot_time" => $time,
                                    "timeslot_datetime" => $date." at ".$time,
                        ];
                        $fridayAvailabilityTitle = $dateTitle;
                        $nextFridayWeekDate = $dateLabel;
                        array_push($nextFridayAvailableTimeSlots,$dataArray);
                    }
                    break;
                case 'Saturday':
                    if($tomorrowsWeekDay != "Saturday" && $dayAfterTomorrowsWeekDay != "Saturday"){
                        $saturdayAvailabilityTitle = $dateTitle;
                        $nextSaturdayWeekDate = $dateLabel;
                        array_push($nextSaturdayAvailableTimeSlots,$dataArray);
                    }else{
                        $dateOfTheDay = "next ".$day;
                        $date = date('Y-m-d', strtotime($dateOfTheDay));
                        $date = Carbon::createFromFormat('Y-m-d', $date);
                        $daysToAdd = 14;
                        $date = $date->addDays($daysToAdd);
                        $date = date('Y-m-d', strtotime($date));
                        $date = date('M d, l', strtotime($date));

                        $dateTitle = date('M d', strtotime($date));
                        $dateLabel = date('F d Y', strtotime($date));
                        $dataArray = ["timeslot_id" => $timeSlot->id,
                                    "timeslot_time" => $time,
                                    "timeslot_datetime" => $date." at ".$time,
                        ];
                        $saturdayAvailabilityTitle = $dateTitle;
                        $nextSaturdayWeekDate = $dateLabel;
                        array_push($nextSaturdayAvailableTimeSlots,$dataArray);
                    }
                    break; 
                case 'Sunday':
                    if($tomorrowsWeekDay != "Sunday" && $dayAfterTomorrowsWeekDay != "Sunday"){
                        $sundayAvailabilityTitle = $dateTitle;
                        $nextSundayWeekDate = $dateLabel;
                        array_push($nextSundayAvailableTimeSlots,$dataArray);
                    }else{
                        $dateOfTheDay = "next ".$day;
                        $date = date('Y-m-d', strtotime($dateOfTheDay));
                        $date = Carbon::createFromFormat('Y-m-d', $date);
                        $daysToAdd = 14;
                        $date = $date->addDays($daysToAdd);
                        $date = date('Y-m-d', strtotime($date));
                        $date = date('M d, l', strtotime($date));

                        $dateTitle = date('M d', strtotime($date));
                        $dateLabel = date('F d Y', strtotime($date));
                        $dataArray = ["timeslot_id" => $timeSlot->id,
                                    "timeslot_time" => $time,
                                    "timeslot_datetime" => $date." at ".$time,
                        ];
                        $sundayAvailabilityTitle = $dateTitle;
                        $nextSundayWeekDate = $dateLabel;
                        array_push($nextSundayAvailableTimeSlots,$dataArray);
                    }
                    break;
            }
        }

        foreach($timeSlots as $timeSlot){
            $day = Day::where('id', $timeSlot->day_id)->value('name');
            $time = Time::where('id', $timeSlot->time_id)->value('time');
            $dateOfTheDay = "next ".$day;
            $date = date('M d, l', strtotime($dateOfTheDay));
            $dateTitle = date('M d', strtotime($dateOfTheDay));
            $dateLabel = date('F d Y', strtotime($dateOfTheDay));
            $dataArray = ["timeslot_id" => $timeSlot->id,
                          "timeslot_time" => $time,
                          "timeslot_datetime" => $date." at ".$time,
            ];
            switch ($day) {
                case 'Monday':
                    if($tomorrowsWeekDay != "Monday" && $dayAfterTomorrowsWeekDay != "Monday"){
                        $mondayAvailabilityTitle = $dateTitle;
                        $thisMondayWeekDate = $dateLabel;
                        array_push($thisMondayAvailableTimeSlots,$dataArray);
                    }else{
                        $dateOfTheDay = "next ".$day;
                        $date = date('Y-m-d', strtotime($dateOfTheDay));
                        $date = Carbon::createFromFormat('Y-m-d', $date);
                        $daysToAdd = 7;
                        $date = $date->addDays($daysToAdd);
                        $date = date('Y-m-d', strtotime($date));
                        $date = date('M d, l', strtotime($date));

                        $dateTitle = date('M d', strtotime($date));
                        $dateLabel = date('F d Y', strtotime($date));
                        $dataArray = ["timeslot_id" => $timeSlot->id,
                                    "timeslot_time" => $time,
                                    "timeslot_datetime" => $date." at ".$time,
                        ];
                        $mondayAvailabilityTitle = $dateTitle;
                        $thisMondayWeekDate = $dateLabel;
                        array_push($thisMondayAvailableTimeSlots,$dataArray);
                    }
                    break;
                case 'Tuesday':
                    if($tomorrowsWeekDay != "Tuesday" && $dayAfterTomorrowsWeekDay != "Tuesday"){
                        $tuesdayAvailabilityTitle = $dateTitle;
                        $thisTuesdayWeekDate = $dateLabel;
                        array_push($thisTuesdayAvailableTimeSlots,$dataArray);
                    }else{
                        $dateOfTheDay = "next ".$day;
                        $date = date('Y-m-d', strtotime($dateOfTheDay));
                        $date = Carbon::createFromFormat('Y-m-d', $date);
                        $daysToAdd = 7;
                        $date = $date->addDays($daysToAdd);
                        $date = date('Y-m-d', strtotime($date));
                        $date = date('M d, l', strtotime($date));

                        $dateTitle = date('M d', strtotime($date));
                        $dateLabel = date('F d Y', strtotime($date));
                        $dataArray = ["timeslot_id" => $timeSlot->id,
                                    "timeslot_time" => $time,
                                    "timeslot_datetime" => $date." at ".$time,
                        ];
                        $tuesdayAvailabilityTitle = $dateTitle;
                        $thisTuesdayWeekDate = $dateLabel;
                        array_push($thisTuesdayAvailableTimeSlots,$dataArray);
                    }
                    break; 
                case 'Wednesday':
                    if($tomorrowsWeekDay != "Wednesday" && $dayAfterTomorrowsWeekDay != "Wednesday"){
                        $wednesdayAvailabilityTitle = $dateTitle;
                        $thisWednesdayWeekDate = $dateLabel;
                        array_push($thisWednesdayAvailableTimeSlots,$dataArray);
                    }else{
                        $dateOfTheDay = "next ".$day;
                        $date = date('Y-m-d', strtotime($dateOfTheDay));
                        $date = Carbon::createFromFormat('Y-m-d', $date);
                        $daysToAdd = 7;
                        $date = $date->addDays($daysToAdd);
                        $date = date('Y-m-d', strtotime($date));
                        $date = date('M d, l', strtotime($date));

                        $dateTitle = date('M d', strtotime($date));
                        $dateLabel = date('F d Y', strtotime($date));
                        $dataArray = ["timeslot_id" => $timeSlot->id,
                                    "timeslot_time" => $time,
                                    "timeslot_datetime" => $date." at ".$time,
                        ];
                        $wednesdayAvailabilityTitle = $dateTitle;
                        $thisWednesdayWeekDate = $dateLabel;
                        array_push($thisWednesdayAvailableTimeSlots,$dataArray);
                    }
                    break; 
                case 'Thursday':
                    if($tomorrowsWeekDay != "Thursday" && $dayAfterTomorrowsWeekDay != "Thursday"){
                        $thursdayAvailabilityTitle = $dateTitle;
                        $thisThursdayWeekDate = $dateLabel;
                        array_push($thisThursdayAvailableTimeSlots,$dataArray);
                    }else{
                        $dateOfTheDay = "next ".$day;
                        $date = date('Y-m-d', strtotime($dateOfTheDay));
                        $date = Carbon::createFromFormat('Y-m-d', $date);
                        $daysToAdd = 7;
                        $date = $date->addDays($daysToAdd);
                        $date = date('Y-m-d', strtotime($date));
                        $date = date('M d, l', strtotime($date));

                        $dateTitle = date('M d', strtotime($date));
                        $dateLabel = date('F d Y', strtotime($date));
                        $dataArray = ["timeslot_id" => $timeSlot->id,
                                    "timeslot_time" => $time,
                                    "timeslot_datetime" => $date." at ".$time,
                        ];
                        $thursdayAvailabilityTitle = $dateTitle;
                        $thisThursdayWeekDate = $dateLabel;
                        array_push($thisThursdayAvailableTimeSlots,$dataArray);
                    }
                    break;
                case 'Friday':
                    if($tomorrowsWeekDay != "Friday" && $dayAfterTomorrowsWeekDay != "Friday"){
                        $fridayAvailabilityTitle = $dateTitle;
                        $thisFridayWeekDate = $dateLabel;
                        array_push($thisFridayAvailableTimeSlots,$dataArray);
                    }else{
                        $dateOfTheDay = "next ".$day;
                        $date = date('Y-m-d', strtotime($dateOfTheDay));
                        $date = Carbon::createFromFormat('Y-m-d', $date);
                        $daysToAdd = 7;
                        $date = $date->addDays($daysToAdd);
                        $date = date('Y-m-d', strtotime($date));
                        $date = date('M d, l', strtotime($date));

                        $dateTitle = date('M d', strtotime($date));
                        $dateLabel = date('F d Y', strtotime($date));
                        $dataArray = ["timeslot_id" => $timeSlot->id,
                                    "timeslot_time" => $time,
                                    "timeslot_datetime" => $date." at ".$time,
                        ];
                        $fridayAvailabilityTitle = $dateTitle;
                        $thisFridayWeekDate = $dateLabel;
                        array_push($thisFridayAvailableTimeSlots,$dataArray);
                    }
                    break;
                case 'Saturday':
                    if($tomorrowsWeekDay != "Saturday" && $dayAfterTomorrowsWeekDay != "Saturday"){
                        $saturdayAvailabilityTitle = $dateTitle;
                        $thisSaturdayWeekDate = $dateLabel;
                        array_push($thisSaturdayAvailableTimeSlots,$dataArray);
                    }else{
                        $dateOfTheDay = "next ".$day;
                        $date = date('Y-m-d', strtotime($dateOfTheDay));
                        $date = Carbon::createFromFormat('Y-m-d', $date);
                        $daysToAdd = 7;
                        $date = $date->addDays($daysToAdd);
                        $date = date('Y-m-d', strtotime($date));
                        $date = date('M d, l', strtotime($date));

                        $dateTitle = date('M d', strtotime($date));
                        $dateLabel = date('F d Y', strtotime($date));
                        $dataArray = ["timeslot_id" => $timeSlot->id,
                                    "timeslot_time" => $time,
                                    "timeslot_datetime" => $date." at ".$time,
                        ];
                        $saturdayAvailabilityTitle = $dateTitle;
                        $thisSaturdayWeekDate = $dateLabel;
                        array_push($thisSaturdayAvailableTimeSlots,$dataArray);
                    }
                    break; 
                case 'Sunday':
                    if($tomorrowsWeekDay != "Sunday" && $dayAfterTomorrowsWeekDay != "Sunday"){
                        $sundayAvailabilityTitle = $dateTitle;
                        $thisSundayWeekDate = $dateLabel;
                        array_push($thisSundayAvailableTimeSlots,$dataArray);
                    }else{
                        $dateOfTheDay = "next ".$day;
                        $date = date('Y-m-d', strtotime($dateOfTheDay));
                        $date = Carbon::createFromFormat('Y-m-d', $date);
                        $daysToAdd = 7;
                        $date = $date->addDays($daysToAdd);
                        $date = date('Y-m-d', strtotime($date));
                        $date = date('M d, l', strtotime($date));

                        $dateTitle = date('M d', strtotime($date));
                        $dateLabel = date('F d Y', strtotime($date));
                        $dataArray = ["timeslot_id" => $timeSlot->id,
                                    "timeslot_time" => $time,
                                    "timeslot_datetime" => $date." at ".$time,
                        ];
                        $sundayAvailabilityTitle = $dateTitle;
                        $thisSundayWeekDate = $dateLabel;
                        array_push($thisSundayAvailableTimeSlots,$dataArray);
                    }
                    break;
            }
        }


        return response()->json([
                                 'mondayAvailabilityTitle' => $mondayAvailabilityTitle ,
                                 'tuesdayAvailabilityTitle' => $tuesdayAvailabilityTitle ,
                                 'wednesdayAvailabilityTitle' => $wednesdayAvailabilityTitle ,
                                 'thursdayAvailabilityTitle' => $thursdayAvailabilityTitle ,
                                 'fridayAvailabilityTitle' => $fridayAvailabilityTitle ,
                                 'saturdayAvailabilityTitle' => $saturdayAvailabilityTitle ,
                                 'sundayAvailabilityTitle' => $sundayAvailabilityTitle ,

                                 'thisMondayWeekDate' => $thisMondayWeekDate ,
                                 'thisTuesdayWeekDate' => $thisTuesdayWeekDate ,
                                 'thisWednesdayWeekDate' => $thisWednesdayWeekDate ,
                                 'thisThursdayWeekDate' => $thisThursdayWeekDate ,
                                 'thisFridayWeekDate' => $thisFridayWeekDate ,
                                 'thisSaturdayWeekDate' => $thisSaturdayWeekDate ,
                                 'thisSundayWeekDate' => $thisSundayWeekDate ,

                                 'nextMondayWeekDate' => $nextMondayWeekDate ,
                                 'nextTuesdayWeekDate' => $nextTuesdayWeekDate ,
                                 'nextWednesdayWeekDate' => $nextWednesdayWeekDate ,
                                 'nextThursdayWeekDate' => $nextThursdayWeekDate ,
                                 'nextFridayWeekDate' => $nextFridayWeekDate ,
                                 'nextSaturdayWeekDate' => $nextSaturdayWeekDate ,
                                 'nextSundayWeekDate' => $nextSundayWeekDate ,

                                 'thisMondayAvailableTimeSlots'=> $thisMondayAvailableTimeSlots,
                                 'thisTuesdayAvailableTimeSlots'=> $thisTuesdayAvailableTimeSlots,
                                 'thisWednesdayAvailableTimeSlots'=> $thisWednesdayAvailableTimeSlots,
                                 'thisThursdayAvailableTimeSlots'=> $thisThursdayAvailableTimeSlots,
                                 'thisFridayAvailableTimeSlots'=> $thisFridayAvailableTimeSlots,
                                 'thisSaturdayAvailableTimeSlots'=> $thisSaturdayAvailableTimeSlots,
                                 'thisSundayAvailableTimeSlots'=> $thisSundayAvailableTimeSlots,

                                 'nextMondayAvailableTimeSlots'=> $nextMondayAvailableTimeSlots,
                                 'nextTuesdayAvailableTimeSlots'=> $nextTuesdayAvailableTimeSlots,
                                 'nextWednesdayAvailableTimeSlots'=> $nextWednesdayAvailableTimeSlots,
                                 'nextThursdayAvailableTimeSlots'=> $nextThursdayAvailableTimeSlots,
                                 'nextFridayAvailableTimeSlots'=> $nextFridayAvailableTimeSlots,
                                 'nextSaturdayAvailableTimeSlots'=> $nextSaturdayAvailableTimeSlots,
                                 'nextSundayAvailableTimeSlots'=> $nextSundayAvailableTimeSlots,
                                ],200);
    }

    public function addFreeSession(Request $request,TokyFunctions $toky,MailFunctions $mail){

        $phoneNumberInput = str_replace(array('(',')'," ","-"), '',$request->phone_number);
        $phoneNumber = $phoneNumberInput;
        if($phoneNumber[0] != "+" && $phoneNumber[0] != 1){
            $phoneNumber = "+1".$phoneNumber;
        }
        elseif($phoneNumber[0] != "+" && $phoneNumber[0] == 1){
            $phoneNumber = "+".$phoneNumber;
        }

        $fullTimeSlot =$request->time_slot;
        $timeslot_array = explode(" ",$fullTimeSlot);
        $monthPreFix = $timeslot_array[0];
        $dateArray =explode(",",$timeslot_array[1]);
        $dateOfTheMonth = $dateArray[0];
        $dayOfTheWeek = $timeslot_array[2];
        $timeOfTheDay = $timeslot_array[4];


        $day_id =Day::where('name', $dayOfTheWeek)->value('id');
        $time_id = Time::where('time', $timeOfTheDay)->value('id'); 
        $timeslot_id = FreeSessionTimeSlot::where(['day_id' => $day_id,
                                                   'time_id' => $time_id,
                                                   'location_id' => $request->location_id])->value('id');
        $roleId = Role::where('role', "student")->value('id');

        $freeSessionBooking = new FreeSessionBooking();
        $freeSessionBooking->location_id = $request->location_id;
        $freeSessionBooking->student_name = $request->student_name;
        $freeSessionBooking->student_age = $request->student_age;
        $freeSessionBooking->phone_number = $phoneNumber;
        $freeSessionBooking->email = $request->email;
        $freeSessionBooking->topic_id = $request->topic_id;
        $freeSessionBooking->ad_source = $request->ad_source;
        $freeSessionBooking->free_session_time_slot_id = $timeslot_id;
        $freeSessionBooking->expectations = $request->expectations;
        $freeSessionBooking->save();

        $user = new User();
        $user->user_name = $request->student_name;
        $user->password = null;
        $user->full_name = $request->student_name;
        $user->email = $request->email;
        $user->phone_number = $phoneNumber;
        $user->role_id = $roleId;
        $user->is_free_session = true;
        $user->postal_address = $request->address;
        $user->save();

        $topic = Topic::find($request->topic_id);
        $topic->users()->attach($user);
        $location = Location::find($request->location_id);
        $location->users()->attach($user);

        //search or create a free session task class and add student to it
        $this->addFreeSessionTaskClass($request->input(), $user);

        // Send SMS
        $smsMessage = "We have received ".$request->student_name."'s free session reservation, on ".$request->time_slot.
                      "! The address for the free session is: ".$location->address.". We are thrilled to start! To see ".$request->student_name.
                      "'s schedule or to see subscription options after the first session, please go to codewithus.com/g/4133583006 or text us back.";
//        $toky->sms_send($phoneNumber, $smsMessage);

        // Send Email 
        $data = array(
            'student_name' => $request->student_name,
            'free_session_datetime' => $request->time_slot,
        );
        $mail->send_free_session_successful_registration_email($request->email, $data);
        
        return response()->json(['response_msg'=>'Data Saved'],200);
    } 
    
    public function getTodaysWeekDay(){
        $weekMap = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];
        $dayOfTheWeek = Carbon::now()->dayOfWeek;
        $weekday = $weekMap[$dayOfTheWeek];
        return $weekday;
    }

    public function getTomorrowsWeekDay(){
        $weekMap = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];
        $dayOfTheWeek = Carbon::now()->addDays(1)->dayOfWeek;
        $weekday = $weekMap[$dayOfTheWeek];
        return $weekday;
    }

    public function getDayAfterTomorrowsWeekDay(){
        $weekMap = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];
        $dayOfTheWeek = Carbon::now()->addDays(2)->dayOfWeek;
        $weekday = $weekMap[$dayOfTheWeek];
        return $weekday;
    }

    public function addFreeSessionTaskClass($data, $student)
    {
        $fullTimeSlot =$data['time_slot'];
        $timeslot_array = explode(" ",$fullTimeSlot);
        $month = $this->convertStringToMonth($timeslot_array[0]);
        $dateArray =explode(",",$timeslot_array[1]);
        $date = $dateArray[0];
        $day = $timeslot_array[2];
        $time = $timeslot_array[4];
        $time = explode(':', $time);
        $year = Carbon::now()->year;


        $d = Carbon::create($year, $month, $date, $time[0], $time[1], $time[2]);

        $taskClass = TaskClass::where('starts_at','=' ,$d)
            ->where('is_free_session', 1)
            ->where('location_id', $data['location_id'])
            ->first();

        $taskClass = $taskClass ? $taskClass : null;

        if(!$taskClass)
        {
            $taskClass =TaskClass::create([
                'name' => 'Free Session',
                'location_id' => $data['location_id'],
                'is_free_session' => true,
                'starts_at' => $d,
                'ends_at' => $d,
            ]);
        }

        $taskClass->users()->attach($student->id);

        return response()->json(['data' => null, 'message' => 'Student added to free session class!'],200);
    }

    public function convertStringToMonth($str)
    {
            $monthsMap = collect([
                'Jan' => 1, 'Feb'=> 2, 'Mar'=> 3, 'Apr'=> 4, 'May'=> 5, 'Jun'=> 6, 'Jul'=> 7,
                'Aug'=> 8, 'Sep'=> 9, 'Oct'=> 10, 'Nov'=> 11, 'Dec'=> 12
            ]);

            return $monthsMap[$str];
    }

}
