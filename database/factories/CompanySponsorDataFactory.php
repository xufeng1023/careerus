<?php

use Faker\Generator as Faker;

$factory->define(App\CompanySponsorData::class, function (Faker $faker) {
    return [
        'company_id' => 1,
        'job' => $faker->sentence,
        'sponsor_number' => $faker->randomNumber(),
        'year' => $faker->year
    ];
});
