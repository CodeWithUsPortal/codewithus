<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LectureCategory;
use App\LectureSubCategory;
use App\Lecture;

class ViewLectureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student|teacher');
    }

    public function getLectureCategories(){
        $categoriesData = LectureCategory::where('is_deleted', false)->orderby('priority')->get();
        $categories = array();
        foreach($categoriesData as $category){
            $url = "/subCategories/".$category->id."";
            $dataArray = ["id" =>$category->id,
                      "name" => $category->name,
                      "url" => $url,
                    ];
            array_push($categories,$dataArray);           
        }
        return view('view_lecture.categories')->with(['categories' =>$categories]);   
    }

    public function getLectureSubCategories($categoryId){
        if($categoryId != null){
            $subCategoriesData = LectureSubCategory::where('category_id',$categoryId)
                                ->orderby('priority')->get();
        }
        else{
            $subCategoriesData = LectureSubCategory::where('is_deleted', false)
                                 ->orderby('priority')->get();
        }
        $subCategories = array();
        foreach($subCategoriesData as $subCategory){
            $url = "/lectures/".$subCategory->id."";
            $dataArray = ["id" =>$subCategory->id,
                      "name" => $subCategory->name,
                      "url" => $url,
                    ];
            array_push($subCategories,$dataArray);           
        }
        return view('view_lecture.sub_categories')->with(['subCategories' =>$subCategories]);   
    }
    public function getLectures($subCategoryId){
        if($subCategoryId != null){
            $lecturesData = Lecture::where('sub_category_id',$subCategoryId)
                            ->orderby('priority')->get();
        }
        else{
            $lecturesData = Lecture::where('is_deleted', false)
                            ->orderby('priority')->get();
        }
        $lectures = array();
        foreach($lecturesData as $lecture){
            $dataArray = ["id" =>$lecture->id,
                        "name" => $lecture->title,
                        "url" => $lecture->link,
                    ];
            array_push($lectures,$dataArray);           
        }
        return view('view_lecture.lectures')->with(['lectures' =>$lectures]);   
    }

    public function getLectureCategoriesForTeachers(){
        $categoriesData = LectureCategory::where('is_deleted', false)->orderby('priority')->get();
        $categories = array();
        foreach($categoriesData as $category){
            $url = "/subCategories/".$category->id."";
            $dataArray = ["id" =>$category->id,
                      "name" => $category->name,
                      "url" => $url,
                    ];
            array_push($categories,$dataArray);           
        }
        return view('view_lecture.categories_teacher')->with(['categories' =>$categories]);   
    }

    public function getLectureSubCategoriesForTeachers($categoryId){
        if($categoryId != null){
            $subCategoriesData = LectureSubCategory::where('category_id',$categoryId)
                                ->orderby('priority')->get();
        }
        else{
            $subCategoriesData = LectureSubCategory::where('is_deleted', false)
                                 ->orderby('priority')->get();
        }
        $subCategories = array();
        foreach($subCategoriesData as $subCategory){
            $url = "/lectures/".$subCategory->id."";
            $dataArray = ["id" =>$subCategory->id,
                      "name" => $subCategory->name,
                      "url" => $url,
                    ];
            array_push($subCategories,$dataArray);           
        }
        return view('view_lecture.sub_categories_teacher')->with(['subCategories' =>$subCategories]);   
    }
    public function getLecturesForTeachers($subCategoryId){
        if($subCategoryId != null){
            $lecturesData = Lecture::where('sub_category_id',$subCategoryId)
                            ->orderby('priority')->get();
        }
        else{
            $lecturesData = Lecture::where('is_deleted', false)
                            ->orderby('priority')->get();
        }
        $lectures = array();
        foreach($lecturesData as $lecture){
            $dataArray = ["id" =>$lecture->id,
                        "name" => $lecture->title,
                        "url" => $lecture->link,
                    ];
            array_push($lectures,$dataArray);           
        }
        return view('view_lecture.lectures_teacher')->with(['lectures' =>$lectures]);   
    }
    // Following method is to show all the categories, sub_categories and lectures
    // on the same page

    // public function lectures(){
    //     $lectures = array();
    //     $categories = LectureCategory::where('is_deleted', false)->orderby('priority')->get();
        
    //     foreach($categories as $category){
    //         $subCategories = LectureSubCategory::where('category_id', $category->id)->get();
            
    //         foreach($subCategories as $subCategory){
    //             $lectureData = Lecture::where('sub_category_id', $subCategory->id)->get();
    //             if(count($lectureData) > 0){
    //                 $dataArray = ["category" => $category->name,
    //                           "subCategory" => $subCategory->name,
    //                           "lectures" => $lectureData
    //                 ];
    //                 array_push($lectures,$dataArray);
    //             }
    //         }
    //     }
    //     return view('student.lectures')->with(['data' =>$lectures]);
    // }
}
