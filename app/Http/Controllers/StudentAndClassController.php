<?php

namespace App\Http\Controllers;

use App\Mail\NotesToTeacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\TaskClass;
use App\Teacher;
use App\User;
use App\Role;
use App\Location;
use App\Topic;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


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
                      "link_to_profile" => "/edit_student_profile/".$student['id'],
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
                      "link_to_profile" => "/edit_student_profile/".$student['id'],
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
        $student = User::where('id',$request->student_id)->first();
        $topic = $student->topics()->first();
        $studentId = $student['id'];
        $profile = ["student_id" => $student['id'],
                    "full_name" => $student['full_name'],
                    "phone_number" => $student['phone_number'],
                    "email" => $student['email'],
                    "notes" => $student['notes'],
                    "topic_id" => $topic['id'],
                    "topic" => $topic['name'],
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
        $user->notes = $request->notes;
        $user->save();

        if($request->topic_id != null){
            $newTopic = Topic::find($request->topic_id);
            $oldTopic = $user->topics()->first();

            if($oldTopic != null){
                $oldTopic->users()->detach($user);
            }      
            $newTopic->users()->attach($user);
        }
        // if send mail flag is true then process mail request
        if($request->has('sendEmail')) {
            $this->sendEmailToTeacher($user->id, $request->notes);

            return response()->json(['response_msg'=>'Data Updated & Email Sent'],200);
        }
        
        return response()->json(['response_msg'=>'Data Updated'],200);
    }

    public function sendEmailToTeacher($student_id, $notes)
    {
        //get the next class for this student
        $class = User::find($student_id)
            ->taskclasses()
            ->whereDate('starts_at', '>', Carbon::now())
            ->first();

        //get the email of the teacher of the next class for this student
        $teacher = DB::table('task_class_user as tc')
            ->where('tc.task_class_id', $class->pivot->task_class_id)
            ->join('users as u', 'tc.user_id','=','u.id')
            ->join('roles as r', 'u.role_id', '=', 'r.id')
            ->where('role', 'teacher')
            ->orderBy('tc.id', 'desc')
            ->select('u.*')
            ->first();

        //send mail is a teacher is assigned to the class
        if($teacher)
        {
            Mail::to($teacher->email)->send(new NotesToTeacher($notes, $teacher, $class));
        }
    }

    public function getStudentsClasses(Request $request)
    {
        $taskClasses = User::find($request->student_id)->taskclasses()->get();
        $classes = array();

        foreach($taskClasses as $taskClass) {
            $teacher = "Un-assigned";
            $usersAttached = $taskClass->users;
            foreach($usersAttached as $user) {
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
                    "pivot" => $taskClass->users[0]->pivot,
            ];
            array_push($classes, $dataArray);
        }
        return response()->json(['taskClasses'=> $classes],200);
    }

    public function getIncompleteStudentsClasses(Request $request)
    {
        $incompleteTaskClasses = User::find($request->input('student_id'))->incomplete_taskclasses()->get();

        return response()->json(['incompleteTaskClasses'=> $incompleteTaskClasses],200);
    }

    public function unAssignStudent(Request $request)
    {
        DB::table('task_class_user')->where('id', $request->input('pivot')['id'])->delete();
    }

    public function getClassesForStudentLocationAndDate(Request $request){
        $locationData = User::find($request->student_id)->locations()->get();
        $classes = array();
        $classesData = array();
        foreach($locationData as $location){

            if($request->selectedDate != null && $request->selectedDate != ""){
                $classesDataWithoutSelectedDateFilter = TaskClass::where(['is_deleted' => false, 
                                                'location_id' => $location->id,
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
                $classesDataWithoutDate = TaskClass::where(['is_deleted' => false, 
                                                'location_id' => $location->id ])->get();
                foreach($classesDataWithoutDate as $classDataWithoutDate){
                    array_push($classesData, $classDataWithoutDate);
                }
            }

        }
        foreach($classesData as $data){
            $teacher = "Un-assigned";
            $usersAttached = $data->users;
            foreach($usersAttached as $user){
                $role = Role::where('id', $user->role_id)->value('role');
                if($role == "teacher"){
                    $teacher = $user->full_name;
                }
            }
            $timestamp = strtotime($data['starts_at']);
            $time = date('H:i', $timestamp);
            $dataArray = [
                    "taskclass_id" => $data['id'],
                    "taskclass_name" => $time."-".$teacher."-".$data['name'],
                    "taskclass_starting_datetime" => $data['starts_at'],
                    "taskclass_ending_datetime" => $data['ends_at'],
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
        $userData = Location::find($request->location_id)->users()->get();
        $teachers = array();
        foreach($userData as $user){
            $roleId = Role::where('role','teacher')->value('id');
            $teacherData = User::where([ 'id' => $user->id,
                                         'is_deleted' => false, 
                                         'role_id' => $roleId, ])->get();
            foreach($teacherData as $teacher){
                $dataArray = ["teacher_id" => $teacher['id'],
                              "teacher_name" => $teacher['full_name'],
                ];
                array_push($teachers,$dataArray);           
            }
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
        $taskClass->is_free_session = $request->input('isFreeSessionClass');
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

    public function getCompletedClasses(Request $request)
    {
        $completedClasses = User::find($request->input('student_id'))->completed_taskclasses()->get();

        return response()->json(['completedClasses'=> $completedClasses, 'message' => null, 'status'=>'success'],200);
    }

    public function getAllStudentForTaskClass($id)
    {
        // $id = task_class_is
        $taskClasses = DB::table('task_classes as tc')
            ->join('task_class_user as tcu', 'tc.id', '=', 'tcu.task_class_id')
            ->join('users as u', 'u.id', '=', 'tcu.user_id')
            ->join('roles as r', 'r.id', '=', 'u.role_id')
            ->where('r.id', 4)
            ->where('tcu.completed', 0)
            ->where('tc.id', $id)
            ->select('u.*', 'tcu.id as pivot_id', 'tcu.completed as pivot_completed')
            ->get();

        return response()->json(['taskClasses'=> $taskClasses, 'message' => null, 'status'=>'success'],200);
    }


}
