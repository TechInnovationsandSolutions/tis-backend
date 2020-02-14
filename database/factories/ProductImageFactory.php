<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProductImage;
use Faker\Generator as Faker;

$factory->define(ProductImage::class, function (Faker $faker) {
    return [
        'product_id' => $faker->numberBetween(1, 20),
        'title' => $faker->word,
        'url' => $faker->imageUrl()
    ];
});
