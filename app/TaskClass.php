<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\User;

class TaskClass extends Model
{
    protected $table = 'task_classes';
    
    protected $fillable = [
        'name', 'location_id', 'is_completed', 'is_deleted','starts_at', 'ends_at'
    ];

    public function getStartsAtAttribute($value)
    {
        return Carbon::parse($value)->toDayDateTimeString();
    }

    public function getEndsAtAttribute($value)
    {
        return Carbon::parse($value)->toDayDateTimeString();
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('id','task_class_id', 'user_id', 'completed');
    }

    public function completed_user_classes()
    {
        return $this->belongsToMany(TaskClass::class)
            ->wherePivot('completed', 1)
            ->withPivot('id','task_class_id', 'user_id', 'completed');
    }

    public function incomplete_user_classes()
    {
        return $this->belongsToMany(TaskClass::class)
            ->wherePivot('completed', 0)
            ->withPivot('id','task_class_id', 'user_id', 'completed');
    }
}
