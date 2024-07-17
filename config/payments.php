<?php

return [
    'aciworldwide' => [
        'base_url'    => env('ACIWORLDWIDE_BASE_URL'),
        'api_version' => env('ACIWORLDWIDE_API_VERSION'),
        'user_id'     => env('ACIWORLDWIDE_USER_ID'),
        'password'    => env('ACIWORLDWIDE_PASSWORD'),
        'entity_id'   => env('ACIWORLDWIDE_ENTITY_ID'),
    ],
    'paymentiq' => [
    	'base_url'    => env('PAYMENTIQ_API_URL'),
    	'merchant_id' => env('MERCHANT_ID'),
        'environment' => env('PAYMENTIQ_ENVIRONMENT'),
    ],
];
