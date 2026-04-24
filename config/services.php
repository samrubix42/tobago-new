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

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI', '/auth/google/callback'),
    ],

    'phonepe' => [
        'client_id' => env('PHONEPE_CLIENT_ID', env('PHONEPE_MERCHANT_ID', env('CLIENT_ID'))),
        'client_secret' => env('PHONEPE_CLIENT_SECRET', env('PHONEPE_SALT_KEY', env('CLIENT_KEY'))),
        'client_version' => env('PHONEPE_CLIENT_VERSION', env('PHONEPE_SALT_INDEX', env('CLIENT_VERSION', '1'))),
        'auth_base_url' => env('PHONEPE_AUTH_BASE_URL'),
        'base_url' => env('PHONEPE_BASE_URL'),
        'auth_endpoint' => env('PHONEPE_AUTH_ENDPOINT', '/v1/oauth/token'),
        'checkout_endpoint' => env('PHONEPE_CHECKOUT_ENDPOINT', '/checkout/v2/pay'),
        'order_status_endpoint' => env('PHONEPE_ORDER_STATUS_ENDPOINT', '/checkout/v2/order/{merchantOrderId}/status'),
        'expire_after' => (int) env('PHONEPE_EXPIRE_AFTER', 1200),
        'webhook_username' => env('PHONEPE_WEBHOOK_USERNAME'),
        'webhook_password' => env('PHONEPE_WEBHOOK_PASSWORD'),
        'test_mode' => (bool) env('PHONEPE_TEST_MODE', true),

        // Legacy values (kept for backward compatibility with old envs).
        'merchant_id' => env('PHONEPE_MERCHANT_ID', env('CLIENT_ID')),
        'salt_key' => env('PHONEPE_SALT_KEY', env('CLIENT_KEY')),
        'salt_index' => env('PHONEPE_SALT_INDEX', env('CLIENT_VERSION', '1')),
        'pay_endpoint' => env('PHONEPE_PAY_ENDPOINT', '/pg/v1/pay'),
    ],

];
