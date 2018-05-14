<?php

use Faker\Generator as Faker;

$factory->define(App\Company::class, function (Faker $faker) {
    return [
        'name' => preg_replace('/[^a-zA-Z0-9 ]/', ' ', $faker->company),
        'hr' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'website' => 'www.'.$faker->word.'.com',
        'scale' => $faker->numberBetween(0, 20000)
    ];
});
