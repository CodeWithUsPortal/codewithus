<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonSubCategory extends Model
{
    protected $table = 'lesson_sub_categories';

    protected $fillable = ['priority',];

    public function category()
    {
        return $this->belongsTo(LessonCategory::class, 'lesson_category_id', 'id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
