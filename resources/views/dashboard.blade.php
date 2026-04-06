@extends('layouts.app')

@section('title', __('site.dashboard.title'))
@section('meta_description', 'Rifi Media dashboard for service packages, payment follow-up, client guidance, and operational workflows.')
@section('meta_robots', 'noindex,nofollow')

@section('content')
@php
    $isAdmin = ($dashboardMode ?? 'client') === 'admin';
    $currency = __('portal.dashboard.shared.currency');
    $statusClasses = [
        'new' => 'status-warning',
        'plan_selected' => 'status-warning',
        'card_checkout' => 'status-warning',
        'awaiting_whatsapp' => 'status-warning',
        'awaiting_support' => 'status-warning',
        'assigned' => 'status-warning',
        'support_in_progress' => 'status-warning',
        'payment_confirmed' => 'status-success',
        'tutorial_sent' => 'status-success',
        'credentials_sent' => 'status-success',
        'completed' => 'status-success',
        'active' => 'status-success',
        'awaiting_payment' => 'status-warning',
        'awaiting_setup' => 'status-warning',
        'paid' => 'status-success',
        'initiated' => 'status-warning',
        'awaiting_transfer' => 'status-warning',
        'cancelled' => 'status-danger',
        'failed' => 'status-danger',
    ];
    $workflowStatusLabels = __('workflow.statuses');
    $latestPaymentMethod = $latestTransaction?->payment_method ?? $client?->preferred_payment_method ?? null;
    $clientStatus = $client?->onboarding_status ?? $subscription?->status ?? 'new';
    $formatClientPhone = function ($queueClient) {
        return $queueClient->phone ?: trim(($queueClient->user->phone_country_code ?? '').' '.($queueClient->user->phone_number ?? ''));
    };
    $bankActionState = function ($queueClient, $queueTransaction) {
        $paymentConfirmed = ($queueTransaction?->status === 'paid') || filled($queueTransaction?->proof_path);

        return [
            'assign' => ! $queueClient->assigned_admin_id,
            'start_support' => (bool) $queueClient->assigned_admin_id && ! $queueClient->support_started_at,
            'confirm_payment' => (bool) $queueClient->support_started_at && ! $paymentConfirmed,
            'send_tutorial' => $paymentConfirmed && ! $queueClient->setup_tutorial_sent_at,
            'save_credentials' => $paymentConfirmed && (bool) $queueClient->setup_tutorial_sent_at && ! $queueClient->completed_at,
            'mark_completed' => ! $queueClient->completed_at && filled($queueClient->iptv_username) && filled($queueClient->iptv_password),
            'payment_confirmed' => $paymentConfirmed,
        ];
    };
    $cardActionState = function ($queueClient, $queueTransaction) {
        $paymentConfirmed = $queueTransaction?->status === 'paid';

        return [
            'assign' => ! $queueClient->assigned_admin_id,
            'start_support' => (bool) $queueClient->assigned_admin_id && ! $queueClient->support_started_at,
            'send_tutorial' => $paymentConfirmed && (bool) $queueClient->support_started_at && ! $queueClient->setup_tutorial_sent_at,
            'save_credentials' => $paymentConfirmed && (bool) $queueClient->setup_tutorial_sent_at && ! $queueClient->completed_at,
            'mark_completed' => ! $queueClient->completed_at && filled($queueClient->iptv_username) && filled($queueClient->iptv_password),
            'payment_confirmed' => $paymentConfirmed,
        ];
    };
@endphp

<section class="section-space">
    <div class="container-xxl px-3 px-md-4 px-lg-5">
        @if (session('status'))
            <div class="alert alert-rif-success mb-4">
                {{ __('workflow.flash.'.session('status')) }}
            </div>
        @endif

        <div class="mesh-panel p-4 p-lg-5 mb-4">
            <div class="row g-4 align-items-end">
                <div class="col-xl-8">
                    <span class="section-kicker mb-3">{{ $isAdmin ? __('workflow.admin.eyebrow') : __('workflow.client.eyebrow') }}</span>
                    <h1 class="legal-title text-body-rif mb-3">
                        {{ $isAdmin ? __('workflow.admin.headline') : __('workflow.client.headline', ['name' => auth()->user()->name]) }}
                    </h1>
                    <p class="text-soft-rif fs-5 mb-0">
                        {{ $isAdmin ? __('workflow.admin.description') : __('workflow.client.description') }}
                    </p>
                </div>
                <div class="col-xl-4">
                    <div class="surface-card p-4 d-inline-flex align-items-center gap-3">
                        <span class="dashboard-pulse"></span>
                        <div>
                            <div class="small text-uppercase text-soft-rif fw-bold mb-1">{{ __('workflow.common.live_space') }}</div>
                            <div class="fw-semibold text-body-rif">
                                {{ $isAdmin ? __('workflow.admin.live_badge') : __('workflow.client.live_badge') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($isAdmin)
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-xl-3">
                    <article class="surface-card metric-card p-4 h-100">
                        <div class="small text-uppercase text-soft-rif fw-bold mb-3">{{ __('workflow.admin.metrics.total_revenue') }}</div>
                        <h3 class="dashboard-metric-value text-body-rif mb-2">{{ number_format($revenueTotal ?? 0, 2) }}</h3>
                        <div class="fw-semibold mb-3" style="color: var(--rif-green);">{{ $currency }}</div>
                        <p class="text-soft-rif mb-0">{{ __('workflow.admin.metrics.total_revenue_meta') }}</p>
                    </article>
                </div>
                <div class="col-md-6 col-xl-3">
                    <article class="surface-card metric-card p-4 h-100">
                        <div class="small text-uppercase text-soft-rif fw-bold mb-3">{{ __('workflow.admin.metrics.this_month') }}</div>
                        <h3 class="dashboard-metric-value text-body-rif mb-2">{{ number_format($monthlyRevenue ?? 0, 2) }}</h3>
                        <div class="fw-semibold mb-3" style="color: var(--rif-yellow);">{{ $currency }}</div>
                        <p class="text-soft-rif mb-0">{{ __('workflow.admin.metrics.this_month_meta') }}</p>
                    </article>
                </div>
                <div class="col-md-6 col-xl-3">
                    <article class="surface-card metric-card p-4 h-100">
                        <div class="small text-uppercase text-soft-rif fw-bold mb-3">{{ __('workflow.admin.metrics.supported_today') }}</div>
                        <h3 class="dashboard-metric-value text-body-rif mb-3">{{ $supportedTodayCount ?? 0 }}</h3>
                        <p class="text-soft-rif mb-0">{{ __('workflow.admin.metrics.supported_today_meta') }}</p>
                    </article>
                </div>
                <div class="col-md-6 col-xl-3">
                    <article class="surface-card metric-card p-4 h-100">
                        <div class="small text-uppercase text-soft-rif fw-bold mb-3">{{ __('workflow.admin.metrics.pending_transfers') }}</div>
                        <h3 class="dashboard-metric-value text-body-rif mb-3">{{ $pendingTransferCount ?? 0 }}</h3>
                        <p class="text-soft-rif mb-0">{{ __('workflow.admin.metrics.pending_transfers_meta') }}</p>
                    </article>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-xl-7">
                    <article class="surface-card p-4 p-lg-5 h-100">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                            <div>
                                <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-blue);">{{ __('workflow.admin.revenue.kicker') }}</div>
                                <h2 class="h2 text-body-rif mb-0">{{ __('workflow.admin.revenue.title') }}</h2>
                            </div>
                            <span class="status-badge status-success">{{ __('workflow.admin.revenue.badge') }}</span>
                        </div>
                        <div class="chart-shell"><canvas id="dashboardChart"></canvas></div>
                    </article>
                </div>
                <div class="col-xl-5">
                    <article class="surface-card p-4 p-lg-5 h-100">
                        <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-green);">{{ __('workflow.admin.admin_revenue.kicker') }}</div>
                        <h2 class="h2 text-body-rif mb-4">{{ __('workflow.admin.admin_revenue.title') }}</h2>
                        <div class="d-grid gap-3">
                            @foreach ($adminRevenueBreakdown ?? [] as $entry)
                                <div class="soft-card p-4 d-flex justify-content-between align-items-center gap-3">
                                    <div>
                                        <div class="fw-semibold text-body-rif">{{ $entry['name'] }}</div>
                                        <div class="text-soft-rif small">{{ __('workflow.admin.admin_revenue.clients', ['count' => $entry['clients']]) }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-body-rif">{{ number_format($entry['revenue'], 2) }} {{ $currency }}</div>
                                        <div class="text-soft-rif small">{{ __('workflow.admin.admin_revenue.revenue') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </article>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-xl-6">
                    <article class="surface-card overflow-hidden h-100">
                        <div class="p-4 p-lg-5 border-bottom">
                            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                                <div>
                                    <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-yellow);">{{ __('workflow.admin.bank_queue.kicker') }}</div>
                                    <h2 class="h2 text-body-rif mb-0">{{ __('workflow.admin.bank_queue.title') }}</h2>
                                </div>
                                <span class="status-badge status-warning">{{ __('workflow.admin.bank_queue.total', ['count' => ($bankTransferClients ?? collect())->count()]) }}</span>
                            </div>
                        </div>
                        <div class="table-shell">
                            <table class="table-rif table-rif-compact">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Package</th>
                                        <th>Status</th>
                                        <th>Next step</th>
                                        <th class="text-end">Open</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($bankTransferClients ?? [] as $queueClient)
                                        @php
                                            $queueStatus = $queueClient->workflow_status ?? ($queueClient->onboarding_status ?: 'new');
                                            $queueSubscription = $queueClient->latest_subscription;
                                            $queueTransaction = $queueClient->latest_transaction;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="fw-semibold text-body-rif">{{ $queueClient->user->name }}</div>
                                                <div class="small text-soft-rif">{{ $formatClientPhone($queueClient) }}</div>
                                                <div class="small text-soft-rif">{{ $queueTransaction?->bank_name ?: __('portal.dashboard.shared.not_set') }}</div>
                                            </td>
                                            <td>
                                                <div>{{ optional($queueSubscription?->plan)->name ?: __('portal.dashboard.shared.unknown_plan') }}</div>
                                                <div class="small text-soft-rif">{{ number_format((float) ($queueTransaction?->amount_mad ?? 0), 2) }} {{ $currency }}</div>
                                            </td>
                                            <td>
                                                <span class="status-badge {{ $statusClasses[$queueStatus] ?? 'status-warning' }}">{{ $workflowStatusLabels[$queueStatus] ?? ucfirst(str_replace('_', ' ', $queueStatus)) }}</span>
                                            </td>
                                            <td>
                                                <span class="admin-step-pill">{{ ($queueClient->next_action ?? null) === 'completed' ? ($workflowStatusLabels['completed'] ?? 'Completed') : __('workflow.admin.actions.'.($queueClient->next_action ?? 'mark_completed')) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.clients.show', $queueClient) }}" class="btn-rif-outline btn-rif-sm">Open</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-soft-rif py-4">{{ __('workflow.admin.bank_queue.empty') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </article>
                </div>
                <div class="col-xl-6">
                    <article class="surface-card overflow-hidden h-100">
                        <div class="p-4 p-lg-5 border-bottom">
                            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                                <div>
                                    <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-blue);">{{ __('workflow.admin.card_queue.kicker') }}</div>
                                    <h2 class="h2 text-body-rif mb-0">{{ __('workflow.admin.card_queue.title') }}</h2>
                                </div>
                                <span class="status-badge status-success">{{ __('workflow.admin.card_queue.total', ['count' => ($cardPaymentClients ?? collect())->count()]) }}</span>
                            </div>
                        </div>
                        <div class="table-shell">
                            <table class="table-rif table-rif-compact">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Package</th>
                                        <th>Status</th>
                                        <th>Next step</th>
                                        <th class="text-end">Open</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cardPaymentClients ?? [] as $queueClient)
                                        @php
                                            $queueStatus = $queueClient->workflow_status ?? ($queueClient->onboarding_status ?: 'new');
                                            $queueSubscription = $queueClient->latest_subscription;
                                            $queueTransaction = $queueClient->latest_transaction;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="fw-semibold text-body-rif">{{ $queueClient->user->name }}</div>
                                                <div class="small text-soft-rif">{{ $formatClientPhone($queueClient) }}</div>
                                                <div class="small text-soft-rif">{{ $queueTransaction?->provider ?: 'Paddle' }}</div>
                                            </td>
                                            <td>
                                                <div>{{ optional($queueSubscription?->plan)->name ?: __('portal.dashboard.shared.unknown_plan') }}</div>
                                                <div class="small text-soft-rif">{{ number_format((float) ($queueTransaction?->amount_mad ?? 0), 2) }} {{ $currency }}</div>
                                            </td>
                                            <td>
                                                <span class="status-badge {{ $statusClasses[$queueStatus] ?? 'status-warning' }}">{{ $workflowStatusLabels[$queueStatus] ?? ucfirst(str_replace('_', ' ', $queueStatus)) }}</span>
                                            </td>
                                            <td>
                                                <span class="admin-step-pill">{{ ($queueClient->next_action ?? null) === 'completed' ? ($workflowStatusLabels['completed'] ?? 'Completed') : __('workflow.admin.actions.'.($queueClient->next_action ?? 'mark_completed')) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.clients.show', $queueClient) }}" class="btn-rif-outline btn-rif-sm">Open</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-soft-rif py-4">{{ __('workflow.admin.card_queue.empty') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </article>
                </div>
            </div>

            <article class="surface-card overflow-hidden mb-4">
                <div class="p-4 p-lg-5 border-bottom">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                        <div>
                            <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-red);">{{ __('workflow.admin.clients_table.kicker') }}</div>
                            <h2 class="h2 text-body-rif mb-0">{{ __('workflow.admin.clients_table.title') }}</h2>
                        </div>
                        <span class="status-badge status-success">{{ __('workflow.admin.clients_table.total', ['count' => ($clients ?? collect())->count()]) }}</span>
                    </div>
                </div>
                <div class="table-shell">
                    <table class="table-rif">
                        <thead>
                            <tr>
                                <th>{{ __('workflow.admin.clients_table.columns.client') }}</th>
                                <th>{{ __('workflow.admin.clients_table.columns.phone') }}</th>
                                <th>{{ __('workflow.admin.clients_table.columns.payment') }}</th>
                                <th>{{ __('workflow.admin.clients_table.columns.bank') }}</th>
                                <th>{{ __('workflow.admin.clients_table.columns.assigned_to') }}</th>
                                <th>{{ __('workflow.admin.clients_table.columns.status') }}</th>
                                <th>{{ __('workflow.admin.clients_table.columns.proof') }}</th>
                                <th class="text-end">Open</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($clients ?? [] as $managedClient)
                                @php
                                    $managedStatus = $managedClient->onboarding_status ?: 'new';
                                    $managedTransaction = $managedClient->latest_transaction;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-body-rif">{{ $managedClient->user->name }}</div>
                                        <div class="small text-soft-rif">{{ $managedClient->user->email }}</div>
                                    </td>
                                    <td>{{ $managedClient->phone ?: $managedClient->user->phone_country_code.' '.$managedClient->user->phone_number }}</td>
                                    <td>{{ $managedTransaction?->payment_method === 'bank_transfer' ? __('workflow.common.bank_transfer') : __('workflow.common.card') }}</td>
                                    <td>{{ $managedTransaction?->bank_name ?: '-' }}</td>
                                    <td>{{ optional($managedClient->assignedAdmin)->name ?: __('portal.dashboard.shared.unassigned') }}</td>
                                    <td><span class="status-badge {{ $statusClasses[$managedStatus] ?? 'status-warning' }}">{{ $workflowStatusLabels[$managedStatus] ?? ucfirst(str_replace('_', ' ', $managedStatus)) }}</span></td>
                                    <td>
                                        @if ($managedTransaction?->proof_path)
                                            <a href="{{ asset($managedTransaction->proof_path) }}" target="_blank" class="nav-link-rif">{{ __('workflow.admin.clients_table.view_proof') }}</a>
                                        @else
                                            <span class="text-soft-rif">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.clients.show', $managedClient) }}" class="btn-rif-outline btn-rif-sm">Open</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-soft-rif py-4">{{ __('workflow.admin.clients_table.empty') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="surface-card overflow-hidden">
                <div class="p-4 p-lg-5 border-bottom">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                        <div>
                            <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-blue);">{{ __('workflow.admin.transactions.kicker') }}</div>
                            <h2 class="h2 text-body-rif mb-0">{{ __('workflow.admin.transactions.title') }}</h2>
                        </div>
                        <span class="status-badge status-success">{{ __('workflow.admin.transactions.total', ['count' => ($transactions ?? collect())->count()]) }}</span>
                    </div>
                </div>
                <div class="table-shell">
                    <table class="table-rif">
                        <thead>
                            <tr>
                                <th>{{ __('workflow.admin.transactions.columns.reference') }}</th>
                                <th>{{ __('workflow.admin.transactions.columns.client') }}</th>
                                <th>{{ __('workflow.admin.transactions.columns.amount') }}</th>
                                <th>{{ __('workflow.admin.transactions.columns.method') }}</th>
                                <th>{{ __('workflow.admin.transactions.columns.status') }}</th>
                                <th>{{ __('workflow.admin.transactions.columns.paid_at') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions ?? [] as $transaction)
                                @php($transactionStatus = $transaction->status ?: 'initiated')
                                <tr>
                                    <td>{{ $transaction->reference }}</td>
                                    <td>{{ optional($transaction->client->user)->name ?: __('portal.dashboard.shared.unknown_client') }}</td>
                                    <td>{{ number_format((float) $transaction->amount_mad, 2) }} {{ $currency }}</td>
                                    <td>{{ $transaction->payment_method === 'bank_transfer' ? __('workflow.common.bank_transfer') : __('workflow.common.card') }}</td>
                                    <td><span class="status-badge {{ $statusClasses[$transactionStatus] ?? 'status-warning' }}">{{ $workflowStatusLabels[$transactionStatus] ?? ucfirst(str_replace('_', ' ', $transactionStatus)) }}</span></td>
                                    <td>{{ optional($transaction->paid_at)->format('M d, Y H:i') ?: __('portal.dashboard.shared.status_pending') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-soft-rif py-4">{{ __('workflow.admin.transactions.empty') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        @else
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-xl-4">
                    <article class="surface-card metric-card p-4 h-100">
                        <div class="small text-uppercase text-soft-rif fw-bold mb-3">{{ __('workflow.client.metrics.plan') }}</div>
                        <h3 class="dashboard-metric-value text-body-rif mb-3">{{ optional($subscription?->plan)->name ?: __('workflow.client.metrics.pending') }}</h3>
                        <p class="text-soft-rif mb-0">{{ __('workflow.client.metrics.plan_meta') }}</p>
                    </article>
                </div>
                <div class="col-md-6 col-xl-4">
                    <article class="surface-card metric-card p-4 h-100">
                        <div class="small text-uppercase text-soft-rif fw-bold mb-3">{{ __('workflow.client.metrics.payment') }}</div>
                        <h3 class="dashboard-metric-value text-body-rif mb-3">{{ $latestPaymentMethod === 'bank_transfer' ? __('workflow.common.bank_transfer') : ($latestPaymentMethod === 'card' ? __('workflow.common.card') : __('workflow.client.metrics.pending')) }}</h3>
                        <p class="text-soft-rif mb-0">{{ $latestTransaction?->bank_name ?: ($latestTransaction?->provider ?: __('workflow.client.metrics.payment_meta')) }}</p>
                    </article>
                </div>
                <div class="col-md-12 col-xl-4">
                    <article class="surface-card metric-card p-4 h-100">
                        <div class="small text-uppercase text-soft-rif fw-bold mb-3">{{ __('workflow.client.metrics.status') }}</div>
                        <span class="status-badge {{ $statusClasses[$clientStatus] ?? 'status-warning' }}">{{ $workflowStatusLabels[$clientStatus] ?? ucfirst(str_replace('_', ' ', $clientStatus)) }}</span>
                        <p class="text-soft-rif mt-3 mb-0">{{ __('workflow.client.metrics.status_meta') }}</p>
                    </article>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-xl-7">
                    <article class="surface-card p-4 p-lg-5 h-100">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-4 mb-4">
                            <div>
                                <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-green);">{{ __('workflow.client.subscription.kicker') }}</div>
                                <h2 class="h2 text-body-rif mb-3">{{ optional($subscription?->plan)->name ?: __('workflow.client.subscription.waiting_title') }}</h2>
                                <p class="text-soft-rif mb-0">
                                    @if ($subscription && $subscription->status === 'active')
                                        {{ __('workflow.client.subscription.active_text', ['days' => $daysLeft ?? 0]) }}
                                    @else
                                        {{ __('workflow.client.subscription.waiting_text') }}
                                    @endif
                                </p>
                            </div>
                            <div class="d-flex align-items-start">
                                <a href="{{ route('onboarding.show') }}" class="btn-rif-secondary">{{ __('workflow.client.subscription.action') }}</a>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center gap-3 text-soft-rif small fw-semibold mb-2">
                            <span>{{ __('workflow.client.subscription.progress') }}</span>
                            <span>{{ $progressPercentage ?? 0 }}%</span>
                        </div>
                        <div class="progress-rif mb-4"><div class="progress-rif-bar" style="width: {{ $progressPercentage ?? 0 }}%"></div></div>

                        <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-blue);">{{ __('workflow.client.subscription.timeline_kicker') }}</div>
                        <p class="text-soft-rif mb-4">{{ __('workflow.client.subscription.timeline_text') }}</p>
                        <div class="chart-shell"><canvas id="dashboardChart"></canvas></div>
                    </article>
                </div>
                <div class="col-xl-5">
                    <div class="d-grid gap-4">
                        <article class="surface-card p-4 p-lg-5">
                            <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-yellow);">{{ __('workflow.client.setup.kicker') }}</div>
                            <h2 class="h2 text-body-rif mb-3">{{ __('workflow.client.setup.title') }}</h2>
                            <div class="d-grid gap-3">
                                <div class="soft-card p-4">
                                    <div class="small text-uppercase text-soft-rif fw-bold mb-2">{{ __('workflow.client.setup.assigned_admin') }}</div>
                                    <div class="fw-semibold text-body-rif">{{ $client?->assignedAdmin?->name ?: __('portal.dashboard.shared.unassigned') }}</div>
                                    <div class="text-soft-rif">{{ $client?->assignedAdmin?->email ?: __('workflow.client.setup.awaiting_admin') }}</div>
                                </div>
                                <div class="soft-card p-4">
                                    <div class="small text-uppercase text-soft-rif fw-bold mb-2">{{ __('workflow.client.setup.next_step') }}</div>
                                    <div class="fw-semibold text-body-rif">{{ $workflowStatusLabels[$clientStatus] ?? ucfirst(str_replace('_', ' ', $clientStatus)) }}</div>
                                    <div class="text-soft-rif">{{ __('workflow.client.setup.next_step_text') }}</div>
                                </div>
                                <div class="d-grid gap-3">
                                    @if ($latestTransaction?->payment_method === 'card' && $latestTransaction?->status === 'initiated')
                                        <a href="{{ route('checkout.card') }}" class="btn-rif-secondary w-100">{{ __('workflow.client.setup.continue_card') }}</a>
                                    @else
                                        <a href="{{ route('onboarding.show') }}" class="btn-rif-secondary w-100">{{ __('workflow.client.setup.manage_order') }}</a>
                                    @endif
                                    <a href="{{ config('seo.whatsapp_url', 'https://wa.me/212663323824') }}" class="btn-rif-outline w-100">{{ __('workflow.client.setup.whatsapp') }}</a>
                                </div>
                            </div>
                        </article>

                        <article class="dashboard-gradient p-4 p-lg-5">
                            <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-green);">{{ __('workflow.client.payment.kicker') }}</div>
                            <h2 class="h2 text-body-rif mb-3">{{ __('workflow.client.payment.title') }}</h2>
                            <div class="workflow-meta-grid">
                                <div class="workflow-meta-item"><span>{{ __('workflow.common.payment') }}</span><strong>{{ $latestPaymentMethod === 'bank_transfer' ? __('workflow.common.bank_transfer') : ($latestPaymentMethod === 'card' ? __('workflow.common.card') : '-') }}</strong></div>
                                <div class="workflow-meta-item"><span>{{ __('workflow.common.provider') }}</span><strong>{{ $latestTransaction?->provider ?: '-' }}</strong></div>
                                <div class="workflow-meta-item"><span>{{ __('workflow.common.bank') }}</span><strong>{{ $latestTransaction?->bank_name ?: '-' }}</strong></div>
                                <div class="workflow-meta-item"><span>{{ __('workflow.common.reference') }}</span><strong>{{ $latestTransaction?->reference ?: '-' }}</strong></div>
                            </div>
                            @if ($latestTransaction?->proof_path)
                                <a href="{{ asset($latestTransaction->proof_path) }}" target="_blank" class="btn-rif-outline w-100 mt-4">{{ __('workflow.client.payment.view_proof') }}</a>
                            @endif
                        </article>

                        @if ($client?->iptv_username && $client?->iptv_password)
                            <article class="surface-card p-4 p-lg-5">
                                <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-blue);">{{ __('workflow.client.credentials.kicker') }}</div>
                                <h2 class="h2 text-body-rif mb-3">{{ __('workflow.client.credentials.title') }}</h2>
                                <div class="workflow-meta-grid">
                                    <div class="workflow-meta-item"><span>{{ __('workflow.client.credentials.username') }}</span><strong>{{ $client->iptv_username }}</strong></div>
                                    <div class="workflow-meta-item"><span>{{ __('workflow.client.credentials.password') }}</span><strong>{{ $client->iptv_password }}</strong></div>
                                </div>
                            </article>
                        @endif
                    </div>
                </div>
            </div>

            <article class="surface-card overflow-hidden">
                <div class="p-4 p-lg-5 border-bottom">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                        <div>
                            <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-blue);">{{ __('workflow.client.history.kicker') }}</div>
                            <h2 class="h2 text-body-rif mb-0">{{ __('workflow.client.history.title') }}</h2>
                        </div>
                        <span class="status-badge status-success">{{ __('workflow.client.history.total', ['count' => ($transactions ?? collect())->count()]) }}</span>
                    </div>
                </div>
                <div class="table-shell">
                    <table class="table-rif">
                        <thead>
                            <tr>
                                <th>{{ __('workflow.client.history.columns.date') }}</th>
                                <th>{{ __('workflow.client.history.columns.plan') }}</th>
                                <th>{{ __('workflow.client.history.columns.amount') }}</th>
                                <th>{{ __('workflow.client.history.columns.method') }}</th>
                                <th>{{ __('workflow.client.history.columns.status') }}</th>
                                <th>{{ __('workflow.client.history.columns.proof') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions ?? [] as $transaction)
                                @php($transactionStatus = $transaction->status ?: 'initiated')
                                <tr>
                                    <td>{{ optional($transaction->paid_at)->format('M d, Y H:i') ?: __('portal.dashboard.shared.status_pending') }}</td>
                                    <td>{{ optional(optional($transaction->subscription)->plan)->name ?: __('portal.dashboard.client.plan_fallback') }}</td>
                                    <td>{{ number_format((float) $transaction->amount_mad, 2) }} {{ $currency }}</td>
                                    <td>{{ $transaction->payment_method === 'bank_transfer' ? __('workflow.common.bank_transfer') : __('workflow.common.card') }}</td>
                                    <td><span class="status-badge {{ $statusClasses[$transactionStatus] ?? 'status-warning' }}">{{ $workflowStatusLabels[$transactionStatus] ?? ucfirst(str_replace('_', ' ', $transactionStatus)) }}</span></td>
                                    <td>
                                        @if ($transaction->proof_path)
                                            <a href="{{ asset($transaction->proof_path) }}" target="_blank" class="nav-link-rif">{{ __('workflow.client.payment.view_proof') }}</a>
                                        @else
                                            <span class="text-soft-rif">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-soft-rif py-4">{{ __('workflow.client.history.empty') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartCanvas = document.getElementById('dashboardChart');
    if (!chartCanvas || typeof Chart === 'undefined') return;

    const isDark = (document.documentElement.getAttribute('data-theme') || 'dark') === 'dark';

    new Chart(chartCanvas, {
        type: 'line',
        data: {
            labels: @json($chartLabels ?? []),
            datasets: [{
                label: @json($isAdmin ? __('workflow.admin.revenue.dataset_label') : __('workflow.client.subscription.dataset_label')),
                data: @json($chartValues ?? []),
                borderColor: '#1E98D7',
                backgroundColor: 'rgba(122, 199, 12, 0.16)',
                fill: true,
                borderWidth: 3,
                tension: 0.35,
                pointBackgroundColor: '#FFD500',
                pointBorderColor: '#D6003A',
                pointRadius: 4
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: isDark ? '#F8FAFC' : '#0F172A' } } },
            scales: {
                x: { ticks: { color: isDark ? '#CBD5E1' : '#475569' }, grid: { color: isDark ? 'rgba(148, 163, 184, 0.12)' : 'rgba(148, 163, 184, 0.18)' } },
                y: { ticks: { color: isDark ? '#CBD5E1' : '#475569' }, grid: { color: isDark ? 'rgba(148, 163, 184, 0.12)' : 'rgba(148, 163, 184, 0.18)' } }
            }
        }
    });
});
</script>
@endpush


