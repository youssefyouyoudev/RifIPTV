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
            ['email' => 'admin@rifiptv.test'],
            [
                'name' => 'RIF Support Admin',
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

        $supSixMonthPlan = $plans->first(fn (Plan $plan) => $plan->family_slug === 'sup' && $plan->duration_months === 6);
        $trexThreeMonthPlan = $plans->first(fn (Plan $plan) => $plan->family_slug === 'trex' && $plan->duration_months === 3);
        $trexYearPlan = $plans->first(fn (Plan $plan) => $plan->family_slug === 'trex' && $plan->duration_months === 12);

        $clientUser = User::firstOrCreate(
            ['email' => 'client@rifiptv.test'],
            [
                'name' => 'Client Demo',
                'role' => 'client',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $client = Client::firstOrCreate(
            ['user_id' => $clientUser->id],
            [
                'assigned_admin_id' => $admin->id,
                'phone' => '+212600000000',
                'preferred_payment_method' => 'card',
                'preferred_bank' => null,
                'onboarding_status' => 'completed',
                'city' => 'Tangier',
                'support_notes' => 'Prefers WhatsApp support and evening follow-ups.',
                'last_contacted_at' => now()->subDay(),
                'support_started_at' => now()->subDays(19),
                'setup_tutorial_sent_at' => now()->subDays(18),
                'credentials_sent_at' => now()->subDays(18),
                'completed_at' => now()->subDays(18),
                'iptv_username' => 'rifdemo01',
                'iptv_password' => 'demo-pass-01',
            ]
        );

        $subscription = Subscription::firstOrCreate(
            ['user_id' => $clientUser->id, 'plan_id' => $trexThreeMonthPlan->id],
            [
                'client_id' => $client->id,
                'starts_at' => now()->subDays(18),
                'activated_at' => now()->subDays(18),
                'expires_at' => now()->addDays(72),
                'status' => 'active',
            ]
        );

        Transaction::firstOrCreate(
            ['reference' => 'RIF-2026-0001'],
            [
                'user_id' => $clientUser->id,
                'client_id' => $client->id,
                'assigned_admin_id' => $admin->id,
                'subscription_id' => $subscription->id,
                'amount_mad' => $trexThreeMonthPlan->price_mad,
                'payment_method' => 'card',
                'provider' => 'Paddle',
                'status' => 'paid',
                'paid_at' => now()->subDays(18),
                'verified_at' => now()->subDays(18),
            ]
        );

        $bankClientUser = User::firstOrCreate(
            ['email' => 'bankclient@rifiptv.test'],
            [
                'name' => 'Bank Transfer Demo',
                'role' => 'client',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $bankClient = Client::firstOrCreate(
            ['user_id' => $bankClientUser->id],
            [
                'assigned_admin_id' => $admin->id,
                'phone' => '+212611223344',
                'preferred_payment_method' => 'bank_transfer',
                'preferred_bank' => 'CIH Bank',
                'onboarding_status' => 'awaiting_whatsapp',
                'city' => 'Nador',
                'last_contacted_at' => now()->subHours(5),
            ]
        );

        $bankSubscription = Subscription::firstOrCreate(
            ['user_id' => $bankClientUser->id, 'plan_id' => $supSixMonthPlan->id],
            [
                'client_id' => $bankClient->id,
                'status' => 'awaiting_payment',
            ]
        );

        Transaction::firstOrCreate(
            ['reference' => 'RIF-2026-0002'],
            [
                'user_id' => $bankClientUser->id,
                'client_id' => $bankClient->id,
                'assigned_admin_id' => $admin->id,
                'subscription_id' => $bankSubscription->id,
                'amount_mad' => $supSixMonthPlan->price_mad,
                'payment_method' => 'bank_transfer',
                'provider' => 'National bank transfer',
                'bank_name' => 'CIH Bank',
                'status' => 'awaiting_transfer',
            ]
        );

        $cardQueueUser = User::firstOrCreate(
            ['email' => 'cardqueue@rifiptv.test'],
            [
                'name' => 'Card Queue Demo',
                'role' => 'client',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $cardQueueClient = Client::firstOrCreate(
            ['user_id' => $cardQueueUser->id],
            [
                'assigned_admin_id' => $admin->id,
                'phone' => '+33655667788',
                'preferred_payment_method' => 'card',
                'onboarding_status' => 'awaiting_support',
                'city' => 'Casablanca',
                'last_contacted_at' => now()->subHours(3),
            ]
        );

        $cardQueueSubscription = Subscription::firstOrCreate(
            ['user_id' => $cardQueueUser->id, 'plan_id' => $trexYearPlan->id],
            [
                'client_id' => $cardQueueClient->id,
                'status' => 'awaiting_setup',
            ]
        );

        Transaction::firstOrCreate(
            ['reference' => 'RIF-2026-0003'],
            [
                'user_id' => $cardQueueUser->id,
                'client_id' => $cardQueueClient->id,
                'assigned_admin_id' => $admin->id,
                'subscription_id' => $cardQueueSubscription->id,
                'amount_mad' => $trexYearPlan->price_mad,
                'payment_method' => 'card',
                'provider' => 'Paddle',
                'status' => 'paid',
                'paid_at' => now()->subHours(2),
                'verified_at' => now()->subHours(2),
            ]
        );
    }
}
