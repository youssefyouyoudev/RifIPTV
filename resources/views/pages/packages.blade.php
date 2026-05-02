@extends('layouts.app')

@section('title', $page['meta_title'])
@section('meta_description', $page['meta_description'])
@section('canonical', route('pages.packages'))

@php
    $pageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => $page['headline'],
        'description' => $page['meta_description'],
        'url' => request()->url(),
        'inLanguage' => app()->getLocale(),
    ];
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($breadcrumbs)->values()->map(fn (array $item, int $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['label'],
            'item' => $item['url'],
        ])->all(),
    ];

    $initialPlanSlug = data_get($plans, '0.slug', 'basic');
@endphp

@section('structured_data')
    <script type="application/ld+json">
        {!! json_encode($pageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('content')
    <section class="section-space internal-page-shell">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <nav aria-label="Breadcrumb" class="mb-3">
                <ol class="breadcrumb small mb-0">
                    @foreach ($breadcrumbs as $crumb)
                        <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                            @if ($loop->last)
                                <span>{{ $crumb['label'] }}</span>
                            @else
                                <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>

            <div class="page-intro-shell reveal-up mb-4">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-8">
                        <span class="section-kicker mb-3">{{ $page['kicker'] }}</span>
                        <h1 class="legal-title text-body-rif mb-3">{{ $page['headline'] }}</h1>
                        <p class="text-soft-rif fs-5 mb-0">{{ $page['description'] }}</p>
                    </div>
                    <div class="col-lg-4">
                        <div class="page-quickfacts-card h-100">
                            <div class="page-quickfacts-list">
                                <div><span>{{ app()->isLocale('ar') ? 'منطقة الخدمة' : (app()->isLocale('fr') ? 'Zone de service' : (app()->isLocale('es') ? 'Zona de servicio' : 'Service area')) }}</span><strong>{{ config('seo.service_region', 'Morocco') }}</strong></div>
                                <div><span>{{ app()->isLocale('ar') ? 'ساعات الدعم' : (app()->isLocale('fr') ? 'Horaires du support' : (app()->isLocale('es') ? 'Horario de soporte' : 'Support hours')) }}</span><strong>{{ config('seo.support_hours', 'Monday to Saturday, 09:00 to 22:00') }}</strong></div>
                                <div><span>WhatsApp</span><strong>+212 663 323 824</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pack-switcher reveal-up" data-pack-switcher data-pack-default="{{ $initialPlanSlug }}">
                <div class="pack-toggle-bar mb-4" role="tablist" aria-label="Package families">
                    @foreach ($plans as $plan)
                        <button type="button" class="pack-toggle-btn {{ $plan['slug'] === $initialPlanSlug ? 'is-active' : '' }}" data-pack-toggle="{{ $plan['slug'] }}">
                            <span class="pack-toggle-logo-wrap"><strong>{{ $plan['code'] }}</strong></span>
                            <span class="pack-toggle-copy">
                                <strong>{{ $plan['label'] }} / {{ $plan['code'] }}</strong>
                                <small>{{ $plan['summary'] }}</small>
                            </span>
                        </button>
                    @endforeach
                </div>

                @foreach ($plans as $plan)
                    <article class="surface-card family-pricing-shell p-4 p-lg-5 pack-panel {{ $plan['slug'] === $initialPlanSlug ? 'is-active' : '' }}" data-pack-panel="{{ $plan['slug'] }}">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4 mb-4">
                            <div class="family-pricing-brand">
                                <span class="family-pricing-logo-wrap d-inline-flex align-items-center justify-content-center">{{ $plan['code'] }}</span>
                                <div>
                                    <div class="text-soft-rif small text-uppercase fw-bold mb-2">{{ $page['kicker'] }}</div>
                                    <h2 class="h2 text-body-rif mb-2">{{ $plan['name'] }}</h2>
                                    <p class="text-soft-rif mb-0">{{ $plan['summary'] }}</p>
                                </div>
                            </div>

                            @auth
                                <a href="{{ route('onboarding.show') }}" class="btn-rif-outline family-pricing-cta" data-track-event="plan_select" data-track-label="packages_{{ $plan['slug'] }}_choose">{{ $plan['choose_cta'] }}</a>
                            @else
                                <a href="{{ route('register') }}" class="btn-rif-outline family-pricing-cta" data-track-event="register_start" data-track-label="packages_{{ $plan['slug'] }}_register">{{ $plan['choose_cta'] }}</a>
                            @endauth
                        </div>

                        <div class="row g-3">
                            @foreach ($plan['prices'] as $price)
                                <div class="col-md-6 col-xl-4">
                                    <article class="service-plan-card {{ $price['featured'] ? 'service-plan-card-featured' : '' }}">
                                        @if ($price['featured'])
                                            <span class="service-plan-badge">{{ $price['badge'] ?: $plan['featured_badge'] }}</span>
                                        @endif
                                        <div class="service-plan-head">
                                            <div>
                                                <span class="service-plan-label">{{ $plan['code'] }}</span>
                                                <h3 class="service-plan-name">{{ $price['duration_label'] }}</h3>
                                            </div>
                                        </div>
                                        <div class="service-plan-price">{{ $price['price'] }} <span>{{ __('workflow.common.currency') }}</span></div>
                                        <ul class="service-plan-features">
                                            @foreach ($plan['features'] as $feature)
                                                <li>
                                                    <span class="family-plan-check"><i data-lucide="check" class="icon-sm"></i></span>
                                                    <span>{{ $feature }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="d-flex flex-column gap-3 mt-auto">
                                            @auth
                                                <a href="{{ route('onboarding.show') }}" class="{{ $price['featured'] ? 'btn-rif-primary' : 'btn-rif-secondary' }} w-100" data-track-event="plan_select" data-track-label="packages_{{ $plan['slug'] }}_{{ $price['months'] }}m">{{ $plan['continue_cta'] }}</a>
                                            @else
                                                <a href="{{ route('register') }}" class="{{ $price['featured'] ? 'btn-rif-primary' : 'btn-rif-secondary' }} w-100" data-track-event="register_start" data-track-label="packages_{{ $plan['slug'] }}_{{ $price['months'] }}m_register">{{ $plan['continue_cta'] }}</a>
                                            @endauth
                                            <a href="{{ config('seo.whatsapp_url', 'https://wa.me/212663323824') }}" class="btn-rif-outline w-100" target="_blank" rel="noopener" data-track-event="whatsapp_click" data-track-label="packages_{{ $plan['slug'] }}_whatsapp">{{ $plan['talk_cta'] }}</a>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endforeach
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

        const activate = function (slug) {
            buttons.forEach(function (button) {
                button.classList.toggle('is-active', button.getAttribute('data-pack-toggle') === slug);
            });

            panels.forEach(function (panel) {
                panel.classList.toggle('is-active', panel.getAttribute('data-pack-panel') === slug);
            });
        };

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                activate(button.getAttribute('data-pack-toggle'));
            });
        });
    });
});
</script>
@endpush
