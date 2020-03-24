<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\TaskClass;
use App\Teacher;
use App\User;
use App\Role;
use App\Location;
use App\Topic;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher|admin');
    }

    public function getLocations(){
        //$user = Auth::user();
        //$role = $user->role->role;
        $role = "admin";
        if($role == "admin"){
            $locationData = Location::where('is_deleted', false)->get();
        }
        elseif($role == "teacher"){
            $locationData = $user->locations()->get();
            // $locationData = Location::where(['is_deleted' => false,
            //                                  'id' => $locationId, ])->get();
        }
        $locations = array();
        foreach($locationData as $location){
            $dataArray = ["location_id" => $location['id'],
                      "location_name" => $location['location_name'],
            ];
            array_push($locations,$dataArray);           
        }
        return response()->json(['locations'=> $locations],200);
    }
    public function getCalendarEvents(Request $request){
        $taskClasses = TaskClass::where(['is_deleted' => false,
                                     'is_completed' => false,
                                     'location_id' => $request->location_id])
                                     ->orderBy('starts_at','ASC')->get();
        $events = array();
        foreach($taskClasses as $taskClass){
            $students = array();
            $teacher = "Un-Assigned";
            $starts_at = $taskClass->starts_at;
            $ends_at = $taskClass->ends_at;
            $timestamp_starttime = strtotime($taskClass->starts_at);
            $startTime = date('H:i', $timestamp_starttime);

            $timestamp_endtime = strtotime($taskClass->ends_at);
            $endTime = date('H:i', $timestamp_endtime);

            $teacher = $startTime."-".$teacher."-".$taskClass->name."-".$endTime;
            $pivotTableData = $taskClass->users;
            foreach($pivotTableData as $data){
                $role = Role::where('id',$data->role_id)->value('role');
                if($role == "teacher"){
                    $teacher = $startTime."-".$data->full_name."-".$taskClass->name."-".$endTime;
                }
                else{
                    $dataArray = [
                        "student_id" => $data->id,
                        "student_name" => $data->full_name,
                        "starts_at" => $starts_at,
                        "ends_at" => $ends_at,
                    ];
                    array_push($students,$dataArray);
                }
            }
            if(count($pivotTableData) > 0){
                $title = "<b>".$teacher."</b>";
                $content = '<div style="background-color:#FCF3CF;margin:5px;color:black">';
                foreach($students as $student){
                    $content = $content."<a href='/edit_student_profile/".$student['student_id']."'>".$student['student_name'].'</a></div><div style="background-color:#FCF3CF;margin:5px;color:black">';
                    $starts_at = $student['starts_at'];
                    $ends_at = $student['ends_at'];
                }
                $content = $content."</div>";
                $dataArray = [
                                "start" => $starts_at,
                                "end" => $ends_at,
                                "title" => $title,
                                "content" => $content,
                                "class" => 'leisure'
                ];
                array_push($events, $dataArray);
            }      
        }

        if(count($events) <= 0){
            return response()->json(['response_msg'=>'No scheduled classes are found for this Location.'],200);
        }
        else{
            return response()->json(['events'=> $events],200);
        }
    }

}
