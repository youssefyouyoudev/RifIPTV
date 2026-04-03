<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // =========================
        // ADMINS
        // =========================
        $admins = [
            ['name' => 'RIF Support Admin', 'email' => 'contact@rifimedia.com'],
            ['name' => 'Youssef', 'email' => 'youssef@rifimedia.com'],
            ['name' => 'Yassine', 'email' => 'yassine@rifimedia.com'],
            ['name' => 'Jawad', 'email' => 'jawad@rifimedia.com'],
            ['name' => 'Tariq', 'email' => 'tariq@rifimedia.com'],
        ];

        foreach ($admins as $adminData) {
            User::updateOrCreate(
                ['email' => $adminData['email']],
                [
                    'name' => $adminData['name'],
                    'role' => 'admin',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }

        // =========================
        // RESET PLANS ONLY
        // =========================
        Plan::query()->delete();

        // =========================
        // PLANS (REAL STRUCTURE)
        // =========================
        $plans = [
            // BASIC
            [
                'family' => 'Basic',
                'family_slug' => 'basic',
                'name' => '3 Months',
                'duration_months' => 3,
                'price_mad' => 89,
                'features' => ['Standard access', 'Basic support'],
                'is_featured' => false,
                'badge_text' => null,
                'sort_order' => 10,
            ],
            [
                'family' => 'Basic',
                'family_slug' => 'basic',
                'name' => '6 Months',
                'duration_months' => 6,
                'price_mad' => 149,
                'features' => ['Better stability', 'WhatsApp support'],
                'is_featured' => true,
                'badge_text' => 'Popular',
                'sort_order' => 20,
            ],
            [
                'family' => 'Basic',
                'family_slug' => 'basic',
                'name' => '12 Months',
                'duration_months' => 12,
                'price_mad' => 199,
                'features' => ['Best savings', 'Long-term access'],
                'is_featured' => false,
                'badge_text' => 'Best Value',
                'sort_order' => 30,
            ],

            // ADVANCED
            [
                'family' => 'Advanced',
                'family_slug' => 'advanced',
                'name' => '3 Months',
                'duration_months' => 3,
                'price_mad' => 149,
                'features' => ['HD/Full HD', 'Faster support'],
                'is_featured' => false,
                'badge_text' => null,
                'sort_order' => 110,
            ],
            [
                'family' => 'Advanced',
                'family_slug' => 'advanced',
                'name' => '6 Months',
                'duration_months' => 6,
                'price_mad' => 259,
                'features' => ['Multi-device help', 'Priority response'],
                'is_featured' => true,
                'badge_text' => 'Recommended',
                'sort_order' => 120,
            ],
            [
                'family' => 'Advanced',
                'family_slug' => 'advanced',
                'name' => '12 Months',
                'duration_months' => 12,
                'price_mad' => 449,
                'features' => ['Stable streaming', 'Extended support'],
                'is_featured' => false,
                'badge_text' => null,
                'sort_order' => 130,
            ],

            // PREMIUM
            [
                'family' => 'Premium',
                'family_slug' => 'premium',
                'name' => '3 Months',
                'duration_months' => 3,
                'price_mad' => 249,
                'features' => ['Premium servers', 'High priority'],
                'is_featured' => false,
                'badge_text' => null,
                'sort_order' => 210,
            ],
            [
                'family' => 'Premium',
                'family_slug' => 'premium',
                'name' => '6 Months',
                'duration_months' => 6,
                'price_mad' => 399,
                'features' => ['Ultra stability', 'VIP support'],
                'is_featured' => true,
                'badge_text' => 'Best Seller',
                'sort_order' => 220,
            ],
            [
                'family' => 'Premium',
                'family_slug' => 'premium',
                'name' => '12 Months',
                'duration_months' => 12,
                'price_mad' => 599,
                'features' => ['Full premium', 'Maximum priority'],
                'is_featured' => false,
                'badge_text' => 'Annual',
                'sort_order' => 230,
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
}
