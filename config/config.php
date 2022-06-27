<?php

return [
    'products' => [
        'buyer' => [
            'auth' => 'web'
        ],
        'prefix' => '/admin',
        'middleware' => ['auth:admin'],
        'policy' => 'App\\Policies\\ProductPolicy',
        'layout' => 'layouts.app',
        'rewardables' => [
            'Vgplay\Giftcode\Models\Giftcode',
            'Vgplay\IngameItem\Models\IngameItem',
        ],
        'payment_units' => [
            'ticket' => 'Vé sự kiện',
            'point' => 'Điểm sự kiện',
        ]
    ]
];
