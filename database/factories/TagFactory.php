<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        "name" => $faker->name,
        "type" => Tag::FOOD_TYPE_TAG,
        "image_url" => $faker->imageUrl()
    ];
});
