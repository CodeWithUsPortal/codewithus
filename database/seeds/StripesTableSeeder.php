<?php

use Illuminate\Database\Seeder;
use App\Stripe;

class StripesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Stripe::create(['product_id' => 'plan_F4bbPob7ML3Tla',
                        'product_name'=>'One Student in Small-Group Classes ($148/month)',
                        'price' => 15,
                        'number_of_credits' => 1,
                        'is_subscription' => false,
                        'task_class_type_id' => 1,
                        'location_id' => 1]);
        Stripe::create(['product_id' => 'plan_F4dUZo3OZrHNun',
                        'product_name'=>'One-on-One Coding Classes ($240/month)',
                        'price' => 10,
                        'number_of_credits' => 4.3,
                        'is_subscription' => true,
                        'task_class_type_id' => 2,
                        'location_id' => 2]);
        Stripe::create(['product_id' => '108',
                        'product_name'=>'Kings After School Coding (8 Weeks) $108',
                        'price' => 5,
                        'number_of_credits' => 1,
                        'is_subscription' => true,
                        'task_class_type_id' => 1,
                        'location_id' => 1]);
        Stripe::create(['product_id' => '147',
                        'product_name'=>'Fammatre Coding (7 Weeks) $147',
                        'price' => 200,
                        'number_of_credits' => 4,
                        'is_subscription' => false,
                        'task_class_type_id' => 2,
                        'location_id' => 2]);
        Stripe::create(['product_id' => 'plan_FvrNWFt6NGhWCI',
                        'product_name'=>'One Student in Small-Group Classes ($148/month)',
                        'price' => 500,
                        'number_of_credits' => 4,
                        'is_subscription' => false,
                        'task_class_type_id' => 1,
                        'location_id' => 1]);
        Stripe::create(['product_id' => 'plan_FvrQwAFc3GEOH0',
                        'product_name'=>'One-on-One Coding Classes ($240/month)',
                        'price' => 200,
                        'number_of_credits' => 0,
                        'is_subscription' => false,
                        'task_class_type_id' => 2,
                        'location_id' => 1]);
    }
}
