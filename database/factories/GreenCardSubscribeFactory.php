<?php

use Faker\Generator as Faker;

$factory->define(App\GreenCardSubscribe::class, function (Faker $faker) {
    return [
        'url' => $faker->url,
        'email' => $faker->unique()->safeEmail
    ];
});
