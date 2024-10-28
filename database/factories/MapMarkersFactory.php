<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MapMarkers;
use Faker\Generator as Faker;

$factory->define(MapMarkers::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'state' => $faker->text,
        'address' => $faker->word,
        'city' => $faker->text,
        'zip_code' => $faker->word,
        'marker_type' => $faker->word,
        'marker_icon' => $faker->word,
        'lat' => $faker->word,
        'long' => $faker->word,
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
