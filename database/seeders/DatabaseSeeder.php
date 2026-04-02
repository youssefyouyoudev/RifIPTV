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
                'name' => 'RIF IPTV Admin',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $plans = collect([
            [
                'family' => 'MAX OTT',
                'family_slug' => 'max-ott',
                'name' => '1 Month',
                'duration_months' => 1,
                'price_mad' => 70.00,
                'features' => ['International channels', 'Fast activation', 'Stable quality', 'WhatsApp follow-up'],
                'is_featured' => false,
                'badge_text' => null,
                'sort_order' => 10,
            ],
            [
                'family' => 'MAX OTT',
                'family_slug' => 'max-ott',
                'name' => '3 Months',
                'duration_months' => 3,
                'price_mad' => 149.00,
                'features' => ['Best MAX OTT value', 'Live sports', 'Movies and series', 'Priority handling'],
                'is_featured' => true,
                'badge_text' => 'Popular',
                'sort_order' => 20,
            ],
            [
                'family' => 'MAX OTT',
                'family_slug' => 'max-ott',
                'name' => '6 Months',
                'duration_months' => 6,
                'price_mad' => 249.00,
                'features' => ['Longer access', 'Smooth streaming', 'Support included', 'Better savings'],
                'is_featured' => false,
                'badge_text' => null,
                'sort_order' => 30,
            ],
            [
                'family' => 'MAX OTT',
                'family_slug' => 'max-ott',
                'name' => '12 Months',
                'duration_months' => 12,
                'price_mad' => 449.00,
                'features' => ['Full year access', 'Best yearly savings', 'Stable experience', 'Priority support'],
                'is_featured' => false,
                'badge_text' => 'Best Value',
                'sort_order' => 40,
            ],
            [
                'family' => 'T-REX',
                'family_slug' => 't-rex',
                'name' => '1 Month',
                'duration_months' => 1,
                'price_mad' => 99.00,
                'features' => ['Premium servers', 'Sports coverage', 'High quality playback', 'Responsive support'],
                'is_featured' => false,
                'badge_text' => null,
                'sort_order' => 110,
            ],
            [
                'family' => 'T-REX',
                'family_slug' => 't-rex',
                'name' => '3 Months',
                'duration_months' => 3,
                'price_mad' => 249.00,
                'features' => ['Balanced premium offer', 'Sports and VOD', 'Reliable streams', 'Priority setup'],
                'is_featured' => true,
                'badge_text' => 'Best Seller',
                'sort_order' => 120,
            ],
            [
                'family' => 'T-REX',
                'family_slug' => 't-rex',
                'name' => '6 Months',
                'duration_months' => 6,
                'price_mad' => 349.00,
                'features' => ['Long term premium access', 'Secure delivery', 'High stability', 'Easy renewal'],
                'is_featured' => false,
                'badge_text' => null,
                'sort_order' => 130,
            ],
            [
                'family' => 'T-REX',
                'family_slug' => 't-rex',
                'name' => '12 Months',
                'duration_months' => 12,
                'price_mad' => 599.00,
                'features' => ['Best T-REX yearly price', 'Continuous support', 'Top priority', 'Premium all year'],
                'is_featured' => false,
                'badge_text' => 'Annual Value',
                'sort_order' => 140,
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

        $maxOttSixMonthPlan = $plans->first(fn (Plan $plan) => $plan->family_slug === 'max-ott' && $plan->duration_months === 6);
        $trexThreeMonthPlan = $plans->first(fn (Plan $plan) => $plan->family_slug === 't-rex' && $plan->duration_months === 3);
        $trexYearPlan = $plans->first(fn (Plan $plan) => $plan->family_slug === 't-rex' && $plan->duration_months === 12);

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
            ['user_id' => $bankClientUser->id, 'plan_id' => $maxOttSixMonthPlan->id],
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
                'amount_mad' => $maxOttSixMonthPlan->price_mad,
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
