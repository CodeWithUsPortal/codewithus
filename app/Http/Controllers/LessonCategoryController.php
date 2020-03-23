<?php

namespace App\Http\Controllers;

use App\LessonCategory;
use Illuminate\Http\Request;

class LessonCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    public function index()
    {
        return view('lessons.categories');
    }
    public function allLessonCategories(){
        $data = LessonCategory::where('is_deleted', false)->orderby('priority')->get();
        return $data;
    }
    public function storeLessonCategory(Request $request){
        $userId = auth()->user()->id;
        $data = new LessonCategory();
        $data->name = $request->category_name;
        $data->created_by = $userId;
        $data->save();

        $categoryAdded = LessonCategory::where('name',$request->category_name)->first();
        $categoryAdded->priority =  $categoryAdded->id;
        $categoryAdded->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function updateLessonCategory(Request $request, $id){
        $userId = auth()->user()->id;
        $data = LessonCategory::find($id);
        $data->name = $request->category_name;
        $data->created_by = $userId;
        //$data->created_at = Now();
        $data->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function destroyLessonCategory($id){
        $userId = auth()->user()->id;
        $data = LessonCategory::find($id);
        $data->is_deleted = true;
        $data->created_by = $userId;
        //$data->created_at = Now();
        $data->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function updateCategoryPriority(Request $request){
        $categories = LessonCategory::all();
        foreach($categories as $category){
            $category_id = $category->id;
            foreach($request->categories as $categoryFromView){
                if($categoryFromView['id'] == $category_id ){
                    if($categoryFromView['priority'] != $category->priority )
                        $category->update(['priority' => $categoryFromView['priority'] ]);
                }
            }
        }
    }
}
