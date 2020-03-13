<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(TopicsTableSeeder::class);
        $this->call(DaysTableSeeder::class);
        $this->call(TimesTableSeeder::class);
        $this->call(FreeSessionTimeSlotsTableSeeder::class);
    }
}
