<?php

use Faker\Generator as Faker;

$factory->define(App\Profile::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'phone' => '(888)888-8888',
        'resume' => 'resume.pdf'
    ];
});
