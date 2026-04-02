@extends('layouts.app')

@section('title', __('workflow.onboarding.title'))
@section('meta_description', __('workflow.onboarding.meta_description'))
@section('meta_robots', 'noindex,nofollow')

@section('content')
@php
    $currentPlanId = old('plan_id', $selectedSubscription?->plan_id);
    $currentMethod = old('payment_method', $selectedTransaction?->payment_method);
    $selectedBankName = old('bank_name');
    $familyMeta = [
        'max-ott' => [
            'logo' => asset('images/plan-max-ott.png'),
            'accent' => 'family-max',
            'description' => __('workflow.onboarding.families.max_ott'),
        ],
        't-rex' => [
            'logo' => asset('images/plan-trex.png'),
            'accent' => 'family-trex',
            'description' => __('workflow.onboarding.families.t_rex'),
        ],
    ];
    $initialFamilySlug = old('plan_family', optional($selectedSubscription?->plan)->family_slug) ?: $planFamilies->keys()->first();
    $durationLabels = [
        1 => __('workflow.onboarding.durations.1'),
        3 => __('workflow.onboarding.durations.3'),
        6 => __('workflow.onboarding.durations.6'),
        12 => __('workflow.onboarding.durations.12'),
    ];
@endphp

<section class="section-space">
    <div class="container-xxl px-3 px-md-4 px-lg-5">
        @if (session('status'))
            <div class="alert alert-rif-success mb-4">{{ __('workflow.flash.'.session('status')) }}</div>
        @endif

        <div class="mesh-panel p-4 p-lg-5 mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-xl-8">
                    <span class="section-kicker mb-3">{{ __('workflow.onboarding.kicker') }}</span>
                    <h1 class="legal-title text-body-rif mb-3">{{ __('workflow.onboarding.headline') }}</h1>
                    <p class="text-soft-rif fs-5 mb-0">{{ __('workflow.onboarding.description') }}</p>
                </div>
                <div class="col-xl-4">
                    <div class="workflow-steps">
                        @foreach (__('workflow.onboarding.steps') as $step)
                            <div class="workflow-step">
                                <span class="workflow-step-index">{{ $loop->iteration }}</span>
                                <div>
                                    <div class="fw-semibold text-body-rif">{{ data_get($step, 'title') }}</div>
                                    <div class="text-soft-rif small">{{ data_get($step, 'text') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-7">
                <article class="surface-card p-4 p-lg-5 mb-4">
                    <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-blue);">{{ __('workflow.onboarding.plan_kicker') }}</div>
                    <h2 class="h2 text-body-rif mb-4">{{ __('workflow.onboarding.plan_title') }}</h2>
                    <form method="POST" action="{{ route('onboarding.plan') }}">
                        @csrf
                        <div class="pack-switcher" data-pack-switcher data-pack-default="{{ $initialFamilySlug }}">
                            <div class="pack-toggle-bar mb-4" role="tablist" aria-label="Plan families">
                                @foreach ($planFamilies as $familySlug => $familyPlans)
                                    @php
                                        $family = $familyPlans->first();
                                        $familyMetaItem = $familyMeta[$familySlug] ?? null;
                                    @endphp
                                    <button
                                        type="button"
                                        class="pack-toggle-btn {{ $familySlug === $initialFamilySlug ? 'is-active' : '' }}"
                                        data-pack-toggle="{{ $familySlug }}"
                                    >
                                        @if ($familyMetaItem)
                                            <span class="pack-toggle-logo-wrap">
                                                <img src="{{ $familyMetaItem['logo'] }}" alt="{{ $family->family }}" class="pack-toggle-logo">
                                            </span>
                                        @endif
                                        <span class="pack-toggle-copy">
                                            <strong>{{ $family->family }}</strong>
                                            <small>{{ $familyMetaItem['description'] ?? '' }}</small>
                                        </span>
                                    </button>
                                @endforeach
                            </div>

                            <input type="hidden" name="plan_family" value="{{ $initialFamilySlug }}" data-pack-family-input>

                            <div class="d-grid gap-4">
                            @foreach ($planFamilies as $familySlug => $familyPlans)
                                @php
                                    $family = $familyPlans->first();
                                    $familyMetaItem = $familyMeta[$familySlug] ?? null;
                                @endphp
                                <div class="plan-family-card {{ $familyMetaItem['accent'] ?? '' }} pack-panel {{ $familySlug === $initialFamilySlug ? 'is-active' : '' }}" data-pack-panel="{{ $familySlug }}">
                                    <div class="plan-family-header">
                                        <div class="plan-family-brand">
                                            @if ($familyMetaItem)
                                                <img src="{{ $familyMetaItem['logo'] }}" alt="{{ $family->family }}" class="plan-family-logo">
                                            @endif
                                            <div>
                                                <div class="small text-uppercase fw-bold text-soft-rif">{{ __('workflow.onboarding.family_label') }}</div>
                                                <h3 class="h3 text-body-rif mb-1">{{ $family->family }}</h3>
                                                <p class="text-soft-rif mb-0">{{ $familyMetaItem['description'] ?? '' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        @foreach ($familyPlans as $plan)
                                            <div class="col-md-6">
                                                @php
                                                    $badgeTone = $plan->is_featured ? 'popular' : ($plan->duration_months === 12 ? 'value' : 'default');
                                                @endphp
                                                <label
                                                    class="plan-selector-card {{ (int) $currentPlanId === (int) $plan->id ? 'is-selected' : '' }}"
                                                    data-select-card="plan"
                                                    data-plan-name="{{ $family->family }} - {{ $durationLabels[$plan->duration_months] ?? $plan->name }}"
                                                    data-plan-price="{{ number_format((float) $plan->price_mad, 2) }} {{ __('workflow.common.currency') }}"
                                                >
                                                    <input type="radio" name="plan_id" value="{{ $plan->id }}" class="visually-hidden" {{ (int) $currentPlanId === (int) $plan->id ? 'checked' : '' }}>
                                                    <span class="selection-indicator" aria-hidden="true">
                                                        <i data-lucide="check" class="icon-sm"></i>
                                                    </span>
                                                    @if ($plan->badge_text)
                                                        <span class="family-plan-badge family-plan-badge-{{ $badgeTone }} mb-3">{{ $plan->badge_text }}</span>
                                                    @endif
                                                    <span class="family-plan-label">{{ __('workflow.common.plan') }}</span>
                                                    <strong class="d-block h4 text-body-rif mt-2 mb-2">{{ $durationLabels[$plan->duration_months] ?? $plan->name }}</strong>
                                                    <div class="family-plan-subtitle">{{ __('workflow.onboarding.duration_caption', ['months' => $plan->duration_months]) }}</div>
                                                    <div class="display-price mt-3">{{ number_format((float) $plan->price_mad, 0) }} <span>{{ __('workflow.common.currency') }}</span></div>
                                                    <ul class="workflow-list workflow-list-check mt-3">
                                                        @foreach ($plan->features ?? [] as $feature)
                                                            <li>
                                                                <span class="family-plan-check">
                                                                    <i data-lucide="check" class="icon-sm"></i>
                                                                </span>
                                                                <span>{{ $feature }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                        @error('plan_id')
                            <p class="small text-rif-danger mt-3 mb-0">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="btn-rif-secondary mt-4">{{ __('workflow.onboarding.select_plan') }}</button>
                    </form>
                </article>

                <article class="surface-card p-4 p-lg-5">
                    <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-green);">{{ __('workflow.onboarding.payment_kicker') }}</div>
                    <h2 class="h2 text-body-rif mb-4">{{ __('workflow.onboarding.payment_title') }}</h2>
                    <form method="POST" action="{{ route('onboarding.payment') }}">
                        @csrf
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="payment-choice-card {{ $currentMethod === 'card' ? 'is-selected' : '' }}" data-select-card="payment" data-payment-label="{{ __('workflow.common.card') }}">
                                    <input type="radio" name="payment_method" value="card" class="visually-hidden" {{ $currentMethod === 'card' ? 'checked' : '' }}>
                                    <span class="selection-indicator" aria-hidden="true">
                                        <i data-lucide="check" class="icon-sm"></i>
                                    </span>
                                    <span class="payment-choice-badge">{{ __('workflow.common.card') }}</span>
                                    <strong class="d-block h5 text-body-rif mt-3 mb-2">{{ __('workflow.onboarding.card_title') }}</strong>
                                    <p class="text-soft-rif mb-0">{{ __('workflow.onboarding.card_text') }}</p>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="payment-choice-card {{ $currentMethod === 'bank_transfer' ? 'is-selected' : '' }}" data-select-card="payment" data-payment-label="{{ __('workflow.common.bank_transfer') }}">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="visually-hidden" {{ $currentMethod === 'bank_transfer' ? 'checked' : '' }}>
                                    <span class="selection-indicator" aria-hidden="true">
                                        <i data-lucide="check" class="icon-sm"></i>
                                    </span>
                                    <span class="payment-choice-badge">{{ __('workflow.common.bank_transfer') }}</span>
                                    <strong class="d-block h5 text-body-rif mt-3 mb-2">{{ __('workflow.onboarding.bank_title') }}</strong>
                                    <p class="text-soft-rif mb-0">{{ __('workflow.onboarding.bank_text') }}</p>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="bank_name" class="form-label form-label-rif">{{ __('workflow.onboarding.bank_label') }}</label>
                            <select id="bank_name" name="bank_name" class="form-select form-control-rif">
                                <option value="">{{ __('workflow.onboarding.bank_placeholder') }}</option>
                                @foreach ($bankOptions as $bankKey => $bankName)
                                    <option value="{{ $bankKey }}" {{ $selectedBankName === $bankKey ? 'selected' : '' }}>{{ $bankName }}</option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
                            @enderror
                            @error('bank_name')
                                <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn-rif-secondary">{{ __('workflow.onboarding.continue_payment') }}</button>
                    </form>
                </article>
            </div>

            <div class="col-xl-5">
                <article class="surface-card p-4 p-lg-5 mb-4">
                    <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-yellow);">{{ __('workflow.onboarding.summary_kicker') }}</div>
                    <h2 class="h2 text-body-rif mb-4">{{ __('workflow.onboarding.summary_title') }}</h2>
                    <div class="workflow-meta-grid">
                        <div class="workflow-meta-item"><span>{{ __('workflow.common.plan') }}</span><strong data-summary-plan>{{ optional($selectedSubscription?->plan)->family ? optional($selectedSubscription?->plan)->family.' - '.($durationLabels[optional($selectedSubscription?->plan)->duration_months] ?? optional($selectedSubscription?->plan)->name) : '-' }}</strong></div>
                        <div class="workflow-meta-item"><span>{{ __('workflow.common.amount') }}</span><strong data-summary-price>{{ number_format((float) ($selectedSubscription?->plan?->price_mad ?? 0), 2) }} {{ __('workflow.common.currency') }}</strong></div>
                        <div class="workflow-meta-item"><span>{{ __('workflow.common.payment') }}</span><strong data-summary-payment>{{ $currentMethod ? __('workflow.common.'.$currentMethod) : '-' }}</strong></div>
                        <div class="workflow-meta-item"><span>{{ __('workflow.common.reference') }}</span><strong>{{ $selectedTransaction?->reference ?: '-' }}</strong></div>
                    </div>
                </article>

                <article class="dashboard-gradient p-4 p-lg-5">
                    <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-green);">{{ __('workflow.onboarding.next_kicker') }}</div>
                    <h2 class="h2 text-body-rif mb-3">{{ __('workflow.onboarding.next_title') }}</h2>
                    <p class="text-soft-rif mb-4">{{ __('workflow.onboarding.next_text') }}</p>
                    <div class="d-grid gap-3">
                        <a href="https://wa.me/212600000000" class="btn-rif-outline w-100">{{ __('workflow.onboarding.whatsapp') }}</a>
                        <a href="{{ route('dashboard') }}" class="btn-rif-secondary w-100">{{ __('workflow.onboarding.dashboard') }}</a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-pack-switcher]').forEach(function (switcher) {
        const buttons = switcher.querySelectorAll('[data-pack-toggle]');
        const panels = switcher.querySelectorAll('[data-pack-panel]');
        const familyInput = switcher.querySelector('[data-pack-family-input]');

        const activate = function (slug) {
            buttons.forEach(function (button) {
                button.classList.toggle('is-active', button.getAttribute('data-pack-toggle') === slug);
            });

            panels.forEach(function (panel) {
                panel.classList.toggle('is-active', panel.getAttribute('data-pack-panel') === slug);
            });

            if (familyInput) {
                familyInput.value = slug;
            }
        };

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                activate(button.getAttribute('data-pack-toggle'));
            });
        });
    });

    const updateSelectedCards = function (selector) {
        document.querySelectorAll(selector).forEach(function (card) {
            const input = card.querySelector('input[type="radio"]');

            if (!input) {
                return;
            }

            card.classList.toggle('is-selected', input.checked);
        });
    };

    const summaryPlan = document.querySelector('[data-summary-plan]');
    const summaryPrice = document.querySelector('[data-summary-price]');
    const summaryPayment = document.querySelector('[data-summary-payment]');

    document.querySelectorAll('[data-select-card="plan"] input[type="radio"]').forEach(function (input) {
        input.addEventListener('change', function () {
            updateSelectedCards('[data-select-card="plan"]');

            const card = input.closest('[data-select-card="plan"]');

            if (card && summaryPlan) {
                summaryPlan.textContent = card.getAttribute('data-plan-name') || '-';
            }

            if (card && summaryPrice) {
                summaryPrice.textContent = card.getAttribute('data-plan-price') || '-';
            }
        });
    });

    document.querySelectorAll('[data-select-card="payment"] input[type="radio"]').forEach(function (input) {
        input.addEventListener('change', function () {
            updateSelectedCards('[data-select-card="payment"]');

            const card = input.closest('[data-select-card="payment"]');

            if (card && summaryPayment) {
                summaryPayment.textContent = card.getAttribute('data-payment-label') || '-';
            }
        });
    });

    updateSelectedCards('[data-select-card="plan"]');
    updateSelectedCards('[data-select-card="payment"]');
});
</script>
@endpush
