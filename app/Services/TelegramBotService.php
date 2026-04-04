<?php

namespace App\Services;

use App\Models\TelegramSubscriber;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramBotService
{
    public function isConfigured(): bool
    {
        return filled(config('services.telegram.bot_token'));
    }

    public function activeSubscribers(): Collection
    {
        return TelegramSubscriber::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get();
    }

    public function sendToSubscribers(string $message): void
    {
        if (! $this->isConfigured()) {
            return;
        }

        $this->activeSubscribers()->each(function (TelegramSubscriber $subscriber) use ($message): void {
            $this->sendMessage($subscriber->chat_id, $message);
        });
    }

    public function sendMessage(string $chatId, string $message): bool
    {
        if (! $this->isConfigured()) {
            return false;
        }

        try {
            $response = Http::timeout(10)
                ->asForm()
                ->post($this->apiUrl('sendMessage'), [
                    'chat_id' => $chatId,
                    'text' => $message,
                ]);

            if ($response->failed()) {
                Log::warning('Telegram sendMessage failed.', [
                    'chat_id' => $chatId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (\Throwable $exception) {
            Log::warning('Telegram sendMessage exception.', [
                'chat_id' => $chatId,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    protected function apiUrl(string $method): string
    {
        return sprintf(
            'https://api.telegram.org/bot%s/%s',
            config('services.telegram.bot_token'),
            $method
        );
    }
}
