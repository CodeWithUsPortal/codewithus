<?php

use Illuminate\Database\Seeder;
use App\TaskClassType;
class TaskClassTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaskClassType::create(['type_name'=>'Small-group class']);
        TaskClassType::create(['type_name'=>'Private class']);
        TaskClassType::create(['type_name'=>'15 dollar class']);
    }
}
