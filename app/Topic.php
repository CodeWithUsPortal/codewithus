<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'topics';
    protected $fillable = [
        'name'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
