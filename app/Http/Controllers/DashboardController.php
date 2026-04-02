<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
            ->filter(fn (Client $client) => optional($client->latest_transaction)->payment_method === 'bank_transfer')
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
}
