<?php

return [
    'trusted_hosts' => array_values(array_filter(array_map(
        static fn (string $host) => trim($host),
        explode(',', (string) env('TRUSTED_HOSTS', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)))
    ))),
    'hsts_max_age' => (int) env('SECURITY_HSTS_MAX_AGE', 31536000),
    'permissions_policy' => env(
        'SECURITY_PERMISSIONS_POLICY',
        'accelerometer=(), autoplay=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()'
    ),
    'referrer_policy' => env('SECURITY_REFERRER_POLICY', 'strict-origin-when-cross-origin'),
];
