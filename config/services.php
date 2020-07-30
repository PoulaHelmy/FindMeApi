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
    'facebook' => [
        'client_id' => env('FACEBOOK_KEY'),
        'client_secret' => env('FACEBOOK_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI')
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CALLBACK_URL'),
    ],
    'linkedin' => [
        'client_id' => env('LINKEDIN_KEY'),
        'client_secret' => env('LINKEDIN_SECRET'),
        'redirect' => env('LINKEDIN_REDIRECT_URI')
    ],
    'twitter' => [
        'client_id' => env('twitter_client_id'),
        'client_secret' => env('twitter_client_secret'),
        'redirect' => env('twitter_callback'),
    ],
    'pusher' => [
        'beams_instance_id' => '437c4ed3-3e16-4f16-98a1-ccd2114c3df4',
        'beams_secret_key' => 'CD43A66178BF7C13498B00CE506C115FA33455D535858399388A09F68092B895',
    ],
];

//    'facebook' => [
//        'client_id' => env('FACEBOOK_APP_ID'),
//        'client_secret' => env('GITHUB_APP_SECRET'),
//        'redirect' => env('FACEBOOK_REDIRECT_URI'),
//    ],
//app id  1052447848464710
//secret edd63512cd511e7929b7f8e81316d8d5
//https://localhost:8000/login/facebook/callback
