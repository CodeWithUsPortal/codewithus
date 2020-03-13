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
        Location::create(['location_name'=>'Campbell','secret_code' => '6692', 'show_free_session'=> false ]);
        Location::create(['location_name'=>'Long Island','secret_code' => '6693', 'show_free_session'=> true ]);
        Location::create(['location_name'=>'Fremont','secret_code' => '6694', 'show_free_session'=> true ]);
    }
}
