@extends('layouts.app')

@section('title', trans('legal.hub.title'))
@section('meta_description', trans('legal.hub.meta_description'))
@section('canonical', route('pages.trust'))

@php
    $pages = [
        'privacy' => route('legal.privacy'),
        'terms' => route('legal.terms'),
        'security' => route('legal.security'),
        'refund' => route('legal.refund'),
        'cookies' => route('legal.cookies'),
    ];
@endphp

@section('content')
    <section class="section-space-lg">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="mesh-panel p-4 p-lg-5 mb-4">
                <span class="section-kicker mb-3">{{ trans('legal.hub.kicker') }}</span>
                <h1 class="legal-title text-body-rif mb-3">{{ trans('legal.hub.headline') }}</h1>
                <p class="text-soft-rif fs-5 mb-0">{{ trans('legal.hub.description') }}</p>
            </div>

            <div class="row g-4">
                @foreach ($pages as $key => $url)
                    <div class="col-md-6 col-xl-4">
                        <article class="surface-card policy-card p-4">
                            <span class="chip-icon mb-3">
                                <i data-lucide="{{ match($key) { 'privacy' => 'shield-check', 'terms' => 'file-text', 'security' => 'lock-keyhole', 'refund' => 'refresh-ccw', default => 'cookie' } }}" class="icon-sm"></i>
                            </span>
                            <h2 class="h3 text-body-rif mb-3">{{ trans("legal.pages.{$key}.title") }}</h2>
                            <p class="text-soft-rif mb-4">{{ trans("legal.pages.{$key}.description") }}</p>
                            <a href="{{ $url }}" class="btn-rif-outline w-100">{{ trans("legal.pages.{$key}.label") }}</a>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
