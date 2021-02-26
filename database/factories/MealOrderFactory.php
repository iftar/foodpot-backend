<?php


/** @var Factory $factory */

use App\Models\Meal;
use App\Models\Order;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(\App\Models\MealOrder::class, function (Faker $faker) {
    return [
        "meal_id" => factory(Meal::class)->create(),
        "order_id" => factory(Order::class)->create(),
        "quantity" => $faker->numberBetween(1,4)
    ];
});
