<?php

return [
    'mode' => env('PAYPAL_MODE', 'sandbox'),
    'sandbox' => [
        'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
        'app_id' => env('PAYPAL_SANDBOX_APP_ID'),
    ],
    'live' => [
        'client_id' => env('PAYPAL_SANDBOX_API_USERNAME'),
        'client_secret' => env('PAYPAL_SANDBOX_API_PASSWORD'),
        'app_id' => env('PAYPAL_APP_ID'),
    ],
];
