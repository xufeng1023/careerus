<?php

use Faker\Generator as Faker;

$factory->define(App\Catagory::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'rfe' => $faker->randomNumber(2)
    ];
});
