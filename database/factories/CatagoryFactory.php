<?php

use Faker\Generator as Faker;

$factory->define(App\Catagory::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'rfe' => $faker->randomNumber(2)
    ];
});
