<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPlanManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_plan_management_screen(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.plans.index'))
            ->assertOk()
            ->assertSee('Manage support plans', false);
    }

    public function test_client_cannot_open_plan_management_screen(): void
    {
        $clientUser = User::factory()->create([
            'role' => 'client',
        ]);

        $this->actingAs($clientUser)
            ->get(route('admin.plans.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_enabled_plan_and_disabled_plans_stay_hidden_from_storefront(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Plan::create([
            'family' => 'Advanced / MAX',
            'family_slug' => 'max',
            'name' => '6 Months',
            'duration_months' => 6,
            'price_mad' => 249,
            'features' => ['Priority support'],
            'is_featured' => false,
            'is_enabled' => false,
            'sort_order' => 120,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.plans.store'), [
                'family_slug' => 'sup',
                'duration_months' => 12,
                'name' => 'Smart TV - 12 Months',
                'price_mad' => 200,
                'features_text' => "Step-by-step Smart TV setup help\nWhatsApp clarification support",
                'badge_text' => 'Best Value',
                'is_enabled' => '1',
                'is_featured' => '1',
                'sort_order' => 30,
            ])
            ->assertRedirect(route('admin.plans.index'));

        $this->assertDatabaseHas('plans', [
            'family_slug' => 'sup',
            'duration_months' => 12,
            'is_enabled' => true,
        ]);

        $this->get('/packages')
            ->assertOk()
            ->assertSee('12 Months', false)
            ->assertSee('200', false)
            ->assertSee('Best Value', false)
            ->assertDontSee('Advanced / MAX', false)
            ->assertDontSee('249', false);
    }

    public function test_disabled_plan_cannot_be_selected_in_onboarding(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
        ]);

        Client::create([
            'user_id' => $user->id,
        ]);

        $plan = Plan::create([
            'name' => 'Hidden plan',
            'family' => 'Premium / TREX',
            'family_slug' => 'trex',
            'duration_months' => 6,
            'price_mad' => 349,
            'features' => ['Hidden'],
            'is_featured' => false,
            'is_enabled' => false,
            'sort_order' => 200,
        ]);

        $this->actingAs($user)
            ->from(route('onboarding.show'))
            ->post(route('onboarding.plan'), [
                'plan_id' => $plan->id,
            ])
            ->assertRedirect(route('onboarding.show'))
            ->assertSessionHasErrors('plan_id');
    }
}
