<?php

use Faker\Generator as Faker;

$factory->define(App\Blog::class, function (Faker $faker) {
    return [
        'title' => rtrim($faker->sentence, '.'),
        'content' => $faker->text,
        'user_id' => factory(App\User::class)->create()->id
    ];
});
