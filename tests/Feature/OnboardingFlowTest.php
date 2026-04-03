<?php

use App\Models\Client;
use App\Models\Plan;
use App\Models\User;

test('selecting a plan redirects the client to checkout', function () {
    $user = User::factory()->create([
        'role' => 'client',
    ]);

    Client::create([
        'user_id' => $user->id,
    ]);

    $plan = Plan::create([
        'name' => 'SUP 3 Months',
        'family' => 'Basic / SUP',
        'family_slug' => 'sup',
        'duration_months' => 3,
        'price_mad' => 89,
        'features' => ['Setup help', 'WhatsApp support'],
        'is_featured' => false,
        'sort_order' => 1,
    ]);

    $response = $this->actingAs($user)->post(route('onboarding.plan'), [
        'plan_id' => $plan->id,
    ]);

    $response->assertRedirect(route('checkout'));
});

test('checkout page requires a selected plan and shows payment options', function () {
    $user = User::factory()->create([
        'role' => 'client',
    ]);

    $client = Client::create([
        'user_id' => $user->id,
    ]);

    $plan = Plan::create([
        'name' => 'MAX 6 Months',
        'family' => 'Advanced / MAX',
        'family_slug' => 'max',
        'duration_months' => 6,
        'price_mad' => 249,
        'features' => ['Guided setup', 'Longer support'],
        'is_featured' => true,
        'sort_order' => 2,
    ]);

    $this->actingAs($user)->post(route('onboarding.plan'), [
        'plan_id' => $plan->id,
    ]);

    $response = $this->actingAs($user)->get(route('checkout'));

    $response
        ->assertOk()
        ->assertSee('Paddle')
        ->assertSee('bank', false);
});
