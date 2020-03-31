<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\LectureSubCategory;

class LectureCategory extends Model
{
    protected $table = 'lecture_categories';
    protected $fillable = [
        'name', 'priority', 'password'
    ];

    public function sub_categories(){
        return $this->hasMany(LectureSubCategory::class);
    }

    public function permitted_students()
    {
        return $this->belongsToMany(LectureCategory::class);
    }
}
