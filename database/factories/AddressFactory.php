<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\OrderAddress;
use Faker\Generator as Faker;

$factory->define(OrderAddress::class, function (Faker $faker) {
    return [
        'user_id' => factory('App\User'),
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'state_id' => $faker->numberBetween(1, 36),
        'lga_id' => $faker->numberBetween(1, 774),
        'city' => $faker->city,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
    ];
});
