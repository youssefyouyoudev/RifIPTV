@extends('layouts.app')

@section('title', __('workflow.onboarding.payment_title').' | Rifi Media')
@section('meta_description', __('workflow.onboarding.next_text'))
@section('meta_robots', 'noindex,nofollow')

@section('content')
@php
    $locale = app()->getLocale();
    $currentMethod = old('payment_method', $selectedTransaction?->payment_method);
    $selectedBankName = old('bank_name');
    $familyMeta = [
        'sup' => 'Basic / SUP',
        'max' => 'Advanced / MAX',
        'trex' => 'Premium / TREX',
    ];
    $durationLabels = [
        3 => __('workflow.onboarding.durations.3'),
        6 => __('workflow.onboarding.durations.6'),
        12 => __('workflow.onboarding.durations.12'),
    ];
    $summaryPlan = ($familyMeta[$subscription->plan->family_slug] ?? strtoupper((string) $subscription->plan->family_slug)).' - '.($durationLabels[$subscription->plan->duration_months] ?? $subscription->plan->name);
    $paymentIntro = match ($locale) {
        'ar' => 'اختر طريقة الدفع المناسبة لإكمال الطلب. البطاقة تنتقل إلى Paddle، أما التحويل البنكي فيُتابَع يدويًا مع فريق الدعم عبر واتساب.',
        'fr' => 'Choisissez votre methode de paiement. La carte continue avec Paddle, tandis que le virement est suivi manuellement via WhatsApp.',
        'es' => 'Elige tu metodo de pago. La tarjeta continua con Paddle, mientras que la transferencia bancaria se sigue manualmente por WhatsApp.',
        default => 'Choose your payment method. Card payments continue with Paddle, while bank transfers move to manual WhatsApp follow-up.',
    };
    $stagePill = match ($locale) {
        'ar' => 'الخطوة 2 من 2',
        'fr' => 'Etape 2 sur 2',
        'es' => 'Paso 2 de 2',
        default => 'Step 2 of 2',
    };
    $journeySteps = [
        [
            'title' => __('workflow.onboarding.plan_title'),
            'text' => match ($locale) {
                'ar' => 'تم حفظ الباقة المختارة ويمكنك العودة إليها إذا أردت التعديل.',
                'fr' => 'Votre formule est enregistree et vous pouvez encore la modifier.',
                'es' => 'Tu paquete ya esta guardado y aun puedes cambiarlo.',
                default => 'Your package is saved and can still be changed.',
            },
            'state' => 'complete',
        ],
        [
            'title' => __('workflow.onboarding.payment_title'),
            'text' => match ($locale) {
                'ar' => 'اختر الآن البطاقة أو التحويل البنكي لإكمال الطلب.',
                'fr' => 'Choisissez maintenant carte ou virement bancaire pour finaliser la demande.',
                'es' => 'Ahora elige tarjeta o transferencia bancaria para terminar el pedido.',
                default => 'Choose card or bank transfer to finish the order.',
            },
            'state' => 'active',
        ],
    ];
    $backLabel = match ($locale) {
        'ar' => 'العودة لتغيير الباقة',
        'fr' => 'Retour aux formules',
        'es' => 'Volver a los paquetes',
        default => 'Back to packages',
    };
    $lastStepTitle = match ($locale) {
        'ar' => 'الخطوة الأخيرة قبل المتابعة',
        'fr' => 'Une derniere etape avant de continuer',
        'es' => 'Un ultimo paso antes de continuar',
        default => 'One more step before you continue',
    };
@endphp

<section class="section-space">
    <div class="container-xxl px-3 px-md-4 px-lg-5">
        @if (session('status'))
            <div class="alert alert-rif-success mb-4">{{ __('workflow.flash.'.session('status')) }}</div>
        @endif

        <div class="mesh-panel p-4 p-lg-5 mb-4">
            <div class="workflow-topbar">
                <div class="workflow-topbar-copy">
                    <span class="section-kicker mb-3">{{ __('workflow.onboarding.payment_kicker') }}</span>
                    <h1 class="legal-title text-body-rif mb-3">{{ __('workflow.onboarding.payment_title') }}</h1>
                    <p class="text-soft-rif fs-5 mb-0">{{ $paymentIntro }}</p>
                </div>
                <div class="workflow-step-stack">
                    @foreach ($journeySteps as $step)
                        <div class="workflow-step-card {{ $step['state'] === 'active' ? 'is-active' : '' }} {{ $step['state'] === 'complete' ? 'is-complete' : '' }}">
                            <span class="workflow-step-index">{{ $loop->iteration }}</span>
                            <div>
                                <div class="fw-semibold text-body-rif">{{ $step['title'] }}</div>
                                <div class="text-soft-rif small">{{ $step['text'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="workflow-layout">
            <aside class="workflow-sidebar">
                <article class="surface-card workflow-sidebar-card p-4">
                    <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-yellow);">{{ __('workflow.onboarding.summary_kicker') }}</div>
                    <h2 class="h3 text-body-rif mb-3">{{ __('workflow.onboarding.summary_title') }}</h2>
                    <div class="workflow-meta-grid workflow-meta-grid-single">
                        <div class="workflow-meta-item">
                            <span>{{ __('workflow.common.plan') }}</span>
                            <strong>{{ $summaryPlan }}</strong>
                        </div>
                        <div class="workflow-meta-item">
                            <span>{{ __('workflow.common.amount') }}</span>
                            <strong>{{ number_format((float) $subscription->plan->price_mad, 2) }} {{ __('workflow.common.currency') }}</strong>
                        </div>
                        <div class="workflow-meta-item">
                            <span>{{ __('workflow.common.reference') }}</span>
                            <strong>{{ $selectedTransaction?->reference ?: '-' }}</strong>
                        </div>
                    </div>
                </article>

                <article class="dashboard-gradient p-4">
                    <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-green);">{{ __('workflow.onboarding.next_kicker') }}</div>
                    <h2 class="h3 text-body-rif mb-3">{{ $lastStepTitle }}</h2>
                    <p class="text-soft-rif mb-4">{{ __('workflow.onboarding.next_text') }}</p>
                    <div class="d-grid gap-3">
                        <a href="{{ route('onboarding.show') }}" class="btn-rif-outline w-100">{{ $backLabel }}</a>
                        <a href="{{ config('seo.whatsapp_url', 'https://wa.me/212663323824') }}" class="btn-rif-secondary w-100">{{ __('workflow.onboarding.whatsapp') }}</a>
                    </div>
                </article>
            </aside>

            <div class="workflow-main">
                <article class="surface-card p-4 p-lg-5">
                    <div class="workflow-section-head">
                        <div>
                            <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-green);">{{ __('workflow.onboarding.payment_kicker') }}</div>
                            <h2 class="h2 text-body-rif mb-2">{{ __('workflow.onboarding.payment_title') }}</h2>
                            <p class="text-soft-rif mb-0">{{ $paymentIntro }}</p>
                        </div>
                        <span class="workflow-pill">
                            <i data-lucide="credit-card" class="icon-sm"></i>
                            <span>{{ $stagePill }}</span>
                        </span>
                    </div>

                    <div class="workflow-journey-note mb-4">
                        <div class="workflow-journey-note-icon">
                            <i data-lucide="badge-check" class="icon-sm"></i>
                        </div>
                        <div>
                            <strong class="d-block text-body-rif mb-1">
                                @if ($locale === 'ar')
                                    اختر وسيلة الدفع
                                @elseif ($locale === 'fr')
                                    Choisissez votre moyen de paiement
                                @elseif ($locale === 'es')
                                    Elige tu metodo de pago
                                @else
                                    Choose your payment method
                                @endif
                            </strong>
                            <span class="text-soft-rif small">
                                @if ($locale === 'ar')
                                    البطاقة تفتح خطوة الدفع السريع، والتحويل البنكي يفعّل متابعة يدوية مع الدعم.
                                @elseif ($locale === 'fr')
                                    La carte ouvre un paiement rapide, tandis que le virement active un suivi manuel.
                                @elseif ($locale === 'es')
                                    La tarjeta abre un pago rapido, mientras que la transferencia activa un seguimiento manual.
                                @else
                                    Card opens the fast checkout flow, while transfer starts manual support follow-up.
                                @endif
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('onboarding.payment') }}" data-payment-form>
                        @csrf
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="payment-choice-card payment-choice-card-large {{ $currentMethod === 'card' ? 'is-selected' : '' }}" data-select-card="payment" data-payment-label="{{ __('workflow.common.card') }}">
                                    <input type="radio" name="payment_method" value="card" class="visually-hidden" {{ $currentMethod === 'card' ? 'checked' : '' }}>
                                    <span class="selection-indicator" aria-hidden="true"><i data-lucide="check" class="icon-sm"></i></span>
                                    <span class="payment-choice-badge">{{ __('workflow.common.card') }}</span>
                                    <strong class="d-block h4 text-body-rif mt-3 mb-2">{{ __('workflow.onboarding.card_title') }}</strong>
                                    <p class="text-soft-rif mb-3">{{ __('workflow.onboarding.card_text') }}</p>
                                    <div class="workflow-feature-pills">
                                        <span class="workflow-feature-pill">Paddle</span>
                                        <span class="workflow-feature-pill">{{ $locale === 'ar' ? 'سريع' : ($locale === 'fr' ? 'Rapide' : ($locale === 'es' ? 'Rapido' : 'Fast')) }}</span>
                                        <span class="workflow-feature-pill">{{ $locale === 'ar' ? 'آمن' : ($locale === 'fr' ? 'Securise' : ($locale === 'es' ? 'Seguro' : 'Secure')) }}</span>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="payment-choice-card payment-choice-card-large {{ $currentMethod === 'bank_transfer' ? 'is-selected' : '' }}" data-select-card="payment" data-payment-label="{{ __('workflow.common.bank_transfer') }}">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="visually-hidden" {{ $currentMethod === 'bank_transfer' ? 'checked' : '' }}>
                                    <span class="selection-indicator" aria-hidden="true"><i data-lucide="check" class="icon-sm"></i></span>
                                    <span class="payment-choice-badge payment-choice-badge-bank">{{ __('workflow.common.bank_transfer') }}</span>
                                    <strong class="d-block h4 text-body-rif mt-3 mb-2">{{ __('workflow.onboarding.bank_title') }}</strong>
                                    <p class="text-soft-rif mb-3">{{ __('workflow.onboarding.bank_text') }}</p>
                                    <div class="workflow-feature-pills">
                                        <span class="workflow-feature-pill">{{ $locale === 'ar' ? 'بنوك محلية' : ($locale === 'fr' ? 'Banques locales' : ($locale === 'es' ? 'Bancos locales' : 'Local banks')) }}</span>
                                        <span class="workflow-feature-pill">WhatsApp</span>
                                        <span class="workflow-feature-pill">{{ $locale === 'ar' ? 'مراجعة يدوية' : ($locale === 'fr' ? 'Revue manuelle' : ($locale === 'es' ? 'Revision manual' : 'Manual review')) }}</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="workflow-bank-panel {{ $currentMethod === 'bank_transfer' ? 'is-visible' : '' }}" data-bank-panel>
                            <label for="bank_name" class="form-label form-label-rif">{{ __('workflow.onboarding.bank_label') }}</label>
                            <select id="bank_name" name="bank_name" class="form-select form-control-rif">
                                <option value="">{{ __('workflow.onboarding.bank_placeholder') }}</option>
                                @foreach ($bankOptions as $bankKey => $bankName)
                                    <option value="{{ $bankKey }}" {{ $selectedBankName === $bankKey ? 'selected' : '' }}>{{ $bankName }}</option>
                                @endforeach
                            </select>
                            <p class="text-soft-rif small mt-3 mb-0">
                                @if ($locale === 'ar')
                                    بعد اختيار البنك، سيتابع فريق الدعم طلبك يدويًا عبر واتساب حتى يتم تأكيد التحويل.
                                @elseif ($locale === 'fr')
                                    Apres avoir choisi la banque, le support suivra votre demande manuellement via WhatsApp.
                                @elseif ($locale === 'es')
                                    Despues de elegir el banco, el soporte seguira tu solicitud manualmente por WhatsApp.
                                @else
                                    After choosing your bank, support will follow up manually with you on WhatsApp.
                                @endif
                            </p>
                        </div>

                        @error('payment_method')
                            <p class="small text-rif-danger mt-3 mb-0">{{ $message }}</p>
                        @enderror
                        @error('bank_name')
                            <p class="small text-rif-danger mt-2 mb-0">{{ $message }}</p>
                        @enderror

                        <div class="workflow-submit-bar mt-4">
                            <div class="text-soft-rif small" data-payment-summary-copy>
                                {{ $currentMethod === 'bank_transfer' ? __('workflow.onboarding.bank_text') : __('workflow.onboarding.card_text') }}
                            </div>
                            <button type="submit" class="btn-rif-secondary">{{ __('workflow.onboarding.continue_payment') }}</button>
                        </div>
                    </form>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bankPanel = document.querySelector('[data-bank-panel]');
    const summaryCopy = document.querySelector('[data-payment-summary-copy]');

    const updateSelectedCards = function () {
        document.querySelectorAll('[data-select-card="payment"]').forEach(function (card) {
            const input = card.querySelector('input[type="radio"]');
            if (!input) {
                return;
            }
            card.classList.toggle('is-selected', input.checked);
        });
    };

    const syncPaymentUi = function (value) {
        if (bankPanel) {
            bankPanel.classList.toggle('is-visible', value === 'bank_transfer');
        }

        if (summaryCopy) {
            const activeCard = document.querySelector('[data-select-card="payment"].is-selected');
            if (activeCard) {
                summaryCopy.textContent = activeCard.querySelector('p')?.textContent || '';
            }
        }
    };

    document.querySelectorAll('[data-select-card="payment"] input[type="radio"]').forEach(function (input) {
        input.addEventListener('change', function () {
            updateSelectedCards();
            syncPaymentUi(input.value);
        });
    });

    updateSelectedCards();
    syncPaymentUi(document.querySelector('[data-select-card="payment"] input[type="radio"]:checked')?.value || '');
});
</script>
@endpush
