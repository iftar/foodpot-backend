<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CollectionPoint;
use App\Models\Meal;
use Faker\Generator as Faker;

$factory->define(Meal::class, function (Faker $faker) {
    return [
        "name" => $faker->name,
        "description" => $faker->sentence(7),
        "total_quantity_available" => $faker->numberBetween(3, 80),
        "collection_point_id" => factory(CollectionPoint::class)->create()->id
    ];
});
