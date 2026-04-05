@extends('layouts.app')

@section('title', $page['title'].' | RIF Media')
@section('meta_description', $page['meta_description'])

@section('structured_data')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $page['title'],
            'description' => $page['meta_description'],
            'url' => request()->url().'?lang='.app()->getLocale(),
            'inLanguage' => app()->getLocale(),
            'breadcrumb' => [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => route('home', absolute: true),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => $page['title'],
                        'item' => request()->url(),
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('content')
    @php
        $isArabic = app()->isLocale('ar');
        $ctaPrimary = 'https://wa.me/212600000000';
        $ctaSecondary = route('home').'#plans';
    @endphp

    <section class="section-space-lg">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="page-hero-shell reveal-up">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="section-kicker mb-3">{{ $page['kicker'] }}</span>
                        <h1 class="legal-title text-body-rif mb-3">{{ $page['headline'] }}</h1>
                        <p class="text-soft-rif fs-5 mb-0">{{ $page['description'] }}</p>
                    </div>
                    <div class="col-lg-5">
                        <div class="page-summary-card h-100">
                            <div class="page-summary-grid">
                                @foreach ($page['cards'] as $card)
                                    <article class="soft-card p-4 h-100">
                                        <h2 class="h4 text-body-rif mb-2">{{ $card['title'] }}</h2>
                                        <p class="text-soft-rif mb-0">{{ $card['text'] }}</p>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-1">
                <div class="col-lg-8">
                    <div class="surface-card p-4 p-lg-5 h-100">
                        <div class="section-kicker mb-3">{{ $page['kicker'] }}</div>
                        <h2 class="section-title text-body-rif mb-3">{{ $page['title'] }}</h2>
                        <p class="text-soft-rif fs-5 mb-4">
                            {{ $page['description'] }}
                        </p>
                        <div class="row g-3">
                            @foreach ($page['cards'] as $index => $card)
                                <div class="col-md-6">
                                    <article class="benefit-card surface-card h-100 p-4 {{ $index === 1 ? 'benefit-card-emphasis' : '' }}">
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
                            {{ $isArabic ? 'يمكنك التواصل مباشرة عبر واتساب أو الرجوع إلى الباقات لاختيار الخدمة المناسبة قبل المتابعة.' : 'You can contact support directly on WhatsApp or go back to the packages section to choose the service that fits your needs.' }}
                        </p>
                        <div class="d-flex flex-column gap-3">
                            <a href="{{ $ctaPrimary }}" class="btn-rif-secondary" target="_blank" rel="noopener">
                                {{ $isArabic ? 'تواصل عبر واتساب' : 'Talk on WhatsApp' }}
                            </a>
                            <a href="{{ $ctaSecondary }}" class="btn-rif-outline">
                                {{ $isArabic ? 'عرض الباقات' : 'View packages' }}
                            </a>
                            <a href="{{ route('legal.index') }}" class="btn-rif-outline">
                                {{ $isArabic ? 'مركز الثقة' : 'Trust center' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
