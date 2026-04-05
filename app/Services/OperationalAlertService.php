<?php

namespace App\Services;

use App\Mail\ClientSubscribedMail;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OperationalAlertService
{
    public function __construct(protected TelegramBotService $telegram)
    {
    }

    public function notifyCheckoutSubmitted(Client $client, Transaction $transaction): void
    {
        $planName = $transaction->subscription?->plan?->name ?? 'Selected plan';
        $methodLabel = $transaction->payment_method === 'bank_transfer' ? 'Bank transfer' : 'Card';
        $amount = number_format((float) $transaction->amount_mad, 2, '.', '');

        $this->sendMailToAdmins(
            $client,
            'New client checkout submitted',
            "A client submitted checkout.\n\nPlan: {$planName}\nAmount: {$amount} MAD\nMethod: {$methodLabel}"
        );

        $this->sendTelegramOperationalMessage(
            "New checkout submitted\nClient: {$client->user->name}\nPlan: {$planName}\nAmount: {$amount} MAD\nMethod: {$methodLabel}"
        );
    }

    public function notifyPaymentConfirmed(Client $client, Transaction $transaction): void
    {
        $planName = $transaction->subscription?->plan?->name ?? 'Selected plan';
        $methodLabel = $transaction->payment_method === 'bank_transfer' ? 'Bank transfer' : 'Card';
        $amount = number_format((float) $transaction->amount_mad, 2, '.', '');

        $this->sendMailToAdmins(
            $client,
            'Client payment confirmed',
            "A client payment has been confirmed.\n\nPlan: {$planName}\nAmount: {$amount} MAD\nMethod: {$methodLabel}"
        );

        $this->sendTelegramOperationalMessage(
            "Payment confirmed\nClient: {$client->user->name}\nPlan: {$planName}\nAmount: {$amount} MAD\nMethod: {$methodLabel}"
        );
    }

    public function adminMailRecipients(): Collection
    {
        $configured = collect(config('mail.admin_recipients', []))
            ->filter()
            ->map(fn (string $email) => trim($email))
            ->unique();

        $admins = User::query()
            ->where('role', 'admin')
            ->pluck('email')
            ->filter()
            ->map(fn (string $email) => trim($email));

        return $configured
            ->merge($admins)
            ->filter()
            ->unique()
            ->values();
    }

    public function telegramRecipients(): Collection
    {
        $configuredAdmins = collect(config('services.telegram.admin_chat_ids', []))
            ->filter()
            ->map(fn (string $chatId) => trim($chatId));

        $subscribers = $this->telegram->activeSubscribers()
            ->pluck('chat_id')
            ->map(fn (string $chatId) => trim($chatId));

        return $configuredAdmins
            ->merge($subscribers)
            ->filter()
            ->unique()
            ->values();
    }

    protected function sendMailToAdmins(Client $client, string $subject, string $summary): void
    {
        $recipients = $this->adminMailRecipients();

        Log::info('Operational alert email dispatch.', [
            'client_id' => $client->id,
            'recipients' => $recipients->all(),
            'subject' => $subject,
        ]);

        if ($recipients->isEmpty()) {
            return;
        }

        Mail::to($recipients->all())->send(new ClientSubscribedMail($client, $subject, $summary));
    }

    protected function sendTelegramOperationalMessage(string $message): void
    {
        $this->telegramRecipients()->each(function (string $chatId) use ($message): void {
            $this->telegram->sendMessage($chatId, $message);
        });
    }
}
