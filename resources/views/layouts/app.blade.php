<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}">
@php
    $isArabic = app()->isLocale('ar');
    $brandName = data_get(trans('site.brand'), 'name', 'Rifi Media');
    $brandSubtitle = data_get(trans('site.brand'), 'subtitle', 'Device Setup & Technical Support');
    $brandLogo = asset('images/rifmedia-logo-128.png');
    $schemaLogo = asset('images/rifmedia-logo-512.png');
    $themeCss = app()->environment('production') ? asset('css/rifiptv.min.css') : asset('css/rifiptv.css');
    $seoConfig = config('seo');
    $brandEmail = data_get($seoConfig, 'contact_email', 'contact@rifimedia.com');
    $brandPhone = data_get($seoConfig, 'contact_phone');
    $brandWhatsapp = data_get($seoConfig, 'whatsapp_url', 'https://wa.me/212663323824');
    $supportHours = data_get($seoConfig, 'support_hours', 'Monday to Saturday, 09:00 to 22:00');
    $defaultOgImage = asset(ltrim((string) data_get($seoConfig, 'default_og_image', '/images/hero-light.png'), '/'));
    $socialProfiles = collect(data_get($seoConfig, 'social_profiles', []))->filter()->values();
    $socialLinks = collect(data_get($seoConfig, 'social_links', []))
        ->filter(fn (array $link) => filled(data_get($link, 'url')))
        ->values();
    $localeLabels = trans('site.locales');
    $nav = array_merge(trans('site.nav'), ['sign_in' => $isArabic ? 'تسجيل الدخول' : 'Sign in']);
    $footer = trans('site.footer');
    $blogLabel = $isArabic ? 'المدونة' : 'Blog';
    $helpCenterLabel = $isArabic ? 'مركز المساعدة' : (app()->isLocale('fr') ? 'Centre d’aide' : 'Help center');
    $legalUi = [
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
    $canonicalBase = trim($__env->yieldContent('canonical')) ?: request()->url();
    $localizedBaseUrl = \App\Support\SeoUrl::xDefault($canonicalBase);
    $metaTitle = trim($__env->yieldContent('title')) ?: data_get($seoConfig, 'default_title', $brandName);
    $metaDescription = trim($__env->yieldContent('meta_description')) ?: data_get($seoConfig, 'default_description', __('site.home.meta_description'));
    $metaRobots = trim($__env->yieldContent('meta_robots')) ?: data_get($seoConfig, 'default_robots', 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1');
    $canonicalUrl = \App\Support\SeoUrl::withLocale($canonicalBase, app()->getLocale());
    $localeUrls = collect(\App\Support\SeoUrl::localeMap($canonicalBase, $supportedLocales));
    $ogLocale = [
        'en' => 'en_US',
        'fr' => 'fr_FR',
        'es' => 'es_ES',
        'ar' => 'ar_MA',
    ][app()->getLocale()] ?? 'en_US';
    $ogLocaleAlternates = collect($supportedLocales)
        ->reject(fn (string $locale) => $locale === app()->getLocale())
        ->map(fn (string $locale) => [
            'en' => 'en_US',
            'fr' => 'fr_FR',
            'es' => 'es_ES',
            'ar' => 'ar_MA',
        ][$locale] ?? null)
        ->filter()
        ->values();
    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $brandName,
        'url' => rtrim(config('app.url'), '/'),
        'logo' => $schemaLogo,
        'description' => $metaDescription,
        'email' => $brandEmail,
        'contactPoint' => [[
            '@type' => 'ContactPoint',
            'contactType' => 'customer support',
            'email' => $brandEmail,
            'url' => $brandWhatsapp,
            'availableLanguage' => $supportedLocales,
        ]],
    ];
    if ($brandPhone) {
        $organizationSchema['telephone'] = $brandPhone;
        $organizationSchema['contactPoint'][0]['telephone'] = $brandPhone;
    }
    if ($socialProfiles->isNotEmpty()) {
        $organizationSchema['sameAs'] = $socialProfiles->all();
    }
    $localBusinessSchema = [
        '@context' => 'https://schema.org',
        '@type' => data_get($seoConfig, 'business_type', 'ProfessionalService'),
        'name' => $brandName,
        'url' => rtrim(config('app.url'), '/'),
        'image' => $defaultOgImage,
        'logo' => $schemaLogo,
        'description' => $metaDescription,
        'email' => $brandEmail,
        'areaServed' => data_get($seoConfig, 'area_served', 'MA'),
        'serviceType' => data_get($seoConfig, 'service_types', []),
        'openingHours' => $supportHours,
    ];
    if ($brandPhone) {
        $localBusinessSchema['telephone'] = $brandPhone;
    }
    if ($socialProfiles->isNotEmpty()) {
        $localBusinessSchema['sameAs'] = $socialProfiles->all();
    }
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
            ? __('workflow.admin.eyebrow')
            : __('workflow.client.eyebrow'))
        : null;
@endphp
<head>
    @include('partials.seo-meta')
    @if(request()->getHost() !== '127.0.0.1' && request()->getHost() !== 'localhost')
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-LXMHC9NGBP');

            window.__rifiLoadThirdParty = function () {
                if (window.__rifiThirdPartyLoaded) {
                    return;
                }

                window.__rifiThirdPartyLoaded = true;

                const injectScript = function (src, attributes = {}) {
                    const script = document.createElement('script');
                    script.src = src;
                    Object.entries(attributes).forEach(function ([key, value]) {
                        if (value === true) {
                            script.setAttribute(key, key);
                        } else {
                            script.setAttribute(key, value);
                        }
                    });
                    document.head.appendChild(script);
                };

                injectScript('https://www.googletagmanager.com/gtag/js?id=G-LXMHC9NGBP', { async: true });
                injectScript('https://consent.cookiebot.com/uc.js', {
                    id: 'Cookiebot',
                    'data-cbid': '1c1a8441-b356-4d54-ab00-36a0d40490f1',
                    type: 'text/javascript',
                    async: true,
                });
            };

            window.addEventListener('load', function () {
                if ('requestIdleCallback' in window) {
                    requestIdleCallback(window.__rifiLoadThirdParty, { timeout: 3500 });
                } else {
                    setTimeout(window.__rifiLoadThirdParty, 1500);
                }
            }, { once: true });
        </script>
    @endif
    @stack('preloads')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=DM+Sans:wght@400;500;700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ $themeCss }}" rel="stylesheet">
    <script>
        (function () {
            document.documentElement.classList.add('js');
            const storedTheme = localStorage.getItem('rif-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = storedTheme ?? (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.setAttribute('data-bs-theme', theme);
        }());
    </script>
    <script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($localBusinessSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @yield('structured_data')
    @stack('head')
</head>
<body class="@yield('body_class')">
    <a href="#main-content" class="skip-link">Skip to main content</a>

    @if ($showHeader)
        <header class="app-header">
            <div class="container-xxl px-3 px-md-4 px-lg-5">
                <div class="header-shell px-3 px-md-4 py-3" data-header-shell>
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <a href="{{ route('home') }}" class="brand-link brand-link-logo-only" aria-label="{{ $brandName }}">
                            <span class="brand-logo brand-logo-header">
                                <img src="{{ $brandLogo }}" alt="{{ $brandName }} device setup and technical support logo" class="img-fluid" width="128" height="70" decoding="async">
                            </span>
                        </a>

                        <nav class="header-nav d-none d-lg-flex align-items-center gap-4" aria-label="Primary">
                            <a href="{{ route('home') }}" class="nav-link-rif">{{ data_get($nav, 'home', 'Home') }}</a>
                            <a href="{{ route('pages.services') }}" class="nav-link-rif">{{ data_get($nav, 'features', 'Services') }}</a>
                            <a href="{{ route('pages.about') }}" class="nav-link-rif">{{ data_get($nav, 'about', 'About') }}</a>
                            <a href="{{ route('pages.packages') }}" class="nav-link-rif">{{ data_get($nav, 'pricing', 'Packages') }}</a>
                            <a href="{{ route('pages.contact') }}" class="nav-link-rif">{{ data_get($nav, 'support', 'Contact') }}</a>
                            <a href="{{ route('seo.blog') }}" class="nav-link-rif">{{ $blogLabel }}</a>
                            <a href="{{ route('pages.trust') }}" class="nav-link-rif">{{ $legalUi['hub_kicker'] }}</a>
                        </nav>

                        <div class="d-none d-xl-flex align-items-center gap-3 header-desktop-tools">
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
                    <a href="{{ route('pages.services') }}">{{ data_get($nav, 'features', 'Services') }}</a>
                    <a href="{{ route('pages.about') }}">{{ data_get($nav, 'about', 'About') }}</a>
                    <a href="{{ route('pages.packages') }}">{{ data_get($nav, 'pricing', 'Packages') }}</a>
                    <a href="{{ route('pages.contact') }}">{{ data_get($nav, 'support', 'Contact') }}</a>
                    <a href="{{ route('seo.blog') }}">{{ $blogLabel }}</a>
                    <a href="{{ route('pages.trust') }}">{{ $legalUi['hub_kicker'] }}</a>

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

    <main id="main-content" class="app-main" tabindex="-1">
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
                                    <img src="{{ $brandLogo }}" alt="{{ $brandName }} device setup and technical support logo" class="img-fluid" width="128" height="70" loading="lazy" decoding="async">
                                </span>
                            </div>
                            <p class="text-soft-rif mb-3">{{ $legalUi['hub_description'] }}</p>
                            <p class="text-soft-rif small mb-2">{{ $brandEmail }}</p>
                            <p class="text-soft-rif small mb-2">{{ $supportHours }}</p>
                            <a href="{{ $brandWhatsapp }}" class="text-soft-rif small text-decoration-none" target="_blank" rel="noopener">WhatsApp</a>
                            <p class="text-soft-rif small mb-0">{{ data_get($footer, 'copyright', 'Rifi Media. All rights reserved.') }}</p>
                            <p class="text-soft-rif small mt-3 mb-0">{{ data_get($footer, 'disclaimer') }}</p>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <p class="section-kicker mb-3">{{ $legalUi['browse'] }}</p>
                            <div class="footer-links d-flex flex-column gap-2">
                                <a href="{{ route('pages.services') }}">{{ data_get($nav, 'features', 'Services') }}</a>
                                <a href="{{ route('pages.about') }}">{{ data_get($nav, 'about', 'About') }}</a>
                                <a href="{{ route('pages.contact') }}">{{ data_get($nav, 'support', 'Contact') }}</a>
                                <a href="{{ route('seo.blog') }}">{{ $blogLabel }}</a>
                                <a href="{{ route('help.center') }}">{{ $helpCenterLabel }}</a>
                                <a href="{{ route('pages.trust') }}">{{ $legalUi['hub_headline'] }}</a>
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
                            @if ($socialLinks->isNotEmpty())
                                <div class="social-links-grid mb-3" aria-label="Social links">
                                    @foreach ($socialLinks as $socialLink)
                                        <a href="{{ $socialLink['url'] }}" class="social-link-chip" target="_blank" rel="noopener noreferrer me">
                                            <i data-lucide="{{ $socialLink['icon'] }}" class="icon-sm"></i>
                                            <span>{{ $socialLink['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
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

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/lucide@0.468.0/dist/umd/lucide.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const root = document.documentElement;
            const headerShell = document.querySelector('[data-header-shell]');
            const mobileToggle = document.querySelector('[data-mobile-toggle]');
            const mobileMenu = document.querySelector('[data-mobile-menu]');
            const themeButtons = document.querySelectorAll('[data-theme-toggle]');
            const revealItems = document.querySelectorAll('.reveal-up');
            const locale = document.documentElement.lang || 'en';

            window.rifiTrackEvent = function (eventName, params = {}) {
                const payload = Object.assign({
                    page_locale: locale,
                    page_path: window.location.pathname,
                    page_title: document.title
                }, params);

                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push(Object.assign({ event: eventName }, payload));

                if (typeof window.gtag === 'function') {
                    window.gtag('event', eventName, payload);
                }
            };

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

            document.addEventListener('click', function (event) {
                const trackedLink = event.target.closest('a,button');

                if (!trackedLink) {
                    return;
                }

                const href = trackedLink.getAttribute('href') || '';
                const eventName = trackedLink.dataset.trackEvent;
                const eventLabel = trackedLink.dataset.trackLabel || trackedLink.textContent.trim().slice(0, 80);

                if (eventName) {
                    window.rifiTrackEvent(eventName, {
                        label: eventLabel,
                        destination: href || trackedLink.dataset.trackDestination || ''
                    });

                    return;
                }

                if (href.includes('wa.me')) {
                    window.rifiTrackEvent('whatsapp_click', {
                        label: eventLabel,
                        destination: href
                    });
                } else if (href.startsWith('tel:')) {
                    window.rifiTrackEvent('call_click', {
                        label: eventLabel,
                        destination: href
                    });
                } else if (href.includes('/contact')) {
                    window.rifiTrackEvent('contact_click', {
                        label: eventLabel,
                        destination: href
                    });
                } else if (href.includes('/register')) {
                    window.rifiTrackEvent('register_start', {
                        label: eventLabel,
                        destination: href
                    });
                } else if (href.includes('/onboarding')) {
                    window.rifiTrackEvent('checkout_start', {
                        label: eventLabel,
                        destination: href
                    });
                }
            }, { passive: true });

            document.querySelectorAll('details.faq-item-card').forEach(function (detail) {
                detail.addEventListener('toggle', function () {
                    if (!detail.open) {
                        return;
                    }

                    const summary = detail.querySelector('summary');

                    window.rifiTrackEvent('faq_expand', {
                        label: summary ? summary.textContent.trim().slice(0, 120) : 'faq'
                    });
                });
            });

            document.querySelectorAll('form').forEach(function (form) {
                let started = false;

                form.addEventListener('focusin', function () {
                    if (started) {
                        return;
                    }

                    started = true;
                    window.rifiTrackEvent('form_start', {
                        label: form.getAttribute('action') || window.location.pathname
                    });
                });

                form.addEventListener('submit', function () {
                    window.rifiTrackEvent('form_submit', {
                        label: form.getAttribute('action') || window.location.pathname
                    });
                });
            });

            if (revealItems.length) {
                if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    const revealObserver = new IntersectionObserver(function (entries, observer) {
                        entries.forEach(function (entry) {
                            if (!entry.isIntersecting) {
                                return;
                            }

                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        });
                    }, {
                        rootMargin: '0px 0px -10% 0px',
                        threshold: 0.08
                    });

                    revealItems.forEach(function (item) {
                        revealObserver.observe(item);
                    });
                } else {
                    revealItems.forEach(function (item) {
                        item.classList.add('is-visible');
                    });
                }
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
