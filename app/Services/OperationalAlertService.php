<?php

namespace App\Services;

use App\Mail\ClientSubscribedMail;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OperationalAlertService
{
    public function __construct(protected TelegramBotService $telegram)
    {
    }

    public function notifyCheckoutSubmitted(Client $client, Transaction $transaction): void
    {
        $payload = $this->payload($client, $transaction);

        $this->dispatchOperationalAlert(
            $client,
            $payload,
            'New client checkout submitted',
            'New checkout',
            'A client completed the first checkout step and needs operational follow-up.'
        );
    }

    public function notifyCheckoutUpdated(Client $client, Transaction $transaction, string $updateLabel = 'Checkout details updated'): void
    {
        $payload = $this->payload($client, $transaction);

        $this->dispatchOperationalAlert(
            $client,
            $payload,
            'Checkout updated',
            'Checkout update',
            $updateLabel
        );
    }

    public function notifyPaymentConfirmed(Client $client, Transaction $transaction, string $updateLabel = 'A payment update was recorded for an existing checkout.'): void
    {
        $payload = $this->payload($client, $transaction);

        $this->dispatchOperationalAlert(
            $client,
            $payload,
            'Checkout updated: payment confirmed',
            'Payment confirmed',
            $updateLabel
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

    protected function dispatchOperationalAlert(
        Client $client,
        array $payload,
        string $subject,
        string $eyebrow,
        string $summary
    ): void {
        $this->sendMailToAdmins($client, $subject, $summary, $eyebrow, $this->mailDetails($payload));
        $this->sendTelegramOperationalMessage($this->telegramMessage($subject, $payload));
    }

    protected function sendMailToAdmins(Client $client, string $subject, string $summary, string $eyebrow, array $details): void
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

        Mail::to($recipients->all())->send(new ClientSubscribedMail($client, $subject, $summary, $eyebrow, $details));
    }

    protected function sendTelegramOperationalMessage(string $message): void
    {
        $this->telegramRecipients()->each(function (string $chatId) use ($message): void {
            $this->telegram->sendMessage($chatId, $message);
        });
    }

    protected function payload(Client $client, Transaction $transaction): array
    {
        $client->loadMissing('user', 'assignedAdmin');
        $transaction->loadMissing('subscription.plan', 'assignedAdmin');

        $methodLabel = match ($transaction->payment_method) {
            'bank_transfer' => 'Bank transfer',
            'cash' => 'Cash payment',
            default => 'Card payment',
        };
        $providerLabel = $transaction->provider ?: match ($transaction->payment_method) {
            'bank_transfer' => 'Manual review',
            'cash' => 'Cash collection follow-up',
            default => 'Paddle',
        };

        return [
            'client_name' => $client->user->name,
            'client_email' => $client->user->email,
            'client_phone' => trim(($client->user->phone_country_code ?? '').' '.($client->user->phone_number ?? '')) ?: 'Not provided',
            'plan_name' => $transaction->subscription?->plan?->name ?? 'Selected package',
            'amount' => number_format((float) $transaction->amount_mad, 2, '.', '').' MAD',
            'payment_method' => $methodLabel,
            'provider' => $providerLabel,
            'bank_name' => $transaction->bank_name,
            'reference' => $transaction->reference ?: 'Not generated yet',
            'status' => str($transaction->status ?: 'pending')->replace('_', ' ')->title()->toString(),
            'proof' => $transaction->proof_path ? url($transaction->proof_path) : 'No proof uploaded',
            'assigned_admin' => $transaction->assignedAdmin?->name ?? $client->assignedAdmin?->name ?? 'Not assigned yet',
        ];
    }

    protected function mailDetails(array $payload): array
    {
        return [
            ['label' => 'Package', 'value' => $payload['plan_name']],
            ['label' => 'Amount', 'value' => $payload['amount']],
            ['label' => 'Payment method', 'value' => $payload['payment_method']],
            ['label' => 'Provider', 'value' => $payload['provider']],
            ['label' => 'Bank', 'value' => $payload['bank_name'] ?: 'Not applicable'],
            ['label' => 'Reference', 'value' => $payload['reference']],
            ['label' => 'Phone', 'value' => $payload['client_phone']],
            ['label' => 'Assigned admin', 'value' => $payload['assigned_admin']],
            ['label' => 'Payment proof', 'value' => $payload['proof']],
        ];
    }

    protected function telegramMessage(string $subject, array $payload): string
    {
        $lines = [
            $subject,
            'Client: '.$payload['client_name'],
            'Email: '.$payload['client_email'],
            'Phone: '.$payload['client_phone'],
            'Package: '.$payload['plan_name'],
            'Amount: '.$payload['amount'],
            'Method: '.$payload['payment_method'],
            'Provider: '.$payload['provider'],
        ];

        if (filled($payload['bank_name'])) {
            $lines[] = 'Bank: '.$payload['bank_name'];
        }

        $lines[] = 'Reference: '.$payload['reference'];
        $lines[] = 'Status: '.$payload['status'];
        $lines[] = 'Assigned admin: '.$payload['assigned_admin'];

        if (! empty($payload['proof']) && $payload['proof'] !== 'No proof uploaded') {
            $lines[] = 'Proof: '.$payload['proof'];
        }

        return implode("\n", Arr::where($lines, fn ($line) => filled($line)));
    }
}
