<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TaskClass;
use App\Teacher;
use App\User;
use App\Role;
use App\Location;
use App\Topic;
use Auth;


class StudentAndClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher|admin');
    }
    public function getStudentByFullName(Request $request){
        $roleId = Role::where('role','student')->value('id');
        $studentData = User::where(['full_name' => $request->full_name,
                                    'is_deleted' => false,
                                    'role_id' => $roleId])->get();
        $students = array();
        foreach($studentData as $student){
            $dataArray = ["student_id" => $student['id'],
                      "link_to_profile" => "/codewithus/edit_student_profile/".$student['id'],
                      "student_name" => $student['full_name']." - ".$student['phone_number'],
                      "student_phonenumber" => $student['phone_number'],
            ];
            array_push($students,$dataArray);
        }
        if(count($students) <= 0){
            return response()->json(['response_msg'=>'No Student exists with this information'],200);
        }
        else{
            return response()->json(['students'=> $students],200);
        }
    }

    public function getStudentByPhoneNumber(Request $request){
        $roleId = Role::where('role','student')->value('id');
        $studentData = User::where(['phone_number' => $request->phone_number,
                                    'is_deleted' => false,
                                    'role_id' => $roleId])->get();
        $students = array();
        foreach($studentData as $student){
            $dataArray = ["student_id" => $student['id'],
                      "link_to_profile" => "/codewithus/edit_student_profile/".$student['id'],
                      "student_name" => $student['full_name']." - ".$student['phone_number'],
                      "student_phonenumber" => $student['phone_number'],
            ];
            array_push($students,$dataArray);
        }
        if(count($students) <= 0){
            return response()->json(['response_msg'=>'No Student exists with this information'],200);
        }
        else{
            return response()->json(['students'=> $students],200);
        }
    }

    public function getStudentProfile(Request $request){
        $student = User::where('id',$request->student_id)->get();
        $topic = Topic::where('id',$student[0]->topic_id)->value('name');
        $profile = ["student_id" => $student[0]->id,
                    "full_name" => $student[0]->full_name,
                    "phone_number" => $student[0]->phone_number,
                    "email" => $student[0]->email,
                    "notes" => $student[0]->notes,
                    "topic_id" => $student[0]->topic_id,
                    "topic" => $topic,
        ];
        return response()->json(['profile'=> $profile],200);
    }
    public function editStudentAssignmentToClasses($studentId){
        $user = Auth::user();
        $role = $user->role->role;
        if($role == "admin"){
            return view('task_class.admin_edit_student_profile')->withStudent($studentId);
        }
        elseif($role == "teacher"){
            return view('task_class.teacher_edit_student_profile')->withStudent($studentId);
        }
    }

    public function editStudentProfile(Request $request){
        $user = User::where('id', $request->student_id)->first();
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->topic_id = $request->topic_id;
        $user->notes = $request->notes;
        $user->save();
        return response()->json(['response_msg'=>'Data Updated'],200);
    }

    public function getStudentsClasses(Request $request){
        $taskClasses = User::find($request->student_id)->taskclasses()->where('is_completed', false)->get();
        $classes = array();
        foreach($taskClasses as $taskClass){
            $teacher = "Un-assigned";
            $usersAttached = $taskClass->users;
            foreach($usersAttached as $user){
                $role = Role::where('id', $user->role_id)->value('role');
                if($role == "teacher"){
                    $teacher = $user->full_name;
                }
            }
            $timestamp = strtotime($taskClass['starts_at']);
            $time = date('H:i', $timestamp);
            $date = date('d-m-Y',$timestamp);
            $dataArray = [
                    "taskclass_studentid" => $request->student_id,
                    "taskclass_id" => $taskClass['id'],
                    "taskclass_name" => $teacher." - ".$taskClass['name'],
                    "taskclass_date" => $date,
                    "taskclass_time" => $time,
            ];
            array_push($classes,$dataArray);   
        }
        return response()->json(['taskClasses'=> $classes],200);
    }

    public function unAssignStudent(Request $request){
        $taskClass = TaskClass::find($request->taskclass_id);
        $user = User::find($request->taskclass_studentid);
        $taskClass->users()->detach($user);
    }

    public function getClassesForStudentLocationAndDate(Request $request){
        $locationIdForStudent = User::where('id',$request->student_id)->value('location_id');
        $classesData = array();
        if($request->selectedDate != null && $request->selectedDate != ""){
            $classesDataWithoutSelectedDateFilter = TaskClass::where(['is_deleted' => false, 
                                             'is_completed' => false,
                                             'location_id' => $locationIdForStudent,
                                             ])->get();
            foreach($classesDataWithoutSelectedDateFilter as $classDataWithoutSelectedDateFilter){
                $startsAtDate = date("Y-m-d", strtotime($classDataWithoutSelectedDateFilter->starts_at));
                $selectedDate = date("Y-m-d", strtotime($request->selectedDate));
                if( $startsAtDate == $selectedDate){
                    array_push($classesData, $classDataWithoutSelectedDateFilter);
                }
            }
        }
        else{
            $classesData = TaskClass::where(['is_deleted' => false, 
                                             'is_completed' => false,
                                             'location_id' => $locationIdForStudent ])->get();
        }

        $classes = array();
        foreach($classesData as $class){
            $teacher = "Un-assigned";
            $usersAttached = $class->users;
            foreach($usersAttached as $user){
                $role = Role::where('id', $user->role_id)->value('role');
                if($role == "teacher"){
                    $teacher = $user->full_name;
                }
            }
            $timestamp = strtotime($class['starts_at']);
            $time = date('H:i', $timestamp);
            $dataArray = [
                    "taskclass_id" => $class['id'],
                    "taskclass_name" => $time."-".$teacher."-".$class['name'],
                    "taskclass_starting_datetime" => $class['starts_at'],
                    "taskclass_ending_datetime" => $class['ends_at'],
            ];
            array_push($classes,$dataArray);           
        }
        if(count($classes) <= 0){
            return response()->json(['response_msg'=>'No Classes exist for this Information'],200);
        }
        else{
            return response()->json(['classes'=> $classes],200);
        } 
    }

    public function getTeachersForTheLocation(Request $request){
        $roleId = Role::where('role','teacher')->value('id');
        $teacherData = User::where(['is_deleted' => false, 
                                    'role_id' => $roleId,
                                    'location_id' => $request->location_id ])->get();
        $teachers = array();
        foreach($teacherData as $teacher){
            $dataArray = ["teacher_id" => $teacher['id'],
                      "teacher_name" => $teacher['full_name'],
            ];
            array_push($teachers,$dataArray);           
        }
        if(count($teachers) <= 0){
            return response()->json(['response_msg'=>'No teachers found for this location.'],200);
        }
        else{
            return response()->json(['teachers'=> $teachers],200);
        }
    }

    public function addTaskClass(Request $request){
        $className = $request->taskclass_name;
        $teacherId = $request->teacher_id;
        $locationId = $request->location_id;
        $classStartingdatetime = date("Y-m-d H:i:s", strtotime($request->startingDatetime));
        $classEndingdatetime = date("Y-m-d H:i:s", strtotime($request->endingDatetime));
  
        $taskClass = new TaskClass();
        $taskClass->name = $className;
        $taskClass->location_id = $locationId;
        $taskClass->is_completed = false;
        $taskClass->starts_at = $classStartingdatetime;
        $taskClass->ends_at = $classEndingdatetime;
        $taskClass->save();

        $user = User::find($teacherId);
        $taskClass->users()->attach($user);
        return response()->json(['response_msg'=>'Data saved'],200);
    }
 
    public function addStudentToClass(Request $request){
        $user = User::find($request->selectedStudentId);
        $taskClass = TaskClass::find($request->selectedClassId);
        $taskClass->users()->attach($user);
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    
    public function addTaskClassForm(){
        $user = Auth::user();
        $role = $user->role->role;
        if($role == "admin"){
            return view('task_class.admin_add_class_form');
        }
        elseif($role == "teacher"){
            return view('task_class.teacher_add_class_form');
        }
    }

    public function addStudentForm(){
        $user = Auth::user();
        $role = $user->role->role;
        if($role == "admin"){
            return view('task_class.admin_add_students_form');
        }
        elseif($role == "teacher"){
            return view('task_class.teacher_add_students_form');
        }
    }

    public function addLocationToStudent(Request $request){
        $user = User::find($request->selectedStudentId);
        $location = Location::find($request->selectedLocationId);
        if( $user->locations->contains($location->id)){
            return response()->json(['response_msg'=>'You cannot add duplicate location.'],200);
        }
        else{
            $location->users()->attach($user);
            return response()->json(['response_msg'=>'Data saved'],200);
        } 
    }

    public function getStudentsLocations(Request $request){
        $locationData = User::find($request->student_id)->locations()->get();
        $locations = array();
        foreach($locationData as $location){
            $dataArray = [
                    "student_id" => $request->teacher_id,
                    "location_id" => $location['id'],
                    "location_name" => $location['location_name'],
            ];
            array_push($locations,$dataArray);   
        }
        return response()->json(['student_locations'=> $locations],200);
    }

    public function removeStudentLocation(Request $request){
        $locationData = User::find($request->student_id)->locations()->get();
        if(count($locationData) <= 1){
            return response()->json(['response_msg'=>'You can not delete this location.'],200);
        }
        else{
            $user = User::find($request->student_id);
            $location = Location::find($request->selectedLocationId);
            $location->users()->detach($user);
            return response()->json(['response_msg'=>'Data deleted'],200);
        }
    }

}
