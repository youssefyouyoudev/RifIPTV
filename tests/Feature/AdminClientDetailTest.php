<?php

use App\Models\Client;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;

test('admin can open a separate client detail view', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'name' => 'Admin User',
    ]);

    $clientUser = User::factory()->create([
        'role' => 'client',
        'name' => 'Client Detail',
        'email' => 'client-detail@example.com',
    ]);

    $client = Client::create([
        'user_id' => $clientUser->id,
        'assigned_admin_id' => $admin->id,
        'city' => 'Tangier',
        'onboarding_status' => 'support_in_progress',
    ]);

    $plan = Plan::create([
        'name' => 'SUP 6 Months',
        'family' => 'Basic / SUP',
        'family_slug' => 'sup',
        'duration_months' => 6,
        'price_mad' => 149,
        'features' => ['Support'],
        'sort_order' => 1,
    ]);

    $subscription = Subscription::create([
        'user_id' => $clientUser->id,
        'client_id' => $client->id,
        'plan_id' => $plan->id,
        'status' => 'awaiting_setup',
    ]);

    Transaction::create([
        'user_id' => $clientUser->id,
        'client_id' => $client->id,
        'subscription_id' => $subscription->id,
        'amount_mad' => 149,
        'payment_method' => 'bank_transfer',
        'bank_name' => 'CIH Bank',
        'status' => 'awaiting_transfer',
        'reference' => 'TX-DETAIL-1',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.clients.show', $client));

    $response
        ->assertOk()
        ->assertSee('Client Detail')
        ->assertSee('CIH Bank')
        ->assertSee('Handle this client');
});
