<?php

namespace App\Http\Controllers;

use App\Models\TelegramSubscriber;
use App\Services\TelegramBotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TelegramWebhookController extends Controller
{
    public function __invoke(Request $request, string $secret, TelegramBotService $telegram): JsonResponse
    {
        abort_unless(
            filled(config('services.telegram.webhook_secret'))
            && hash_equals((string) config('services.telegram.webhook_secret'), $secret),
            404
        );

        $message = $request->input('message', $request->input('edited_message', []));
        $chat = data_get($message, 'chat', []);
        $from = data_get($message, 'from', []);
        $text = trim((string) data_get($message, 'text', ''));
        $chatId = (string) data_get($chat, 'id', '');

        if ($chatId === '') {
            return response()->json(['ok' => true]);
        }

        $subscriber = TelegramSubscriber::query()->firstOrNew([
            'chat_id' => $chatId,
        ]);

        $subscriber->fill([
            'username' => data_get($from, 'username'),
            'first_name' => data_get($from, 'first_name'),
            'last_name' => data_get($from, 'last_name'),
            'language_code' => data_get($from, 'language_code'),
            'last_seen_at' => now(),
        ]);

        if ($text === '/stop') {
            $subscriber->is_active = false;
            $subscriber->save();

            $telegram->sendMessage($chatId, 'You have been unsubscribed from updates. Send /start anytime to subscribe again.');

            return response()->json(['ok' => true]);
        }

        if ($text === '/start') {
            $subscriber->is_active = true;
            $subscriber->subscribed_at = $subscriber->subscribed_at ?: now();
            $subscriber->save();

            $telegram->sendMessage(
                $chatId,
                "Welcome to RIF Media updates.\n\nYou are now subscribed to service alerts and support notifications.\nSend /stop anytime to unsubscribe."
            );

            return response()->json(['ok' => true]);
        }

        $subscriber->save();

        return response()->json(['ok' => true]);
    }
}
