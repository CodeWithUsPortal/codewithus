<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permanent_class_schedules()
    {
        return $this->hasMany(PermanentClassSchedule::class, 'location_id', 'id');
    }
}
