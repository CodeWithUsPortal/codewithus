<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\LessonSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        return view('lessons.lessons');
    }

    public function allLessons(){
        $allLessons = Lesson::where('is_deleted', false)->orderby('priority')->get();
        $lessons = array();
        foreach($allLessons as $lesson){
            $subCategory = LessonSubCategory::find($lesson['lesson_sub_category_id']);
            $subcategoryName = $subCategory->name;
            $dataArray = ["id" => $lesson['id'],
                "name" => $lesson['title'],
                "link" => $lesson['link'],
                "subcategory_id" => $lesson['lesson_sub_category_id'],
                "subcategory_name" => $subcategoryName,
            ];
            array_push($lessons,$dataArray);
        }


        $allSubCategories = LessonSubCategory::where('is_deleted', false)->get();
        $subCategories = array();
        foreach($allSubCategories as $subCategory){
            $dataArray = ["id" => $subCategory['id'],
                "name" => $subCategory['name'],
            ];
            array_push($subCategories,$dataArray);
        }

        return response()->json(['lessons'=> $lessons, 'subcategories'=> $subCategories],200);
    }
    public function storeLesson(Request $request)
    {
        $userId = auth()->user()->id;

        $data = new Lesson();
        $data->title = $request->input('lesson_name');
        $data->link = $request->input('lesson_link');
        $data->created_by = $userId;
        $data->lesson_sub_category_id = $request->subcategory_id;
        $data->save();

        $lessonAdded = Lesson::where('title',$request->lesson_name)->first();
        $lessonAdded->priority =  $lessonAdded->id;
        $lessonAdded->update();

        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function updateLesson(Request $request, $id){
        $userId = auth()->user()->id;
        $data = Lesson::find($id);
        $data->title = $request->lesson_name;
        $data->link = $request->lesson_link;
        $data->created_by = $userId;
        $data->lesson_sub_category_id = $request->subcategory_id;
        $data->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function destroyLesson($id){
        $userId = auth()->user()->id;
        $data = Lesson::find($id);
        $data->is_deleted = true;
        $data->created_by = $userId;
        //$data->created_at = Now();
        $data->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function updateLessonPriority(Request $request){
        $lessons = Lesson::all();
        foreach($lessons as $lesson){
            $lesson_id = $lesson->id;
            foreach($request->lessons as $lessonFromView){
                if($lessonFromView['id'] == $lesson_id ){
                    if($lessonFromView['priority'] != $lesson->priority )
                        $lesson->update(['priority' => $lessonFromView['priority'] ]);
                }
            }
        }
    }

}
