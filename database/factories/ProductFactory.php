<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {

    $images = array();
    for ($i = 1; $i <= rand(1, 4); $i++) {
        $images[] = $faker->$faker->imageUrl(640, 480);
    }

    return [
        'name' => $faker->word,
        'category_id' => factory('App\Category'),
        'description' => $faker->paragraph(3),
        'excerpts' => $faker->sentence(4),
        'cost' => $faker->numberBetween(500, 50000),
        'discount' => $faker->numberBetween(2, 15),
        'quantity' => $faker->numberBetween(5, 20),
        'images' => json_encode($images)
    ];
});
