<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Commercial Launch Offer Configuration
    |--------------------------------------------------------------------------
    |
    | Single source of truth for the promotional free period, billing rules,
    | and agency attribution shown on the public marketing & pricing pages.
    |
    */
    'launch_offer_ends_at' => env('LAUNCH_OFFER_ENDS_AT', '2026-12-31'),

    'payment_card_required' => false,

    'auto_billing_after_offer' => false,

    'agency' => [
        'name' => 'Three Digit Software',
        'url' => 'https://threedigitsoftware.com/',
        'support_email' => env('SUPPORT_EMAIL', config('mail.from.address')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Funnel Analytics (Provider-Neutral)
    |--------------------------------------------------------------------------
    |
    | Supported drivers: "log" (default), "webhook" (posts JSON to endpoint).
    |
    */
    'analytics' => [
        'enabled' => env('FUNNEL_ANALYTICS_ENABLED', true),
        'driver' => env('FUNNEL_ANALYTICS_DRIVER', 'log'),
        'webhook_url' => env('FUNNEL_ANALYTICS_WEBHOOK_URL'),
    ],
];
