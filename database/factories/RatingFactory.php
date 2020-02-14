<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Rating;
use Faker\Generator as Faker;

$factory->define(Rating::class, function (Faker $faker) {
    return [
        'product_id' => $faker->numberBetween(1, 10),
        'name' => $faker->name(),
        'rate' => $faker->numberBetween(1, 5),
        'comment' => $faker->paragraph,
    ];
});
