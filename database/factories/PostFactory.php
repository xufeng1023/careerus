<?php

use Faker\Generator as Faker;
use PHPUnit\Runner\Filter\Factory;

$factory->define(App\Post::class, function (Faker $faker) {
    if(! App\Catagory::count()) {
        factory(App\Catagory::class)->create();
    }

    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph(25),
        'user_id' => 1,
        'catagory_id' => App\Catagory::all()->random(),
        'company_id' => factory(App\Company::class)->create(),
        'url' => $faker->url,
        'location' => $faker->city.','.$faker->stateAbbr,
        'job_type' => array_random(['Full-time', 'Part-time', 'Internship']),
        'identity' => str_random(50).md5(time()),
        'recommended' => 0
    ];
});
