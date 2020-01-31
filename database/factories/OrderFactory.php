<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'product_id' => factory('App\Product'),
        'address_id' => factory('App\OrderAddress'),
        'user_id' => factory('App\User'),
        'amount' => $faker->numberBetween(200, 50000),
        'quantity' => $faker->numberBetween(1, 12),
        'status' => $faker->numberBetween(0, 5)
    ];
});
