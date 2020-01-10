<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Rating;
use Faker\Generator as Faker;

$factory->define(Rating::class, function (Faker $faker) {
    return [
        'product_id' => factory('App\Product'),
        'name' => $faker->name(),
        'rate' => $faker->numberBetween(1, 5),
        'comment' => $faker->paragraph,
    ];
});
