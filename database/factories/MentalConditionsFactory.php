<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MentalConditions;
use Faker\Generator as Faker;

$factory->define(MentalConditions::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'content' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
