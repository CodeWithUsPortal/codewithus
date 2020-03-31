<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskClassType extends Model
{
    protected $table = 'task_class_types';
    
    protected $fillable = [
        'type_name','send_reminder'
    ];
}
