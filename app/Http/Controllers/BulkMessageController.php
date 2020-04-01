<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Location;
use App\Mail\BulkMessageStudent;
use App\Mail\BulkMessageTeacher;
use App\Topic;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BulkMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function showMessageFormTeachers()
    {
        return view('admin.bulk_messages.teachers');
    }

    public function getBulkMessageData()
    {
        $data['locations'] = Location::where('is_deleted', 0)->get();
        $data['topics'] = Topic::where('is_deleted', 0)->get();

        return response()->json(['data'=> $data, 'message' => null, 'status'=>'success'],200);
    }

    public function showMessageFormStudents()
    {
        return view('admin.bulk_messages.students');
    }

    public function sendMessage(Request $request)
    {
        $location_id = $request->input('location');
        $topic_id = $request->input('topic');
        $age = $request->input('age');
        $m = $request->input('message');

        if($request->input('students'))
        {
            //students
            if($request->has('age'))
            {
                $users = array();
                $ages = explode(',', $request->input('age'));
                // get all students
                $us = User::where('role_id', 4)->where('dob', '<>', null)->get();
                foreach($ages as $a){
                  foreach($us as $index => $u) {
                      
                  }
                }

                dd($users);

            } else {
                $users = DB::table('users as u')
                    ->where('u.role_id', 4)
                    ->join('location_user as lu', 'lu.user_id', '=', 'u.id')
                    ->join('locations as l', 'l.id', '=', 'lu.location_id')
                    ->where('l.id', $location_id)
                    ->select('u.*')
                    ->get();
            }
        } else {
            //teachers
            if($request->has('topic')) {
                $users = DB::table('users as u')
                    ->where('u.role_id', 2)
                    ->join('location_user as lu', 'lu.user_id', '=', 'u.id')
                    ->join('locations as l', 'l.id', '=', 'lu.location_id')
                    ->where('l.id', $location_id)
                    ->join('topic_user as tu', 'tu.user_id', '=', 'u.id')
                    ->join('topics as t', 't.id', '=', 'tu.topic_id')
                    ->where('t.id', $topic_id)
                    ->select('u.*')
                    ->get();
            } else {
                $users = DB::table('users as u')
                    ->where('u.role_id', 2)
                    ->join('location_user as lu', 'lu.user_id', '=', 'u.id')
                    ->join('locations as l', 'l.id', '=', 'lu.location_id')
                    ->where('l.id', $location_id)
                    ->select('u.*')
                    ->get();
            }
        }


        if($request->input('type') == 'email')
        {
            //send email
            foreach($users as $user)
            {
                if($request->input('students'))
                {
                    $user->email ? Mail::to($user->email)->send(new BulkMessageStudent($user->user_name,$m)) : null;
                } else {
                    $user->email ? Mail::to($user->email)->send(new BulkMessageTeacher($user->user_name,$m)) : null;
                }
            }

        } else {
            //send sms
            foreach($users as $user)
            {
                $msg = 'Hi '.$user->user_name.','.$m;
                $user->phone_number ? Helper::sendSMS($msg,$user->phone_number) : null;
            }
        }
    }
}
