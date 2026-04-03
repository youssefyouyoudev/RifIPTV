<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'contact@rifimedia.com'],
            [
                'name' => 'RIF Support Admin',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Additional admins
        User::firstOrCreate(
            ['email' => 'youssef@rifimedia.com'],
            [
                'name' => 'Youssef',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'yassine@rifimedia.com'],
            [
                'name' => 'Yassine',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'jawad@rifimedia.com'],
            [
                'name' => 'Jawad',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'tariq@rifimedia.com'],
            [
                'name' => 'Tariq',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        Plan::query()->whereIn('family_slug', ['max-ott', 't-rex'])->delete();

        $plans = collect([
            [
                'family' => 'Basic / SUP',
                'family_slug' => 'sup',
                'name' => '3 Months',
                'duration_months' => 3,
                'price_mad' => 89.00,
                'features' => ['Basic setup', 'Installation help', 'WhatsApp support', 'Simple follow-up'],
                'is_featured' => false,
                'badge_text' => null,
                'sort_order' => 10,
            ],
            [
                'family' => 'Basic / SUP',
                'family_slug' => 'sup',
                'name' => '6 Months',
                'duration_months' => 6,
                'price_mad' => 149.00,
                'features' => ['Multi-device help', 'Setup review', 'Longer support', 'Better value'],
                'is_featured' => true,
                'badge_text' => 'Popular',
                'sort_order' => 20,
            ],
            [
                'family' => 'Basic / SUP',
                'family_slug' => 'sup',
                'name' => '12 Months',
                'duration_months' => 12,
                'price_mad' => 199.00,
                'features' => ['Annual support', 'Regular follow-up', 'Gentle priority', 'Yearly savings'],
                'is_featured' => false,
                'badge_text' => 'Best Value',
                'sort_order' => 30,
            ],
            [
                'family' => 'Advanced / MAX',
                'family_slug' => 'max',
                'name' => '3 Months',
                'duration_months' => 3,
                'price_mad' => 149.00,
                'features' => ['Advanced setup', 'Multi-device guidance', 'Practical help', 'Higher priority'],
                'is_featured' => false,
                'badge_text' => null,
                'sort_order' => 110,
            ],
            [
                'family' => 'Advanced / MAX',
                'family_slug' => 'max',
                'name' => '6 Months',
                'duration_months' => 6,
                'price_mad' => 249.00,
                'features' => ['Longer optimization', 'Technical diagnosis', 'Extended follow-up', 'More comfort'],
                'is_featured' => true,
                'badge_text' => 'Recommended',
                'sort_order' => 120,
            ],
            [
                'family' => 'Advanced / MAX',
                'family_slug' => 'max',
                'name' => '12 Months',
                'duration_months' => 12,
                'price_mad' => 449.00,
                'features' => ['Annual assistance', 'Stronger stability', 'Ongoing support', 'Better coverage'],
                'is_featured' => false,
                'badge_text' => null,
                'sort_order' => 130,
            ],
            [
                'family' => 'Premium / TREX',
                'family_slug' => 'trex',
                'name' => '3 Months',
                'duration_months' => 3,
                'price_mad' => 249.00,
                'features' => ['Premium support', 'Detailed follow-up', 'Advanced help', 'Priority handling'],
                'is_featured' => false,
                'badge_text' => null,
                'sort_order' => 210,
            ],
            [
                'family' => 'Premium / TREX',
                'family_slug' => 'trex',
                'name' => '6 Months',
                'duration_months' => 6,
                'price_mad' => 349.00,
                'features' => ['Intensive support', 'Longer assistance', 'Technical review', 'More peace of mind'],
                'is_featured' => true,
                'badge_text' => 'Best Seller',
                'sort_order' => 220,
            ],
            [
                'family' => 'Premium / TREX',
                'family_slug' => 'trex',
                'name' => '12 Months',
                'duration_months' => 12,
                'price_mad' => 599.00,
                'features' => ['Full support', 'Year-round follow-up', 'Highest priority', 'Extended coverage'],
                'is_featured' => false,
                'badge_text' => 'Annual',
                'sort_order' => 230,
            ],
        ])->map(function (array $planData) {
            return Plan::updateOrCreate(
                [
                    'family_slug' => $planData['family_slug'],
                    'duration_months' => $planData['duration_months'],
                ],
                $planData
            );
        });


    }
}
