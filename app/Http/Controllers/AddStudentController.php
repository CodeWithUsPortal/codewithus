<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\User;
use App\Location;
use Auth;

class AddStudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin|teacher|parent');
    }

    public function addStudentForm(){
        $user = Auth::user();
        $role = $user->role->role;
        if($role == "admin"){
            return view('add_students.admin_add_student_form');
        }
        else if($role == "teacher"){
            return view('add_students.teacher_add_student_form');
        }
        else if($role == "parent"){
            return view('add_students.parent_add_student_form');
        }
    }

    public function getParentsPhoneNumber(){
        $user = Auth::user();
        $role = $user->role->role;
        if($role == "parent"){
            $phoneNumber = $user->phone_number;
            return response()->json(['phoneNumber'=> $phoneNumber],200);
        }
        else{
            return response()->json(['response_msg'=>'Not a Parent'],200);
        }
    }

    public function getLocations(){
        $user = Auth::user();
        $role = $user->role->role;
        $locations = array();
        if($role == "parent" || $role == "admin"){
            $locationsData = Location::where('is_deleted',false)->get();
            foreach( $locationsData as $location){
                $dataArray = ["location_id" => $location['id'],
                              "location_name" => $location['location_name'],                
                ];
                array_push($locations,$dataArray);
            }
        }
        else if($role == "teacher"){
            $locationsData = $user->locations()->get();
            foreach( $locationsData as $location){
                $dataArray = ["location_id" => $location['id'],
                              "location_name" => $location['location_name'],                
                ];
                array_push($locations,$dataArray);
            }
        }
        return response()->json(['locations'=> $locations],200);
    }

    public function addStudent(Request $request){
        $roleId = Role::where('role', 'student')->value('id');

        $phoneNumberInput = str_replace(array('(',')'," ","-"), '',$request->phone_number);
        $phoneNumber = $phoneNumberInput;
        if($phoneNumber[0] != "+" && $phoneNumber[0] != 1){
            $phoneNumber = "+1".$phoneNumber;
        }
        elseif($phoneNumber[0] != "+" && $phoneNumber[0] == 1){
            $phoneNumber = "+".$phoneNumber;
        }

        $user = new User();
        $user->user_name = $request->full_name;
        $user->full_name = $request->full_name;
        $user->phone_number = $phoneNumber;
        $user->dob = $request->dob;
        $user->role_id = $roleId;
        $user->save();

        $location = Location::find($request->location_id);
        $location->users()->attach($user);

        return response()->json(['response_msg'=>'Student Added'],200);
    }
}
