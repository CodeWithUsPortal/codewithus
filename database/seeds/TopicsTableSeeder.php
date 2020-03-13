<?php

use Illuminate\Database\Seeder;
use App\Topic;
class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Topic::create(['name'=>'Not sure' ]);
        Topic::create(['name'=>'Scratch' ]);
        Topic::create(['name'=>'Robotics' ]);
        Topic::create(['name'=>'Python' ]);
        Topic::create(['name'=>'Minecraft Modding' ]);
        Topic::create(['name'=>'Java' ]);
        Topic::create(['name'=>'Javascript' ]);
    }
}
