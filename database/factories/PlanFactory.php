<?php

use Faker\Generator as Faker;

$factory->define(App\Plan::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'price' => $faker->randomNumber(3),
        'points' => $faker->randomNumber(5)
    ];
});
