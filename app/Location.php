<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';

    protected $fillable = ['location_name', 'address', 'secret_code', 'show_free_session', 'online', 'classroom_url',
        'is_deleted', 'created_at', 'updated_at'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permanent_class_schedules()
    {
        return $this->hasMany(PermanentClassSchedule::class, 'location_id', 'id');
    }
}
