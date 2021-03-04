<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Meal;
use Faker\Generator as Faker;

$factory->define(Meal::class, function (Faker $faker) {
    $collection_point = factory(\App\Models\CollectionPoint::class)->create();
    return [
        "name" => $faker->name,
        "description" => $faker->sentence(7),
        "total_quantity_available" => $faker->numberBetween(3, 80),
        "collection_point_id" => $collection_point->id,
    ];
});
