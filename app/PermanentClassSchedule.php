<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermanentClassSchedule extends Model
{
    protected $fillable = ['day', 'time', 'location_id', 'student_id'];

    protected $days = ['Monday','Tuesday', 'Wednesday','Thursday','Friday','Saturday','Sunday'];

    public function student(){
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function location(){
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function getDayAttribute($value){
        return $this->days[$value];
    }
}
