<?php

return [
    'default_title' => env('SEO_DEFAULT_TITLE', 'Rifi Media | Smart TV Setup, Device Configuration & Technical Support in Morocco'),
    'default_description' => env(
        'SEO_DEFAULT_DESCRIPTION',
        'Rifi Media helps clients across Morocco with Smart TV setup, device configuration, app guidance, troubleshooting, and practical technical support for connected devices.'
    ),
    'default_robots' => env('SEO_DEFAULT_ROBOTS', 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1'),
    'default_og_image' => env('SEO_DEFAULT_OG_IMAGE', '/images/hero-light.png'),
    'twitter_handle' => env('SEO_TWITTER_HANDLE', '@rifimedia'),
    'business_type' => env('SEO_BUSINESS_TYPE', 'ProfessionalService'),
    'contact_email' => env('SEO_CONTACT_EMAIL', 'contact@rifimedia.com'),
    'contact_phone' => env('SEO_CONTACT_PHONE', ''),
    'whatsapp_url' => env('SEO_WHATSAPP_URL', 'https://wa.me/212663323824'),
    'support_hours' => env('SEO_SUPPORT_HOURS', 'Monday to Saturday, 09:00 to 22:00'),
    'area_served' => env('SEO_AREA_SERVED', 'MA'),
    'service_region' => env('SEO_SERVICE_REGION', 'Morocco'),
    'service_types' => array_values(array_filter(array_map(
        static fn (string $service) => trim($service),
        explode(',', (string) env('SEO_SERVICE_TYPES', 'Smart TV Setup,Device Configuration,App Guidance,Technical Support,Troubleshooting,Client Follow-up'))
    ))),
    'social_profiles' => array_values(array_filter(array_map(
        static fn ($url) => trim((string) $url),
        [
            env('SEO_FACEBOOK_URL', 'https://www.facebook.com/rifimedia'),
            env('SEO_INSTAGRAM_URL', 'https://www.instagram.com/rifimedia'),
            env('SEO_X_URL', 'https://x.com/rifimedia'),
            env('SEO_LINKEDIN_URL', 'https://www.linkedin.com/company/rifimedia'),
            env('SEO_YOUTUBE_URL', 'https://www.youtube.com/@rifimedia'),
            env('SEO_TIKTOK_URL', 'https://www.tiktok.com/@rifimedia'),
            env('SEO_TELEGRAM_URL', 'https://t.me/+RCWEzxXYbjIzMmI0'),
            env('SEO_WHATSAPP_URL', 'https://wa.me/212663323824'),
        ]
    ))),
    'social_links' => [
        [
            'label' => 'Facebook',
            'icon' => 'facebook',
            'url' => env('SEO_FACEBOOK_URL', 'https://www.facebook.com/rifimedia'),
        ],
        [
            'label' => 'Instagram',
            'icon' => 'instagram',
            'url' => env('SEO_INSTAGRAM_URL', 'https://www.instagram.com/rifimedia'),
        ],
        [
            'label' => 'X',
            'icon' => 'twitter',
            'url' => env('SEO_X_URL', 'https://x.com/rifimedia'),
        ],
        [
            'label' => 'LinkedIn',
            'icon' => 'linkedin',
            'url' => env('SEO_LINKEDIN_URL', 'https://www.linkedin.com/company/rifimedia'),
        ],
        [
            'label' => 'YouTube',
            'icon' => 'youtube',
            'url' => env('SEO_YOUTUBE_URL', 'https://www.youtube.com/@rifimedia'),
        ],
        [
            'label' => 'TikTok',
            'icon' => 'music-4',
            'url' => env('SEO_TIKTOK_URL', 'https://www.tiktok.com/@rifimedia'),
        ],
        [
            'label' => 'Telegram',
            'icon' => 'send',
            'url' => env('SEO_TELEGRAM_URL', 'https://t.me/+RCWEzxXYbjIzMmI0'),
        ],
        [
            'label' => 'WhatsApp',
            'icon' => 'message-circle-more',
            'url' => env('SEO_WHATSAPP_URL', 'https://wa.me/212663323824'),
        ],
    ],
];
