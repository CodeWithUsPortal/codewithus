<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->role;
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
                break;
            case 'teacher':
                return redirect()->route('teacher.dashboard');
                break; 
            case 'parent':
                return redirect()->route('parent.dashboard');
                break; 
            case 'student':
                return redirect()->route('student.dashboard');
                break; 
        }
        return view('home');
    }
}
