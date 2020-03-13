<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LectureSubCategory;
use App\Lecture;
use Auth;
class LectureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher|admin');
    }
    public function allLectures(){
        $allLectures = Lecture::where('is_deleted', false)->orderby('priority')->get();
        $lectures = array();
        foreach($allLectures as $lecture){
            $subCategory = LectureSubCategory::find($lecture['sub_category_id']);
            $subcategoryName = $subCategory->name;
            $dataArray = ["id" => $lecture['id'],
                      "name" => $lecture['title'],
                      "link" => $lecture['link'],
                      "subcategory_id" => $lecture['sub_category_id'],
                      "subcategory_name" => $subcategoryName,
                    ];
            array_push($lectures,$dataArray);           
        }


        $allSubCategories = LectureSubCategory::where('is_deleted', false)->get();
        $subCategories = array();
        foreach($allSubCategories as $subCategory){
            $dataArray = ["id" => $subCategory['id'],
                      "name" => $subCategory['name'],
                    ];
            array_push($subCategories,$dataArray);
        }
       
        return response()->json(['lectures'=> $lectures, 'subcategories'=> $subCategories],200);
    }
    public function indexLectures(){
        $user = Auth::user();
        $role = $user->role->role;
        if($role == "admin"){
            return view('lecture.admin_index');
        }
        elseif($role == "admin"){
            return view('lecture.teacher_index');
        }
    }
    public function storeLecture(Request $request){
        $userId = auth()->user()->id;
        $data = new Lecture();
        $data->title = $request->lecture_name;
        $data->link = $request->lecture_link;
        $data->created_by = $userId;
        $data->sub_category_id = $request->subcategory_id;
        $data->save();
        
        $lectureAdded = Lecture::where('title',$request->lecture_name)->first();
        $lectureAdded->priority =  $lectureAdded->id;
        $lectureAdded->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function updateLecture(Request $request, $id){
        $userId = auth()->user()->id;
        $data = Lecture::find($id);
        $data->title = $request->lecture_name;
        $data->link = $request->lecture_link;
        $data->created_by = $userId;
        $data->sub_category_id = $request->subcategory_id;
        $data->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function destroyLecture($id){
        $userId = auth()->user()->id;
        $data = Lecture::find($id);
        $data->is_deleted = true;
        $data->created_by = $userId;
        //$data->created_at = Now();
        $data->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function updateLecturePriority(Request $request){
        $lectures = Lecture::all();
        foreach($lectures as $lecture){
	        $lecture_id = $lecture->id;
	        foreach($request->lectures as $lectureFromView){
		        if($lectureFromView['id'] == $lecture_id ){
                    if($lectureFromView['priority'] != $lecture->priority )
                        $lecture->update(['priority' => $lectureFromView['priority'] ]);
		        }
	        }
        }
    }
}
