<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\LectureCategory;

class LectureSubCategory extends Model
{
    protected $table = 'lecture_sub_categories';
    protected $fillable = [
        'priority',
    ];
       
    public function category(){
        return $this->belongsTo(LectureCategory::class);
    }

}
