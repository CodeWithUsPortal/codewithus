<?php

return [
    'sms' => [
        'api_key' => env('SMS_API_KEY', '23480ecaa2d37d33905eae528df2d19e86c898c4653ec9e73b3d01ba96182f74'),
        'from' => env('SMS_FROM', '+14089097717'),
        'email' => env('SMS_EMAIL', 'team@codewithus.com'),
        'url' => env('SMS_URL', 'https://api.toky.co/v1/sms/send'),
        'method' => env('SMS_METHOD', 'POST'),
    ],

];