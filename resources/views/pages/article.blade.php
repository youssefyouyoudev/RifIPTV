@extends('layouts.app')

@section('title', $article['meta_title'])
@section('meta_description', $article['meta_description'])
@section('canonical', route('seo.blog.show', $article['slug']))

@php
    $articleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $article['headline'],
        'description' => $article['meta_description'],
        'url' => route('seo.blog.show', $article['slug']),
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Rifi Media',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('images/rifmedia-logo-512.png'),
            ],
        ],
        'inLanguage' => app()->getLocale(),
    ];
@endphp

@section('structured_data')
    <script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endsection

@section('content')
    @php($isArabic = app()->isLocale('ar'))

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

            <div class="page-intro-shell reveal-up mb-4">
                <span class="section-kicker mb-3">Article</span>
                <h1 class="legal-title text-body-rif mb-3">{{ $article['headline'] }}</h1>
                <p class="text-soft-rif fs-5 mb-0">{{ $article['description'] }}</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="surface-card p-4 p-lg-5 h-100">
                        @foreach ($article['sections'] as $section)
                            <p class="text-soft-rif fs-5 {{ $loop->last ? 'mb-0' : 'mb-4' }}">{{ $section }}</p>
                        @endforeach
                    </div>

                    @if (!empty($article['faqs']))
                        <div class="surface-card p-4 p-lg-5 mt-4 reveal-up">
                            <span class="section-kicker mb-3">FAQ</span>
                            <div class="d-grid gap-3">
                                @foreach ($article['faqs'] as $faq)
                                    <details class="faq-item-card">
                                        <summary>{{ $faq['q'] }}</summary>
                                        <p class="text-soft-rif mb-0">{{ $faq['a'] }}</p>
                                    </details>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <div class="surface-card p-4 p-lg-5 page-action-card">
                        <span class="section-kicker mb-3">{{ $isArabic ? 'روابط تالية' : 'Next links' }}</span>
                        <h2 class="h3 text-body-rif mb-3">{{ $isArabic ? 'اكتشف الصفحات المرتبطة بهذه الخدمة.' : 'Explore the pages linked to this service.' }}</h2>
                        <div class="d-flex flex-column gap-3 mb-4">
                            <a href="{{ config('seo.whatsapp_url', 'https://wa.me/212663323824') }}" class="btn-rif-secondary" target="_blank" rel="noopener" data-track-event="whatsapp_click" data-track-label="article_sidebar_whatsapp">
                                {{ $isArabic ? 'تواصل عبر واتساب' : 'Talk on WhatsApp' }}
                            </a>
                            <a href="{{ route('pages.contact') }}" class="btn-rif-outline">{{ $isArabic ? 'التواصل' : 'Contact' }}</a>
                        </div>
                        <div class="d-grid gap-3">
                            @foreach ($article['links'] as $link)
                                <a href="{{ $link['url'] }}" class="surface-card benefit-card d-block p-3 text-decoration-none">
                                    <strong class="d-block text-body-rif mb-1">{{ $link['title'] }}</strong>
                                    <span class="text-soft-rif small">{{ $link['text'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
