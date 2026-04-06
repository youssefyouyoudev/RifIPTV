@extends('layouts.app')

@section('title', $page['meta_title'])
@section('meta_description', $page['meta_description'])
@section('canonical', request()->url())

@php
    $faqPageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($faqItems)->map(fn (array $item) => [
            '@type' => 'Question',
            'name' => $item['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $item['a'],
            ],
        ])->all(),
    ];

    $faqBreadcrumbSchema = [
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
        {!! json_encode($faqPageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($faqBreadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('content')
    @php($isArabic = app()->isLocale('ar'))

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
                            <div class="small text-uppercase fw-semibold text-soft-rif mb-3">
                                {{ $isArabic ? 'ملخص سريع' : 'Quick summary' }}
                            </div>
                            <div class="page-quickfacts-list">
                                <div>
                                    <span>{{ $isArabic ? 'نوع المساعدة' : 'Help type' }}</span>
                                    <strong>{{ $isArabic ? 'إعداد ومتابعة تقنية' : 'Setup and technical follow-up' }}</strong>
                                </div>
                                <div>
                                    <span>{{ $isArabic ? 'نطاق الخدمة' : 'Service area' }}</span>
                                    <strong>{{ config('seo.service_region', 'Morocco') }}</strong>
                                </div>
                                <div>
                                    <span>{{ $isArabic ? 'ساعات الدعم' : 'Support hours' }}</span>
                                    <strong>{{ config('seo.support_hours', 'Monday to Saturday, 09:00 to 22:00') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="surface-card p-4 p-lg-5 faq-list-card">
                        <div class="d-grid gap-3">
                            @foreach ($faqItems as $faq)
                                <details class="faq-item-card">
                                    <summary>{{ $faq['q'] }}</summary>
                                    <p class="text-soft-rif mb-0">{{ $faq['a'] }}</p>
                                </details>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="surface-card p-4 p-lg-5 h-100 page-action-card faq-sidebar-card">
                        <span class="section-kicker mb-3">{{ $isArabic ? 'هل تحتاج مساعدة؟' : 'Need help?' }}</span>
                        <h2 class="h3 text-body-rif mb-3">
                            {{ $isArabic ? 'تواصل مع الفريق إذا كنت تريد شرحًا إضافيًا.' : 'Talk to the team if you need extra clarification.' }}
                        </h2>
                        <p class="text-soft-rif mb-4">
                            {{ $isArabic ? 'نساعدك في فهم خطوات الإعداد والدفع والمتابعة التقنية واختيار الصفحة المناسبة لجهازك.' : 'We can help you understand setup steps, payment flow, technical follow-up, and the right service page for your device.' }}
                        </p>
                        <div class="d-flex flex-column gap-3">
                            <a href="{{ config('seo.whatsapp_url', 'https://wa.me/212663323824') }}" class="btn-rif-secondary" target="_blank" rel="noopener">
                                {{ $isArabic ? 'تواصل عبر واتساب' : 'Talk on WhatsApp' }}
                            </a>
                            <a href="{{ route('pages.contact') }}" class="btn-rif-outline">{{ __('site.nav.support') }}</a>
                            <a href="{{ route('pages.services') }}" class="btn-rif-outline">{{ __('site.nav.features') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
