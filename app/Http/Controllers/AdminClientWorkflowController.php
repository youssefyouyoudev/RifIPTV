<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Services\OperationalAlertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AdminClientWorkflowController extends Controller
{
    public function __construct(protected OperationalAlertService $alerts)
    {
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $admin = $request->user();

        abort_unless($admin->isAdmin(), 403);

        $action = $request->validate([
            'action' => ['required', 'in:assign,start_support,confirm_payment,send_tutorial,save_credentials,mark_completed,update_profile,save_order'],
        ])['action'];

        $subscription = $client->subscriptions()->with('plan')->latest('id')->first();
        $transaction = $client->transactions()->latest('id')->first();

        match ($action) {
            'assign' => $this->assignClient($client, $admin->id),
            'start_support' => $this->startSupport($client, $admin->id),
            'confirm_payment' => $this->confirmPayment($request, $client, $transaction, $subscription, $admin->id),
            'send_tutorial' => $this->sendTutorial($client, $admin->id),
            'save_credentials' => $this->saveCredentials($request, $client, $admin->id),
            'mark_completed' => $this->markCompleted($client, $subscription, $admin->id),
            'update_profile' => $this->updateProfile($request, $client, $admin->id),
            'save_order' => $this->saveOrder($request, $client, $admin->id),
        };

        return back()->with('status', 'client-workflow-updated');
    }

    protected function assignClient(Client $client, int $adminId): void
    {
        $client->update([
            'assigned_admin_id' => $adminId,
            'last_contacted_at' => now(),
            'onboarding_status' => in_array($client->onboarding_status, ['new', 'plan_selected'], true)
                ? 'assigned'
                : $client->onboarding_status,
        ]);
    }

    protected function startSupport(Client $client, int $adminId): void
    {
        $client->update([
            'assigned_admin_id' => $adminId,
            'support_started_at' => now(),
            'last_contacted_at' => now(),
            'onboarding_status' => 'support_in_progress',
        ]);
    }

    protected function confirmPayment(Request $request, Client $client, ?Transaction $transaction, $subscription, int $adminId): void
    {
        if (! $transaction) {
            return;
        }

        $data = $request->validate([
            'payment_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:4096'],
        ]);

        $proofPath = $transaction->proof_path;

        if ($request->hasFile('payment_proof')) {
            $directory = public_path('uploads/payment-proofs');
            File::ensureDirectoryExists($directory);

            $file = $request->file('payment_proof');
            $filename = now()->format('YmdHis').'-'.Str::slug($client->user->name ?: 'client').'.'.$file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $proofPath = 'uploads/payment-proofs/'.$filename;
        }

        $transaction->update([
            'assigned_admin_id' => $adminId,
            'status' => 'paid',
            'paid_at' => $transaction->paid_at ?: now(),
            'verified_at' => now(),
            'proof_path' => $proofPath,
        ]);

        if ($subscription) {
            $subscription->update([
                'status' => 'awaiting_setup',
            ]);
        }

        $client->update([
            'assigned_admin_id' => $adminId,
            'last_contacted_at' => now(),
            'onboarding_status' => 'payment_confirmed',
        ]);

        $transaction->loadMissing('subscription.plan');
        $client->loadMissing('user');
        $this->alerts->notifyPaymentConfirmed($client, $transaction, 'The admin reviewed the transfer, attached proof when available, and marked the payment as confirmed.');
    }

    protected function sendTutorial(Client $client, int $adminId): void
    {
        $client->update([
            'assigned_admin_id' => $adminId,
            'setup_tutorial_sent_at' => now(),
            'last_contacted_at' => now(),
            'onboarding_status' => 'tutorial_sent',
        ]);
    }

    protected function saveCredentials(Request $request, Client $client, int $adminId): void
    {
        $data = $request->validate([
            'iptv_username' => ['required', 'string', 'max:255'],
            'iptv_password' => ['required', 'string', 'max:255'],
        ]);

        $client->update([
            'assigned_admin_id' => $adminId,
            'iptv_username' => $data['iptv_username'],
            'iptv_password' => $data['iptv_password'],
            'credentials_sent_at' => now(),
            'last_contacted_at' => now(),
            'onboarding_status' => 'credentials_sent',
        ]);
    }

    protected function markCompleted(Client $client, $subscription, int $adminId): void
    {
        $now = now();

        if ($subscription && $subscription->plan) {
            $startsAt = $subscription->starts_at ?: $now->copy();
            $expiresAt = $startsAt->copy()->addMonths(max((int) $subscription->plan->duration_months, 1));

            $subscription->update([
                'starts_at' => $startsAt,
                'activated_at' => $now,
                'expires_at' => $expiresAt,
                'status' => 'active',
            ]);
        }

        $client->update([
            'assigned_admin_id' => $adminId,
            'completed_at' => $now,
            'last_contacted_at' => $now,
            'onboarding_status' => 'completed',
        ]);
    }

    protected function updateProfile(Request $request, Client $client, int $adminId): void
    {
        $data = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($client->user_id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:255'],
            'support_notes' => ['nullable', 'string', 'max:3000'],
            'assigned_admin_id' => ['nullable', Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'admin'))],
        ]);

        $client->user->update([
            'name' => $data['client_name'],
            'email' => $data['email'],
        ]);

        $client->update([
            'assigned_admin_id' => $data['assigned_admin_id'] ?: $adminId,
            'phone' => preg_replace('/\s+/', '', (string) ($data['phone'] ?? '')) ?: null,
            'city' => $data['city'] ?: null,
            'support_notes' => $data['support_notes'] ?: null,
        ]);
    }

    protected function saveOrder(Request $request, Client $client, int $adminId): void
    {
        $data = $request->validate([
            'plan_id' => ['required', Rule::exists('plans', 'id')->where(fn ($query) => $query->where('is_enabled', true))],
            'payment_method' => ['required', 'in:card,bank_transfer,cash'],
            'bank_name' => [
                Rule::requiredIf(fn () => $request->input('payment_method') === 'bank_transfer'),
                'nullable',
                Rule::in(array_keys($this->bankOptions())),
            ],
        ]);

        $plan = Plan::query()->findOrFail($data['plan_id']);
        $subscription = $this->currentDraftSubscription($client);

        if (! $subscription || $subscription->status === 'active') {
            $subscription = new Subscription();
            $subscription->user_id = $client->user_id;
            $subscription->client_id = $client->id;
        }

        $subscription->plan_id = $plan->id;
        $subscription->status = 'awaiting_payment';
        $subscription->starts_at = null;
        $subscription->activated_at = null;
        $subscription->expires_at = null;
        $subscription->save();

        $transaction = $this->currentDraftTransaction($client, $subscription) ?? new Transaction();
        $transaction->user_id = $client->user_id;
        $transaction->client_id = $client->id;
        $transaction->assigned_admin_id = $adminId;
        $transaction->subscription_id = $subscription->id;
        $transaction->amount_mad = $plan->price_mad;
        $transaction->reference = $transaction->reference ?: $this->reference('ADM');
        $transaction->paid_at = null;
        $transaction->verified_at = null;
        $transaction->proof_path = null;

        if ($data['payment_method'] === 'card') {
            $transaction->payment_method = 'card';
            $transaction->provider = 'Paddle';
            $transaction->bank_name = null;
            $transaction->status = 'initiated';

            $client->update([
                'assigned_admin_id' => $adminId,
                'preferred_payment_method' => 'card',
                'preferred_bank' => null,
                'onboarding_status' => 'card_checkout',
            ]);
        } elseif ($data['payment_method'] === 'cash') {
            $transaction->payment_method = 'cash';
            $transaction->provider = 'Cash payment';
            $transaction->bank_name = null;
            $transaction->status = 'awaiting_cash';

            $client->update([
                'assigned_admin_id' => $adminId,
                'preferred_payment_method' => 'cash',
                'preferred_bank' => null,
                'onboarding_status' => 'awaiting_whatsapp',
            ]);
        } else {
            $transaction->payment_method = 'bank_transfer';
            $transaction->provider = 'National bank transfer';
            $transaction->bank_name = $this->bankOptions()[$data['bank_name']];
            $transaction->status = 'awaiting_transfer';

            $client->update([
                'assigned_admin_id' => $adminId,
                'preferred_payment_method' => 'bank_transfer',
                'preferred_bank' => $this->bankOptions()[$data['bank_name']],
                'onboarding_status' => 'awaiting_whatsapp',
            ]);
        }

        $transaction->save();
    }

    protected function currentDraftSubscription(Client $client): ?Subscription
    {
        return $client->subscriptions()
            ->with('plan')
            ->where('status', '!=', 'active')
            ->latest('id')
            ->first()
            ?? $client->subscriptions()->with('plan')->latest('id')->first();
    }

    protected function currentDraftTransaction(Client $client, ?Subscription $subscription = null): ?Transaction
    {
        if ($subscription) {
            return $client->transactions()
                ->where('subscription_id', $subscription->id)
                ->latest('id')
                ->first();
        }

        return $client->transactions()
            ->whereIn('status', ['initiated', 'awaiting_transfer', 'awaiting_cash', 'awaiting_payment'])
            ->latest('id')
            ->first();
    }

    protected function bankOptions(): array
    {
        return [
            'attijariwafa' => 'Attijariwafa Bank',
            'cih' => 'CIH Bank',
            'bank_of_africa' => 'Bank of Africa',
            'chaabi' => 'Chaabi Bank',
            'cashplus' => 'Cash Plus',
            'saham' => 'Saham Bank',
        ];
    }

    protected function reference(string $prefix): string
    {
        return $prefix.'-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
    }

}
