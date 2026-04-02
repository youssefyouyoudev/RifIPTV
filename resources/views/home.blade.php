@extends('layouts.app')

@section('title', __('site.home.title'))
@section('meta_description', __('site.home.meta_description'))
@section('body_class', 'page-home')

@section('content')
    @php
        $featureHighlights = trans('site.home.feature_highlights');
        $payments = trans('site.home.payments');
        $pricing = trans('site.home.pricing');
        $benefits = trans('site.home.benefits.items');
        $miniPoints = array_values(trans('site.home.mini_points'));
        $isArabic = app()->getLocale() === 'ar';
        $paymentLogos = [
            'paddle' => 'images/payment-paddle.jpg',
            'saham' => 'images/payment-saham-bank.webp',
            'cih' => 'images/payment-cih-bank.jpg',
            'boa' => 'images/payment-bank-of-africa.png',
            'chaabi' => 'images/payment-chaabi-bank.png',
            'cashplus' => 'images/payment-cashplus.png',
            'attijari' => 'images/payment-attijariwafa-bank.png',
        ];

        $hero = match (app()->getLocale()) {
            'ar' => [
                'eyebrow' => 'أكثر من 10,000 قناة مشهورة',
                'title_top' => 'شاهد العالم كله',
                'title_bottom' => 'من شاشة واحدة',
                'description' => 'واجهة أبسط وأكثر فخامة تعرض قوة RIF IPTV مع قنوات مشهورة ورياضة وأفلام وترفيه، بتجربة مشاهدة مريحة على كل الأجهزة.',
                'device_text' => 'يعمل على التلفاز والتابلت والهاتف والويب',
                'note' => 'تصميم أكثر هدوءًا ووضوحًا ليظهر الخدمة بشكل احترافي في العربية وباقي اللغات.',
                'chips' => [
                    ['icon' => 'trophy', 'label' => 'رياضة'],
                    ['icon' => 'calendar-range', 'label' => 'أحداث مباشرة'],
                    ['icon' => 'film', 'label' => 'أفلام'],
                    ['icon' => 'tv', 'label' => 'ترفيه'],
                ],
                'stats' => ['10,000+ قناة', 'بث 24/7', 'TV / Mobile / Tablet'],
            ],
            'fr' => [
                'eyebrow' => 'Plus de 10 000 chaines connues',
                'title_top' => 'Le monde entier',
                'title_bottom' => 'sur un seul ecran',
                'description' => 'Une hero plus propre et premium qui met en valeur RIF IPTV avec des chaines connues, du sport, des films et une lecture fluide sur tous les appareils.',
                'device_text' => 'Compatible TV, tablette, mobile et web',
                'note' => 'Une presentation plus douce, plus claire et mieux equilibree sur toutes les langues.',
                'chips' => [
                    ['icon' => 'trophy', 'label' => 'Sports'],
                    ['icon' => 'calendar-range', 'label' => 'Evenements'],
                    ['icon' => 'film', 'label' => 'Films'],
                    ['icon' => 'tv', 'label' => 'Divertissement'],
                ],
                'stats' => ['10,000+ chaines', 'Streaming 24/7', 'TV / Mobile / Tablette'],
            ],
            'es' => [
                'eyebrow' => 'Mas de 10,000 canales famosos',
                'title_top' => 'Todo un mundo',
                'title_bottom' => 'en una pantalla',
                'description' => 'Una hero mas limpia y premium que presenta RIF IPTV con canales famosos, deportes, peliculas y una experiencia fluida en todos los dispositivos.',
                'device_text' => 'Funciona en TV, tablet, movil y web',
                'note' => 'Una presentacion mas clara, equilibrada y profesional en todos los idiomas.',
                'chips' => [
                    ['icon' => 'trophy', 'label' => 'Deportes'],
                    ['icon' => 'calendar-range', 'label' => 'Eventos'],
                    ['icon' => 'film', 'label' => 'Peliculas'],
                    ['icon' => 'tv', 'label' => 'Entretenimiento'],
                ],
                'stats' => ['10,000+ canales', 'Streaming 24/7', 'TV / Movil / Tablet'],
            ],
            default => [
                'eyebrow' => 'Over 10,000 famous channels',
                'title_top' => 'The whole world',
                'title_bottom' => 'on one screen',
                'description' => 'A cleaner, more premium hero that presents RIF IPTV with famous channels, sports, movies, and a smooth multi-device viewing experience.',
                'device_text' => 'Works on TV, tablet, phone, and web',
                'note' => 'A softer, more balanced presentation that stays clean across every language and device size.',
                'chips' => [
                    ['icon' => 'trophy', 'label' => 'Sports'],
                    ['icon' => 'calendar-range', 'label' => 'Live Events'],
                    ['icon' => 'film', 'label' => 'Movies'],
                    ['icon' => 'tv', 'label' => 'Entertainment'],
                ],
                'stats' => ['10,000+ channels', '24/7 streaming', 'TV / Mobile / Tablet'],
            ],
        };

        $planFamilies = match (app()->getLocale()) {
            'ar' => [
                [
                    'name' => 'MAX OTT',
                    'slug' => 'max-ott',
                    'logo' => asset('images/plan-max-ott.png'),
                    'description' => 'باقة مرنة واقتصادية للمشاهدة اليومية بجودة ممتازة.',
                    'plans' => [
                        ['name' => 'شهر واحد', 'price' => '70', 'badge' => null, 'subtitle' => 'دخول سريع للبداية', 'benefits' => ['قنوات مباشرة مشهورة', 'أفلام ومسلسلات', 'بث مستقر', 'دعم واتساب']],
                        ['name' => '3 أشهر', 'price' => '149', 'badge' => 'الأكثر طلبًا', 'subtitle' => 'أفضل توازن في السعر', 'benefits' => ['قنوات مباشرة مشهورة', 'أفلام ومسلسلات', 'جودة Full HD و 4K', 'دعم سريع']],
                        ['name' => '6 أشهر', 'price' => '249', 'badge' => null, 'subtitle' => 'راحة أكثر وتوفير أفضل', 'benefits' => ['مشاهدة طويلة المدى', 'رياضة وترفيه', 'أجهزة متعددة', 'متابعة الدعم']],
                        ['name' => '12 شهرًا', 'price' => '449', 'badge' => 'أفضل قيمة', 'subtitle' => 'الخيار السنوي الاقتصادي', 'benefits' => ['أفضل سعر سنوي', 'استقرار أعلى', 'أولوية أفضل', 'طمأنينة طوال السنة']],
                    ],
                ],
                [
                    'name' => 'T-REX',
                    'slug' => 't-rex',
                    'logo' => asset('images/plan-trex.png'),
                    'description' => 'باقة أقوى لعشاق الجودة العالية والقنوات الرياضية والمحتوى المميز.',
                    'plans' => [
                        ['name' => 'شهر واحد', 'price' => '99', 'badge' => null, 'subtitle' => 'نسخة قوية للاستخدام المكثف', 'benefits' => ['قنوات رياضية أقوى', 'أفلام وسلاسل', 'خوادم مميزة', 'دعم سريع']],
                        ['name' => '3 أشهر', 'price' => '249', 'badge' => 'الأكثر مبيعًا', 'subtitle' => 'الخيار المفضل لعشاق T-REX', 'benefits' => ['رياضة ومحتوى بريميوم', 'جودة عالية', 'دعم ذو أولوية', 'قيمة متوازنة']],
                        ['name' => '6 أشهر', 'price' => '349', 'badge' => null, 'subtitle' => 'قوة أكثر مع توفير أفضل', 'benefits' => ['مشاهدة مريحة', 'خوادم مستقرة', 'أجهزة متعددة', 'تجديد أسهل']],
                        ['name' => '12 شهرًا', 'price' => '599', 'badge' => 'الخيار السنوي', 'subtitle' => 'أفضل التزام طويل الأمد', 'benefits' => ['أفضل قيمة سنوية', 'أولوية أعلى', 'استقرار قوي', 'دعم مستمر']],
                    ],
                ],
            ],
            'fr' => [
                [
                    'name' => 'MAX OTT',
                    'slug' => 'max-ott',
                    'logo' => asset('images/plan-max-ott.png'),
                    'description' => 'Une formule souple et economique pour un streaming quotidien propre et stable.',
                    'plans' => [
                        ['name' => '1 mois', 'price' => '70', 'badge' => null, 'subtitle' => 'Acces rapide pour commencer', 'benefits' => ['Chaines live connues', 'Films et series', 'Lecture stable', 'Support WhatsApp']],
                        ['name' => '3 mois', 'price' => '149', 'badge' => 'Populaire', 'subtitle' => 'Le meilleur equilibre prix / duree', 'benefits' => ['Chaines live connues', 'Films et series', 'Qualite Full HD et 4K', 'Support rapide']],
                        ['name' => '6 mois', 'price' => '249', 'badge' => null, 'subtitle' => 'Plus de confort et plus d economie', 'benefits' => ['Acces longue duree', 'Sports et divertissement', 'Multi-appareils', 'Suivi support']],
                        ['name' => '12 mois', 'price' => '449', 'badge' => 'Meilleure valeur', 'subtitle' => 'Le meilleur choix annuel', 'benefits' => ['Meilleur prix annuel', 'Plus de stabilite', 'Priorite plus forte', 'Tranquillite sur l annee']],
                    ],
                ],
                [
                    'name' => 'T-REX',
                    'slug' => 't-rex',
                    'logo' => asset('images/plan-trex.png'),
                    'description' => 'Une ligne plus premium pour les clients qui veulent plus de puissance et de sport.',
                    'plans' => [
                        ['name' => '1 mois', 'price' => '99', 'badge' => null, 'subtitle' => 'Version plus puissante pour usage intensif', 'benefits' => ['Sports premium', 'Films et series', 'Serveurs plus forts', 'Support rapide']],
                        ['name' => '3 mois', 'price' => '249', 'badge' => 'Best seller', 'subtitle' => 'Le choix prefere pour T-REX', 'benefits' => ['Sport et contenu premium', 'Haute qualite', 'Support prioritaire', 'Valeur equilibree']],
                        ['name' => '6 mois', 'price' => '349', 'badge' => null, 'subtitle' => 'Plus de puissance avec plus d economie', 'benefits' => ['Confort longue duree', 'Serveurs stables', 'Multi-appareils', 'Renouvellement simple']],
                        ['name' => '12 mois', 'price' => '599', 'badge' => 'Annuel', 'subtitle' => 'Le meilleur engagement long terme', 'benefits' => ['Meilleur prix annuel', 'Priorite haute', 'Stabilite solide', 'Support continu']],
                    ],
                ],
            ],
            'es' => [
                [
                    'name' => 'MAX OTT',
                    'slug' => 'max-ott',
                    'logo' => asset('images/plan-max-ott.png'),
                    'description' => 'Una opcion flexible y economica para un streaming diario limpio y estable.',
                    'plans' => [
                        ['name' => '1 mes', 'price' => '70', 'badge' => null, 'subtitle' => 'Acceso rapido para empezar', 'benefits' => ['Canales en vivo conocidos', 'Peliculas y series', 'Reproduccion estable', 'Soporte por WhatsApp']],
                        ['name' => '3 meses', 'price' => '149', 'badge' => 'Popular', 'subtitle' => 'El mejor equilibrio entre precio y tiempo', 'benefits' => ['Canales en vivo conocidos', 'Peliculas y series', 'Calidad Full HD y 4K', 'Soporte rapido']],
                        ['name' => '6 meses', 'price' => '249', 'badge' => null, 'subtitle' => 'Mas comodidad y mejor ahorro', 'benefits' => ['Acceso largo', 'Deportes y entretenimiento', 'Multi-dispositivo', 'Seguimiento del soporte']],
                        ['name' => '12 meses', 'price' => '449', 'badge' => 'Mejor valor', 'subtitle' => 'La mejor opcion anual', 'benefits' => ['Mejor precio anual', 'Mas estabilidad', 'Mayor prioridad', 'Tranquilidad todo el ano']],
                    ],
                ],
                [
                    'name' => 'T-REX',
                    'slug' => 't-rex',
                    'logo' => asset('images/plan-trex.png'),
                    'description' => 'Una linea mas premium para clientes que buscan mas potencia, deporte y estabilidad.',
                    'plans' => [
                        ['name' => '1 mes', 'price' => '99', 'badge' => null, 'subtitle' => 'Version mas fuerte para uso intensivo', 'benefits' => ['Deportes premium', 'Peliculas y series', 'Servidores mas potentes', 'Soporte rapido']],
                        ['name' => '3 meses', 'price' => '249', 'badge' => 'Mas vendido', 'subtitle' => 'La opcion favorita de T-REX', 'benefits' => ['Deporte y contenido premium', 'Alta calidad', 'Soporte prioritario', 'Valor equilibrado']],
                        ['name' => '6 meses', 'price' => '349', 'badge' => null, 'subtitle' => 'Mas potencia con mejor ahorro', 'benefits' => ['Comodidad a largo plazo', 'Servidores estables', 'Multi-dispositivo', 'Renovacion sencilla']],
                        ['name' => '12 meses', 'price' => '599', 'badge' => 'Anual', 'subtitle' => 'La mejor opcion a largo plazo', 'benefits' => ['Mejor precio anual', 'Alta prioridad', 'Estabilidad fuerte', 'Soporte continuo']],
                    ],
                ],
            ],
            default => [
                [
                    'name' => 'MAX OTT',
                    'slug' => 'max-ott',
                    'logo' => asset('images/plan-max-ott.png'),
                    'description' => 'A flexible, affordable pack for everyday streaming with a smooth premium feel.',
                    'plans' => [
                        ['name' => '1 Month', 'price' => '70', 'badge' => null, 'subtitle' => 'Quick access to get started', 'benefits' => ['Known live channels', 'Movies and series', 'Stable playback', 'WhatsApp support']],
                        ['name' => '3 Months', 'price' => '149', 'badge' => 'Popular', 'subtitle' => 'The best balance of value and duration', 'benefits' => ['Known live channels', 'Movies and series', 'Full HD and 4K quality', 'Fast support']],
                        ['name' => '6 Months', 'price' => '249', 'badge' => null, 'subtitle' => 'More comfort and better savings', 'benefits' => ['Longer access', 'Sports and entertainment', 'Multi-device use', 'Support follow-up']],
                        ['name' => '12 Months', 'price' => '449', 'badge' => 'Best Value', 'subtitle' => 'The smartest yearly option', 'benefits' => ['Best yearly price', 'More stability', 'Higher priority', 'Peace of mind all year']],
                    ],
                ],
                [
                    'name' => 'T-REX',
                    'slug' => 't-rex',
                    'logo' => asset('images/plan-trex.png'),
                    'description' => 'A stronger premium line for bigger sports, premium content, and more power users.',
                    'plans' => [
                        ['name' => '1 Month', 'price' => '99', 'badge' => null, 'subtitle' => 'A stronger version for heavier streaming', 'benefits' => ['Premium sports', 'Movies and series', 'Stronger servers', 'Fast support']],
                        ['name' => '3 Months', 'price' => '249', 'badge' => 'Best Seller', 'subtitle' => 'The favorite T-REX choice', 'benefits' => ['Sports and premium content', 'High quality playback', 'Priority support', 'Balanced value']],
                        ['name' => '6 Months', 'price' => '349', 'badge' => null, 'subtitle' => 'More power with better savings', 'benefits' => ['Long-term comfort', 'Stable servers', 'Multi-device support', 'Easy renewal']],
                        ['name' => '12 Months', 'price' => '599', 'badge' => 'Annual Pick', 'subtitle' => 'The best long-term commitment', 'benefits' => ['Best yearly price', 'Higher priority', 'Strong stability', 'Continuous support']],
                    ],
                ],
            ],
        };
        $initialFamilySlug = $planFamilies[0]['slug'] ?? 'max-ott';
    @endphp

    <section class="section-space">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="section-shell hero-shell">
                <div class="hero-stage">
                    <div class="row g-4 g-xl-5 align-items-center hero-grid">
                        <div class="col-xl-5">
                            <div class="hero-copy {{ $isArabic ? 'hero-copy-ar' : '' }}">
                                <span class="hero-kicker mb-3">{{ $hero['eyebrow'] }}</span>
                                <h1 class="hero-title {{ $isArabic ? 'hero-title-ar' : 'hero-title-compact' }} mb-4">
                                    <span class="d-block">{{ $hero['title_top'] }}</span>
                                    <span class="d-block accent">{{ $hero['title_bottom'] }}</span>
                                </h1>
                                <p class="hero-description text-muted-rif mb-4">{{ $hero['description'] }}</p>

                                <div class="hero-points d-flex flex-wrap gap-3 mb-4">
                                    @foreach ($miniPoints as $point)
                                        <span class="hero-point">
                                            <i data-lucide="check-circle-2" class="icon-sm"></i>
                                            <span>{{ $point }}</span>
                                        </span>
                                    @endforeach
                                </div>

                                <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                                    <a href="#plans" class="btn-rif-primary">{{ __('site.home.cta') }}</a>
                                    <a href="#support" class="btn-rif-outline">{{ __('site.home.support.whatsapp') }}</a>
                                </div>

                                <div class="row g-3 hero-stats">
                                    @foreach ($hero['stats'] as $stat)
                                        <div class="col-sm-4">
                                            <div class="metric-pill p-3 text-center h-100">
                                                <span class="hero-stat-value">{{ $stat }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-7">
                            <div class="hero-visual-panel">
                                <div class="hero-visual-media">
                                    <img src="{{ asset('images/hero-light.png') }}" alt="RIF IPTV premium streaming hero" class="hero-showcase-image-light">
                                    <img src="{{ asset('images/hero-dark.png') }}" alt="RIF IPTV premium streaming hero" class="hero-showcase-image-dark">
                                    <div class="hero-visual-scrim"></div>

                                    <div class="hero-visual-top">
                                        <span class="hero-visual-badge">{{ $hero['eyebrow'] }}</span>
                                        <span class="hero-visual-badge hero-visual-brand">RIF IPTV</span>
                                    </div>

                                    <div class="hero-device-card">
                                        <span class="hero-device-pill">{{ $hero['device_text'] }}</span>
                                        <p class="hero-device-copy mb-0">{{ $hero['note'] }}</p>
                                    </div>
                                </div>

                                <div class="hero-chip-grid mt-3">
                                    @foreach ($hero['chips'] as $chip)
                                        <div class="hero-chip-card">
                                            <span class="chip-icon">
                                                <i data-lucide="{{ $chip['icon'] }}" class="icon-sm"></i>
                                            </span>
                                            <span class="text-body-rif fw-semibold">{{ $chip['label'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="features" class="row g-4 mt-2">
                    @foreach ($featureHighlights as $feature)
                        <div class="col-md-6 col-xl-3">
                            <article class="surface-card feature-card p-4">
                                <span class="chip-icon mb-3">
                                    <i data-lucide="{{ $feature['icon'] }}" class="icon-sm"></i>
                                </span>
                                <h3 class="h4 text-body-rif mb-3">{{ $feature['title'] }}</h3>
                                <p class="text-soft-rif mb-0">{{ $feature['text'] }}</p>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="section-space-lg">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="section-shell p-4 p-lg-5">
                <div class="text-center mx-auto mb-5" style="max-width: 760px;">
                    <span class="section-kicker mb-3">{{ $payments['kicker'] }}</span>
                    <h2 class="section-title text-body-rif mb-3">{{ $payments['title'] }}</h2>
                    <p class="text-soft-rif fs-5 mb-0">{{ $payments['description'] }}</p>
                </div>

                <article class="payment-featured mb-4">
                    <div class="payment-featured-badge">{{ $payments['methods']['paddle']['badge'] }}</div>
                    <div class="payment-featured-inner">
                        <div class="payment-logo-wrap payment-logo-wrap-xl">
                            <img src="{{ asset($paymentLogos['paddle']) }}" alt="{{ $payments['methods']['paddle']['title'] }}" class="img-fluid payment-logo-image payment-logo-image-paddle">
                        </div>
                    </div>
                </article>

                <div class="payment-logo-grid">
                    @foreach (['cih', 'attijari', 'boa', 'chaabi', 'saham', 'cashplus'] as $paymentKey)
                        <article class="payment-logo-card">
                            <div class="payment-logo-wrap">
                                <img src="{{ asset($paymentLogos[$paymentKey]) }}" alt="{{ $payments['methods'][$paymentKey]['title'] }}" class="img-fluid payment-logo-image">
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="plans" class="section-space-lg">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="text-center mx-auto mb-5" style="max-width: 760px;">
                <span class="section-kicker mb-3">{{ $pricing['kicker'] }}</span>
                <h2 class="section-title text-body-rif mb-3">{{ $pricing['title'] }}</h2>
                <p class="text-soft-rif fs-5 mb-0">{{ $pricing['description'] }}</p>
            </div>

            <div class="pack-switcher" data-pack-switcher data-pack-default="{{ $initialFamilySlug }}">
                <div class="pack-toggle-bar mb-4" role="tablist" aria-label="Plan packs">
                    @foreach ($planFamilies as $family)
                        <button
                            type="button"
                            class="pack-toggle-btn {{ $family['slug'] === $initialFamilySlug ? 'is-active' : '' }}"
                            data-pack-toggle="{{ $family['slug'] }}"
                        >
                            <span class="pack-toggle-logo-wrap">
                                <img src="{{ $family['logo'] }}" alt="{{ $family['name'] }}" class="pack-toggle-logo">
                            </span>
                            <span class="pack-toggle-copy">
                                <strong>{{ $family['name'] }}</strong>
                                <small>{{ $family['description'] }}</small>
                            </span>
                        </button>
                    @endforeach
                </div>

                @foreach ($planFamilies as $family)
                    <article
                        class="surface-card family-pricing-shell p-4 p-lg-5 {{ $family['slug'] === 'max-ott' ? 'family-max' : 'family-trex' }} pack-panel {{ $family['slug'] === $initialFamilySlug ? 'is-active' : '' }}"
                        data-pack-panel="{{ $family['slug'] }}"
                    >
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4 mb-4">
                            <div class="family-pricing-brand">
                                <span class="family-pricing-logo-wrap">
                                    <img src="{{ $family['logo'] }}" alt="{{ $family['name'] }}" class="family-pricing-logo">
                                </span>
                                <div>
                                    <div class="text-soft-rif small text-uppercase fw-bold mb-2">{{ $pricing['label'] }}</div>
                                    <h3 class="h2 text-body-rif mb-2">{{ $family['name'] }}</h3>
                                    <p class="text-soft-rif mb-0">{{ $family['description'] }}</p>
                                </div>
                            </div>
                            <a href="{{ route('checkout') }}" class="btn-rif-outline family-pricing-cta">{{ $pricing['cta'] }}</a>
                        </div>

                        <div class="family-pricing-meta mb-4">
                            @foreach (collect($family['plans'])->flatMap(fn ($plan) => $plan['benefits'])->unique()->take(4) as $highlight)
                                <span class="family-pricing-meta-item">
                                    <i data-lucide="sparkles" class="icon-sm"></i>
                                    <span>{{ $highlight }}</span>
                                </span>
                            @endforeach
                        </div>

                        <div class="row g-3">
                            @foreach ($family['plans'] as $plan)
                                @php
                                    $badgeTone = $loop->index === 1 ? 'popular' : ($loop->last ? 'value' : 'default');
                                    $buttonClass = $loop->index === 1 ? 'btn-rif-primary' : 'btn-rif-secondary';
                                @endphp
                                <div class="col-sm-6 col-xl-3">
                                    <article class="family-plan-mini {{ !empty($plan['badge']) ? 'has-badge' : '' }} family-plan-tone-{{ $badgeTone }}">
                                        @if (!empty($plan['badge']))
                                            <span class="family-plan-badge family-plan-badge-{{ $badgeTone }}">{{ $plan['badge'] }}</span>
                                        @endif
                                        <span class="family-plan-label">{{ __('workflow.common.plan') }}</span>
                                        <div class="family-plan-name">{{ $plan['name'] }}</div>
                                        <p class="family-plan-subtitle">{{ $plan['subtitle'] }}</p>
                                        <div class="family-plan-price">{{ $plan['price'] }} <span>MAD</span></div>

                                        <ul class="family-plan-benefits">
                                            @foreach ($plan['benefits'] as $benefit)
                                                <li>
                                                    <span class="family-plan-check">
                                                        <i data-lucide="check" class="icon-sm"></i>
                                                    </span>
                                                    <span>{{ $benefit }}</span>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <a href="{{ route('checkout') }}" class="{{ $buttonClass }} w-100 mt-auto">{{ $pricing['cta'] }}</a>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-space-lg">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="row g-4">
                        @foreach ($benefits as $benefit)
                            <div class="col-md-6">
                                <article class="surface-card benefit-card p-4">
                                    <span class="chip-icon mb-3" style="background: rgba(214,0,58,0.12); color: var(--rif-red);">
                                        <i data-lucide="{{ $benefit['icon'] }}" class="icon-sm"></i>
                                    </span>
                                    <h3 class="h4 text-body-rif mb-3">{{ $benefit['title'] }}</h3>
                                    <p class="text-soft-rif mb-0">{{ $benefit['text'] }}</p>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-5">
                    <span class="section-kicker mb-3" style="background: rgba(122,199,12,0.12); color: var(--rif-green);">{{ __('site.home.benefits.kicker') }}</span>
                    <h2 class="section-title narrative-title text-body-rif mb-3">{{ __('site.home.benefits.title') }}</h2>
                    <p class="text-soft-rif fs-5 mb-0">{{ __('site.home.benefits.description') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section id="support" class="section-space pb-5">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="support-banner p-4 p-lg-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="hero-kicker mb-3">{{ __('site.home.support.kicker') }}</span>
                        <h2 class="section-title text-body-rif mb-3">{{ __('site.home.support.title') }}</h2>
                        <p class="text-soft-rif fs-5 mb-0">{{ __('site.home.support.description') }}</p>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-lg-end">
                            <a href="https://wa.me/212600000000" class="btn-rif-secondary">{{ __('site.home.support.whatsapp') }}</a>
                            <a href="#plans" class="btn-rif-outline">{{ __('site.home.support.plans') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-pack-switcher]').forEach(function (switcher) {
        const buttons = switcher.querySelectorAll('[data-pack-toggle]');
        const panels = switcher.querySelectorAll('[data-pack-panel]');

        const activate = function (slug) {
            buttons.forEach(function (button) {
                button.classList.toggle('is-active', button.getAttribute('data-pack-toggle') === slug);
            });

            panels.forEach(function (panel) {
                panel.classList.toggle('is-active', panel.getAttribute('data-pack-panel') === slug);
            });
        };

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                activate(button.getAttribute('data-pack-toggle'));
            });
        });
    });
});
</script>
@endpush
