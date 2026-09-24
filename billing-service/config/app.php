<?php

return [
    'name' => env('APP_NAME', 'DICH_VU_HOA_DON_THANH_TOAN'),
    'env' => env('APP_ENV', 'local'),
    'debug' => (bool) env('APP_DEBUG', true),
    'url' => env('APP_URL', 'http://127.0.0.1:8004'),
    'timezone' => 'Asia/Ho_Chi_Minh',
    'locale' => 'vi',
    'fallback_locale' => 'en',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
];