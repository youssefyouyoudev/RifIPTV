<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminClientDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_a_separate_client_detail_view(): void
    {
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
    }

    public function test_admin_can_open_client_detail_even_when_client_is_assigned_to_another_admin(): void
    {
        $ownerAdmin = User::factory()->create([
            'role' => 'admin',
            'name' => 'Owner Admin',
        ]);

        $viewerAdmin = User::factory()->create([
            'role' => 'admin',
            'name' => 'Viewer Admin',
        ]);

        $clientUser = User::factory()->create([
            'role' => 'client',
            'name' => 'Shared Client',
            'email' => 'shared-client@example.com',
        ]);

        $client = Client::create([
            'user_id' => $clientUser->id,
            'assigned_admin_id' => $ownerAdmin->id,
            'onboarding_status' => 'awaiting_whatsapp',
        ]);

        $response = $this->actingAs($viewerAdmin)->get(route('admin.clients.show', $client));

        $response
            ->assertOk()
            ->assertSee('Shared Client')
            ->assertSee('Checkout')
            ->assertSee('Edit client details');
    }

    public function test_admin_can_complete_order_setup_on_behalf_of_a_client(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'Workflow Admin',
        ]);

        $clientUser = User::factory()->create([
            'role' => 'client',
            'name' => 'Rescue Client',
        ]);

        $client = Client::create([
            'user_id' => $clientUser->id,
            'onboarding_status' => 'new',
        ]);

        $plan = Plan::create([
            'name' => 'Smart TV - 12 Months',
            'family' => 'Smart TV',
            'family_slug' => 'smart_tv',
            'duration_months' => 12,
            'price_mad' => 200,
            'features' => ['Setup help'],
            'is_enabled' => true,
            'is_featured' => true,
            'sort_order' => 10,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.clients.workflow', $client), [
            'action' => 'save_order',
            'plan_id' => $plan->id,
            'payment_method' => 'cash',
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('status', 'client-workflow-updated');

        $client->refresh();
        $subscription = $client->subscriptions()->latest('id')->first();
        $transaction = $client->transactions()->latest('id')->first();

        $this->assertSame($admin->id, $client->assigned_admin_id);
        $this->assertSame('awaiting_whatsapp', $client->onboarding_status);
        $this->assertSame('cash', $client->preferred_payment_method);
        $this->assertNotNull($subscription);
        $this->assertSame($plan->id, $subscription->plan_id);
        $this->assertSame('awaiting_payment', $subscription->status);
        $this->assertNotNull($transaction);
        $this->assertSame('cash', $transaction->payment_method);
        $this->assertSame('awaiting_cash', $transaction->status);
        $this->assertSame('200.00', $transaction->amount_mad);
    }
}
