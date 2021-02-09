<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CollectionPoint;
use App\Models\Meal;
use Faker\Generator as Faker;

$factory->define(Meal::class, function (Faker $faker) {
    $collection_point = CollectionPoint::all()->random()->id ?? factory(CollectionPoint::class)->create()->id;
    return [
        "name" => $faker->name,
        "description" => $faker->sentence(7),
        "quantity" => $faker->numberBetween(3, 80),
        "collection_point_id" => $collection_point,
    ];
});
