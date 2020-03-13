<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreeSessionBooking extends Model
{
    protected $table = 'free_session_bookings';
    protected $fillable = [
        'location_id','student_name','student_age','phone_number','email','topic_id',
        'ad_source','free_session_time_slot_id','student_expectations'
    ];

}
