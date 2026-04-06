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
                                <div><span>Service area</span><strong>{{ config('seo.service_region', 'Morocco') }}</strong></div>
                                <div><span>Support hours</span><strong>{{ config('seo.support_hours', 'Monday to Saturday, 09:00 to 22:00') }}</strong></div>
                                <div><span>WhatsApp</span><strong>+212 663 323 824</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @foreach ($plans as $plan)
                    @php($featuredPrice = collect($plan['prices'])->firstWhere('featured', true) ?? $plan['prices'][0])
                    <div class="col-lg-4">
                        <article class="family-plan-mini h-100 {{ $plan['slug'] === 'advanced' ? 'family-plan-tone-popular' : ($plan['slug'] === 'premium' ? 'family-plan-tone-value' : '') }}">
                            @if (! empty($plan['highlight']))
                                <span class="family-plan-badge {{ $plan['slug'] === 'advanced' ? 'family-plan-badge-popular' : 'family-plan-badge-value' }}">{{ $plan['highlight'] }}</span>
                            @endif
                            <span class="family-plan-label">{{ $plan['label'] }}</span>
                            <h2 class="family-plan-name">{{ $plan['name'] }}</h2>
                            <p class="family-plan-subtitle">{{ $plan['summary'] }}</p>
                            <div class="service-plan-meta-grid">
                                <div><span>Scope</span><strong>{{ $plan['scope'] }}</strong></div>
                                <div><span>Devices</span><strong>{{ $plan['devices'] }}</strong></div>
                                <div><span>Response</span><strong>{{ $plan['response'] }}</strong></div>
                                <div><span>Follow-up</span><strong>{{ $plan['follow_up'] }}</strong></div>
                            </div>
                            <div class="family-plan-price">{{ $featuredPrice['price'] }}<span>MAD</span></div>
                            <div class="duration-chip-row">
                                @foreach ($plan['prices'] as $price)
                                    <span class="duration-chip {{ $price['featured'] ? 'is-featured' : '' }}">{{ $price['months'] }} months</span>
                                @endforeach
                            </div>
                            <ul class="family-plan-benefits">
                                @foreach ($plan['features'] as $feature)
                                    <li>
                                        <span class="family-plan-check"><i data-lucide="check" class="icon-xs"></i></span>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="d-flex flex-column gap-3 mt-auto">
                                @auth
                                    <a href="{{ route('onboarding.show') }}" class="btn-rif-secondary w-100">Get started</a>
                                @else
                                    <a href="{{ route('register') }}" class="btn-rif-secondary w-100">Get started</a>
                                @endauth
                                <a href="{{ config('seo.whatsapp_url', 'https://wa.me/212663323824') }}" class="btn-rif-outline w-100" target="_blank" rel="noopener">Talk to support</a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
