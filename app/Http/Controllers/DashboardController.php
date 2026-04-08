<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard($user);
        }

        return $this->clientDashboard($user);
    }

    public function showAdminClient(Request $request, Client $client): View|RedirectResponse
    {
        $admin = $request->user();

        abort_unless($admin->isAdmin(), 403);

        $client->load([
            'user',
            'assignedAdmin',
            'subscriptions.plan',
            'transactions.subscription.plan',
            'transactions.assignedAdmin',
        ]);

        $client->latest_subscription = $client->subscriptions->sortByDesc('id')->first();
        $client->latest_transaction = $client->transactions->sortByDesc('id')->first();

        if ($client->assigned_admin_id && (int) $client->assigned_admin_id !== (int) $admin->id) {
            return redirect()->route('dashboard');
        }

        $actionState = $this->actionStateForClient($client);

        return view('admin.clients.show', [
            'clientRecord' => $client,
            'latestSubscription' => $client->latest_subscription,
            'latestTransaction' => $client->latest_transaction,
            'actionState' => $actionState,
            'nextAction' => $this->nextActionLabel($actionState),
            'statusLabel' => __('workflow.statuses.'.$this->clientWorkflowStatus($client)),
        ]);
    }

    protected function adminDashboard(User $user): View
    {
        $clients = Client::query()
            ->with([
                'user',
                'assignedAdmin',
                'subscriptions.plan',
                'transactions.assignedAdmin',
            ])
            ->latest('id')
            ->get()
            ->each(function (Client $client): void {
                $client->latest_subscription = $client->subscriptions->sortByDesc('id')->first();
                $client->latest_transaction = $client->transactions->sortByDesc('id')->first();
                $client->action_state = $this->actionStateForClient($client);
                $client->next_action = $this->nextActionLabel($client->action_state);
                $client->workflow_status = $this->clientWorkflowStatus($client);
            });

        $managedClients = $clients
            ->filter(fn (Client $client) => (int) $client->assigned_admin_id === (int) $user->id)
            ->values();

        $actionableClients = $clients
            ->filter(function (Client $client) use ($user): bool {
                if ($client->completed_at) {
                    return false;
                }

                return ! $client->assigned_admin_id || (int) $client->assigned_admin_id === (int) $user->id;
            })
            ->values();

        $bankTransferClients = $actionableClients
            ->filter(fn (Client $client) => in_array(optional($client->latest_transaction)->payment_method, ['bank_transfer', 'cash'], true))
            ->values();

        $cardPaymentClients = $actionableClients
            ->filter(fn (Client $client) => optional($client->latest_transaction)->payment_method === 'card')
            ->values();

        $subscriptions = Subscription::query()
            ->with(['client.user', 'plan'])
            ->whereHas('client', fn ($query) => $query->where('assigned_admin_id', $user->id))
            ->latest('id')
            ->get();

        $transactions = Transaction::query()
            ->with(['client.user', 'subscription.plan', 'assignedAdmin'])
            ->whereHas('client', fn ($query) => $query->where('assigned_admin_id', $user->id))
            ->latest('id')
            ->get();

        $paidTransactions = $transactions->where('status', 'paid');
        $revenueTotal = (float) $paidTransactions->sum('amount_mad');
        $monthlyRevenue = (float) $paidTransactions
            ->filter(fn (Transaction $transaction) => optional($transaction->paid_at)->isCurrentMonth())
            ->sum('amount_mad');

        $supportedTodayCount = $managedClients->filter(function (Client $client): bool {
            return optional($client->last_contacted_at)->isToday()
                || optional($client->support_started_at)->isToday()
                || optional($client->completed_at)->isToday();
        })->count();

        $monthOffsets = collect(range(5, 0));
        $chartLabels = $monthOffsets
            ->map(fn (int $monthsAgo) => Carbon::now()->subMonths($monthsAgo)->format('M'))
            ->values();

        $chartValues = $monthOffsets
            ->map(function (int $monthsAgo) use ($paidTransactions) {
                $month = Carbon::now()->subMonths($monthsAgo)->format('Y-m');

                return (float) $paidTransactions
                    ->filter(fn (Transaction $transaction) => optional($transaction->paid_at)?->format('Y-m') === $month)
                    ->sum('amount_mad');
            })
            ->values();

        $adminRevenueBreakdown = User::query()
            ->where('role', 'admin')
            ->with(['managedClients.transactions'])
            ->get()
            ->map(function (User $admin) {
                $revenue = $admin->managedClients
                    ->flatMap(fn (Client $client) => $client->transactions)
                    ->where('status', 'paid')
                    ->sum('amount_mad');

                return [
                    'name' => $admin->name,
                    'clients' => $admin->managedClients->count(),
                    'revenue' => (float) $revenue,
                ];
            });

        return view('dashboard', [
            'dashboardMode' => 'admin',
            'revenueTotal' => $revenueTotal,
            'monthlyRevenue' => $monthlyRevenue,
            'supportedTodayCount' => $supportedTodayCount,
            'pendingTransferCount' => $bankTransferClients->count(),
            'cardPaymentCount' => $cardPaymentClients->count(),
            'activeSubscriptionsCount' => $subscriptions->where('status', 'active')->count(),
            'clients' => $managedClients,
            'bankTransferClients' => $bankTransferClients,
            'cardPaymentClients' => $cardPaymentClients,
            'subscriptions' => $subscriptions,
            'transactions' => $transactions,
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
            'adminRevenueBreakdown' => $adminRevenueBreakdown,
        ]);
    }

    protected function clientDashboard(User $user): View
    {
        $client = $user->clientProfile()->with('assignedAdmin')->first();

        $subscriptions = Subscription::query()
            ->with(['plan', 'transactions'])
            ->when(
                $client,
                fn ($query) => $query->where('client_id', $client->id),
                fn ($query) => $query->where('user_id', $user->id)
            )
            ->latest('id')
            ->get();

        $subscription = $subscriptions->firstWhere('status', 'active') ?? $subscriptions->first();

        $transactions = Transaction::query()
            ->with(['subscription.plan', 'assignedAdmin'])
            ->when(
                $client,
                fn ($query) => $query->where('client_id', $client->id),
                fn ($query) => $query->where('user_id', $user->id)
            )
            ->latest('id')
            ->get();

        $latestTransaction = $transactions->first();

        $daysLeft = $subscription?->expires_at?->isFuture()
            ? max(now()->diffInDays($subscription->expires_at), 0)
            : 0;

        $progressPercentage = 0;

        if ($subscription?->starts_at && $subscription?->expires_at && $subscription->expires_at->greaterThan($subscription->starts_at)) {
            $totalCycle = max($subscription->starts_at->diffInDays($subscription->expires_at), 1);
            $elapsed = min($subscription->starts_at->diffInDays(now()), $totalCycle);
            $progressPercentage = (int) round(($elapsed / $totalCycle) * 100);
        }

        $monthOffsets = collect(range(5, 0));
        $chartLabels = $monthOffsets
            ->map(fn (int $monthsAgo) => Carbon::now()->subMonths($monthsAgo)->format('M'))
            ->values();

        $chartValues = $monthOffsets
            ->map(function (int $monthsAgo) use ($transactions) {
                $month = Carbon::now()->subMonths($monthsAgo)->format('Y-m');

                return (float) $transactions
                    ->where('status', 'paid')
                    ->filter(fn (Transaction $transaction) => optional($transaction->paid_at)?->format('Y-m') === $month)
                    ->sum('amount_mad');
            })
            ->values();

        return view('dashboard', [
            'dashboardMode' => 'client',
            'client' => $client,
            'subscription' => $subscription,
            'transactions' => $transactions,
            'latestTransaction' => $latestTransaction,
            'daysLeft' => $daysLeft,
            'progressPercentage' => $progressPercentage,
            'chartLabels' => $chartLabels,
            'chartValues' => $chartValues,
        ]);
    }

    protected function clientWorkflowStatus(Client $client): string
    {
        return $client->onboarding_status ?: 'new';
    }

    protected function actionStateForClient(Client $client): array
    {
        $transaction = $client->latest_transaction;
        $paymentMethod = $transaction?->payment_method;

        if (in_array($paymentMethod, ['bank_transfer', 'cash'], true)) {
            $paymentConfirmed = ($transaction?->status === 'paid') || filled($transaction?->proof_path);

            return [
                'assign' => ! $client->assigned_admin_id,
                'start_support' => (bool) $client->assigned_admin_id && ! $client->support_started_at,
                'confirm_payment' => (bool) $client->support_started_at && ! $paymentConfirmed,
                'send_tutorial' => $paymentConfirmed && ! $client->setup_tutorial_sent_at,
                'save_credentials' => $paymentConfirmed && (bool) $client->setup_tutorial_sent_at && ! $client->completed_at,
                'mark_completed' => ! $client->completed_at && filled($client->iptv_username) && filled($client->iptv_password),
                'payment_confirmed' => $paymentConfirmed,
            ];
        }

        $paymentConfirmed = $transaction?->status === 'paid';

        return [
            'assign' => ! $client->assigned_admin_id,
            'start_support' => (bool) $client->assigned_admin_id && ! $client->support_started_at,
            'confirm_payment' => false,
            'send_tutorial' => $paymentConfirmed && (bool) $client->support_started_at && ! $client->setup_tutorial_sent_at,
            'save_credentials' => $paymentConfirmed && (bool) $client->setup_tutorial_sent_at && ! $client->completed_at,
            'mark_completed' => ! $client->completed_at && filled($client->iptv_username) && filled($client->iptv_password),
            'payment_confirmed' => $paymentConfirmed,
        ];
    }

    protected function nextActionLabel(array $actionState): string
    {
        return match (true) {
            $actionState['assign'] => 'assign',
            $actionState['start_support'] => 'start_support',
            $actionState['confirm_payment'] => 'confirm_payment',
            $actionState['send_tutorial'] => 'send_tutorial',
            $actionState['save_credentials'] => 'save_credentials',
            $actionState['mark_completed'] => 'mark_completed',
            default => 'completed',
        };
    }
}
