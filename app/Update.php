<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    protected $table = 'updates';
    protected $fillable = [
        'phone_number','user_id', 'is_teacherOrAdmin', 'content',
    ];
}
