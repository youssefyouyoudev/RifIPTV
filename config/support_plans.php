<?php

return [
    'plans' => [
        [
            'slug' => 'smart_tv',
            'label' => 'Smart TV',
            'name' => 'Smart TV Packs',
            'summary' => 'Clear Smart TV packs for guided setup, app help, and follow-up support.',
            'highlight' => null,
            'scope' => 'Smart TV setup',
            'devices' => '1 screen',
            'response' => 'Standard',
            'follow_up' => 'WhatsApp follow-up',
            'features' => [
                'Step-by-step Smart TV setup help',
                'App installation guidance',
                'Account and access review',
                'WhatsApp clarification support',
            ],
            'prices' => [
                ['months' => 3, 'price' => 80, 'featured' => false],
                ['months' => 6, 'price' => 140, 'featured' => false],
                ['months' => 12, 'price' => 200, 'featured' => true, 'badge' => 'Best Value'],
            ],
        ],
        [
            'slug' => 'sup',
            'label' => 'Support',
            'name' => 'SUP Pack',
            'summary' => 'The SUP category stays available as one clear 12-month support pack.',
            'highlight' => null,
            'scope' => 'Support pack',
            'devices' => '1 screen',
            'response' => 'Standard',
            'follow_up' => 'WhatsApp follow-up',
            'features' => [
                'Step-by-step device setup help',
                'App guidance and clarification',
                'Account and access review',
                'WhatsApp clarification support',
            ],
            'prices' => [
                ['months' => 12, 'price' => 100, 'featured' => false],
            ],
        ],
    ],
];
