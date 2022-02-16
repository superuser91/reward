<?php

return [
    'prefix' => '/admin',
    'middleware' => ['auth:admin'],
    'policy' => 'App\\Policies\\ProductPolicy'
];
