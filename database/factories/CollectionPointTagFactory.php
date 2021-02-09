<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CollectionPointTag;
use App\Models\CollectionPoint;
use App\Models\Tag;
use App\Models\Meal;
use Faker\Generator as Faker;

$factory->define(CollectionPointTag::class, function (Faker $faker) {
    return [
        "collection_point_id" => CollectionPoint::all()->random()->id,
        "tag_id" => factory(Tag::class)->create()->id,
    ];
});
