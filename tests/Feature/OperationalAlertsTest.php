<?php

namespace Tests\Feature;

use App\Mail\ClientSubscribedMail;
use App\Models\Client;
use App\Models\Plan;
use App\Models\TelegramSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OperationalAlertsTest extends TestCase
{
    use RefreshDatabase;

    public function test_bank_transfer_checkout_sends_email_and_telegram_alerts(): void
    {
        Config::set('services.telegram.bot_token', 'test-token');
        Config::set('services.telegram.admin_chat_ids', ['9001']);
        Config::set('mail.admin_recipients', ['ops@rifimedia.com']);

        Http::fake([
            'https://api.telegram.org/*' => Http::response(['ok' => true], 200),
        ]);
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@rifimedia.com',
        ]);

        $clientUser = User::factory()->create([
            'role' => 'client',
            'name' => 'Bank Client',
            'email' => 'client@rifimedia.com',
        ]);

        Client::create([
            'user_id' => $clientUser->id,
            'assigned_admin_id' => $admin->id,
        ]);

        TelegramSubscriber::create([
            'chat_id' => '7001',
            'first_name' => 'Subscriber',
            'is_active' => true,
            'subscribed_at' => now(),
        ]);

        TelegramSubscriber::create([
            'chat_id' => '7002',
            'first_name' => 'Inactive',
            'is_active' => false,
            'subscribed_at' => now(),
        ]);

        $plan = Plan::create([
            'name' => 'SUP 3 Months',
            'family' => 'Basic / SUP',
            'family_slug' => 'sup',
            'duration_months' => 3,
            'price_mad' => 89,
            'features' => ['Basic setup'],
            'sort_order' => 1,
        ]);

        $this->actingAs($clientUser)->post(route('onboarding.plan'), [
            'plan_id' => $plan->id,
        ]);

        $response = $this->actingAs($clientUser)->post(route('onboarding.payment'), [
            'payment_method' => 'bank_transfer',
            'bank_name' => 'cih',
        ]);

        $response->assertRedirect(route('dashboard'));

        Mail::assertSent(ClientSubscribedMail::class, function (ClientSubscribedMail $mail) {
            return $mail->hasTo('ops@rifimedia.com')
                && $mail->hasTo('admin@rifimedia.com')
                && $mail->subjectLine === 'New client checkout submitted'
                && collect($mail->details)->contains(fn ($detail) => $detail['label'] === 'Bank' && $detail['value'] === 'CIH Bank');
        });

        Http::assertSentCount(2);
        Http::assertSent(fn ($request) => data_get($request->data(), 'chat_id') === '7001' && str_contains((string) data_get($request->data(), 'text'), 'Bank: CIH Bank'));
        Http::assertSent(fn ($request) => data_get($request->data(), 'chat_id') === '9001' && str_contains((string) data_get($request->data(), 'text'), 'Reference:'));
    }

    public function test_confirmed_card_checkout_sends_email_and_telegram_alerts(): void
    {
        Config::set('services.telegram.bot_token', 'test-token');
        Config::set('services.telegram.admin_chat_ids', ['9001']);
        Config::set('mail.admin_recipients', ['ops@rifimedia.com']);

        Http::fake([
            'https://api.telegram.org/*' => Http::response(['ok' => true], 200),
        ]);
        Mail::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@rifimedia.com',
        ]);

        $clientUser = User::factory()->create([
            'role' => 'client',
            'name' => 'Card Client',
            'email' => 'card-client@rifimedia.com',
        ]);

        Client::create([
            'user_id' => $clientUser->id,
            'assigned_admin_id' => $admin->id,
        ]);

        TelegramSubscriber::create([
            'chat_id' => '7001',
            'first_name' => 'Subscriber',
            'is_active' => true,
            'subscribed_at' => now(),
        ]);

        $plan = Plan::create([
            'name' => 'MAX 6 Months',
            'family' => 'Advanced / MAX',
            'family_slug' => 'max',
            'duration_months' => 6,
            'price_mad' => 249,
            'features' => ['Advanced setup'],
            'sort_order' => 2,
        ]);

        $this->actingAs($clientUser)->post(route('onboarding.plan'), [
            'plan_id' => $plan->id,
        ]);

        $this->actingAs($clientUser)->post(route('onboarding.payment'), [
            'payment_method' => 'card',
        ]);

        $response = $this->actingAs($clientUser)->post(route('checkout.card.confirm'));

        $response->assertRedirect(route('dashboard'));

        Mail::assertSent(ClientSubscribedMail::class, function (ClientSubscribedMail $mail) {
            return $mail->hasTo('ops@rifimedia.com')
                && $mail->hasTo('admin@rifimedia.com')
                && $mail->subjectLine === 'Checkout updated: payment confirmed'
                && $mail->eyebrow === 'Payment confirmed';
        });

        Http::assertSentCount(2);
        Http::assertSent(fn ($request) => data_get($request->data(), 'chat_id') === '7001' && str_contains((string) data_get($request->data(), 'text'), 'Provider: Paddle'));
        Http::assertSent(fn ($request) => data_get($request->data(), 'chat_id') === '9001' && str_contains((string) data_get($request->data(), 'text'), 'Status: Paid'));
    }
}
