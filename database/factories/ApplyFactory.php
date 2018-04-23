<?php

use Faker\Generator as Faker;

$factory->define(App\Apply::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\User::class)->create(),
        'post_id'=> factory(App\Post::class)->create()
    ];
});
