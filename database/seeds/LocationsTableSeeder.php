<?php

use Illuminate\Database\Seeder;
use App\Location;
class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::create(['location_name'=>'Campbell','secret_code' => '6692' ]);
        Location::create(['location_name'=>'Long Island','secret_code' => '6693' ]);
    }
}
