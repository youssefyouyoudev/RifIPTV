<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_selecting_a_plan_redirects_the_client_to_checkout(): void
    {
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
            'is_enabled' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('onboarding.plan'), [
            'plan_id' => $plan->id,
        ]);

        $response->assertRedirect(route('checkout'));
    }

    public function test_checkout_page_requires_a_selected_plan_and_shows_payment_options(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
        ]);

        Client::create([
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
            'is_enabled' => true,
            'sort_order' => 2,
        ]);

        $this->actingAs($user)->post(route('onboarding.plan'), [
            'plan_id' => $plan->id,
        ]);

        $response = $this->actingAs($user)->get(route('checkout'));

        $response
            ->assertOk()
            ->assertSee('Paddle')
            ->assertSee('bank', false)
            ->assertSee('cash', false);
    }

    public function test_cash_payment_creates_manual_cash_transaction_and_redirects_to_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
        ]);

        $client = Client::create([
            'user_id' => $user->id,
        ]);

        $plan = Plan::create([
            'name' => 'SUP 12 Months',
            'family' => 'Basic / SUP',
            'family_slug' => 'sup',
            'duration_months' => 12,
            'price_mad' => 199,
            'features' => ['Step-by-step setup help'],
            'is_featured' => true,
            'is_enabled' => true,
            'sort_order' => 3,
        ]);

        $this->actingAs($user)->post(route('onboarding.plan'), [
            'plan_id' => $plan->id,
        ]);

        $response = $this->actingAs($user)->post(route('onboarding.payment'), [
            'payment_method' => 'cash',
        ]);

        $response
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('status', 'cash-payment-waiting');

        $transaction = Transaction::query()->latest('id')->first();

        $this->assertNotNull($transaction);
        $this->assertSame($client->id, $transaction->client_id);
        $this->assertSame('cash', $transaction->payment_method);
        $this->assertSame('Cash payment', $transaction->provider);
        $this->assertSame('awaiting_cash', $transaction->status);
    }
}
