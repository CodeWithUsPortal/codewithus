<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return view('locations.index');
    }

    public function getLocations()
    {
        $locations = Location::where('is_deleted', 0)->get();

        return response()->json(['locations' => $locations, 'message' => null, 'status' => 'success'], 200);
    }

    public function store(Request $request)
    {
        if(!$request->has('id')) {
            $this->validate($request, [
                'location_name' => 'required|unique:locations,location_name',
            ]);
        } else {
            $this->validate($request, [
                'location_name' => 'required|unique:locations,location_name,'.$request->input('id'),
            ]);
        }

        $location = Location::updateOrCreate(
            ['id' => $request->input('id')],
            [
                'location_name' => $request->input('location_name'),
               'secret_code' => $request->input('secret_code'),
               'classroom_url' => $request->has('classroom_url') ? $request->input('classroom_url') : null,
               'address' => $request->input('address'),
               'show_free_session' => $request->input('show_free_session'),
               'online' => $request->input('online'),
            ]
        );

        if($request->has('id'))
        {
            return response()->json(['data' => $location, 'message' => 'Location updated', 'status' => 'success'], 200);
        } else {
            return response()->json(['data' => $location, 'message' => 'Location added successfully', 'status' => 'success'], 200);
        }
    }

}
