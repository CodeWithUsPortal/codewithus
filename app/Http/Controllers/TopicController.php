<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TaskClass;
use App\Teacher;
use App\User;
use App\Role;
use App\Location;
use App\Topic;

class TopicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin|teacher');
    }
    
    public function topicsIndexPage(){
        return view('topic.index');
    }
    public function getTopics(){
        $topicsData = Topic::where(['is_deleted' => false,])->get();
        $topics = array();
        foreach($topicsData as $topic){
            $dataArray = [
                "topic_id" => $topic->id,
                "topic_name" => $topic->name,
            ];
            array_push($topics,$dataArray);
        }
        return response()->json(['topics'=> $topics],200);
    }
    public function addTopic(Request $request){
        Topic::create([  
            'name' => $request->topic_name,
        ]);
        return response()->json(['response_msg'=>'Data Saved'],200);
    }
    public function editTopic(Request $request, $id){
        $data = Topic::find($id);
        $data->name = $request->topic_name;
        $data->update();
        return response()->json(['response_msg'=>'Data updated'],200);
    }
    public function deleteTopic($id){
        $data = Topic::find($id);
        $data->is_deleted = true;
        $data->update();
        return response()->json(['response_msg'=>'Data deleted'],200);
    }
}
