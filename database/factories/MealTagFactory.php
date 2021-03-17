<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MealTag;
use App\Models\Tag;
use App\Models\Meal;
use Faker\Generator as Faker;

$factory->define(MealTag::class, function (Faker $faker) {
    return [
        "meal_id" => Meal::all()->random()->id,
        "tag_id" => Tag::all()->random()->id
    ];
});
