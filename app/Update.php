<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    protected $table = 'updates';
    protected $fillable = [
        'phone_number','user_id', 'is_teacherOrAdmin', 'teacher_id', 'content',
    ];

    public function teacher()
    {
        return $this->belongsTo('App\User', 'teacher_id', 'id');
    }
}
