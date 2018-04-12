<?php

use Faker\Generator as Faker;
use PHPUnit\Runner\Filter\Factory;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->text,
        'user_id' => 1,
        'catagory_id' => factory(App\Catagory::class)->create(),
        'company_id' => factory(App\Company::class)->create(),
        'location' => 'New York, NY'
    ];
});
