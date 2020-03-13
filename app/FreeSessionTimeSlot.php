<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreeSessionTimeSlot extends Model
{
    protected $table = 'free_session_time_slots';
    protected $fillable = [
        'day_id','time_id','location_id'
    ];

}
