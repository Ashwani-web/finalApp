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
    
    'google' => [
        'client_id' => '144290534963-sdei7jfdq5ko5cv270nhk8gd63cbc3sb.apps.googleusercontent.com',
        'client_secret' => '_wtKKQT5ws4ipwi8ZM6baxRV',
        'redirect' => 'http://192.168.0.25:8000/callbackgoogle'],
    
    'facebook' => [
         'client_id' => '380582502718899',
         'client_secret' => '7b4d77bc40d9397718206596d699fc0c',
         'redirect' => 'http://192.168.0.25:8000/callbackfacebook'],
    
    'linkedin' => [
        'client_id' => '81r9eksvtb0ja3',
        'client_secret' => 'dtyfYaqszRImemmN',
        'redirect' => 'http://192.168.0.25:8000/callbacklinkedin'
    ],

];
