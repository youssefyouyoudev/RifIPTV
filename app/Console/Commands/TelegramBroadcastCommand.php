<?php

namespace App\Console\Commands;

use App\Services\TelegramBotService;
use Illuminate\Console\Command;

class TelegramBroadcastCommand extends Command
{
    protected $signature = 'telegram:broadcast {message : The message to send to all active subscribers}';

    protected $description = 'Broadcast a Telegram message to all active bot subscribers.';

    public function handle(TelegramBotService $telegram): int
    {
        if (! $telegram->isConfigured()) {
            $this->error('Telegram bot is not configured.');

            return self::FAILURE;
        }

        $count = $telegram->activeSubscribers()->count();

        if ($count === 0) {
            $this->warn('No active Telegram subscribers found.');

            return self::SUCCESS;
        }

        $telegram->sendToSubscribers((string) $this->argument('message'));

        $this->info("Broadcast queued for {$count} subscriber(s).");

        return self::SUCCESS;
    }
}
