<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TblFaq;
use Faker\Generator as Faker;

$factory->define(TblFaq::class, function (Faker $faker) {

    return [
        'question' => $faker->text,
        'answer' => $faker->text,
        'status' => $faker->randomDigitNotNull,
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
