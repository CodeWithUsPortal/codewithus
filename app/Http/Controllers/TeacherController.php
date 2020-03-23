<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Update;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;
use Torann\LaravelAsana\Facade\Asana;
use App\TaskClass;
use App\Teacher;
use App\User;
use App\Role;
use App\Location;
use App\Topic;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher|admin');
    }
    
    public function index()
    {
        return view('teacher.index');
    }

    public function newUpdateForm($taskId=null){
        if($taskId != null){
            return view('teacher.update')->withTaskid($taskId);
        }
        else{
            return view('teacher.update')->withTaskid("");
        }
    }
    public function writeAnUpdate(Request $request){
        // When a teacher writes an update, we should get the phone number from the task data
        // Right now, its dummy
        $userId = auth()->user()->id;
        $content = $request->input('content');
        $teacherName = auth()->user()->user_name;
        if($request->taskId == null){
            Update::create([  
                'user_id' => $userId,
                'is_teacherOrAdmin' => true,
                'content' => $content,
            ]);
        }
        else{
            $task = Asana::getTask($request->taskId);
            $projectName = $task->data->projects[0]->name;
            $phoneNumber = $this->get_string_between($projectName, '{', '}');
            if($phoneNumber[0] != "+" && $phoneNumber[0] != 1){
                $phoneNumber = "+1".$phoneNumber;
            }
            elseif($phoneNumber[0] != "+" && $phoneNumber[0] == 1){
                $phoneNumber = "+".$phoneNumber;
            }

            $createdUpdate = Update::create([  
                'phone_number' => $phoneNumber,
                'user_id' => $userId,
                'is_teacherOrAdmin' => true,
                'content' => $content,
            ]);
           
            $student_name = explode (" ", $projectName);
            $textContent = "Teacher ".$teacherName." has written an update for ".$student_name[0]. 
                            ". Click on this link to see it: https://portal.codewithus.com/parent/update/".$phoneNumber."/".$createdUpdate->id;
            
            // //Start of SMS sending function
            $ch = curl_init();
            $api_key = '23480ecaa2d37d33905eae528df2d19e86c898c4653ec9e73b3d01ba96182f74';
            $headers = array();
            $headers[] = "X-Toky-Key: {$api_key}";
            //{"from":"+16282275444", "to": "+16282275222", "text": "Hello from Toky"}
            $data = array("from" => "+14089097717", "email" => "team@codewithus.com",
                        "to" => $phoneNumber, 
                        "text" => $textContent);
        
            $json_data = json_encode($data);   
        
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, "https://api.toky.co/v1/sms/send");
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch,CURLOPT_POSTFIELDS, $json_data);
            
            $curl_response = curl_exec($ch); // Send request
        
            curl_close($ch); // close cURL resource 
            // //End of SMS sending function
        }
        
        return redirect('/teacher/updates');
    }

    public function updates(){
        $userId = auth()->user()->id;
        $updates = Update::where('user_id', $userId)->orderBy('created_at','desc')->get();
        return view("teacher.updates",compact('updates'));
    }
    public function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    public function calendarView(){
        return view('teacher.calendar');
    }

    public function lessonsForTeachers(){
        return view('teacher.lessons-for-teachers');
    }
    public function storeStudentUpdates(Request $request)
    {
        $student = User::find($request->student_id);

        $u = Update::create([
            'phone_number' => $student->phone_number,
            'user_id' => $student->id,
            'content' => $request->message,
            'is_teacher' => 0,
            'teacher_id' => Auth::user()->id,
        ]);

        $sms_url = route('teachers-update', [$student->phone_number, $u->id]);

//        $this->sendSMS($sms_url, $u);

        return response()->json(['data' => null, 'message' => 'Update added!'],200);
    }
    public function sendSMS($url, $update)
    {
        // //Start of SMS sending function
        $ch = curl_init();
        $api_key = '23480ecaa2d37d33905eae528df2d19e86c898c4653ec9e73b3d01ba96182f74';
        $headers = array();
        $headers[] = "X-Toky-Key: {$api_key}";
        //{"from":"+16282275444", "to": "+16282275222", "text": "Hello from Toky"}
        $data = array("from" => "+14089097717", "email" => "team@codewithus.com",
            "to" => $update->phone_number,
            "text" => $url);

        $json_data = json_encode($data);

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, "https://api.toky.co/v1/sms/send");
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch,CURLOPT_POSTFIELDS, $json_data);

        $curl_response = curl_exec($ch); // Send request

        curl_close($ch); // close cURL resource
        // //End of SMS sending function
    }
    public function markClassAsCompleted(Request $request)
    {
        DB::table('task_class_user')->where(['id' => $request->input('id')])->update(['completed'=>1]);

        return response()->json(['data'=> null, 'message' => 'Task class marked as completed!', 'status'=>'success'],200);
    }

    public function markClassAsInCompleted(Request $request)
    {
        DB::table('task_class_user')->where(['id' => $request->input('id')])->update(['completed'=>0]);

        return response()->json(['data'=> null, 'message' => 'Task class marked as incomplete!', 'status'=>'success'],200);
    }

    public function completedClasses($id)
    {
        return view('teacher.completed-classes')->withId($id);
    }


}

