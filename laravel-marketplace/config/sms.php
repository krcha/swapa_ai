<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default SMS Provider
    |--------------------------------------------------------------------------
    |
    | This option controls the default SMS provider that will be used
    | for sending verification codes. You may change this value as needed.
    |
    */

    'default' => env('SMS_DEFAULT_PROVIDER', 'twilio'),

    /*
    |--------------------------------------------------------------------------
    | SMS Providers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the SMS providers for your application.
    | Each provider should have its own configuration section.
    |
    */

    'providers' => [
        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'from_number' => env('TWILIO_FROM_NUMBER'),
            'webhook_url' => env('APP_URL') . '/webhooks/twilio',
        ],

        'vip' => [
            'api_url' => env('VIP_SMS_API_URL', 'https://api.vip.rs/sms/send'),
            'username' => env('VIP_SMS_USERNAME'),
            'password' => env('VIP_SMS_PASSWORD'),
            'from_name' => env('VIP_SMS_FROM_NAME', 'Laravel Marketplace'),
        ],

        'telenor' => [
            'api_url' => env('TELENOR_SMS_API_URL', 'https://api.telenor.rs/sms/send'),
            'api_key' => env('TELENOR_SMS_API_KEY'),
            'sender_name' => env('TELENOR_SMS_SENDER_NAME', 'Laravel Marketplace'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Verification Code Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure various verification code settings.
    |
    */

    'verification' => [
        'code_length' => 6,
        'expires_minutes' => 5,
        'max_attempts' => 5,
        'rate_limit_per_hour' => 3,
        'rate_limit_per_ip' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure security settings for SMS verification.
    |
    */

    'security' => [
        'fraud_protection' => true,
        'max_codes_per_ip_per_hour' => 10,
        'max_codes_per_phone_per_hour' => 3,
        'block_duration_minutes' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Message Templates
    |--------------------------------------------------------------------------
    |
    | Here you may configure the SMS message templates.
    |
    */

    'templates' => [
        'verification' => 'Your Laravel Marketplace verification code is: {code}. Valid for {minutes} minutes.',
        'welcome' => 'Welcome to Laravel Marketplace! Your account has been verified.',
        'reminder' => 'Your verification code expires in {minutes} minutes. Code: {code}',
    ],

    /*
    |--------------------------------------------------------------------------
    | Country Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure country-specific settings.
    |
    */

    'country' => [
        'default' => 'RS', // Serbia
        'phone_formats' => [
            'RS' => [
                'pattern' => '/^(\+381|381|0)[0-9]{8,9}$/',
                'international' => '+381',
                'national_prefix' => '0',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure logging settings for SMS operations.
    |
    */

    'logging' => [
        'enabled' => env('SMS_LOGGING_ENABLED', true),
        'level' => env('SMS_LOGGING_LEVEL', 'info'),
        'log_success' => true,
        'log_failures' => true,
        'log_attempts' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Testing Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure testing settings for SMS verification.
    |
    */

    'testing' => [
        'enabled' => env('SMS_TESTING_ENABLED', false),
        'test_codes' => [
            '123456', // Always valid for testing
            '000000', // Always invalid for testing
        ],
        'mock_provider' => env('SMS_MOCK_PROVIDER', false),
    ],
];
