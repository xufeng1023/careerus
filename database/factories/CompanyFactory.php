<?php

use Faker\Generator as Faker;

$factory->define(App\CompanyData::class, function (Faker $faker) {
    return [
        'name' => preg_replace('/[^a-zA-Z0-9 ]/', ' ', $faker->company),
        'address' => $faker->address,
        'state' => $faker->stateAbbr,
        'city' => $faker->city,
        'zip' => $faker->postcode,
        'email' => $faker->unique()->safeEmail
    ];
});
