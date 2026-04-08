@extends('layouts.app')

@section('title', $page['meta_title'] ?? ($page['title'].' | Rifi Media'))
@section('meta_description', $page['meta_description'])
@section('canonical', request()->url())

@php
    $pageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => $page['title'],
        'description' => $page['meta_description'],
        'url' => request()->url(),
        'inLanguage' => app()->getLocale(),
    ];

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($breadcrumbs ?? [])
            ->values()
            ->map(fn (array $item, int $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['label'],
                'item' => $item['url'],
            ])->all(),
    ];

    $serviceSchema = (($page['schema_type'] ?? null) === 'Service')
        ? [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $page['headline'],
            'description' => $page['meta_description'],
            'provider' => [
                '@type' => 'Organization',
                'name' => data_get(trans('site.brand'), 'name', 'Rifi Media'),
                'url' => config('app.url'),
            ],
            'areaServed' => config('seo.service_region', 'Morocco'),
            'url' => request()->url(),
        ]
        : null;
@endphp

@section('structured_data')
    <script type="application/ld+json">
        {!! json_encode($pageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @if ($serviceSchema)
        <script type="application/ld+json">
            {!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endif
@endsection

@section('content')
    @php
        $isArabic = app()->isLocale('ar');
        $ctaPrimary = config('seo.whatsapp_url', 'https://wa.me/212663323824');
        $ctaSecondary = route('pages.packages');
        $supportHours = config('seo.support_hours', 'Monday to Saturday, 09:00 to 22:00');
        $supportEmail = config('seo.contact_email', 'contact@rifimedia.com');
        $sections = $page['sections'] ?? [];
        $faqs = $page['faqs'] ?? [];
    @endphp

    <section class="section-space internal-page-shell">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            @if (!empty($breadcrumbs))
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
            @endif
            <div class="page-intro-shell reveal-up">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-8">
                        <span class="section-kicker mb-3">{{ $page['kicker'] }}</span>
                        <h1 class="legal-title text-body-rif mb-3">{{ $page['headline'] }}</h1>
                        <p class="text-soft-rif fs-5 mb-0">{{ $page['description'] }}</p>
                    </div>
                    <div class="col-lg-4">
                        <div class="page-quickfacts-card h-100">
                            <div class="small text-uppercase fw-semibold text-soft-rif mb-3">
                                {{ $isArabic ? 'تفاصيل سريعة' : 'Quick details' }}
                            </div>
                            <div class="page-quickfacts-list">
                                <div>
                                    <span>{{ $isArabic ? 'العلامة' : 'Brand' }}</span>
                                    <strong>{{ data_get(trans('site.brand'), 'name', 'Rifi Media') }}</strong>
                                </div>
                                <div>
                                    <span>{{ $isArabic ? 'البريد' : 'Email' }}</span>
                                    <strong>{{ $supportEmail }}</strong>
                                </div>
                                <div>
                                    <span>{{ $isArabic ? 'النطاق' : 'Service area' }}</span>
                                    <strong>{{ config('seo.service_region', 'Morocco') }}</strong>
                                </div>
                                <div>
                                    <span>{{ $isArabic ? 'أوقات الدعم' : 'Support hours' }}</span>
                                    <strong>{{ $supportHours }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-4">
                <div class="col-lg-8">
                    <div class="surface-card p-4 p-lg-5 h-100">
                        <div class="section-kicker mb-3">{{ $isArabic ? 'ما الذي يشمله هذا القسم؟' : 'What this page covers' }}</div>
                        <h2 class="section-title text-body-rif mb-3">{{ $page['title'] }}</h2>
                        <p class="text-soft-rif fs-5 mb-4">{{ $page['description'] }}</p>
                        <div class="row g-3">
                            @foreach ($page['cards'] as $index => $card)
                                <div class="col-md-6">
                                    <article class="page-feature-card h-100 p-4 {{ $index === 1 ? 'benefit-card-emphasis' : '' }}">
                                        <h3 class="h4 text-body-rif mb-2">{{ $card['title'] }}</h3>
                                        <p class="text-soft-rif mb-0">{{ $card['text'] }}</p>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="surface-card p-4 p-lg-5 h-100 page-action-card">
                        <span class="section-kicker mb-3">{{ $isArabic ? 'خطوات واضحة' : 'Clear next steps' }}</span>
                        <h2 class="h3 text-body-rif mb-3">
                            {{ $isArabic ? 'ابدأ من الصفحة المناسبة ثم أكمل مع الفريق.' : 'Start from the right page and continue with the team.' }}
                        </h2>
                        <p class="text-soft-rif mb-4">
                            {{ $isArabic ? 'يمكنك التواصل مباشرة عبر واتساب أو الرجوع إلى صفحة الباقات لاختيار خطة الدعم المناسبة قبل المتابعة.' : 'You can contact support directly on WhatsApp or go back to the packages section to choose the service that fits your needs.' }}
                        </p>
                        <div class="d-flex flex-column gap-3">
                            <a href="{{ $ctaPrimary }}" class="btn-rif-secondary" target="_blank" rel="noopener" data-track-event="whatsapp_click" data-track-label="page_sidebar_whatsapp">
                                {{ $isArabic ? 'تواصل عبر واتساب' : 'Talk on WhatsApp' }}
                            </a>
                            <a href="{{ $ctaSecondary }}" class="btn-rif-outline" data-track-event="purchase_intent" data-track-label="page_sidebar_packages">
                                {{ $isArabic ? 'عرض الباقات' : 'View packages' }}
                            </a>
                            <a href="{{ route('pages.trust') }}" class="btn-rif-outline">
                                {{ $isArabic ? 'مركز الثقة' : 'Trust center' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if (! empty($sections))
                <div class="surface-card p-4 p-lg-5 mt-4 reveal-up">
                    <div class="row g-3">
                        @foreach ($sections as $section)
                            <div class="col-md-6">
                                <article class="page-feature-card h-100 p-4">
                                    <h2 class="h4 text-body-rif mb-2">{{ $section['title'] }}</h2>
                                    <p class="text-soft-rif mb-0">{{ $section['text'] }}</p>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (!empty($page['links']))
                <div class="surface-card p-4 p-lg-5 mt-4">
                    <span class="section-kicker mb-3">{{ $isArabic ? 'روابط مفيدة' : 'Helpful links' }}</span>
                    <div class="row g-3">
                        @foreach ($page['links'] as $link)
                            <div class="col-md-6 col-xl-4">
                                <a href="{{ $link['url'] }}" class="surface-card benefit-card d-block h-100 p-4 text-decoration-none">
                                    <h3 class="h5 text-body-rif mb-2">{{ $link['title'] }}</h3>
                                    <p class="text-soft-rif mb-0">{{ $link['text'] }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (! empty($faqs))
                <div class="surface-card p-4 p-lg-5 mt-4 reveal-up">
                    <span class="section-kicker mb-3">FAQ</span>
                    <div class="d-grid gap-3">
                        @foreach ($faqs as $faq)
                            <details class="faq-item-card">
                                <summary>{{ $faq['q'] }}</summary>
                                <p class="text-soft-rif mb-0">{{ $faq['a'] }}</p>
                            </details>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
