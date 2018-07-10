<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'poppler' => [
        'html_path' => env('POPPLER_HTML_PATH'),
        'info_path' => env('POPPLER_INFO_PATH')
    ],

    'github' => [
        'client_id' => '2004e13032aabcb8fc37',//env('GITHUB_CLIENT_ID'),         // Your GitHub Client ID
        'client_secret' => 'a379efc500ec4f1822d27c2a5b9b4973b5ca41d3',//env('GITHUB_CLIENT_SECRET'), // Your GitHub Client Secret
        'redirect' => 'https://careerus.com/login/github/callback',
    ],

    'google' => [
        'client_id' => '176109523039-mempaa3uqo1oe1rm0vk1a6ddne63ue1k.apps.googleusercontent.com',
        'client_secret' => '',
        'redirect' => 'https://careerus.com/login/google/callback',
    ]

];
