<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\LectureSubCategory;

class LectureCategory extends Model
{
    protected $table = 'lecture_categories';
    protected $fillable = [
        'priority',
    ];

    public function sub_categories(){
        return $this->hasMany(LectureSubCategory::class);
    }
}
