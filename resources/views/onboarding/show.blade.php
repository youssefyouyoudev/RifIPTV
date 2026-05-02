@extends('layouts.app')

@section('title', __('workflow.onboarding.title'))
@section('meta_description', __('workflow.onboarding.meta_description'))
@section('meta_robots', 'noindex,nofollow')

@section('content')
@php
    $locale = app()->getLocale();
    $currentPlanId = old('plan_id', $selectedSubscription?->plan_id);
    $selectedPlan = $plans->firstWhere('id', (int) $currentPlanId) ?? $selectedSubscription?->plan;
    $familyMeta = [
        'smart_tv' => [
            'display_name' => 'Smart TV',
            'icon' => 'tv-2',
            'accent' => 'family-sup',
            'description' => match ($locale) {
                'ar' => 'باقات Smart TV بثلاث مدد واضحة مع إعداد موجه ومتابعة عملية.',
                'fr' => 'Des packs Smart TV avec 3 durees claires, une installation guidee et un suivi pratique.',
                'es' => 'Packs Smart TV con 3 duraciones claras, instalacion guiada y seguimiento practico.',
                default => 'Smart TV packs with 3 clear durations, guided setup, and practical follow-up.',
            },
        ],
        'sup' => [
            'display_name' => 'SUP',
            'icon' => 'sparkles',
            'accent' => 'family-sup',
            'description' => match ($locale) {
                'ar' => 'الخيار الأساسي للبداية الواضحة والسريعة.',
                'fr' => 'Le pack SUP reste disponible uniquement en 12 mois.',
                'es' => 'El pack SUP sigue disponible solo por 12 meses.',
                default => 'The SUP pack is currently available in a single 12-month duration.',
            },
        ],
        'max' => [
            'display_name' => 'Advanced / MAX',
            'icon' => 'shield',
            'accent' => 'family-max',
            'description' => match ($locale) {
                'ar' => 'خيار متوازن للعملاء الذين يحتاجون دعماً أقوى.',
                'fr' => 'Une formule equilibree pour un accompagnement plus solide.',
                'es' => 'Una opcion equilibrada para clientes que necesitan mas apoyo.',
                default => 'A balanced option for clients who need stronger support.',
            },
        ],
        'trex' => [
            'display_name' => 'Premium / TREX',
            'icon' => 'crown',
            'accent' => 'family-trex',
            'description' => match ($locale) {
                'ar' => 'الخيار الأقوى للدعم المتقدم والمتابعة الأطول.',
                'fr' => 'La formule la plus complete pour un suivi plus avance.',
                'es' => 'La opcion mas completa para un seguimiento mas avanzado.',
                default => 'The strongest option for longer, more advanced support.',
            },
        ],
    ];
    $initialFamilySlug = old('plan_family', optional($selectedPlan)->family_slug) ?: ($planFamilies->keys()->first() ?? 'sup');
    $durationLabels = [
        3 => __('workflow.onboarding.durations.3'),
        6 => __('workflow.onboarding.durations.6'),
        12 => __('workflow.onboarding.durations.12'),
    ];
    $summaryName = $selectedPlan ? $selectedPlan->name : '-';
    $stageIntro = match ($locale) {
        'ar' => 'ابدأ باختيار خط الخدمة المناسب، ثم حدّد المدة التي تناسبك، وبعدها تنتقل مباشرة إلى صفحة Checkout لاختيار طريقة الدفع.',
        'fr' => 'Choisissez d abord la ligne de service, puis la duree adaptee, avant de passer a la page checkout.',
        'es' => 'Elige primero la linea de servicio, luego la duracion adecuada, y despues continua al checkout.',
        default => 'Choose the service line first, then the right duration, and continue directly to checkout.',
    };
    $stagePill = match ($locale) {
        'ar' => 'الخطوة 1 من 2',
        'fr' => 'Etape 1 sur 2',
        'es' => 'Paso 1 de 2',
        default => 'Step 1 of 2',
    };
    $submitNote = match ($locale) {
        'ar' => 'بعد حفظ الباقة المختارة ستنتقل مباشرة إلى صفحة Checkout لاختيار طريقة الدفع.',
        'fr' => 'Apres avoir enregistre la formule, vous passerez directement a la page checkout.',
        'es' => 'Despues de guardar el paquete, pasaras directamente a la pagina de checkout.',
        default => 'After saving the selected package, you will continue directly to checkout.',
    };
    $journeySteps = [
        [
            'title' => __('workflow.onboarding.plan_title'),
            'text' => match ($locale) {
                'ar' => 'اختر الباقة والمدة المناسبة قبل الانتقال للدفع.',
                'fr' => 'Choisissez la formule et la duree adaptee avant le paiement.',
                'es' => 'Selecciona el paquete y la duracion adecuada antes del pago.',
                default => 'Pick the package and duration before payment.',
            },
            'state' => 'active',
        ],
        [
            'title' => __('workflow.onboarding.payment_title'),
            'text' => match ($locale) {
                'ar' => 'في الصفحة التالية تختار البطاقة أو التحويل البنكي.',
                'fr' => 'Dans la page suivante, choisissez carte ou virement bancaire.',
                'es' => 'En la siguiente pagina eliges tarjeta o transferencia bancaria.',
                default => 'In the next screen, choose card or bank transfer.',
            },
            'state' => 'pending',
        ],
    ];
@endphp

<section class="section-space">
    <div class="container-xxl px-3 px-md-4 px-lg-5">
        @if (session('status'))
            <div class="alert alert-rif-success mb-4">{{ __('workflow.flash.'.session('status')) }}</div>
        @endif

        <div class="mesh-panel p-4 p-lg-5 mb-4">
            <div class="workflow-topbar">
                <div class="workflow-topbar-copy">
                    <span class="section-kicker mb-3">{{ __('workflow.onboarding.kicker') }}</span>
                    <h1 class="legal-title text-body-rif mb-3">{{ __('workflow.onboarding.headline') }}</h1>
                    <p class="text-soft-rif fs-5 mb-0">{{ __('workflow.onboarding.description') }}</p>
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
                            <strong data-summary-plan>{{ $summaryName }}</strong>
                        </div>
                        <div class="workflow-meta-item">
                            <span>{{ __('workflow.common.amount') }}</span>
                            <strong data-summary-price>{{ $selectedPlan ? number_format((float) $selectedPlan->price_mad, 2).' '.__('workflow.common.currency') : '- '.__('workflow.common.currency') }}</strong>
                        </div>
                        <div class="workflow-meta-item">
                            <span>{{ __('workflow.common.payment') }}</span>
                            <strong>{{ __('workflow.onboarding.payment_title') }}</strong>
                        </div>
                    </div>
                </article>

                <article class="dashboard-gradient p-4">
                    <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-green);">{{ __('workflow.onboarding.next_kicker') }}</div>
                    <h2 class="h3 text-body-rif mb-3">{{ __('workflow.onboarding.next_title') }}</h2>
                    <p class="text-soft-rif mb-4">{{ __('workflow.onboarding.next_text') }}</p>
                    <div class="d-grid gap-3">
                        <a href="{{ config('seo.whatsapp_url', 'https://wa.me/212663323824') }}" class="btn-rif-outline w-100">{{ __('workflow.onboarding.whatsapp') }}</a>
                        <a href="{{ route('dashboard') }}" class="btn-rif-secondary w-100">{{ __('workflow.onboarding.dashboard') }}</a>
                    </div>
                </article>
            </aside>

            <div class="workflow-main">
                <article class="surface-card p-4 p-lg-5">
                    <div class="workflow-section-head">
                        <div>
                            <div class="small text-uppercase fw-bold mb-2" style="color: var(--rif-blue);">{{ __('workflow.onboarding.plan_kicker') }}</div>
                            <h2 class="h2 text-body-rif mb-2">{{ __('workflow.onboarding.plan_title') }}</h2>
                            <p class="text-soft-rif mb-0">{{ $stageIntro }}</p>
                        </div>
                        <span class="workflow-pill">
                            <i data-lucide="layers-3" class="icon-sm"></i>
                            <span>{{ $stagePill }}</span>
                        </span>
                    </div>

                    <div class="workflow-journey-note mb-4">
                        <div class="workflow-journey-note-icon">
                            <i data-lucide="sparkles" class="icon-sm"></i>
                        </div>
                        <div>
                            <strong class="d-block text-body-rif mb-1">
                                @if ($locale === 'ar')
                                    اختر الباقة أولًا
                                @elseif ($locale === 'fr')
                                    Choisissez d abord la formule
                                @elseif ($locale === 'es')
                                    Elige primero el paquete
                                @else
                                    Start by choosing your package
                                @endif
                            </strong>
                            <span class="text-soft-rif small">{{ $submitNote }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('onboarding.plan') }}">
                        @csrf
                        <div class="pack-switcher" data-pack-switcher data-pack-default="{{ $initialFamilySlug }}">
                            <div class="pack-toggle-bar mb-4" role="tablist" aria-label="Plan families">
                                @foreach ($planFamilies as $familySlug => $familyPlans)
                                    @php $meta = $familyMeta[$familySlug] ?? null; @endphp
                                    <button type="button" class="pack-toggle-btn {{ $familySlug === $initialFamilySlug ? 'is-active' : '' }}" data-pack-toggle="{{ $familySlug }}">
                                        <span class="pack-toggle-logo-wrap">
                                            <i data-lucide="{{ $meta['icon'] ?? 'layers-3' }}" class="icon-sm"></i>
                                        </span>
                                        <span class="pack-toggle-copy">
                                            <strong>{{ $meta['display_name'] ?? strtoupper($familySlug) }}</strong>
                                            <small>{{ $meta['description'] ?? '' }}</small>
                                        </span>
                                    </button>
                                @endforeach
                            </div>

                            <input type="hidden" name="plan_family" value="{{ $initialFamilySlug }}" data-pack-family-input>

                            @foreach ($planFamilies as $familySlug => $familyPlans)
                                @php $meta = $familyMeta[$familySlug] ?? null; @endphp
                                <div class="plan-family-card {{ $meta['accent'] ?? '' }} pack-panel {{ $familySlug === $initialFamilySlug ? 'is-active' : '' }}" data-pack-panel="{{ $familySlug }}">
                                    <div class="plan-family-header align-items-start">
                                        <div class="plan-family-brand">
                                            <span class="family-pricing-logo-wrap">
                                                <i data-lucide="{{ $meta['icon'] ?? 'layers-3' }}" class="icon-sm"></i>
                                            </span>
                                            <div>
                                                <div class="small text-uppercase fw-bold text-soft-rif">{{ __('workflow.onboarding.family_label') }}</div>
                                                <h3 class="h3 text-body-rif mb-1">{{ $meta['display_name'] ?? strtoupper($familySlug) }}</h3>
                                                <p class="text-soft-rif mb-0">{{ $meta['description'] ?? '' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        @foreach ($familyPlans as $plan)
                                            @php
                                                $badgeTone = $plan->is_featured ? 'popular' : ($plan->duration_months === 12 ? 'value' : 'default');
                                            @endphp
                                            <div class="col-lg-4 col-md-6">
                                                <label class="plan-selector-card plan-selector-card-compact {{ (int) $currentPlanId === (int) $plan->id ? 'is-selected' : '' }}" data-select-card="plan" data-plan-name="{{ $plan->name }}" data-plan-price="{{ number_format((float) $plan->price_mad, 2) }} {{ __('workflow.common.currency') }}">
                                                    <input type="radio" name="plan_id" value="{{ $plan->id }}" class="visually-hidden" {{ (int) $currentPlanId === (int) $plan->id ? 'checked' : '' }}>
                                                    <span class="selection-indicator" aria-hidden="true">
                                                        <i data-lucide="check" class="icon-sm"></i>
                                                    </span>
                                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                                        <span class="family-plan-label">{{ strtoupper($familySlug) }}</span>
                                                        @if ($plan->badge_text)
                                                            <span class="family-plan-badge family-plan-badge-{{ $badgeTone }}">{{ $plan->badge_text }}</span>
                                                        @endif
                                                    </div>
                                                    <strong class="d-block h4 text-body-rif mb-1">{{ $durationLabels[$plan->duration_months] ?? $plan->name }}</strong>
                                                    <div class="family-plan-subtitle">{{ __('workflow.onboarding.duration_caption', ['months' => $plan->duration_months]) }}</div>
                                                    <div class="display-price mt-3">{{ number_format((float) $plan->price_mad, 0) }} <span>{{ __('workflow.common.currency') }}</span></div>
                                                    <ul class="workflow-list workflow-list-check mt-3">
                                                        @foreach (array_slice($plan->features ?? [], 0, 4) as $feature)
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

                        @error('plan_id')
                            <p class="small text-rif-danger mt-3 mb-0">{{ $message }}</p>
                        @enderror

                        <div class="workflow-submit-bar mt-4">
                            <div class="text-soft-rif small">{{ $submitNote }}</div>
                            <button type="submit" class="btn-rif-secondary">{{ __('workflow.onboarding.select_plan') }}</button>
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

    const updateSelectedCards = function () {
        document.querySelectorAll('[data-select-card="plan"]').forEach(function (card) {
            const input = card.querySelector('input[type="radio"]');
            if (!input) {
                return;
            }
            card.classList.toggle('is-selected', input.checked);
        });
    };

    const summaryPlan = document.querySelector('[data-summary-plan]');
    const summaryPrice = document.querySelector('[data-summary-price]');

    document.querySelectorAll('[data-select-card="plan"] input[type="radio"]').forEach(function (input) {
        input.addEventListener('change', function () {
            updateSelectedCards();
            const card = input.closest('[data-select-card="plan"]');
            if (card && summaryPlan) {
                summaryPlan.textContent = card.getAttribute('data-plan-name') || '-';
            }
            if (card && summaryPrice) {
                summaryPrice.textContent = card.getAttribute('data-plan-price') || '-';
            }
        });
    });

    updateSelectedCards();
});
</script>
@endpush
