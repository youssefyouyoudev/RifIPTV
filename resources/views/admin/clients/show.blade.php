@extends('layouts.app')

@section('title', ($clientRecord->user->name ?? 'Client').' | Admin')
@section('meta_description', 'Admin client workflow detail view')
@section('meta_robots', 'noindex,nofollow')

@section('content')
@php
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
        'awaiting_cash' => 'status-warning',
        'cancelled' => 'status-danger',
        'failed' => 'status-danger',
    ];
    $workflowStatusLabels = __('workflow.statuses');
    $clientStatus = $clientRecord->onboarding_status ?: 'new';
    $clientPhone = $clientRecord->phone ?: trim(($clientRecord->user->phone_country_code ?? '').' '.($clientRecord->user->phone_number ?? ''));
    $checkoutStatusClasses = [
        'complete' => 'status-success',
        'pending' => 'status-warning',
        'plan_selected' => 'status-warning',
        'not_started' => 'status-danger',
    ];
    $paymentMethodLabel = $latestTransaction?->payment_method === 'bank_transfer'
        ? __('workflow.common.bank_transfer')
        : ($latestTransaction?->payment_method === 'cash' ? __('workflow.common.cash') : __('workflow.common.card'));
    $providerLabel = $latestTransaction?->provider ?: ($latestTransaction?->payment_method === 'card' ? 'Paddle' : '-');
    $stepOrder = [
        'assign' => __('workflow.admin.actions.assign'),
        'save_order' => 'Complete order setup',
        'start_support' => __('workflow.admin.actions.start_support'),
        'confirm_payment' => __('workflow.admin.actions.confirm_payment'),
        'send_tutorial' => __('workflow.admin.actions.send_tutorial'),
        'save_credentials' => __('workflow.admin.actions.save_credentials'),
        'mark_completed' => __('workflow.admin.actions.mark_completed'),
    ];
@endphp

<section class="section-space">
    <div class="container-xxl px-3 px-md-4 px-lg-5">
        @if (session('status'))
            <div class="alert alert-rif-success mb-4">{{ __('workflow.flash.'.session('status')) }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-rif-danger mb-4">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mesh-panel p-4 p-lg-5 mb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-end">
                <div>
                    <span class="section-kicker mb-3">{{ __('workflow.admin.clients_table.kicker') }}</span>
                    <h1 class="legal-title text-body-rif mb-2">{{ $clientRecord->user->name }}</h1>
                    <p class="text-soft-rif fs-5 mb-0">A full operational view for this client, including package, payment, delivery, and the next required action.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="status-badge {{ $statusClasses[$clientStatus] ?? 'status-warning' }}">{{ $statusLabel }}</span>
                    <span class="status-badge {{ $checkoutStatusClasses[$checkoutState] ?? 'status-warning' }}">{{ $checkoutLabel }}</span>
                    @if ($latestTransaction?->status)
                        <span class="status-badge {{ $statusClasses[$latestTransaction->status] ?? 'status-warning' }}">{{ $workflowStatusLabels[$latestTransaction->status] ?? ucfirst(str_replace('_', ' ', $latestTransaction->status)) }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-4">
                <div class="d-grid gap-4">
                    <article class="surface-card p-4">
                        <div class="small text-uppercase text-soft-rif fw-bold mb-3">Client profile</div>
                        <div class="admin-detail-list">
                            <div><span>Name</span><strong>{{ $clientRecord->user->name }}</strong></div>
                            <div><span>Email</span><strong>{{ $clientRecord->user->email }}</strong></div>
                            <div><span>Phone</span><strong>{{ $clientPhone ?: __('portal.dashboard.shared.not_set') }}</strong></div>
                            <div><span>City</span><strong>{{ $clientRecord->city ?: __('portal.dashboard.shared.not_set') }}</strong></div>
                            <div><span>Assigned to</span><strong>{{ $clientRecord->assignedAdmin?->name ?: __('portal.dashboard.shared.unassigned') }}</strong></div>
                        </div>
                    </article>

                    <article class="surface-card p-4">
                        <div class="small text-uppercase text-soft-rif fw-bold mb-3">Package & payment</div>
                        <div class="admin-detail-list">
                            <div><span>Package</span><strong>{{ $latestSubscription?->plan?->name ?: __('portal.dashboard.shared.unknown_plan') }}</strong></div>
                            <div><span>Amount</span><strong>{{ number_format((float) ($latestTransaction?->amount_mad ?? 0), 2) }} {{ $currency }}</strong></div>
                            <div><span>Checkout</span><strong>{{ $checkoutLabel }}</strong></div>
                            <div><span>Payment type</span><strong>{{ $paymentMethodLabel }}</strong></div>
                            <div><span>Bank</span><strong>{{ $latestTransaction?->bank_name ?: '-' }}</strong></div>
                            <div><span>Provider</span><strong>{{ $providerLabel }}</strong></div>
                            <div><span>Reference</span><strong>{{ $latestTransaction?->reference ?: '-' }}</strong></div>
                        </div>
                        @if ($latestTransaction?->proof_path)
                            @php
                                $extension = pathinfo($latestTransaction->proof_path, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp
                            @if ($isImage)
                                <img src="/public/{{ $latestTransaction->proof_path }}" alt="Payment Proof" class="img-fluid mt-4" data-bs-toggle="modal" data-bs-target="#proofModal" style="cursor: pointer;">
                            @else
                                <p class="mt-4 text-soft-rif">File type: {{ strtoupper($extension) }}</p>
                                <a href="/public/{{ $latestTransaction->proof_path }}" target="_blank" class="btn-rif-outline w-100 mt-2">{{ __('workflow.admin.clients_table.view_proof') }}</a>
                            @endif
                        @endif
                    </article>

                    <article class="surface-card p-4">
                        <div class="small text-uppercase text-soft-rif fw-bold mb-3">Delivery details</div>
                        <div class="admin-detail-list">
                            <div><span>Tutorial sent</span><strong>{{ $clientRecord->setup_tutorial_sent_at ? $clientRecord->setup_tutorial_sent_at->format('M d, Y H:i') : '-' }}</strong></div>
                            <div><span>Credentials sent</span><strong>{{ $clientRecord->credentials_sent_at ? $clientRecord->credentials_sent_at->format('M d, Y H:i') : '-' }}</strong></div>
                            <div><span>Username</span><strong>{{ $clientRecord->iptv_username ?: '-' }}</strong></div>
                            <div><span>Password</span><strong>{{ $clientRecord->iptv_password ?: '-' }}</strong></div>
                            <div><span>Completed</span><strong>{{ $clientRecord->completed_at ? $clientRecord->completed_at->format('M d, Y H:i') : '-' }}</strong></div>
                        </div>
                    </article>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="d-grid gap-4">
                    <article class="surface-card p-4 p-lg-5">
                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
                            <div>
                                <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-blue);">Workflow status</div>
                                <h2 class="h2 text-body-rif mb-0">Step-by-step handling</h2>
                            </div>
                            <span class="admin-step-pill admin-step-pill-active">{{ $stepOrder[$nextAction] ?? 'Completed' }}</span>
                        </div>
                        <div class="admin-progress-grid">
                            @foreach ($stepOrder as $stepKey => $stepLabel)
                                @php
                                    $done = !($actionState[$stepKey] ?? false) && in_array($stepKey, ['assign', 'save_order', 'start_support', 'confirm_payment', 'send_tutorial', 'save_credentials', 'mark_completed'], true);
                                    $active = ($actionState[$stepKey] ?? false);
                                @endphp
                                <div class="admin-progress-item {{ $active ? 'is-active' : ($done ? 'is-done' : '') }}">
                                    <span class="admin-progress-dot"></span>
                                    <strong>{{ $stepLabel }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </article>

                    <article class="surface-card p-4 p-lg-5">
                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
                            <div>
                                <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-green);">Action center</div>
                                <h2 class="h2 text-body-rif mb-0">Handle this client</h2>
                            </div>
                            <a href="{{ route('dashboard') }}" class="btn-rif-outline">Back to dashboard</a>
                        </div>

                        <div class="d-grid gap-4">
                            <form method="POST" action="{{ route('admin.clients.workflow', $clientRecord) }}" class="admin-action-panel admin-action-panel-form">
                                @csrf
                                <input type="hidden" name="action" value="update_profile">
                                <div class="w-100">
                                    <h3 class="h5 text-body-rif mb-2">Edit client details</h3>
                                    <p class="text-soft-rif mb-3">All admins can review and update the client profile, assignment, and support notes from here.</p>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label form-label-rif">Name</label>
                                            <input type="text" name="client_name" class="form-control form-control-rif" value="{{ old('client_name', $clientRecord->user->name) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label form-label-rif">Email</label>
                                            <input type="email" name="email" class="form-control form-control-rif" value="{{ old('email', $clientRecord->user->email) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label form-label-rif">Phone</label>
                                            <input type="text" name="phone" class="form-control form-control-rif" value="{{ old('phone', $clientPhone) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label form-label-rif">City</label>
                                            <input type="text" name="city" class="form-control form-control-rif" value="{{ old('city', $clientRecord->city) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label form-label-rif">Assigned admin</label>
                                            <select name="assigned_admin_id" class="form-select form-control-rif">
                                                @foreach ($adminUsers as $adminUser)
                                                    <option value="{{ $adminUser->id }}" @selected((int) old('assigned_admin_id', $clientRecord->assigned_admin_id ?: auth()->id()) === (int) $adminUser->id)>{{ $adminUser->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label form-label-rif">Support notes</label>
                                            <textarea name="support_notes" rows="4" class="form-control form-control-rif">{{ old('support_notes', $clientRecord->support_notes) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn-rif-secondary">Save profile</button>
                            </form>

                            <form method="POST" action="{{ route('admin.clients.workflow', $clientRecord) }}" class="admin-action-panel admin-action-panel-form">
                                @csrf
                                <input type="hidden" name="action" value="save_order">
                                <div class="w-100">
                                    <h3 class="h5 text-body-rif mb-2">Complete order on behalf of client</h3>
                                    <p class="text-soft-rif mb-3">If the client stopped before choosing a plan or payment method, an admin can complete the order setup here and continue the workflow.</p>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label form-label-rif">Plan</label>
                                            <select name="plan_id" class="form-select form-control-rif">
                                                @foreach ($availablePlans as $plan)
                                                    <option value="{{ $plan->id }}" @selected((int) old('plan_id', $latestSubscription?->plan_id) === (int) $plan->id)>
                                                        {{ $plan->name }} - {{ (int) $plan->price_mad }} {{ $currency }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label form-label-rif">Payment method</label>
                                            <select name="payment_method" class="form-select form-control-rif" data-admin-payment-method>
                                                <option value="cash" @selected(old('payment_method', $latestTransaction?->payment_method) === 'cash')>{{ __('workflow.common.cash') }}</option>
                                                <option value="bank_transfer" @selected(old('payment_method', $latestTransaction?->payment_method) === 'bank_transfer')>{{ __('workflow.common.bank_transfer') }}</option>
                                                <option value="card" @selected(old('payment_method', $latestTransaction?->payment_method) === 'card')>{{ __('workflow.common.card') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6" data-admin-bank-wrap>
                                            <label class="form-label form-label-rif">Bank</label>
                                            <select name="bank_name" class="form-select form-control-rif">
                                                <option value="">Choose bank</option>
                                                @foreach ($bankOptions as $bankKey => $bankLabel)
                                                    <option value="{{ $bankKey }}" @selected(old('bank_name') === $bankKey || ($latestTransaction?->payment_method === 'bank_transfer' && $latestTransaction?->bank_name === $bankLabel))>{{ $bankLabel }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn-rif-secondary">Save order details</button>
                            </form>

                            @if ($actionState['assign'])
                                <form method="POST" action="{{ route('admin.clients.workflow', $clientRecord) }}" class="admin-action-panel">
                                    @csrf
                                    <input type="hidden" name="action" value="assign">
                                    <div>
                                        <h3 class="h5 text-body-rif mb-2">{{ __('workflow.admin.actions.assign') }}</h3>
                                        <p class="text-soft-rif mb-0">Take over this client or move the ownership to your queue so the next actions stay together.</p>
                                    </div>
                                    <button type="submit" class="btn-rif-secondary">{{ __('workflow.admin.actions.assign') }}</button>
                                </form>
                            @endif

                            @if ($actionState['start_support'])
                                <form method="POST" action="{{ route('admin.clients.workflow', $clientRecord) }}" class="admin-action-panel">
                                    @csrf
                                    <input type="hidden" name="action" value="start_support">
                                    <div>
                                        <h3 class="h5 text-body-rif mb-2">{{ __('workflow.admin.actions.start_support') }}</h3>
                                        <p class="text-soft-rif mb-0">Mark support as started so the client record moves into active handling.</p>
                                    </div>
                                    <button type="submit" class="btn-rif-secondary">{{ __('workflow.admin.actions.start_support') }}</button>
                                </form>
                            @endif

                            @if ($actionState['confirm_payment'])
                                <form method="POST" action="{{ route('admin.clients.workflow', $clientRecord) }}" enctype="multipart/form-data" class="admin-action-panel admin-action-panel-form">
                                    @csrf
                                    <input type="hidden" name="action" value="confirm_payment">
                                    <div class="w-100">
                                        <h3 class="h5 text-body-rif mb-2">{{ __('workflow.admin.actions.confirm_payment') }}</h3>
                                        <p class="text-soft-rif mb-3">Upload payment proof if available, then mark the transfer as confirmed.</p>
                                        <label class="form-label form-label-rif mb-2">{{ __('workflow.admin.actions.payment_proof') }}</label>
                                        <input type="file" name="payment_proof" class="form-control form-control-rif">
                                    </div>
                                    <button type="submit" class="btn-rif-secondary">{{ __('workflow.admin.actions.confirm_payment') }}</button>
                                </form>
                            @endif

                            @if ($actionState['send_tutorial'])
                                <form method="POST" action="{{ route('admin.clients.workflow', $clientRecord) }}" class="admin-action-panel">
                                    @csrf
                                    <input type="hidden" name="action" value="send_tutorial">
                                    <div>
                                        <h3 class="h5 text-body-rif mb-2">{{ __('workflow.admin.actions.send_tutorial') }}</h3>
                                        <p class="text-soft-rif mb-0">Confirm that the setup guide or tutorial has been shared with the client.</p>
                                    </div>
                                    <button type="submit" class="btn-rif-secondary">{{ __('workflow.admin.actions.send_tutorial') }}</button>
                                </form>
                            @endif

                            @if ($actionState['save_credentials'])
                                <form method="POST" action="{{ route('admin.clients.workflow', $clientRecord) }}" class="admin-action-panel admin-action-panel-form">
                                    @csrf
                                    <input type="hidden" name="action" value="save_credentials">
                                    <div class="w-100">
                                        <h3 class="h5 text-body-rif mb-2">{{ __('workflow.admin.actions.save_credentials') }}</h3>
                                        <p class="text-soft-rif mb-3">Save the final credentials before closing the delivery cycle.</p>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label form-label-rif">{{ __('workflow.admin.actions.iptv_username') }}</label>
                                                <input type="text" name="iptv_username" class="form-control form-control-rif" value="{{ $clientRecord->iptv_username }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label form-label-rif">{{ __('workflow.admin.actions.iptv_password') }}</label>
                                                <input type="text" name="iptv_password" class="form-control form-control-rif" value="{{ $clientRecord->iptv_password }}">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn-rif-secondary">{{ __('workflow.admin.actions.save_credentials') }}</button>
                                </form>
                            @endif

                            @if ($actionState['mark_completed'])
                                <form method="POST" action="{{ route('admin.clients.workflow', $clientRecord) }}" class="admin-action-panel">
                                    @csrf
                                    <input type="hidden" name="action" value="mark_completed">
                                    <div>
                                        <h3 class="h5 text-body-rif mb-2">{{ __('workflow.admin.actions.mark_completed') }}</h3>
                                        <p class="text-soft-rif mb-0">Close the workflow and move this client into a completed active state.</p>
                                    </div>
                                    <button type="submit" class="btn-rif-secondary">{{ __('workflow.admin.actions.mark_completed') }}</button>
                                </form>
                            @endif

                            @if (! collect($actionState)->except('payment_confirmed')->contains(true))
                                <div class="soft-card p-4 text-soft-rif">
                                    All required actions are complete for this client. You can review the history and payment proof from this page.
                                </div>
                            @endif
                        </div>
                    </article>

                    <article class="surface-card overflow-hidden">
                        <div class="p-4 p-lg-5 border-bottom">
                            <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-yellow);">Transaction history</div>
                            <h2 class="h2 text-body-rif mb-0">Recent client activity</h2>
                        </div>
                        <div class="table-shell">
                            <table class="table-rif">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Paid at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($clientRecord->transactions->sortByDesc('id') as $transaction)
                                        @php($transactionStatus = $transaction->status ?: 'initiated')
                                        <tr>
                                            <td>{{ $transaction->reference }}</td>
                                            <td>{{ number_format((float) $transaction->amount_mad, 2) }} {{ $currency }}</td>
                                            <td>{{ $transaction->payment_method === 'bank_transfer' ? __('workflow.common.bank_transfer') : ($transaction->payment_method === 'cash' ? __('workflow.common.cash') : __('workflow.common.card')) }}</td>
                                            <td><span class="status-badge {{ $statusClasses[$transactionStatus] ?? 'status-warning' }}">{{ $workflowStatusLabels[$transactionStatus] ?? ucfirst(str_replace('_', ' ', $transactionStatus)) }}</span></td>
                                            <td>{{ optional($transaction->paid_at)->format('M d, Y H:i') ?: __('portal.dashboard.shared.status_pending') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-soft-rif py-4">{{ __('workflow.admin.transactions.empty') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
</section>

@if ($latestTransaction?->proof_path)
<!-- Proof Modal -->
<div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proofModalLabel">Payment Proof</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="/public/{{ $latestTransaction->proof_path }}" alt="Payment Proof" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const paymentMethod = document.querySelector('[data-admin-payment-method]');
    const bankWrap = document.querySelector('[data-admin-bank-wrap]');

    if (!paymentMethod || !bankWrap) {
        return;
    }

    const syncBankVisibility = function () {
        bankWrap.style.display = paymentMethod.value === 'bank_transfer' ? '' : 'none';
    };

    paymentMethod.addEventListener('change', syncBankVisibility);
    syncBankVisibility();
});
</script>
@endpush
