<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProductsSubCategory;
use Faker\Generator as Faker;

$factory->define(ProductsSubCategory::class, function (Faker $faker) {

    return [
        'title' => $faker->word,
        'slug' => $faker->word,
        'description' => $faker->text,
        'parent_id' => $faker->randomDigitNotNull,
        'thumbnail' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
