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
    'content_security_policy' => env(
        'SECURITY_CONTENT_SECURITY_POLICY',
        "default-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'self'; object-src 'none'; ".
        "img-src 'self' data: blob: https:; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; ".
        "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net; ".
        "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://www.googletagmanager.com https://consent.cookiebot.com; ".
        "connect-src 'self' https://www.google-analytics.com https://region1.google-analytics.com https://region1.analytics.google.com https://consentcdn.cookiebot.com https://consent.cookiebot.com; ".
        "frame-src https://consentcdn.cookiebot.com"
    ),
    'cross_origin_resource_policy' => env('SECURITY_CROSS_ORIGIN_RESOURCE_POLICY', 'same-site'),
];
