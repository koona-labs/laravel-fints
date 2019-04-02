<?php

return [

    'credentials' => [
        'host' => env('HBCI_HOST'),
        'port' => env('HBCI_PORT', 443),
        'bank_code' => env('HBCI_BANKCODE'),
        'username' => env('HBCI_USERNAME'),
        'pin' => env('HBCI_PIN')
    ],
    
    'encrypt_pin' => true,
    
    'logging' => [
        'enabled' => false, 
        'logger' => null // class name of implementation of LoggerInterface, if null laravel's default logger is used 
    ]
    

]; 