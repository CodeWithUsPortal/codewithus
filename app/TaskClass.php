<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class TaskClass extends Model
{
    protected $table = 'task_classes';
    
    protected $fillable = [
        'name', 'location_id', 'is_completed', 'is_deleted','starts_at', 'ends_at'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
