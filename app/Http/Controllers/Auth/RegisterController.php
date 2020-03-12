<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Role;
use Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Torann\LaravelAsana;
use Torann\LaravelAsana\Facade;
use Torann\LaravelAsana\Facade\Asana;
use App\Location;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
   

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function showRegistrationFormForStudentsPage1()
    {
        return view('auth.students_register_page1');
    }
    public function studentsRegistrationPage1(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|max:255|unique:users',
        ]);
        if ($validator->fails()) {
            return back()->with('duplicate_user_name','User name already exists');
        }
        else{
            return view('auth.students_register_page2')
              ->withUsername($request->user_name)->withPassword($request->password)->withError("");
        }
    }
    public function showRegistrationFormForTeachers(){
        return view('auth.teachers_register');
    }

    public function showRegistrationFormForAdmins(){
        return view('auth.admins_register');
    }
    // When the registration forms gets submit, it calls RegisterController@register
    // function but we are putting an intermediate method here to perform some extra 
    // checks before the registration happens.
    public function registrationChecks(Request $request){
        if($request->role_type == "teacher" || $request->role_type == "admin" ){
            $location = Location::where('secret_code',$request->secret_code)->get();
            if(count($location) <= 0){
                return back()->with('incorrect_security_code','Incorrect Security Code.');
            }
            else{
                if($request->role_type == "admin"){
                    $this->register($request);
                }
                else{
                    $teacher_email_found_in_asana = false;
                    $users = Asana::getUsers();
                    $users = json_decode(json_encode($users), True);
                    $users = $users['data'];

                    foreach($users as $user){
                        $user_asana_id = $user['gid'];
                        $user_data = Asana::getUserInfo($user_asana_id);
                        $user_data = json_decode(json_encode($user_data), True);
                        $user_email_id = $user_data['data']['email'];
                        if($user_email_id == $request->email){
                                $teacher_email_found_in_asana = true;
                                break;
                        }
                    }
                    if($teacher_email_found_in_asana){
                        $this->register($request);
                    }
                    else{
                        return back()->with('asana_account_doesnot_exists','Asana account with this email address does not exists.');
                    }
                }
            }
        }
        elseif($request->role_type == "student"){

            $location = Location::where('secret_code',$request->secret_code)->get();
            if(count($location) > 0){
                $this->register($request);
            }
            else{
                return view('auth.students_register_page2')
                        ->withUsername($request->user_name)->withPassword($request->password)->withError("Provided Secret Code is wrong.");
            }
        }
        return redirect('/home');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {   
        if($data['role_type'] == "student"){
            return Validator::make($data, [
                'user_name' => 'required|max:255|unique:users',
                'password' => 'required|min:3',
                'full_name' => 'required|max:255',
                'phone_number' => 'required',
                'secret_code' => 'required|min:4',
            ]);
        }
        if($data['role_type'] == "teacher"){
            return Validator::make($data, [
                'user_name' => 'required|max:255|unique:users',
                'full_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'phone_number' => 'required',
                'password' => 'required|min:6|confirmed',
            ]);
        }
        if($data['role_type'] == "admin"){
            return Validator::make($data, [
                'user_name' => 'required|max:255|unique:users',
                'full_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'phone_number' => 'required',
                'password' => 'required|min:6|confirmed',
            ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $phoneNumberInput = str_replace(array('(',')'," ","-"), '',$data['phone_number']);
        $phoneNumber = $phoneNumberInput;
        if($phoneNumber[0] != "+" && $phoneNumber[0] != 1){
            $phoneNumber = "+1".$phoneNumber;
        }
        elseif($phoneNumber[0] != "+" && $phoneNumber[0] == 1){
            $phoneNumber = "+".$phoneNumber;
        }

        $roleId = Role::where('role',$data['role_type'])->value('id');
        $locationId = Location::where('secret_code',$data['secret_code'])->value('id');
        if($data['role_type'] == "student"){
            $user = User::create([        
                'user_name' => $data['user_name'],
                'password' => bcrypt($data['password']),
                'full_name'=> $data['full_name'],
                'phone_number' => $phoneNumber,
                'role_id' => $roleId,
            ]);
            $location = Location::find($locationId);
            $user->locations()->attach($location);
            return $user;
        }
        if($data['role_type'] == "teacher"){
            $user = User::create([  
                'user_name' => $data['user_name'],
                'full_name' => $data['full_name'],
                'password' => bcrypt($data['password']),
                'phone_number' => $phoneNumber,
                'email' => $data['email'],
                'role_id' => $roleId,
            ]);
            $location = Location::find($locationId);
            $user->locations()->attach($location);
            return $user;
        }
        if($data['role_type'] == "admin"){
            $user = User::create([  
                'user_name' => $data['user_name'],
                'full_name' => $data['full_name'],
                'password' => bcrypt($data['password']),
                'phone_number' => $phoneNumber,
                'email' => $data['email'],
                'role_id' => $roleId,
            ]);
            $location = Location::find($locationId);
            $user->locations()->attach($location);
            return $user;
        }
    }
}
