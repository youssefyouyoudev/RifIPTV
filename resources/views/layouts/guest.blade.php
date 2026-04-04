<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
@php
    $isArabic = app()->isLocale('ar');
    $portalCopy = trans('portal.guest');
    $brandName = 'RIF Media';
    $brandSubtitle = $isArabic
        ? 'إعداد الأجهزة والدعم التقني'
        : data_get(trans('site.brand'), 'subtitle', 'Device Setup & Technical Support');
    $brandLogo = asset('images/rifmedia-logo.png');
    $supportedLocales = config('app.supported_locales', ['en']);
    $localeLabels = $isArabic ? ['en' => 'EN', 'fr' => 'FR', 'es' => 'ES', 'ar' => 'AR'] : trans('site.locales');
    $footer = $isArabic
        ? [
            'copyright' => 'RIF Media. جميع الحقوق محفوظة.',
            'disclaimer' => 'نحن لا نوفر ولا نستضيف ولا نوزع أي محتوى إعلامي. نحن نقدم فقط خدمات الإعداد التقني والدعم. المستخدم مسؤول عن احترام القوانين المحلية.',
        ]
        : trans('site.footer');
    $nav = $isArabic
        ? [
            'home' => 'الرئيسية',
            'sign_up' => 'إنشاء حساب',
            'sign_in' => 'تسجيل الدخول',
            'toggle_theme' => 'تبديل الوضع',
        ]
        : array_merge(trans('site.nav'), ['sign_in' => 'Sign in']);
    $metaTitle = trim($__env->yieldContent('title')) ?: $brandName;
    $metaDescription = trim($__env->yieldContent('meta_description')) ?: __('portal.auth.login.meta_description');
    $metaRobots = trim($__env->yieldContent('meta_robots')) ?: 'noindex,nofollow';
    $localizedBaseUrl = request()->url();
    $localeUrls = collect($supportedLocales)->mapWithKeys(fn (string $locale) => [$locale => $localizedBaseUrl.'?lang='.$locale]);
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="{{ $metaRobots }}">
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#F8FAFC">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#020617">
    <link rel="canonical" href="{{ $localizedBaseUrl.'?lang='.app()->getLocale() }}">
    <link rel="icon" href="{{ $brandLogo }}" type="image/png">
    <title>{{ $metaTitle }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=DM+Sans:wght@400;500;700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/rifiptv.css') }}" rel="stylesheet">
    <script>
        (function () {
            const storedTheme = localStorage.getItem('rif-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = storedTheme ?? (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.setAttribute('data-bs-theme', theme);
        }());
    </script>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-LXMHC9NGBP"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-LXMHC9NGBP');
</script>
<script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="1c1a8441-b356-4d54-ab00-36a0d40490f1" type="text/javascript" async></script>
</head>
<body>
    <header class="app-header">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="header-shell px-3 px-md-4 py-3" data-header-shell>
                <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                    <a href="{{ route('home') }}" class="brand-link brand-link-logo-only" aria-label="{{ $brandName }}">
                        <span class="brand-logo brand-logo-header">
                            <img src="{{ $brandLogo }}" alt="{{ $brandName }} logo" class="img-fluid">
                        </span>
                    </a>

                    <div class="auth-header-actions">
                        <div class="dropdown">
                            <button class="lang-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>{{ data_get($localeLabels, app()->getLocale(), strtoupper(app()->getLocale())) }}</span>
                            </button>
                            <ul class="dropdown-menu lang-dropdown-menu dropdown-menu-end">
                                @foreach ($supportedLocales as $locale)
                                    <li>
                                        <a href="{{ $localeUrls[$locale] }}" class="dropdown-item lang-dropdown-item {{ app()->getLocale() === $locale ? 'active' : '' }}">
                                            {{ data_get($localeLabels, $locale, strtoupper($locale)) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="guest-auth-actions">
                            <a href="{{ route('login') }}" class="btn-rif-outline">{{ data_get($nav, 'sign_in', 'Sign in') }}</a>
                            <a href="{{ route('register') }}" class="btn-rif-secondary">{{ data_get($nav, 'sign_up', 'Sign up') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="app-main">
        <div class="auth-layout">
            <div class="container-xxl px-3 px-md-4 px-lg-5">
                <div class="row g-4 align-items-stretch">
                    <div class="col-lg-7 d-none d-lg-block">
                        <section class="auth-shell h-100 p-4 p-xl-5 d-flex flex-column justify-content-between">
                            <div>
                                <span class="section-kicker mb-3">{{ data_get($portalCopy, 'panel.kicker') }}</span>
                                <h1 class="auth-panel-title text-body-rif mb-3">{{ data_get($portalCopy, 'panel.title') }}</h1>
                                <p class="text-soft-rif mb-0">{{ data_get($portalCopy, 'panel.description') }}</p>
                            </div>

                            <div class="row g-3 mt-4">
                                @foreach (data_get($portalCopy, 'stats', []) as $stat)
                                    <div class="col-sm-4">
                                        <div class="soft-card p-4 h-100">
                                            <div class="text-uppercase text-soft-rif small fw-bold mb-2">{{ data_get($stat, 'label') }}</div>
                                            <div class="fs-4 fw-bold text-body-rif">{{ data_get($stat, 'value') }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>

                    <div class="col-lg-5">
                        <section class="auth-shell p-4 p-md-5 h-100">
                            <div class="auth-card-top mb-4">
                                <div>
                                    <span class="brand-subtitle d-block">{{ data_get($portalCopy, 'portal') }}</span>
                                    <span class="text-soft-rif small">{{ data_get($portalCopy, 'lang') }}</span>
                                </div>
                                <a href="{{ route('home') }}" class="nav-link-rif">{{ data_get($nav, 'home', 'Home') }}</a>
                            </div>

                            {{ $slot }}
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="section-space">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="footer-shell p-4 p-lg-5">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="brand-logo brand-logo-footer">
                                <img src="{{ $brandLogo }}" alt="{{ $brandName }} logo" class="img-fluid">
                            </span>
                        </div>
                        <p class="text-soft-rif mb-3">{{ trans('legal.hub.description') }}</p>
                        <p class="text-soft-rif small mb-0">{{ data_get($footer, 'copyright', 'RIF Media. All rights reserved.') }}</p>
                        <p class="text-soft-rif small mt-3 mb-0">{{ data_get($footer, 'disclaimer') }}</p>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <p class="section-kicker mb-3">{{ trans('legal.common.browse') }}</p>
                        <div class="footer-links d-flex flex-column gap-2">
                            <a href="{{ route('legal.index') }}">{{ trans('legal.hub.headline') }}</a>
                            <a href="{{ route('legal.privacy') }}">{{ trans('legal.pages.privacy.label') }}</a>
                            <a href="{{ route('legal.terms') }}">{{ trans('legal.pages.terms.label') }}</a>
                            <a href="{{ route('legal.security') }}">{{ trans('legal.pages.security.label') }}</a>
                            <a href="{{ route('legal.refund') }}">{{ trans('legal.pages.refund.label') }}</a>
                            <a href="{{ route('legal.cookies') }}">{{ trans('legal.pages.cookies.label') }}</a>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="footer-links d-flex flex-wrap gap-2 mb-3">
                            <a href="{{ route('sitemap') }}">Sitemap</a>
                            <a href="{{ route('robots') }}">Robots</a>
                        </div>
                        <div class="dropdown">
                            <button class="lang-dropdown-toggle dropdown-toggle w-100 justify-content-between" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>{{ data_get($localeLabels, app()->getLocale(), strtoupper(app()->getLocale())) }}</span>
                            </button>
                            <ul class="dropdown-menu lang-dropdown-menu w-100">
                                @foreach ($supportedLocales as $locale)
                                    <li>
                                        <a href="{{ $localeUrls[$locale] }}" class="dropdown-item lang-dropdown-item {{ app()->getLocale() === $locale ? 'active' : '' }}">
                                            {{ data_get($localeLabels, $locale, strtoupper($locale)) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <button type="button" class="floating-theme-toggle" data-theme-toggle aria-label="{{ data_get($nav, 'toggle_theme', 'Toggle theme') }}"></button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.468.0/dist/umd/lucide.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const root = document.documentElement;
            const headerShell = document.querySelector('[data-header-shell]');
            const themeButtons = document.querySelectorAll('[data-theme-toggle]');

            const renderThemeButtons = function () {
                const theme = root.getAttribute('data-theme') || 'dark';
                const icon = theme === 'dark' ? 'moon' : 'sun';

                themeButtons.forEach(function (button) {
                    button.innerHTML = '<i data-lucide="' + icon + '" class="icon-sm"></i>';
                    button.setAttribute('aria-label', theme === 'dark' ? 'Dark mode active' : 'Light mode active');
                });

                if (window.lucide) {
                    window.lucide.createIcons();
                }
            };

            themeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    root.setAttribute('data-theme', nextTheme);
                    root.setAttribute('data-bs-theme', nextTheme);
                    localStorage.setItem('rif-theme', nextTheme);
                    renderThemeButtons();
                });
            });

            const handleHeaderState = function () {
                if (!headerShell) {
                    return;
                }

                headerShell.classList.toggle('scrolled', window.scrollY > 12);
            };

            handleHeaderState();
            window.addEventListener('scroll', handleHeaderState, { passive: true });

            renderThemeButtons();
        });
    </script>
</body>
</html>
