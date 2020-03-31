<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Update;
use App\LectureCategory;
use App\LectureSubCategory;
use App\Lecture;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }
    
    public function index()
    {   
        return view('student.index');
    }

    public function newUpdateForm(){
        return view('student.update');
    }
    public function writeAnUpdate(Request $request){
        $phoneNumber = auth()->user()->phone_number;
        $userId = auth()->user()->id;
        $content = $request->input('content');
        Update::create([  
            'phone_number' => $phoneNumber,
            'user_id' => $userId,
            'is_teacherOrAdmin' => false,
            'content' => $content,
        ]);
        return redirect('/student/updates');
    }

    public function updates(){
        $userId = auth()->user()->id;
        $updates = Update::where('user_id', $userId)->orderBy('created_at','desc')->get();
        return view("student.updates",compact('updates'));
    }

    
}
