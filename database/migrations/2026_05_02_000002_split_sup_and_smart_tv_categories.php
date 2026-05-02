<?php

use App\Models\Plan;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $plans = [
            [
                'family' => 'Smart TV',
                'family_slug' => 'smart_tv',
                'name' => 'Smart TV - 3 Months',
                'duration_months' => 3,
                'price_mad' => 80.00,
                'features' => ['Step-by-step Smart TV setup help', 'App installation guidance', 'Account and access review', 'WhatsApp clarification support'],
                'is_featured' => false,
                'is_enabled' => true,
                'badge_text' => null,
                'sort_order' => 10,
            ],
            [
                'family' => 'Smart TV',
                'family_slug' => 'smart_tv',
                'name' => 'Smart TV - 6 Months',
                'duration_months' => 6,
                'price_mad' => 140.00,
                'features' => ['Step-by-step Smart TV setup help', 'App installation guidance', 'Account and access review', 'WhatsApp clarification support'],
                'is_featured' => false,
                'is_enabled' => true,
                'badge_text' => null,
                'sort_order' => 20,
            ],
            [
                'family' => 'Smart TV',
                'family_slug' => 'smart_tv',
                'name' => 'Smart TV - 12 Months',
                'duration_months' => 12,
                'price_mad' => 200.00,
                'features' => ['Step-by-step Smart TV setup help', 'App installation guidance', 'Account and access review', 'WhatsApp clarification support'],
                'is_featured' => true,
                'is_enabled' => true,
                'badge_text' => 'Best Value',
                'sort_order' => 30,
            ],
            [
                'family' => 'SUP',
                'family_slug' => 'sup',
                'name' => 'SUP - 3 Months',
                'duration_months' => 3,
                'price_mad' => 89.00,
                'features' => ['Step-by-step device setup help', 'App guidance and clarification', 'Account and access review', 'WhatsApp clarification support'],
                'is_featured' => false,
                'is_enabled' => false,
                'badge_text' => null,
                'sort_order' => 110,
            ],
            [
                'family' => 'SUP',
                'family_slug' => 'sup',
                'name' => 'SUP - 6 Months',
                'duration_months' => 6,
                'price_mad' => 149.00,
                'features' => ['Step-by-step device setup help', 'App guidance and clarification', 'Account and access review', 'WhatsApp clarification support'],
                'is_featured' => false,
                'is_enabled' => false,
                'badge_text' => null,
                'sort_order' => 120,
            ],
            [
                'family' => 'SUP',
                'family_slug' => 'sup',
                'name' => 'SUP - 12 Months',
                'duration_months' => 12,
                'price_mad' => 100.00,
                'features' => ['Step-by-step device setup help', 'App guidance and clarification', 'Account and access review', 'WhatsApp clarification support'],
                'is_featured' => false,
                'is_enabled' => true,
                'badge_text' => null,
                'sort_order' => 130,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                [
                    'family_slug' => $plan['family_slug'],
                    'duration_months' => $plan['duration_months'],
                ],
                $plan
            );
        }
    }

    public function down(): void
    {
        Plan::query()->where('family_slug', 'smart_tv')->delete();

        $plans = [
            [
                'family' => 'Smart TV / SUP',
                'family_slug' => 'sup',
                'name' => 'Smart TV - 3 Months',
                'duration_months' => 3,
                'price_mad' => 80.00,
                'features' => ['Step-by-step Smart TV setup help', 'App installation guidance', 'Account and access review', 'WhatsApp clarification support'],
                'is_featured' => false,
                'is_enabled' => true,
                'badge_text' => null,
                'sort_order' => 10,
            ],
            [
                'family' => 'Smart TV / SUP',
                'family_slug' => 'sup',
                'name' => 'Smart TV - 6 Months',
                'duration_months' => 6,
                'price_mad' => 140.00,
                'features' => ['Step-by-step Smart TV setup help', 'App installation guidance', 'Account and access review', 'WhatsApp clarification support'],
                'is_featured' => false,
                'is_enabled' => true,
                'badge_text' => null,
                'sort_order' => 20,
            ],
            [
                'family' => 'Smart TV / SUP',
                'family_slug' => 'sup',
                'name' => 'Smart TV - 12 Months',
                'duration_months' => 12,
                'price_mad' => 200.00,
                'features' => ['Step-by-step Smart TV setup help', 'App installation guidance', 'Account and access review', 'WhatsApp clarification support'],
                'is_featured' => true,
                'is_enabled' => true,
                'badge_text' => 'Best Value',
                'sort_order' => 30,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                [
                    'family_slug' => $plan['family_slug'],
                    'duration_months' => $plan['duration_months'],
                ],
                $plan
            );
        }
    }
};
