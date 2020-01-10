<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cart;
use Faker\Generator as Faker;

$factory->define(Cart::class, function (Faker $faker) {
    return [
        'product_id' => factory('App\Product'),
        'user_id' => factory('App\User'),
        'amount' => $faker->numberBetween(200, 50000),
        'quantity' => $faker->numberBetween(1, 12),
    ];
});
