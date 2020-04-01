<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use Illuminate\Http\Request;
use App\LectureCategory;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher|admin');
    }

    public function allLectureCategories(){
        $data = LectureCategory::where('is_deleted', false)->orderby('priority')->get();
        return $data;
    }

    public function indexLectureCategories(){
        $user = Auth::user();
        $role = $user->role->role;
        if($role == "admin"){
            return view('category.admin_index');
        }
        elseif($role == "teacher"){
            return view('category.teacher_index');
        }
    }

    public function storeLectureCategory(Request $request){
        $userId = auth()->user()->id;
        $data = new LectureCategory();
        $data->name = $request->category_name;
        $data->password = Helper::generateUniqueValue('lecture_categories', 'password');
        $data->created_by = $userId;
        $data->save();
      
        $categoryAdded = LectureCategory::where('name',$request->category_name)->first();
        $categoryAdded->priority =  $categoryAdded->id;
        $categoryAdded->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }

    public function updateLectureCategory(Request $request, $id){

        $isDuplicate = LectureCategory::where('password', $request->input('password'))
            ->where('id', '!=', $id)
            ->count();

        if($isDuplicate)
        {
            return response()->json(['response_msg' => 'Duplicate password given, try something else!'], 404);
        } else {
            $userId = auth()->user()->id;
            $data = LectureCategory::find($id);
            $data->name = $request->category_name;
            $data->password = $request->password;
            $data->created_by = $userId;
            $data->update();
            return response()->json(['response_msg' => 'Data saved'], 200);
        }
    }

    public function destroyLectureCategory($id){
        $userId = auth()->user()->id;
        $data = LectureCategory::find($id);
        $data->is_deleted = true;
        $data->created_by = $userId;
        //$data->created_at = Now();
        $data->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }

    public function updateCategoryPriority(Request $request){
        $categories = LectureCategory::all();
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
