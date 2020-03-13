<?php

use Illuminate\Database\Seeder;
use App\Time;
class TimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Time::create(['time'=>'09:00:00']);
        Time::create(['time'=>'10:00:00']);
        Time::create(['time'=>'11:00:00']);
        Time::create(['time'=>'12:00:00']);
        Time::create(['time'=>'13:00:00']);
        Time::create(['time'=>'14:00:00']);
        Time::create(['time'=>'15:00:00']);
        Time::create(['time'=>'16:00:00']);
        Time::create(['time'=>'17:00:00']);
        Time::create(['time'=>'18:00:00']);
        Time::create(['time'=>'19:00:00']);
    }
}
