<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Role;
use App\Location;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name','full_name', 'email', 'phone_number','password','role_id','location_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function hasRole($roles){
        if(is_string($roles)){
            return !! $this->role->role === $roles;
        }
        else{
            foreach($roles as $role){
                if($this->role->role == $role){
                    return true;
                }
            }
        }
        return false;
    }

    public function taskclasses()
    {
        return $this->belongsToMany(TaskClass::class);
    }
    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }
}
