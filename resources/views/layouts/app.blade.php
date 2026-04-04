<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
@php
    $isArabic = app()->isLocale('ar');
    $brandName = 'RIF Media';
    $brandSubtitle = $isArabic
        ? 'إعداد الأجهزة والدعم التقني'
        : data_get(trans('site.brand'), 'subtitle', 'Device Setup & Technical Support');
    $brandLogo = asset('images/rifmedia-logo.png');
    $localeLabels = $isArabic ? ['en' => 'EN', 'fr' => 'FR', 'es' => 'ES', 'ar' => 'AR'] : trans('site.locales');
    $nav = $isArabic
        ? [
            'home' => 'الرئيسية',
            'features' => 'الخدمات',
            'pricing' => 'الباقات',
            'support' => 'الدعم',
            'dashboard' => 'لوحة التحكم',
            'sign_up' => 'إنشاء حساب',
            'sign_in' => 'تسجيل الدخول',
            'choose_plan' => 'ابدأ الطلب',
            'toggle_theme' => 'تبديل الوضع',
        ]
        : array_merge(trans('site.nav'), ['sign_in' => 'Sign in']);
    $footer = $isArabic
        ? [
            'copyright' => 'RIF Media. جميع الحقوق محفوظة.',
            'disclaimer' => 'نحن لا نوفر ولا نستضيف ولا نوزع أي محتوى إعلامي. نحن نقدم فقط خدمات الإعداد التقني والدعم. المستخدم مسؤول عن احترام القوانين المحلية.',
        ]
        : trans('site.footer');
    $legalUi = $isArabic
        ? [
            'hub_kicker' => 'مركز الثقة',
            'hub_headline' => 'السياسات والخصوصية',
            'hub_description' => 'هذا القسم يوضح الخصوصية وشروط الخدمة وسياسة الأمان والاسترجاع وكيفية التعامل مع البيانات والمدفوعات بشكل واضح.',
            'browse' => 'تصفح السياسات',
            'privacy' => 'سياسة الخصوصية',
            'terms' => 'شروط الخدمة',
            'security' => 'الأمان والسلامة',
            'refund' => 'سياسة الاسترجاع',
            'cookies' => 'سياسة ملفات الارتباط',
        ]
        : [
            'hub_kicker' => trans('legal.hub.kicker'),
            'hub_headline' => trans('legal.hub.headline'),
            'hub_description' => trans('legal.hub.description'),
            'browse' => trans('legal.common.browse'),
            'privacy' => trans('legal.pages.privacy.label'),
            'terms' => trans('legal.pages.terms.label'),
            'security' => trans('legal.pages.security.label'),
            'refund' => trans('legal.pages.refund.label'),
            'cookies' => trans('legal.pages.cookies.label'),
        ];
    $showHeader = trim($__env->yieldContent('hide_header')) !== '1';
    $showFooter = trim($__env->yieldContent('hide_footer')) !== '1';
    $dashboardRoute = Route::has('dashboard') ? route('dashboard') : '#';
    $loginRoute = Route::has('login') ? route('login') : '#';
    $registerRoute = Route::has('register') ? route('register') : '#';
    $supportedLocales = config('app.supported_locales', ['en']);
    $localizedBaseUrl = request()->url();
    $localizedUrl = $localizedBaseUrl.'?lang='.app()->getLocale();
    $metaTitle = trim($__env->yieldContent('title')) ?: $brandName;
    $metaDescription = trim($__env->yieldContent('meta_description')) ?: __('site.home.meta_description');
    $metaRobots = trim($__env->yieldContent('meta_robots')) ?: 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1';
    $canonicalUrl = trim($__env->yieldContent('canonical')) ?: $localizedUrl;
    $localeUrls = collect($supportedLocales)->mapWithKeys(fn (string $locale) => [$locale => $localizedBaseUrl.'?lang='.$locale]);
    $ogLocale = [
        'en' => 'en_US',
        'fr' => 'fr_FR',
        'es' => 'es_ES',
        'ar' => 'ar_MA',
    ][app()->getLocale()] ?? 'en_US';
    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $brandName,
        'url' => rtrim(config('app.url'), '/'),
        'logo' => $brandLogo,
        'description' => $metaDescription,
    ];
    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $brandName,
        'url' => rtrim(config('app.url'), '/'),
        'inLanguage' => app()->getLocale(),
        'description' => $metaDescription,
    ];
    $authUser = auth()->user();
    $userInitials = $authUser
        ? collect(explode(' ', trim($authUser->name)))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('')
        : null;
    $userRoleLabel = $authUser
        ? ($authUser->isAdmin()
            ? ($isArabic ? 'مسؤول الإدارة' : __('workflow.admin.eyebrow'))
            : ($isArabic ? 'بوابة العميل' : __('workflow.client.eyebrow')))
        : null;
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="{{ $metaRobots }}">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#F8FAFC">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#020617">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @foreach ($localeUrls as $locale => $localeUrl)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ $localeUrl }}">
    @endforeach
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-LXMHC9NGBP"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-LXMHC9NGBP');
</script>
    <link rel="alternate" hreflang="x-default" href="{{ $localizedBaseUrl }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $brandName }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:locale" content="{{ $ogLocale }}">
    <meta property="og:image" content="{{ asset('images/hero-light.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ asset('images/hero-light.png') }}">
    <link rel="icon" href="{{ $brandLogo }}" type="image/png">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
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
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.468.0/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @yield('structured_data')
    @stack('head')
</head>
<body class="@yield('body_class')">
    @if ($showHeader)
        <header class="app-header">
            <div class="container-xxl px-3 px-md-4 px-lg-5">
                <div class="header-shell px-3 px-md-4 py-3" data-header-shell>
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <a href="{{ route('home') }}" class="brand-link brand-link-logo-only" aria-label="{{ $brandName }}">
                            <span class="brand-logo brand-logo-header">
                                <img src="{{ $brandLogo }}" alt="{{ $brandName }} logo" class="img-fluid">
                            </span>
                        </a>

                        <nav class="header-nav d-none d-lg-flex align-items-center gap-4">
                            <a href="{{ route('home') }}" class="nav-link-rif">{{ data_get($nav, 'home', 'Home') }}</a>
                            <a href="{{ route('home') }}#features" class="nav-link-rif">{{ data_get($nav, 'features', 'Services') }}</a>
                            <a href="{{ route('home') }}#plans" class="nav-link-rif">{{ data_get($nav, 'pricing', 'Packages') }}</a>
                            <a href="{{ route('home') }}#support" class="nav-link-rif">{{ data_get($nav, 'support', 'Support') }}</a>
                            <a href="{{ route('legal.index') }}" class="nav-link-rif">{{ $legalUi['hub_kicker'] }}</a>
                        </nav>

                        <div class="d-none d-xl-flex align-items-center gap-3">
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

                            @auth
                                <div class="header-action-cluster">
                                    <a href="{{ $dashboardRoute }}" class="dashboard-shortcut">
                                        <i data-lucide="layout-dashboard" class="icon-sm"></i>
                                        <span>{{ data_get($nav, 'dashboard', 'Dashboard') }}</span>
                                    </a>
                                    @if (! $authUser->isAdmin())
                                        <a href="{{ route('onboarding.show') }}" class="btn-rif-outline header-utility-link">
                                            <i data-lucide="sparkles" class="icon-sm"></i>
                                            <span>{{ data_get($nav, 'choose_plan', 'Start order') }}</span>
                                        </a>
                                    @endif
                                    <div class="dropdown">
                                        <button class="user-menu-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="user-menu-avatar">{{ $userInitials }}</span>
                                            <span class="user-menu-copy">
                                                <span class="user-menu-name">{{ $authUser->name }}</span>
                                                <span class="user-menu-role">{{ $userRoleLabel }}</span>
                                            </span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end user-menu-dropdown">
                                            <div class="user-menu-summary">
                                                <div class="fw-semibold text-body-rif">{{ $authUser->name }}</div>
                                                <div class="text-soft-rif small">{{ $authUser->email }}</div>
                                            </div>
                                            <a href="{{ $dashboardRoute }}" class="dropdown-item user-menu-item">
                                                <i data-lucide="layout-dashboard" class="icon-sm"></i>
                                                <span>{{ data_get($nav, 'dashboard', 'Dashboard') }}</span>
                                            </a>
                                            <a href="{{ route('profile.edit') }}" class="dropdown-item user-menu-item">
                                                <i data-lucide="user-cog" class="icon-sm"></i>
                                                <span>{{ __('site.dashboard.sidebar.account') }}</span>
                                            </a>
                                            <div class="dropdown-divider user-menu-divider"></div>
                                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                                @csrf
                                                <button type="submit" class="dropdown-item user-menu-item user-menu-logout">
                                                    <i data-lucide="log-out" class="icon-sm"></i>
                                                    <span>{{ __('site.dashboard.sidebar.logout') }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="guest-auth-actions">
                                    <a href="{{ $loginRoute }}" class="btn-rif-outline">{{ data_get($nav, 'sign_in', 'Sign in') }}</a>
                                    <a href="{{ $registerRoute }}" class="btn-rif-secondary">{{ data_get($nav, 'sign_up', 'Sign up') }}</a>
                                </div>
                            @endauth
                        </div>

                        <button type="button" class="menu-toggle d-inline-flex d-xl-none" data-mobile-toggle aria-label="Open navigation">
                            <i data-lucide="menu" class="icon-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="header-shell mobile-menu d-xl-none" data-mobile-menu>
                    <a href="{{ route('home') }}">{{ data_get($nav, 'home', 'Home') }}</a>
                    <a href="{{ route('home') }}#features">{{ data_get($nav, 'features', 'Services') }}</a>
                    <a href="{{ route('home') }}#plans">{{ data_get($nav, 'pricing', 'Packages') }}</a>
                    <a href="{{ route('home') }}#support">{{ data_get($nav, 'support', 'Support') }}</a>
                    <a href="{{ route('legal.index') }}">{{ $legalUi['hub_kicker'] }}</a>

                    @auth
                        <div class="mobile-user-card">
                            <span class="user-menu-avatar">{{ $userInitials }}</span>
                            <div class="min-w-0">
                                <div class="fw-semibold text-body-rif">{{ $authUser->name }}</div>
                                <div class="text-soft-rif small">{{ $userRoleLabel }}</div>
                            </div>
                        </div>
                        <a href="{{ $dashboardRoute }}">{{ data_get($nav, 'dashboard', 'Dashboard') }}</a>
                        @if (! $authUser->isAdmin())
                            <a href="{{ route('onboarding.show') }}">{{ data_get($nav, 'choose_plan', 'Start order') }}</a>
                        @endif
                        <a href="{{ route('profile.edit') }}">{{ __('site.dashboard.sidebar.account') }}</a>
                    @endauth

                    <div class="dropdown mt-3">
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

                    <div class="d-flex flex-column flex-sm-row gap-3 mt-3">
                        @guest
                            <a href="{{ $loginRoute }}" class="btn-rif-outline justify-content-center w-100">{{ data_get($nav, 'sign_in', 'Sign in') }}</a>
                            <a href="{{ $registerRoute }}" class="btn-rif-secondary justify-content-center w-100">{{ data_get($nav, 'sign_up', 'Sign up') }}</a>
                        @else
                            <form method="POST" action="{{ route('logout') }}" class="m-0 w-100">
                                @csrf
                                <button type="submit" class="btn-rif-outline justify-content-center w-100">{{ __('site.dashboard.sidebar.logout') }}</button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </header>
    @endif

    <main class="app-main">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <button type="button" class="floating-theme-toggle" data-theme-toggle aria-label="{{ data_get($nav, 'toggle_theme', 'Toggle theme') }}"></button>

    @if ($showFooter)
        <footer class="section-space mt-5">
            <div class="container-xxl px-3 px-md-4 px-lg-5">
                <div class="footer-shell p-4 p-lg-5">
                    <div class="row g-4">
                        <div class="col-lg-5">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="brand-logo brand-logo-footer">
                                    <img src="{{ $brandLogo }}" alt="{{ $brandName }} logo" class="img-fluid">
                                </span>
                            </div>
                            <p class="text-soft-rif mb-3">{{ $legalUi['hub_description'] }}</p>
                            <p class="text-soft-rif small mb-0">{{ data_get($footer, 'copyright', 'RIF Media. All rights reserved.') }}</p>
                            <p class="text-soft-rif small mt-3 mb-0">{{ data_get($footer, 'disclaimer') }}</p>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <p class="section-kicker mb-3">{{ $legalUi['browse'] }}</p>
                            <div class="footer-links d-flex flex-column gap-2">
                                <a href="{{ route('legal.index') }}">{{ $legalUi['hub_headline'] }}</a>
                                <a href="{{ route('legal.privacy') }}">{{ $legalUi['privacy'] }}</a>
                                <a href="{{ route('legal.terms') }}">{{ $legalUi['terms'] }}</a>
                                <a href="{{ route('legal.security') }}">{{ $legalUi['security'] }}</a>
                                <a href="{{ route('legal.refund') }}">{{ $legalUi['refund'] }}</a>
                                <a href="{{ route('legal.cookies') }}">{{ $legalUi['cookies'] }}</a>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="footer-links d-flex flex-wrap gap-2 mb-3">
                                <a href="{{ route('sitemap') }}">Sitemap</a>
                                <a href="{{ route('robots') }}">Robots</a>
                            </div>
                            <div class="locale-pills">
                                @foreach ($supportedLocales as $locale)
                                    <a href="{{ $localeUrls[$locale] }}" class="locale-pill {{ app()->getLocale() === $locale ? 'active' : '' }}">
                                        {{ data_get($localeLabels, $locale, strtoupper($locale)) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const root = document.documentElement;
            const headerShell = document.querySelector('[data-header-shell]');
            const mobileToggle = document.querySelector('[data-mobile-toggle]');
            const mobileMenu = document.querySelector('[data-mobile-menu]');
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

            if (mobileToggle && mobileMenu) {
                mobileToggle.addEventListener('click', function () {
                    mobileMenu.classList.toggle('is-open');
                });
            }

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
    @stack('scripts')
</body>
</html>
