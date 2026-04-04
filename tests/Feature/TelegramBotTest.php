<?php

use App\Models\TelegramSubscriber;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

test('telegram webhook subscribes a user on start command', function () {
    Config::set('services.telegram.bot_token', 'test-token');
    Config::set('services.telegram.webhook_secret', 'secret-123');

    Http::fake([
        'https://api.telegram.org/*' => Http::response(['ok' => true], 200),
    ]);

    $response = $this->postJson('/telegram/webhook/secret-123', [
        'message' => [
            'text' => '/start',
            'chat' => [
                'id' => 123456789,
            ],
            'from' => [
                'id' => 123456789,
                'username' => 'rifclient',
                'first_name' => 'Rif',
                'last_name' => 'Client',
                'language_code' => 'en',
            ],
        ],
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('telegram_subscribers', [
        'chat_id' => '123456789',
        'username' => 'rifclient',
        'is_active' => true,
    ]);

    Http::assertSent(fn ($request) => str_contains($request->url(), '/sendMessage'));
});

test('telegram broadcast command sends to active subscribers', function () {
    Config::set('services.telegram.bot_token', 'test-token');

    TelegramSubscriber::create([
        'chat_id' => '111',
        'first_name' => 'One',
        'is_active' => true,
        'subscribed_at' => now(),
    ]);

    TelegramSubscriber::create([
        'chat_id' => '222',
        'first_name' => 'Two',
        'is_active' => false,
        'subscribed_at' => now(),
    ]);

    Http::fake([
        'https://api.telegram.org/*' => Http::response(['ok' => true], 200),
    ]);

    Artisan::call('telegram:broadcast', [
        'message' => 'Hello subscribers',
    ]);

    Http::assertSentCount(1);
    Http::assertSent(fn ($request) => data_get($request->data(), 'chat_id') === '111');
});
