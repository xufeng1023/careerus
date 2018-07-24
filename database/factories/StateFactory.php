<?php

use Faker\Generator as Faker;

$factory->define(App\State::class, function (Faker $faker) {
    return [
        'STATE_CODE' => 'NY',
        'STATE_NAME' => 'New York',
        'simplified_name' => ''
    ];
});
