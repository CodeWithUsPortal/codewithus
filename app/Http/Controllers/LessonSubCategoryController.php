<?php

namespace App\Http\Controllers;

use App\LessonCategory;
use App\LessonSubCategory;
use Illuminate\Http\Request;

class LessonSubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    public function index()
    {
        return view('lessons.subcategories');
    }
    public function allLessonSubCategories(){
        $allSubCategories = LessonSubCategory::where('is_deleted', 0)->orderby('priority')->get();
        $subCategories = array();
        foreach($allSubCategories as $subCategory){
            $category = LessonCategory::find($subCategory['lesson_category_id']);
            $categoryName = $category->name;
            $dataArray = ["id" => $subCategory['id'],
                "name" => $subCategory['name'],
                "category_id" => $subCategory['lesson_category_id'],
                "category_name" => $categoryName,
            ];
            array_push($subCategories,$dataArray);
        }


        $allCategories = LessonCategory::where('is_deleted', 0)->get();
        $categories = array();
        foreach($allCategories as $category){
            $dataArray = ["id" => $category['id'],
                "name" => $category['name'],
            ];
            array_push($categories,$dataArray);
        }

        return response()->json(['categories'=> $categories, 'subcategories'=> $subCategories],200);
    }
    public function storeLessonSubCategory(Request $request){
        $userId = auth()->user()->id;
        $data = new LessonSubCategory;
        $data->name = $request->sub_category_name;
        $data->created_by = $userId;
        $data->lesson_category_id = $request->category_id;
        $data->save();

        $subCategoryAdded = LessonSubCategory::where('name',$request->sub_category_name)->first();
        $subCategoryAdded->priority =  $subCategoryAdded->id;
        $subCategoryAdded->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function updateLessonSubCategory(Request $request, $id){
        $userId = auth()->user()->id;
        $data = LessonSubCategory::find($id);
        $data->name = $request->sub_category_name;
        $data->created_by = $userId;
        $data->lesson_category_id = $request->category_id;
        $data->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function destroyLessonSubCategory($id){
        $userId = auth()->user()->id;
        $data = LessonSubCategory::find($id);
        $data->is_deleted = true;
        $data->created_by = $userId;
        //$data->created_at = Now();
        $data->update();
        return response()->json(['response_msg'=>'Data saved'],200);
    }
    public function updateSubCategoryPriority(Request $request){
        $subCategories = LessonSubCategory::all();
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
