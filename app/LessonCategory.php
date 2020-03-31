<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonCategory extends Model
{
    protected $table = 'lesson_categories';

    protected $fillable = ['priority',];

    public function sub_categories()
    {
        return $this->hasMany(LessonSubCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
