<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Transaction;
use App\Services\OperationalAlertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
            'action' => ['required', 'in:assign,start_support,confirm_payment,send_tutorial,save_credentials,mark_completed'],
        ])['action'];

        if ($action !== 'assign' && $client->assigned_admin_id && $client->assigned_admin_id !== $admin->id) {
            abort(403);
        }

        $subscription = $client->subscriptions()->with('plan')->latest('id')->first();
        $transaction = $client->transactions()->latest('id')->first();

        match ($action) {
            'assign' => $this->assignClient($client, $admin->id),
            'start_support' => $this->startSupport($client, $admin->id),
            'confirm_payment' => $this->confirmPayment($request, $client, $transaction, $subscription, $admin->id),
            'send_tutorial' => $this->sendTutorial($client, $admin->id),
            'save_credentials' => $this->saveCredentials($request, $client, $admin->id),
            'mark_completed' => $this->markCompleted($client, $subscription, $admin->id),
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

}
