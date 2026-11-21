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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    'wallet' => [
        'apple_pass_type_id' => env('APPLE_PASS_TYPE_ID', 'pass.com.threedigitsoftware.loyalty'),
        'apple_team_id' => env('APPLE_TEAM_ID', 'THREEDIGIT'),
        'apple_cert_path' => env('APPLE_PASS_CERT_PATH') ?: storage_path('app/wallet/apple-pass.p12'),
        'apple_cert_password' => env('APPLE_PASS_CERT_PASSWORD', ''),
        'apple_cert_base64' => env('APPLE_PASS_CERT_BASE64', ''),
        'apple_wwdr_path' => env('APPLE_WWDR_CERT_PATH') ?: storage_path('app/wallet/AppleWWDRCAG4.pem'),
        'apple_wwdr_base64' => env('APPLE_WWDR_CERT_BASE64', ''),

        'google_issuer_id' => env('GOOGLE_WALLET_ISSUER_ID', ''),
        'google_class_suffix' => env('GOOGLE_WALLET_CLASS_SUFFIX', 'threedigits_loyalty_pass'),
        'google_service_account_json' => env('GOOGLE_WALLET_SERVICE_ACCOUNT_JSON') ?: storage_path('app/wallet/google-service-account.json'),
        'google_service_account_base64' => env('GOOGLE_WALLET_SERVICE_ACCOUNT_BASE64', ''),
    ],

];
