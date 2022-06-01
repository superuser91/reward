<?php

return [
    'products' => [
        'buyer' => [
            'auth' => 'web'
        ],
        'prefix' => '/admin',
        'middleware' => ['auth:admin'],
        'policy' => 'App\\Policies\\ProductPolicy',
    ]
];
