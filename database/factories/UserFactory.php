<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => preg_replace('/[^a-zA-Z]+/', ' ', $faker->name),
        'email' => $faker->unique()->safeEmail,
        'phone' => substr(trim(preg_replace('/[^0-9]+/', '', $faker->unique()->tollFreePhoneNumber), 0).'89797', 0, 10),
        'resume' => 'resumes/mUmNsI6ZkZX1'.str_random(10).'Ka3NPfj9nFxSz3LFLp.pdf',
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'role' => 'student',
        'confirmed' => false,
    ];
});
