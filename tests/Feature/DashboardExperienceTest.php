<?php

use App\Models\Client;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;

test('home route renders the rif iptv homepage', function () {
    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee('RIF IPTV', false)
        ->assertSee('Over 10,000 famous channels', false);
});

test('client dashboard shows subscription-focused experience', function () {
    $user = User::factory()->create([
        'name' => 'Client User',
        'role' => 'client',
    ]);

    $client = Client::create([
        'user_id' => $user->id,
    ]);

    $plan = Plan::create([
        'name' => 'Premium',
        'price_mad' => 149,
        'features' => ['Fast activation'],
        'is_featured' => true,
    ]);

    Subscription::create([
        'user_id' => $user->id,
        'client_id' => $client->id,
        'plan_id' => $plan->id,
        'starts_at' => now()->subDays(10),
        'activated_at' => now()->subDays(10),
        'expires_at' => now()->addDays(20),
        'status' => 'active',
    ]);

    Transaction::create([
        'user_id' => $user->id,
        'client_id' => $client->id,
        'amount_mad' => 149,
        'payment_method' => 'Paddle',
        'status' => 'paid',
        'reference' => 'TX-CLIENT-1',
        'paid_at' => now()->subDay(),
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response
        ->assertOk()
        ->assertSee('Client dashboard')
        ->assertSee('Premium')
        ->assertSee('Payment details')
        ->assertDontSee('Total revenue');
});

test('admin dashboard shows revenue and client management metrics', function () {
    $admin = User::factory()->create([
        'name' => 'Admin User',
        'role' => 'admin',
    ]);

    $clientUser = User::factory()->create([
        'name' => 'Managed Client',
        'role' => 'client',
    ]);

    $client = Client::create([
        'user_id' => $clientUser->id,
        'assigned_admin_id' => $admin->id,
        'city' => 'Tetouan',
    ]);

    $plan = Plan::create([
        'name' => 'Family',
        'price_mad' => 199,
        'features' => ['Support'],
        'is_featured' => false,
    ]);

    $subscription = Subscription::create([
        'user_id' => $clientUser->id,
        'client_id' => $client->id,
        'plan_id' => $plan->id,
        'starts_at' => now()->subDays(12),
        'activated_at' => now()->subDays(12),
        'expires_at' => now()->addDays(18),
        'status' => 'active',
    ]);

    Transaction::create([
        'user_id' => $clientUser->id,
        'client_id' => $client->id,
        'subscription_id' => $subscription->id,
        'amount_mad' => 199,
        'payment_method' => 'Bank Transfer',
        'status' => 'paid',
        'reference' => 'TX-ADMIN-1',
        'paid_at' => now()->subDays(2),
    ]);

    $response = $this->actingAs($admin)->get('/dashboard');

    $response
        ->assertOk()
        ->assertSee('Total revenue')
        ->assertSee('Managed Client')
        ->assertSee('Client tracking table');
});
