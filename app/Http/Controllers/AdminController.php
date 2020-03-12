<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Update;
use PDO;
use Torann\LaravelAsana\Facade\Asana;
use App\TaskClass;
use App\Teacher;
use App\User;
use App\Role;
use App\Location;
use App\Topic;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    public function index()
    {
        return view('admin.index');
    }

    public function newUpdateForm($taskId=null){
        if($taskId != null){
            return view('admin.update')->withTaskid($taskId);
        }
        else{
            return view('admin.update')->withTaskid("");
        }
    }
    public function writeAnUpdate(Request $request){
        // When an admin writes an update, we should get the phone number from the task data
        // Right now, its dummy
        $userId = auth()->user()->id;
        $content = $request->content; 
        $teacherOrAdminName = auth()->user()->user_name;
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

            $createdUser = Update::create([  
                'phone_number' => $phoneNumber,
                'user_id' => $userId,
                'is_teacherOrAdmin' => true,
                'content' => $content,
            ]);
           
            $student_name = explode (" ", $projectName);
            $textContent = "Teacher/Admin ".$teacherOrAdminName." has written an update for ".$student_name[0]. 
                            ". Click on this link to see it: https://portal.codewithus.com/parent/update/".$phoneNumber."/".$createdUser->id;
            
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
        
        return redirect('/admin/updates');
    }

    public function updates(){
        $userId = auth()->user()->id;
        $updates = Update::where('user_id', $userId)->orderBy('created_at','desc')->get();
        return view("admin.updates",compact('updates'));
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
        return view('admin.calendar');
    }
    
}

