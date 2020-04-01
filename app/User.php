<?php

namespace App;

use App\Helper\Helper;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Role;
use App\Location;
use App\Topic;

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
        'user_name','full_name', 'email', 'phone_number','dob','password','role_id','location_id'
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
        return $this->belongsToMany(TaskClass::class)
            ->withPivot('id','task_class_id', 'user_id', 'completed');
    }

    public function completed_taskclasses()
    {
        return $this->belongsToMany(TaskClass::class)
            ->withPivot('id','task_class_id', 'user_id', 'completed')
            ->wherePivot('completed', 1);
    }

    public function incomplete_taskclasses()
    {
        return $this->belongsToMany(TaskClass::class)
            ->withPivot('id','task_class_id', 'user_id', 'completed')
            ->wherePivot('completed', 0);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }
    public function topics()
    {
        return $this->belongsToMany(Topic::class);
    }


    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'created_by', 'id');
    }

    public function lesson_categories()
    {
        return $this->hasMany(LessonCategory::class, 'created_by', 'id');
    }

    public function lesson_sub_categories()
    {
        return $this->hasMany(LessonSubCategory::class, 'created_by', 'id');
    }

    public function updates()
    {
        return $this->hasMany('App\Update', 'teacher_id', 'id');
    }

    public function permanent_class_schedules()
    {
        return $this->hasMany(PermanentClassSchedule::class, 'student_id', 'id');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role_id', 1);
    }

    public function scopeTeachers($query)
    {
        return $query->where('role_id', 2);
    }

    public function scopeParents($query)
    {
        return $query->where('role_id', 3);
    }

    public function scopeStudents($query)
    {
        return $query->where('role_id', 4);
    }

    public function lecture_categories()
    {
        return $this->belongsToMany(LectureCategory::class);
    }
}
