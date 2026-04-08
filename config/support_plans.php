<?php

return [
    'plans' => [
        [
            'slug' => 'sup',
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
                ['months' => 12, 'price' => 199, 'featured' => true],
            ],
        ],
    ],
];
