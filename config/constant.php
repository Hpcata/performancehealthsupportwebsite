<?php

return [
    'ENVIRONMENT' => env('ENVIRONMENT', 'production'),

    'DEFAULT_CURRENCY' => '$',

    'DEFAULT_IMAGE_PATH' => '/image/default.png',

    'SIZE' => 'size',

    'IMG_EXTENTIONS' => ['jpg', 'jpeg', 'jfif', 'pjpeg', 'pjp', 'png', 'webp', 'svg'],
    'VID_EXTENTIONS' => ['MP4', 'MOV', 'WMV', 'AVI', 'AVCHD', 'FLV', 'F4V', 'SWF', 'MKV', 'WEBM'],

    'IMAGE_PATH' => '/image',
    'VIDEO_PATH' => '/video',
    'FILE_PATH' => '/file',

    'STRIPE_URL' => env('STRIPE_URL'),
    'STRIPE_SECRET' => env('STRIPE_SECRET'),

    'CURRENCY' => 'aud',

    'TIMEZONE' => 'Australia/Brisbane',
];
