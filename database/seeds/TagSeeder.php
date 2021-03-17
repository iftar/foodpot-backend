<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 10 Users with no Orders
        factory(\App\Models\Tag::class, 12)->create();
        factory(\App\Models\Meal::class, 32)->create();
        factory(\App\Models\MealTag::class, 12)->create();

    }
}
