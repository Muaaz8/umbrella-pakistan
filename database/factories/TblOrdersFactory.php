<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TblOrders;
use Faker\Generator as Faker;

$factory->define(TblOrders::class, function (Faker $faker) {

    return [
        'order_state' => $faker->word,
        'order_id' => $faker->word,
        'order_sub_id' => $faker->text,
        'customer_id' => $faker->word,
        'total' => $faker->word,
        'shipping_total' => $faker->word,
        'total_tax' => $faker->word,
        'billing' => $faker->text,
        'shipping' => $faker->text,
        'payment' => $faker->text,
        'payment_title' => $faker->word,
        'payment_method' => $faker->word,
        'cart_items' => $faker->text,
        'tax_lines' => $faker->text,
        'shipping_lines' => $faker->text,
        'coupon_lines' => $faker->text,
        'currency' => $faker->word,
        'order_status' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
