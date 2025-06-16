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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'stripe' => [
        'public_key' => env('STRIPE_PUBLIC_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'twocheckout' => [
        'merchant_id' => env('TWOCHECKOUT_MERCHANT_ID'),
        'secret_key' => env('TWOCHECKOUT_SECRET_KEY'),
        'sandbox' => env('TWOCHECKOUT_SANDBOX', true),
    ],

    'marketplace' => [
        'commission_rate' => env('MARKETPLACE_COMMISSION_RATE', 10.00),
        'subscription_enabled' => env('MARKETPLACE_SUBSCRIPTION_ENABLED', true),
    ],

    'ups' => [
        'access_key' => env('UPS_ACCESS_KEY'),
        'user_id' => env('UPS_USER_ID'),
        'password' => env('UPS_PASSWORD'),
        'shipper_number' => env('UPS_SHIPPER_NUMBER'),
        'api_url' => env('UPS_API_URL', 'https://wwwcie.ups.com/rest'),
    ],

    'fedex' => [
        'account_number' => env('FEDEX_ACCOUNT_NUMBER'),
        'meter_number' => env('FEDEX_METER_NUMBER'),
        'key' => env('FEDEX_KEY'),
        'password' => env('FEDEX_PASSWORD'),
        'api_url' => env('FEDEX_API_URL', 'https://wsbeta.fedex.com'),
    ],

];
