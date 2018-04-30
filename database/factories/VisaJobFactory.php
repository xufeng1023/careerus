<?php

use Faker\Generator as Faker;

$factory->define(App\VisaJob::class, function (Faker $faker) {
    return [
        'company_id' => factory(App\Company::class)->create()->id,
        'year' => $faker->year,
        'number_of_visa'=> $faker->randomNumber(),
        'jobs' => $faker->sentence.','.$faker->sentence.','.$faker->sentence.','.$faker->sentence.','.$faker->sentence.','
    ];
});
