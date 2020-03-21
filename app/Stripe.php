<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stripe extends Model
{
    protected $table = 'stripes';

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
