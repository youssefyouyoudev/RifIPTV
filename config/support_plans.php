<?php

return [
    'plans' => [
        [
            'slug' => 'sup',
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
    ],
];
