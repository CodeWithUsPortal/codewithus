<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Update;
use App\User;
use App\Role;
use Auth;
use App\Location;
class ParentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('role:parent');
    }
    public function index()
    {
        return view('parent.index');
    }
    public function updates(){
        $phoneNumber = auth()->user()->phone_number;
        $updatesData = Update::where('phone_number', $phoneNumber)->orderBy('created_at','desc')->get();
        $updates = array();
        foreach($updatesData as $update){
            $userName = User::where('id', $update->user_id)->value('full_name');
            $isTeacher = $update->is_teacherOrAdmin;

            if($isTeacher){
                $created_by = "Teacher: ".$userName;
                $dataArray = ["id" => $update->id,
                              "content" =>  $update->content,
                              "created_at" => $update->created_at,
                              "created_by" => $created_by
                ];
                array_push($updates,$dataArray);
            }
            else{
                $created_by = "Student: ".$userName;
                $dataArray = ["id" => $update->id,
                              "content" =>  $update->content,
                              "created_at" => $update->created_at,
                              "created_by" => $created_by
                ];
                array_push($updates,$dataArray);
            }
        }
        return view("parent.updates",compact('updates'));
    }

    public function studentsListPage(){
        return view('parent.students');
    }
    public function getStudentsList(){
        $roleId = Role::where('role', 'student')->value('id');
        $user = Auth::user();
        $phoneNumber = $user->phone_number;
        $users = User::where(['phone_number' => $phoneNumber,
                              'role_id' => $roleId])->get();
        $students = array();
        foreach($users as $user){
            $locations = $user->locations()->get();
            foreach($locations as $location){
                $dataArray = ["id" => $user->id,
                              "student_name" =>  $user->full_name,
                              "location" => $location->location_name,
                ];
                array_push($students,$dataArray);
            }
        }
        return response()->json(['students'=> $students],200);
    }
}
