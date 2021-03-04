<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CollectionPoint;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Faker\Factory;

$factory->define(CollectionPoint::class, function (Faker $faker) {
    $faker = Factory::create('en_GB');

    return [
        'name'               => $faker->company,
        'address_line_1'     => $faker->streetAddress,
        'address_line_2'     => $faker->streetName,
        'city'               => $faker->city,
        'county'             => $faker->county,
        "start_pick_up_time"       => Carbon::now(),
        "end_pick_up_time"       => Carbon::now()->addHours(11),
        "cut_off_point"       => Carbon::now()->addHours(14),
        'post_code'          => $faker->postcode,
        'max_daily_capacity' => rand(50, 100),
    ];
});
