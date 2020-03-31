<?php

namespace App\Http\Controllers;

use App\PermanentClassSchedule;
use Illuminate\Http\Request;

class PermanentClassScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return response()->json(
            [
                'data' => PermanentClassSchedule::with('location')
                    ->where('is_deleted', '<>', 1)
                    ->orderBy('day')
                    ->get(),
                'message' => null,
                'status' =>200
            ], 200);
    }

    public function store(Request $request)
    {

        $data = [
            'day' => $request->input('day'),
            'time' => $request->input('time'),
            'location_id' => $request->input('location_id'),
            'student_id' => $request->input('student_id'),
        ];

        $present = PermanentClassSchedule::where($data)->get()->count();


        if(!$present) {
            $schedule = PermanentClassSchedule::create($data);
        } else {
            return response()->json(['data'=> null, 'message' => 'Permanent class schedule already exists!', 'status'=>'error'],200);
        }

        return response()->json(['data'=> $schedule, 'message' => 'Permanent class schedule added!', 'status'=>'success'],200);
    }

    public function destroy(PermanentClassSchedule $permanentClassSchedule)
    {
        $permanentClassSchedule->delete();

        return response()->json(['data'=> null, 'message' => 'Permanent class schedule deleted!', 'status'=>'success'],200);
    }
}
