<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lessons';

    protected $fillable = ['priority',];

    public function sub_category()
    {
        return $this->belongsTo(LessonSubCategory::class, 'lesson_sub_category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function task_classes()
    {
        return $this->hasMany(TaskClass::class);
    }
}
