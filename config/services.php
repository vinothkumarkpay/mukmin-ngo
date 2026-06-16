<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'kiplepay' => [
        'merchant_id' => env('KIPLEPAY_MERCHANT_ID'),
        'secret_key' => env('KIPLEPAY_SECRET_KEY'),
        'merchant_id_guest' => env('KIPLEPAY_MERCHANT_ID_GUEST'),
        'secret_key_guest' => env('KIPLEPAY_SECRET_KEY_GUEST'),
        'merchant_id_user' => env('KIPLEPAY_MERCHANT_ID_USER'),
        'secret_key_user' => env('KIPLEPAY_SECRET_KEY_USER'),
        'url' => env('KIPLEPAY_URL', 'https://sandbox.webcash.com.my'),
    ],

];
