<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Payment Gateway
    |--------------------------------------------------------------------------
    |
    | This option controls the default payment gateway that will be used
    | for processing payments. You may change this value as needed.
    |
    */

    'default' => env('PAYMENT_DEFAULT_GATEWAY', 'stripe'),

    /*
    |--------------------------------------------------------------------------
    | Payment Gateways
    |--------------------------------------------------------------------------
    |
    | Here you may configure the payment gateways for your application.
    | Each gateway should have its own configuration section.
    |
    */

    'gateways' => [
        'stripe' => [
            'secret_key' => env('STRIPE_SECRET_KEY'),
            'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            'currency' => 'rsd',
            'redirect_url' => env('APP_URL') . '/payment/success',
        ],

        'nlb' => [
            'merchant_id' => env('NLB_MERCHANT_ID'),
            'api_key' => env('NLB_API_KEY'),
            'api_secret' => env('NLB_API_SECRET'),
            'redirect_url' => env('APP_URL') . '/payment/nlb/callback',
            'webhook_url' => env('APP_URL') . '/webhooks/nlb',
        ],

        'intesa' => [
            'merchant_id' => env('INTESA_MERCHANT_ID'),
            'api_key' => env('INTESA_API_KEY'),
            'api_secret' => env('INTESA_API_SECRET'),
            'redirect_url' => env('APP_URL') . '/payment/intesa/callback',
            'webhook_url' => env('APP_URL') . '/webhooks/intesa',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the default currency and supported currencies
    | for your application.
    |
    */

    'currency' => [
        'default' => 'RSD',
        'supported' => ['RSD', 'EUR', 'USD'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Processing
    |--------------------------------------------------------------------------
    |
    | Here you may configure various payment processing options.
    |
    */

    'processing' => [
        'timeout' => 30, // seconds
        'retry_attempts' => 3,
        'retry_delay' => 5, // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Invoice Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure invoice generation settings.
    |
    */

    'invoice' => [
        'prefix' => 'INV-',
        'number_padding' => 6,
        'due_days' => 30,
        'pdf_storage' => 'invoices',
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure webhook processing settings.
    |
    */

    'webhooks' => [
        'timeout' => 10, // seconds
        'retry_attempts' => 3,
        'retry_delay' => 2, // seconds
    ],
];
