<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Update;
use App\User;

class ViewUpdateController extends Controller
{
    public function viewUpdate($phoneNumber, $updateId){
        $updatesData = Update::where('phone_number',$phoneNumber)->get();
        foreach($updatesData as $update){
            if($update->id == $updateId){

                $userName = User::where('id', $update->user_id)->value('full_name');
                $isTeacher = $update->is_teacherOrAdmin;
                if($isTeacher){
                    $created_by = "Teacher: ".$userName;  
                }
                else{
                    $created_by = "Student: ".$userName;
                }
                $update = ["id" => $update->id,
                            "content" =>  $update->content,
                            "created_at" => $update->created_at,
                            "created_by" => $created_by
                ];
                return view("view_updates.update",compact('update'));
            }
        }
    }
    
    public function viewStudentUpdate($phoneNumber, $updateId)
    {
        $update = Update::where('phone_number',$phoneNumber)
            ->where('id', $updateId)
            ->where('teacher_id', '<>', null)
            ->with('teacher')
            ->first();

        if(!$update) {
            return view("view_updates.blank");
        } else {
            return view("view_updates.teachers-update")->withUpdate($update);
        }

    }
}
