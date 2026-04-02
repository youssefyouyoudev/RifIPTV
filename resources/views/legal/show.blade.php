@extends('layouts.app')

@php
    $page = trans("legal.pages.{$pageKey}");
    $pageRoutes = [
        'privacy' => route('legal.privacy'),
        'terms' => route('legal.terms'),
        'security' => route('legal.security'),
        'refund' => route('legal.refund'),
        'cookies' => route('legal.cookies'),
    ];
@endphp

@section('title', data_get($page, 'title').' | RIF IPTV')
@section('meta_description', data_get($page, 'meta_description'))

@section('structured_data')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => data_get($page, 'title'),
            'description' => data_get($page, 'description'),
            'url' => request()->url().'?lang='.app()->getLocale(),
            'inLanguage' => app()->getLocale(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('content')
    <section class="section-space-lg">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="surface-card p-4 mb-4">
                        <a href="{{ route('legal.index') }}" class="nav-link-rif d-inline-flex align-items-center gap-2 mb-3">
                            <i data-lucide="arrow-left" class="icon-sm"></i>
                            <span>{{ trans('legal.common.back') }}</span>
                        </a>
                        <h1 class="legal-title text-body-rif mb-3">{{ data_get($page, 'title') }}</h1>
                        <p class="text-soft-rif mb-4">{{ data_get($page, 'description') }}</p>
                        <div class="soft-card p-3">
                            <div class="small text-uppercase fw-bold text-danger mb-2">{{ trans('legal.common.updated_label') }}</div>
                            <div class="fw-semibold text-body-rif">{{ data_get($page, 'updated_at') }}</div>
                        </div>
                    </div>

                    <div class="surface-card p-4">
                        <div class="section-kicker mb-3">{{ trans('legal.common.browse') }}</div>
                        <div class="d-flex flex-column gap-2">
                            @foreach ($pageRoutes as $key => $url)
                                <a href="{{ $url }}" class="policy-link {{ $pageKey === $key ? 'active' : '' }}">
                                    {{ trans("legal.pages.{$key}.label") }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <article class="surface-card p-4 p-lg-5 legal-content">
                        @foreach (data_get($page, 'sections', []) as $section)
                            <section class="{{ !$loop->last ? 'pb-4 mb-4 border-bottom' : '' }}">
                                <h2 class="h3 text-body-rif mb-3">{{ data_get($section, 'title') }}</h2>
                                <p class="text-soft-rif mb-3">{{ data_get($section, 'body') }}</p>
                                @if (filled(data_get($section, 'items')))
                                    <ul class="legal-list mb-0">
                                        @foreach (data_get($section, 'items', []) as $item)
                                            <li>
                                                <span class="check-dot">
                                                    <i data-lucide="check" class="icon-sm"></i>
                                                </span>
                                                <span class="text-soft-rif">{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </section>
                        @endforeach
                    </article>

                    <div class="row g-4 mt-1">
                        <div class="col-md-6">
                            <div class="surface-card p-4 h-100">
                                <div class="section-kicker mb-3">{{ trans('legal.common.contact_title') }}</div>
                                <p class="text-soft-rif mb-4">{{ trans('legal.common.contact_body') }}</p>
                                <a href="{{ route('home') }}#support" class="btn-rif-secondary">{{ trans('legal.common.cta') }}</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="surface-card p-4 h-100">
                                <div class="section-kicker mb-3" style="background: rgba(122,199,12,0.12); color: var(--rif-green);">{{ trans('legal.common.related_title') }}</div>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($pageRoutes as $key => $url)
                                        @continue($key === $pageKey)
                                        <a href="{{ $url }}" class="btn-rif-outline">{{ trans("legal.pages.{$key}.label") }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
