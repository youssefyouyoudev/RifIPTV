<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Plan;
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
    }
}
