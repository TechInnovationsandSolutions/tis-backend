<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {



    return [
        'name' => $faker->word,
        'category_id' => factory('App\Category'),
        'description' => $faker->paragraph(3),
        'excerpts' => $faker->sentence(4),
        'cost' => $faker->numberBetween(500, 50000),
        'discount' => $faker->numberBetween(2, 15),
        'quantity' => $faker->numberBetween(5, 20),
    ];
});
