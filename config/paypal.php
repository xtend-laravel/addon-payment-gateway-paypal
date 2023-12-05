<?php

return [
    'mode' => env('PAYPAL_MODE', 'sandbox'),
    'policy' => env('PAYPAL_POLICY', 'automatic'),
    'sandbox' => [
        'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
        'app_id' => env('PAYPAL_SANDBOX_APP_ID'),
    ],
    'live' => [
        'client_id' => env('PAYPAL_LIVE_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
        'app_id' => env('PAYPAL_APP_ID'),

    ],
];
