<?php

return [
    'default_title' => env('SEO_DEFAULT_TITLE', 'RIF Media | Device Setup, App Guidance & Technical Support'),
    'default_description' => env(
        'SEO_DEFAULT_DESCRIPTION',
        'RIF Media helps clients with device setup, app guidance, troubleshooting, payment follow-up, and practical technical support for connected devices.'
    ),
    'default_og_image' => env('SEO_DEFAULT_OG_IMAGE', '/images/hero-light.png'),
    'business_type' => env('SEO_BUSINESS_TYPE', 'ProfessionalService'),
    'contact_email' => env('SEO_CONTACT_EMAIL', 'contact@rifimedia.com'),
    'contact_phone' => env('SEO_CONTACT_PHONE', ''),
    'area_served' => env('SEO_AREA_SERVED', 'MA'),
    'service_types' => array_values(array_filter(array_map(
        static fn (string $service) => trim($service),
        explode(',', (string) env('SEO_SERVICE_TYPES', 'Device Setup Services,App Guidance,Technical Support,Troubleshooting,Payment Follow-up'))
    ))),
    'social_profiles' => array_values(array_filter(array_map(
        static fn ($url) => trim((string) $url),
        [
            env('SEO_FACEBOOK_URL'),
            env('SEO_INSTAGRAM_URL'),
            env('SEO_X_URL'),
            env('SEO_LINKEDIN_URL'),
            env('SEO_YOUTUBE_URL'),
        ]
    ))),
];
