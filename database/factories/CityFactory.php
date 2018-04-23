<?php

use Faker\Generator as Faker;

$factory->define(App\City::class, function (Faker $faker) {
    return [
        'state_id' => 1,
        'name' => $faker->word,
        'county' => $faker->word
    ];
});
