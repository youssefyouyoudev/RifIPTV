<?php

namespace App\Http\Controllers;

use App\Mail\ClientSubscribedMail;
use App\Models\Client;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        $client = $this->clientFor($user->id);
        $familyOrder = ['sup' => 1, 'max' => 2, 'trex' => 3];
        $plans = Plan::query()
            ->whereIn('family_slug', array_keys($familyOrder))
            ->whereIn('duration_months', [3, 6, 12])
            ->get()
            ->sortBy(fn (Plan $plan) => sprintf(
                '%02d-%02d-%06d',
                $familyOrder[$plan->family_slug] ?? 99,
                $plan->duration_months,
                $plan->sort_order ?? 0
            ))
            ->values();
        $subscription = $this->currentDraftSubscription($client);
        $transaction = $this->currentDraftTransaction($client, $subscription);
        $planFamilies = $plans->groupBy(fn (Plan $plan) => $plan->family_slug ?: 'other');

        return view('onboarding.show', [
            'client' => $client,
            'plans' => $plans,
            'planFamilies' => $planFamilies,
            'selectedSubscription' => $subscription,
            'selectedTransaction' => $transaction,
            'bankOptions' => $this->bankOptions(),
        ]);
    }

    public function checkout(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $client = $this->clientFor($user->id);
        $subscription = $this->currentDraftSubscription($client);

        if (! $subscription?->plan) {
            return redirect()->route('onboarding.show');
        }

        $transaction = $this->currentDraftTransaction($client, $subscription);

        return view('checkout.show', [
            'client' => $client,
            'subscription' => $subscription,
            'selectedTransaction' => $transaction,
            'bankOptions' => $this->bankOptions(),
        ]);
    }

    public function storePlan(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        $user = $request->user();
        $client = $this->clientFor($user->id);
        $plan = Plan::query()->findOrFail($data['plan_id']);

        $subscription = $this->currentDraftSubscription($client);

        if (! $subscription || $subscription->status === 'active') {
            $subscription = new Subscription();
            $subscription->user_id = $user->id;
            $subscription->client_id = $client->id;
        }

        $subscription->plan_id = $plan->id;
        $subscription->status = 'awaiting_payment';
        $subscription->starts_at = null;
        $subscription->activated_at = null;
        $subscription->expires_at = null;
        $subscription->save();

        $client->update([
            'onboarding_status' => 'plan_selected',
        ]);

        return redirect()->route('checkout')->with('status', 'plan-selected');
    }

    public function storePayment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'payment_method' => ['required', 'in:card,bank_transfer'],
            'bank_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $client = $this->clientFor($user->id);
        $subscription = $this->currentDraftSubscription($client);

        abort_unless($subscription?->plan, 422, 'Plan selection is required before payment.');

        $bankOptions = $this->bankOptions();

        if ($data['payment_method'] === 'bank_transfer') {
            $request->validate([
                'bank_name' => ['required', 'in:'.implode(',', array_keys($bankOptions))],
            ]);
        }

        $transaction = $this->currentDraftTransaction($client, $subscription) ?? new Transaction();
        $transaction->user_id = $user->id;
        $transaction->client_id = $client->id;
        $transaction->subscription_id = $subscription->id;
        $transaction->amount_mad = $subscription->plan->price_mad;
        $transaction->reference = $transaction->reference ?: $this->reference('RIF');

        if ($data['payment_method'] === 'card') {
            $transaction->payment_method = 'card';
            $transaction->provider = 'Paddle';
            $transaction->bank_name = null;
            $transaction->status = 'initiated';

            $client->update([
                'preferred_payment_method' => 'card',
                'preferred_bank' => null,
                'onboarding_status' => 'card_checkout',
            ]);

            $subscription->update([
                'status' => 'awaiting_payment',
            ]);

            $transaction->save();

            return redirect()->route('checkout.card');
        }

        $transaction->payment_method = 'bank_transfer';
        $transaction->provider = 'National bank transfer';
        $transaction->bank_name = $bankOptions[$data['bank_name']];
        $transaction->status = 'awaiting_transfer';
        $transaction->save();

        $client->update([
            'preferred_payment_method' => 'bank_transfer',
            'preferred_bank' => $bankOptions[$data['bank_name']],
            'onboarding_status' => 'awaiting_whatsapp',
        ]);

        $subscription->update([
            'status' => 'awaiting_payment',
        ]);

        return redirect()->route('dashboard')->with('status', 'bank-transfer-waiting');
    }

    public function cardCheckout(Request $request): View
    {
        $client = $this->clientFor($request->user()->id);
        $subscription = $this->currentDraftSubscription($client);
        $transaction = $this->currentDraftTransaction($client, $subscription);

        abort_unless($subscription && $transaction && $transaction->payment_method === 'card', 404);

        return view('checkout.card', [
            'client' => $client,
            'subscription' => $subscription,
            'transaction' => $transaction,
        ]);
    }

    public function confirmCardCheckout(Request $request): RedirectResponse
    {
        $client = $this->clientFor($request->user()->id);
        $subscription = $this->currentDraftSubscription($client);
        $transaction = $this->currentDraftTransaction($client, $subscription);

        abort_unless($subscription && $transaction && $transaction->payment_method === 'card', 404);

        $transaction->update([
            'provider' => 'Paddle',
            'status' => 'paid',
            'paid_at' => now(),
            'verified_at' => now(),
        ]);

        $client->update([
            'preferred_payment_method' => 'card',
            'onboarding_status' => 'awaiting_support',
        ]);

        $subscription->update([
            'status' => 'awaiting_setup',
        ]);

        // Send email to all admins
        $admins = User::where('role', 'admin')->get();
        Mail::to($admins)->send(new ClientSubscribedMail($client));

        return redirect()->route('dashboard')->with('status', 'card-paid');
    }

    protected function clientFor(int $userId): Client
    {
        return Client::query()->firstOrCreate(
            ['user_id' => $userId],
            ['assigned_admin_id' => \App\Models\User::query()->where('role', 'admin')->value('id')]
        );
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
            ->whereIn('status', ['initiated', 'awaiting_transfer', 'awaiting_payment'])
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
