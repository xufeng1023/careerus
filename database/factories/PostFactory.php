<?php

use Faker\Generator as Faker;
use PHPUnit\Runner\Filter\Factory;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph(100),
        'user_id' => 1,
        'catagory_id' => factory(App\Catagory::class)->create(),
        'company_id' => factory(App\Company::class)->create(),
        'location' => $faker->city.','.$faker->stateAbbr,
        'job_type' => array_random(['Full-time', 'Part-time', 'Internship']),
        'identity' => str_random(50).md5(time())
    ];
});
