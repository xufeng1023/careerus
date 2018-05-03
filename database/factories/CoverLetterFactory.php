<?php

use Faker\Generator as Faker;

$factory->define(App\CoverLetter::class, function (Faker $faker) {
    return [
        'content' => $faker->text
    ];
});
