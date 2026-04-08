@extends('layouts.app')

@section('title', $page['meta_title'])
@section('meta_description', $page['meta_description'])
@section('canonical', route('seo.blog'))

@php
    $blogSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Blog',
        'name' => $page['headline'],
        'description' => $page['meta_description'],
        'url' => route('seo.blog'),
        'inLanguage' => app()->getLocale(),
    ];
@endphp

@section('structured_data')
    <script type="application/ld+json">{!! json_encode($blogSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
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
                <div class="row g-4 align-items-center">
                    <div class="col-lg-8">
                        <span class="section-kicker mb-3">{{ $page['kicker'] }}</span>
                        <h1 class="legal-title text-body-rif mb-3">{{ $page['headline'] }}</h1>
                        <p class="text-soft-rif fs-5 mb-0">{{ $page['description'] }}</p>
                    </div>
                    <div class="col-lg-4">
                        <div class="page-quickfacts-card h-100">
                            <div class="page-quickfacts-list">
                                <div>
                                    <span>{{ $isArabic ? 'النطاق' : 'Coverage' }}</span>
                                    <strong>{{ config('seo.service_region', 'Morocco') }}</strong>
                                </div>
                                <div>
                                    <span>{{ $isArabic ? 'الدعم' : 'Support' }}</span>
                                    <strong>{{ config('seo.support_hours', 'Monday to Saturday, 09:00 to 22:00') }}</strong>
                                </div>
                                <div>
                                    <span>WhatsApp</span>
                                    <strong>+212 663 323 824</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="row g-3">
                        @foreach ($articles as $article)
                            <div class="col-md-6">
                                <a href="{{ route('seo.blog.show', $article['slug']) }}" class="surface-card benefit-card d-block h-100 p-4 text-decoration-none reveal-up">
                                    <span class="section-kicker mb-3">Article</span>
                                    <h2 class="h4 text-body-rif mb-2">{{ $article['headline'] }}</h2>
                                    <p class="text-soft-rif mb-0">{{ $article['description'] }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="surface-card p-4 p-lg-5 h-100 page-action-card">
                        <span class="section-kicker mb-3">{{ $isArabic ? 'الخطوة التالية' : 'Next step' }}</span>
                        <h2 class="h3 text-body-rif mb-3">{{ $isArabic ? 'اقرأ ثم تحدث مع الفريق.' : 'Read first, then talk to the team.' }}</h2>
                        <p class="text-soft-rif mb-4">{{ $isArabic ? 'تربط المقالات بين الفهم العملي والخدمة الفعلية حتى تنتقل من المعرفة إلى الإجراء بسرعة.' : 'These guides connect practical knowledge to the real support flow so you can move from reading to action quickly.' }}</p>
                        <div class="d-flex flex-column gap-3">
                            <a href="{{ config('seo.whatsapp_url', 'https://wa.me/212663323824') }}" class="btn-rif-secondary" target="_blank" rel="noopener" data-track-event="whatsapp_click" data-track-label="blog_sidebar_whatsapp">
                                {{ $isArabic ? 'تواصل عبر واتساب' : 'Talk on WhatsApp' }}
                            </a>
                            <a href="{{ route('pages.services') }}" class="btn-rif-outline">{{ $isArabic ? 'عرض الخدمات' : 'View services' }}</a>
                            <a href="{{ route('help.center') }}" class="btn-rif-outline">{{ $isArabic ? 'مركز المساعدة' : 'Help center' }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
