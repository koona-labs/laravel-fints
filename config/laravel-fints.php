<?php

return [

    'credentials' => [
        'host' => env('FINTS_HOST',null),
        'port' => env('FINTS_PORT', 443),
        'bank_code' => env('FINTS_BANKCODE',null),
        'username' => env('FINTS_USERNAME',null),
        'pin' => env('FINTS_PIN',null)
    ],
    
    'encrypt_pin' => true,
    
    'logging' => [
        'enabled' => false, 
        'logger' => null // class name of implementation of LoggerInterface, if null laravel's default logger is used 
    ]
    

]; 