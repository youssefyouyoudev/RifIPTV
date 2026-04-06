<?php

return [
    'plans' => [
        [
            'slug' => 'basic',
            'label' => 'Basic',
            'name' => 'Basic Support',
            'summary' => 'For simpler setup requests, guided installation steps, and calmer first-time follow-up.',
            'highlight' => null,
            'scope' => 'Core setup',
            'devices' => '1 device',
            'response' => 'Standard',
            'follow_up' => 'Essential follow-up',
            'features' => [
                'Step-by-step device setup help',
                'App installation guidance',
                'Account onboarding review',
                'WhatsApp clarification support',
            ],
            'prices' => [
                ['months' => 3, 'price' => 89, 'featured' => false],
                ['months' => 6, 'price' => 149, 'featured' => true],
                ['months' => 12, 'price' => 199, 'featured' => false],
            ],
        ],
        [
            'slug' => 'advanced',
            'label' => 'Advanced',
            'name' => 'Advanced Support',
            'summary' => 'For broader setup scope, stronger response priority, and longer follow-up across devices.',
            'highlight' => 'Most chosen',
            'scope' => 'Expanded setup',
            'devices' => 'Up to 2 devices',
            'response' => 'Priority',
            'follow_up' => 'Extended follow-up',
            'features' => [
                'Broader setup guidance',
                'App organization assistance',
                'Priority technical checks',
                'Longer follow-up window',
            ],
            'prices' => [
                ['months' => 3, 'price' => 149, 'featured' => false],
                ['months' => 6, 'price' => 249, 'featured' => true],
                ['months' => 12, 'price' => 449, 'featured' => false],
            ],
        ],
        [
            'slug' => 'premium',
            'label' => 'Premium',
            'name' => 'Premium Support',
            'summary' => 'For deeper onboarding, higher priority handling, and stronger long-term technical follow-up.',
            'highlight' => 'Best value',
            'scope' => 'Full support depth',
            'devices' => 'Multi-device help',
            'response' => 'Higher priority',
            'follow_up' => 'Long-term follow-up',
            'features' => [
                'Deeper onboarding assistance',
                'Higher-priority response handling',
                'Advanced troubleshooting review',
                'Longer support continuity',
            ],
            'prices' => [
                ['months' => 3, 'price' => 249, 'featured' => false],
                ['months' => 6, 'price' => 349, 'featured' => true],
                ['months' => 12, 'price' => 599, 'featured' => false],
            ],
        ],
    ],
];
