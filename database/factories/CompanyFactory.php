<?php

use Faker\Generator as Faker;

$factory->define(App\Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'hr' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'website' => 'www.'.$faker->word.'.com'
    ];
});
