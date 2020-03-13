<?php

use Illuminate\Database\Seeder;
use App\FreeSessionTimeSlot;
class FreeSessionTimeSlotsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FreeSessionTimeSlot::create(['day_id'=> 1,'time_id' => 5,'location_id' => 1]);
        FreeSessionTimeSlot::create(['day_id'=> 2,'time_id' => 4,'location_id' => 1]);
        FreeSessionTimeSlot::create(['day_id'=> 3,'time_id' => 3,'location_id' => 1]);
        FreeSessionTimeSlot::create(['day_id'=> 4,'time_id' => 2,'location_id' => 1]);
        FreeSessionTimeSlot::create(['day_id'=> 5,'time_id' => 1,'location_id' => 1]);

        FreeSessionTimeSlot::create(['day_id'=> 6,'time_id' => 1,'location_id' => 2]);
        FreeSessionTimeSlot::create(['day_id'=> 7,'time_id' => 2,'location_id' => 2]);
        FreeSessionTimeSlot::create(['day_id'=> 1,'time_id' => 3,'location_id' => 2]);
        FreeSessionTimeSlot::create(['day_id'=> 2,'time_id' => 4,'location_id' => 2]);
        FreeSessionTimeSlot::create(['day_id'=> 3,'time_id' => 5,'location_id' => 2]);

        FreeSessionTimeSlot::create(['day_id'=> 1,'time_id' => 8,'location_id' => 3]);
        FreeSessionTimeSlot::create(['day_id'=> 2,'time_id' => 9,'location_id' => 3]);
        FreeSessionTimeSlot::create(['day_id'=> 3,'time_id' => 10,'location_id' => 3]);
    }
}
