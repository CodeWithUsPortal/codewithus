<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LectureSubCategory;
use App\LectureCategory;
use Auth;
class SubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher|admin');
    }
    public function allLectureSubCategories(){
        $allSubCategories = LectureSubCategory::where('is_deleted', false)->orderby('priority')->get();
        $subCategories = array();
        foreach($allSubCategories as $subCategory){
            $category = LectureCategory::find($subCategory['category_id']);
            $categoryName = $category->name;
            $dataArray = ["id" => $subCategory['id'],
                      "name" => $subCategory['name'],
                      "category_id" => $subCategory['category_id'],
                      "category_name" => $categoryName,
                    ];
            array_push($subCategories,$dataArray);           
        }


        $allCategories = LectureCategory::all();
        $categories = array();
        foreach($allCategories as $category){
            $dataArray = ["id" => $category['id'],
                      "name" => $category['name'],
                    ];
            array_push($categories,$dataArray);
        }
       
        return response()->json(['categories'=> $categories, 'subcategories'=> $subCategories],200);
    }
    public function indexLectureSubCategories(){
        $user = Auth::user();
        $role = $user->role->role;
        if($role == "admin"){
            return view('subcategory.admin_index');
        }
        elseif($role == "teacher"){
            return view('subcategory.teacher_index');
        }
    }
    public function storeLectureSubCategory(Request $request){
        $userId = auth()->user()->id;
        $data = new LectureSubCategory();
        $data->name = $request->sub_category_name;
        $data->created_by = $userId;
        $data->category_id = $request->category_id;
        $data->save();

        $subCategoryAdded = LectureSubCategory::where('name',$request->sub_category_name)->first();
        $subCategoryAdded->priority =  $subCategoryAdded->id;
        $subCategoryAdded->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function updateLectureSubCategory(Request $request, $id){
        $userId = auth()->user()->id;
        $data = LectureSubCategory::find($id);
        $data->name = $request->sub_category_name;
        $data->created_by = $userId;
        $data->category_id = $request->category_id;
        $data->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function destroyLectureSubCategory($id){
        $userId = auth()->user()->id;
        $data = LectureSubCategory::find($id);
        $data->is_deleted = true;
        $data->created_by = $userId;
        //$data->created_at = Now();
        $data->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function updateSubCategoryPriority(Request $request){
        $subCategories = LectureSubCategory::all();
        foreach($subCategories as $subCategory){
	        $subCategory_id = $subCategory->id;
	        foreach($request->subcategories as $subCategoryFromView){
		        if($subCategoryFromView['id'] == $subCategory_id ){
                    if($subCategoryFromView['priority'] != $subCategory->priority )
			            $subCategory->update(['priority' => $subCategoryFromView['priority'] ]);
		        }
	        }
        }
    }
}
