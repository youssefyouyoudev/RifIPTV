@extends('layouts.app')

@section('title', __('site.home.title'))
@section('meta_description', __('site.home.meta_description'))
@section('body_class', 'page-home')

@section('structured_data')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => __('site.home.title'),
            'description' => __('site.home.meta_description'),
            'url' => request()->url(),
            'inLanguage' => app()->getLocale(),
            'primaryImageOfPage' => asset('images/hero-light.png'),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => __('site.home.pricing.title'),
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Basic / SUP'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Advanced / MAX'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => 'Premium / TREX'],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $isArabic = $locale === 'ar';
        $brandName = data_get(trans('site.brand'), 'name', 'Rifi Media');
        $heroLogo = asset('images/rifmedia-logo.png');
        $primaryCta = auth()->check() && Route::has('onboarding.show') ? route('onboarding.show') : route('register');
        $secondaryCta = 'https://wa.me/212600000000';
        $paymentLogos = [
            'paddle' => 'images/payment-paddle.jpg',
            'cih' => 'images/payment-cih-bank.jpg',
            'attijari' => 'images/payment-attijariwafa-bank.png',
            'boa' => 'images/payment-bank-of-africa.png',
            'chaabi' => 'images/payment-chaabi-bank.png',
            'saham' => 'images/payment-saham-bank.webp',
            'cashplus' => 'images/payment-cashplus.png',
        ];

        $ui = match ($locale) {
            'fr' => [
                'hero_eyebrow' => 'Service professionnel de configuration',
                'hero_title_top' => 'Une installation bien faite.',
                'hero_title_bottom' => 'Un support qui reste avec vous.',
                'hero_description' => 'Nous vous aidons a configurer vos appareils, installer vos applications utiles, organiser vos comptes et garder un suivi technique clair.',
                'hero_tagline' => 'Une presentation plus simple, plus fiable et plus rassurante pour vos besoins de mise en route.',
                'hero_points' => ['Processus securise', 'Support WhatsApp', 'Guide technique clair'],
                'hero_stats' => ['Setup professionnel', 'Suivi quotidien', 'TV / Mobile / Tablette'],
                'payment_kicker' => 'Options de paiement',
                'payment_title' => 'Des paiements clairs et securises pour les clients locaux et internationaux.',
                'payment_description' => 'Paiement international via Paddle et verification manuelle des virements locaux par notre equipe.',
                'payment_badge' => 'Paiement international',
                'payment_note' => 'Carte internationale avec confirmation numerique rapide.',
                'payment_local' => 'Partenaires locaux',
                'package_kicker' => 'Packages de service',
                'package_title' => 'Choisissez le package adapte a vos besoins de support.',
                'package_description' => 'Des offres simples pour la configuration, l accompagnement et le suivi technique.',
                'benefits_kicker' => 'Pourquoi nous choisir',
                'benefits_title' => 'Une interface plus propre, un support plus clair, et une experience plus calme.',
                'benefits_description' => 'Chaque etape est pensee pour rassurer, guider, et faciliter la communication avec le support.',
                'support_kicker' => 'Support',
                'support_title' => 'Besoin d aide avant de commander ?',
                'support_description' => 'Parlez avec notre equipe si vous voulez choisir le bon package ou comprendre les etapes de setup et de paiement.',
                'support_primary' => 'Support WhatsApp',
                'support_secondary' => 'Voir les packages',
            ],
            'es' => [
                'hero_eyebrow' => 'Servicio profesional de configuracion',
                'hero_title_top' => 'Configuracion bien hecha.',
                'hero_title_bottom' => 'Soporte que sigue contigo.',
                'hero_description' => 'Te ayudamos a configurar tus dispositivos, instalar aplicaciones utiles, organizar tus cuentas y mantener un soporte tecnico claro.',
                'hero_tagline' => 'Una presentacion mas limpia y profesional para clientes que quieren confianza y pasos faciles.',
                'hero_points' => ['Proceso seguro', 'Soporte por WhatsApp', 'Guia tecnica clara'],
                'hero_stats' => ['Setup profesional', 'Seguimiento diario', 'TV / Movil / Tablet'],
                'payment_kicker' => 'Opciones de pago',
                'payment_title' => 'Pagos claros y seguros para clientes locales e internacionales.',
                'payment_description' => 'Pago internacional con Paddle y validacion manual de transferencias locales por parte del equipo.',
                'payment_badge' => 'Pago internacional',
                'payment_note' => 'Tarjeta internacional con confirmacion digital rapida.',
                'payment_local' => 'Socios locales',
                'package_kicker' => 'Paquetes de servicio',
                'package_title' => 'Elige el paquete que mejor encaje con tu soporte tecnico.',
                'package_description' => 'Opciones simples para configuracion, acompanamiento y seguimiento.',
                'benefits_kicker' => 'Por que elegirnos',
                'benefits_title' => 'Una interfaz mas limpia, un soporte mas claro y una experiencia mas tranquila.',
                'benefits_description' => 'Cada seccion esta pensada para orientar al cliente y hacer que cada paso se entienda mejor.',
                'support_kicker' => 'Soporte',
                'support_title' => 'Necesitas ayuda antes de empezar?',
                'support_description' => 'Habla con nuestro equipo si quieres elegir el paquete correcto o entender mejor el proceso de setup y pago.',
                'support_primary' => 'WhatsApp Support',
                'support_secondary' => 'Ver paquetes',
            ],
            'ar' => [
                'hero_eyebrow' => 'Ø®Ø¯Ù…Ø© Ø¥Ø¹Ø¯Ø§Ø¯ Ø£Ø¬Ù‡Ø²Ø© Ø§Ø­ØªØ±Ø§ÙÙŠØ©',
                'hero_title_top' => 'Ø¥Ø¹Ø¯Ø§Ø¯ ØµØ­ÙŠØ­',
                'hero_title_bottom' => 'ÙˆØ¯Ø¹Ù… ÙŠØ¨Ù‚Ù‰ Ù…Ø¹Ùƒ',
                'hero_description' => 'Ù†Ø³Ø§Ø¹Ø¯Ùƒ ÙÙŠ Ø¥Ø¹Ø¯Ø§Ø¯ Ø§Ù„ØªÙ„ÙØ§Ø² Ø§Ù„Ø°ÙƒÙŠ ÙˆØ§Ù„ØªØ·Ø¨ÙŠÙ‚Ø§Øª ÙˆØ§Ù„Ø­Ø³Ø§Ø¨Ø§Øª ÙˆØ§Ù„Ù…ØªØ§Ø¨Ø¹Ø© Ø§Ù„ØªÙ‚Ù†ÙŠØ© Ø¨Ø®Ø·ÙˆØ§Øª ÙˆØ§Ø¶Ø­Ø© ÙˆÙˆØ§Ø¬Ù‡Ø© Ø³Ù‡Ù„Ø© ÙˆÙØ±ÙŠÙ‚ Ø¯Ø¹Ù… Ø­Ù‚ÙŠÙ‚ÙŠ.',
                'hero_tagline' => 'Ù…Ù† Ø£ÙˆÙ„ Ø¥Ø¹Ø¯Ø§Ø¯ Ø¥Ù„Ù‰ Ø¢Ø®Ø± Ù…ØªØ§Ø¨Ø¹Ø©ØŒ Ù†Ø¨Ù‚ÙŠ Ø§Ù„ØªØ¬Ø±Ø¨Ø© Ù…Ø±ØªØ¨Ø© ÙˆØ¢Ù…Ù†Ø© ÙˆØ³Ù‡Ù„Ø© Ø§Ù„ÙÙ‡Ù….',
                'hero_points' => ['Ø¥Ø¬Ø±Ø§Ø¡ Ø¢Ù…Ù†', 'Ù…ØªØ§Ø¨Ø¹Ø© Ø¹Ø¨Ø± ÙˆØ§ØªØ³Ø§Ø¨', 'Ø¥Ø±Ø´Ø§Ø¯ ØªÙ‚Ù†ÙŠ ÙˆØ§Ø¶Ø­'],
                'hero_stats' => ['Ø¥Ø¹Ø¯Ø§Ø¯ Ø§Ø­ØªØ±Ø§ÙÙŠ', 'Ø§Ø³ØªØ¬Ø§Ø¨Ø© ÙŠÙˆÙ…ÙŠØ©', 'TV / Mobile / Tablet'],
                'payment_kicker' => 'Ø®ÙŠØ§Ø±Ø§Øª Ø§Ù„Ø¯ÙØ¹',
                'payment_title' => 'Ø¯ÙØ¹ ÙˆØ§Ø¶Ø­ ÙˆØ¢Ù…Ù† Ù„Ù„Ø¹Ù…Ù„Ø§Ø¡ Ø§Ù„Ù…Ø­Ù„ÙŠÙŠÙ† ÙˆØ§Ù„Ø¯ÙˆÙ„ÙŠÙŠÙ†.',
                'payment_description' => 'Ù†ÙˆÙØ± Ø¯ÙØ¹Ø§ Ø¯ÙˆÙ„ÙŠØ§ Ø¢Ù…Ù†Ø§ Ø¹Ø¨Ø± Paddle Ù…Ø¹ Ù…Ø±Ø§Ø¬Ø¹Ø© ÙŠØ¯ÙˆÙŠØ© Ù„Ù„ØªØ­ÙˆÙŠÙ„Ø§Øª Ø§Ù„Ø¨Ù†ÙƒÙŠØ© Ø§Ù„Ù…Ø­Ù„ÙŠØ©.',
                'payment_badge' => 'Ø¯ÙØ¹ Ø¯ÙˆÙ„ÙŠ',
                'payment_note' => 'Ø¨Ø·Ø§Ù‚Ø© Ø¯ÙˆÙ„ÙŠØ© Ù…Ø¹ ØªØ£ÙƒÙŠØ¯ Ø±Ù‚Ù…ÙŠ Ø³Ø±ÙŠØ¹.',
                'payment_local' => 'Ø´Ø±ÙƒØ§Ø¡ Ø§Ù„ØªØ­ÙˆÙŠÙ„ Ø§Ù„Ù…Ø­Ù„ÙŠ',
                'package_kicker' => 'Ø¨Ø§Ù‚Ø§Øª Ø§Ù„Ø®Ø¯Ù…Ø©',
                'package_title' => 'Ø§Ø®ØªØ± Ø¨Ø§Ù‚Ø© Ø§Ù„Ø¯Ø¹Ù… ÙˆØ§Ù„Ø¥Ø¹Ø¯Ø§Ø¯ Ø§Ù„Ù…Ù†Ø§Ø³Ø¨Ø© Ù„Ùƒ.',
                'package_description' => 'Ø£Ø³Ø¹Ø§Ø± ÙˆØ§Ø¶Ø­Ø© Ù„Ø®Ø¯Ù…Ø§Øª Ø§Ù„Ø¥Ø¹Ø¯Ø§Ø¯ØŒ Ø§Ù„Ù…Ø³Ø§Ø¹Ø¯Ø© ÙÙŠ Ø§Ù„ØªØ«Ø¨ÙŠØªØŒ ÙˆØ§Ù„Ù…ØªØ§Ø¨Ø¹Ø© Ø§Ù„ØªÙ‚Ù†ÙŠØ© Ø§Ù„Ù…Ø³ØªÙ…Ø±Ø©.',
                'benefits_kicker' => 'Ù„Ù…Ø§Ø°Ø§ ÙŠØ®ØªØ§Ø±Ù†Ø§ Ø§Ù„Ø¹Ù…Ù„Ø§Ø¡',
                'benefits_title' => 'ÙˆØ§Ø¬Ù‡Ø© Ø£Ù†Ø¸ÙØŒ ÙˆØ¯Ø¹Ù… Ø£ÙˆØ¶Ø­ØŒ ÙˆØªØ¬Ø±Ø¨Ø© Ø£ÙƒØ«Ø± Ø±Ø§Ø­Ø©.',
                'benefits_description' => 'Ù†Ø¨Ù†ÙŠ Ø§Ù„Ø®Ø¯Ù…Ø© Ø­ÙˆÙ„ Ø§Ù„ÙˆØ¶ÙˆØ­ ÙˆØ§Ù„Ø«Ù‚Ø© ÙˆØ³Ù‡ÙˆÙ„Ø© Ø§Ù„Ø®Ø·ÙˆØ§ØªØŒ Ø­ØªÙ‰ ÙŠØ¹Ø±Ù Ø§Ù„Ø¹Ù…ÙŠÙ„ Ø¯Ø§Ø¦Ù…Ø§ Ù…Ø§ Ù‡ÙŠ Ø§Ù„Ø®Ø·ÙˆØ© Ø§Ù„ØªØ§Ù„ÙŠØ©.',
                'support_kicker' => 'Ø§Ù„Ø¯Ø¹Ù…',
                'support_title' => 'Ù‡Ù„ ØªØ­ØªØ§Ø¬ Ù…Ø³Ø§Ø¹Ø¯Ø© Ù‚Ø¨Ù„ ØªÙ†ÙÙŠØ° Ø§Ù„Ø·Ù„Ø¨ØŸ',
                'support_description' => 'ØªÙˆØ§ØµÙ„ Ù…Ø¹Ù†Ø§ Ø¥Ø°Ø§ ÙƒÙ†Øª ØªØ­ØªØ§Ø¬ Ù…Ø³Ø§Ø¹Ø¯Ø© ÙÙŠ Ø§Ø®ØªÙŠØ§Ø± Ø§Ù„Ø¨Ø§Ù‚Ø© Ø§Ù„Ù…Ù†Ø§Ø³Ø¨Ø© Ø£Ùˆ ÙÙ‡Ù… Ø®Ø·ÙˆØ§Øª Ø§Ù„Ø¥Ø¹Ø¯Ø§Ø¯ ÙˆØ§Ù„Ø¯ÙØ¹.',
                'support_primary' => 'Ø¯Ø¹Ù… ÙˆØ§ØªØ³Ø§Ø¨',
                'support_secondary' => 'Ø¹Ø±Ø¶ Ø§Ù„Ø¨Ø§Ù‚Ø§Øª',
            ],
            default => [
                'hero_eyebrow' => 'Professional device setup service',
                'hero_title_top' => 'Setup done right.',
                'hero_title_bottom' => 'Support that stays with you.',
                'hero_description' => 'We help clients configure devices, install the right apps, organize account details, and keep technical support simple from the first step onward.',
                'hero_tagline' => 'A cleaner front-end experience that feels trustworthy, practical, and easy to follow.',
                'hero_points' => ['Secure process', 'WhatsApp support', 'Clear technical guidance'],
                'hero_stats' => ['Professional setup', 'Daily follow-up', 'TV / Mobile / Tablet'],
                'payment_kicker' => 'Payment options',
                'payment_title' => 'Secure payment options for local and international clients.',
                'payment_description' => 'International card payment is available through Paddle, while local transfers are reviewed and confirmed manually by support.',
                'payment_badge' => 'International payment',
                'payment_note' => 'Fast digital confirmation for clients paying by card.',
                'payment_local' => 'Local transfer partners',
                'package_kicker' => 'Service packages',
                'package_title' => 'Choose the support package that fits your setup needs.',
                'package_description' => 'Three clear service plans built around setup guidance, troubleshooting, and reliable follow-up.',
                'benefits_kicker' => 'Why clients choose us',
                'benefits_title' => 'A cleaner interface, clearer support, and a calmer client experience.',
                'benefits_description' => 'Every section is designed to reduce confusion, build trust, and make the next step obvious.',
                'support_kicker' => 'Support',
                'support_title' => 'Need help before you place an order?',
                'support_description' => 'Talk to our team if you need help choosing the right package or understanding the setup and payment process.',
                'support_primary' => 'WhatsApp support',
                'support_secondary' => 'View packages',
            ],
        };
    @endphp

    {{-- Hero Section --}}
    <section class="section-space">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="section-shell hero-shell">
                <div class="hero-stage home-hero-stage">
                    <div class="row g-4 g-xl-5 align-items-center hero-grid">
                        <div class="col-xl-5">
                            <div class="hero-copy {{ $isArabic ? 'hero-copy-ar' : '' }} reveal-up">
                                <span class="hero-kicker mb-3">{{ $ui['hero_eyebrow'] }}</span>
                                <h1 class="hero-title {{ $isArabic ? 'hero-title-ar' : 'hero-title-compact' }} mb-4">
                                    <span class="d-block">{{ $ui['hero_title_top'] }}</span>
                                    <span class="d-block accent">{{ $ui['hero_title_bottom'] }}</span>
                                </h1>
                                <p class="hero-description text-muted-rif mb-4">{{ $ui['hero_description'] }}</p>
                                <div class="hero-points d-flex flex-wrap gap-3 mb-4">
                                    @foreach ($ui['hero_points'] as $point)
                                        <span class="hero-point">
                                            <i data-lucide="check-circle-2" class="icon-sm"></i>
                                            <span>{{ $point }}</span>
                                        </span>
                                    @endforeach
                                </div>
                                <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                                    <a href="{{ $primaryCta }}" class="btn-rif-primary">{{ __('site.home.cta') }}</a>
                                    <a href="{{ $secondaryCta }}" class="btn-rif-outline" target="_blank" rel="noopener">{{ __('site.home.support.whatsapp') }}</a>
                                </div>
                                <div class="row g-3 hero-stats">
                                    @foreach ($ui['hero_stats'] as $stat)
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
                            <div class="hero-visual-panel reveal-up">
                                <div class="hero-brand-stage home-brand-stage">
                                    <div class="hero-brand-orb hero-brand-orb-blue"></div>
                                    <div class="hero-brand-orb hero-brand-orb-green"></div>
                                    <div class="hero-brand-orb hero-brand-orb-yellow"></div>
                                    <div class="hero-visual-top">
                                        <span class="hero-visual-badge">{{ $brandName }}</span>
                                        <span class="hero-visual-badge hero-visual-brand">{{ $ui['hero_eyebrow'] }}</span>
                                    </div>
                                    <div class="home-brand-layout">
                                        <div class="home-brand-card">
                                            <div class="home-brand-logo-shell">
                                                <img src="{{ $heroLogo }}" alt="{{ $brandName }} logo" class="home-brand-logo">
                                            </div>
                                            <p class="home-brand-tagline mb-0">{{ $ui['hero_tagline'] }}</p>
                                        </div>
                                        <div class="home-brand-service-grid">
                                            @foreach (match ($locale) {
                                                'fr' => [
                                                    ['icon' => 'monitor-cog', 'title' => 'Configuration', 'text' => 'Mise en route de Smart TV et d appareils associes.'],
                                                    ['icon' => 'download', 'title' => 'Installation guidee', 'text' => 'Aide concrete pour installer et organiser vos apps.'],
                                                    ['icon' => 'shield-check', 'title' => 'Processus fiable', 'text' => 'Des etapes claires avec validation et suivi humain.'],
                                                    ['icon' => 'messages-square', 'title' => 'Support technique', 'text' => 'Une equipe disponible avant et apres la commande.'],
                                                ],
                                                'es' => [
                                                    ['icon' => 'monitor-cog', 'title' => 'Configuracion', 'text' => 'Puesta en marcha de Smart TV y equipos relacionados.'],
                                                    ['icon' => 'download', 'title' => 'Instalacion guiada', 'text' => 'Ayuda practica para instalar y ordenar tus apps.'],
                                                    ['icon' => 'shield-check', 'title' => 'Proceso fiable', 'text' => 'Pasos claros con validacion y seguimiento humano.'],
                                                    ['icon' => 'messages-square', 'title' => 'Soporte tecnico', 'text' => 'Un equipo disponible antes y despues del pedido.'],
                                                ],
                                                'ar' => [
                                                    ['icon' => 'monitor-cog', 'title' => 'Ø¥Ø¹Ø¯Ø§Ø¯ Ø§Ù„Ø£Ø¬Ù‡Ø²Ø©', 'text' => 'ØªØ¬Ù‡ÙŠØ² Ø§Ù„ØªÙ„ÙØ§Ø² Ø§Ù„Ø°ÙƒÙŠ ÙˆØ§Ù„Ø£Ø¬Ù‡Ø²Ø© Ø§Ù„Ù…Ø±ØªØ¨Ø·Ø© Ø¨Ù‡.'],
                                                    ['icon' => 'download', 'title' => 'ØªØ«Ø¨ÙŠØª Ø§Ù„ØªØ·Ø¨ÙŠÙ‚Ø§Øª', 'text' => 'Ù…Ø³Ø§Ø¹Ø¯Ø© Ø¹Ù…Ù„ÙŠØ© ÙÙŠ Ø§Ù„ØªØ«Ø¨ÙŠØª ÙˆØ§Ù„Ø®Ø·ÙˆØ§Øª Ø§Ù„Ø£ÙˆÙ„Ù‰.'],
                                                    ['icon' => 'shield-check', 'title' => 'ØªÙˆØ¬ÙŠÙ‡ Ø¢Ù…Ù†', 'text' => 'ØªØ¹Ù„ÙŠÙ…Ø§Øª ÙˆØ§Ø¶Ø­Ø© Ù…Ø¹ Ù…ØªØ§Ø¨Ø¹Ø© Ù…Ø±ÙŠØ­Ø© ÙˆØ¢Ù…Ù†Ø©.'],
                                                    ['icon' => 'messages-square', 'title' => 'Ø¯Ø¹Ù… ØªÙ‚Ù†ÙŠ', 'text' => 'ÙØ±ÙŠÙ‚ Ø¨Ø´Ø±ÙŠ ÙŠØ±Ø§ÙÙ‚Ùƒ Ù‚Ø¨Ù„ ÙˆØ¨Ø¹Ø¯ Ø§Ù„Ø·Ù„Ø¨.'],
                                                ],
                                                default => [
                                                    ['icon' => 'monitor-cog', 'title' => 'Device setup', 'text' => 'Smart TV and connected-device configuration done with guidance.'],
                                                    ['icon' => 'download', 'title' => 'App guidance', 'text' => 'Practical help for installation, login flow, and first-time setup.'],
                                                    ['icon' => 'shield-check', 'title' => 'Trusted process', 'text' => 'Clear support steps with secure payment follow-up.'],
                                                    ['icon' => 'messages-square', 'title' => 'Technical support', 'text' => 'A real support team stays available before and after your order.'],
                                                ],
                                            } as $tile)
                                                <article class="home-brand-tile">
                                                    <span class="chip-icon"><i data-lucide="{{ $tile['icon'] }}" class="icon-sm"></i></span>
                                                    <div>
                                                        <h3 class="h6 text-body-rif mb-1">{{ $tile['title'] }}</h3>
                                                        <p class="text-soft-rif mb-0">{{ $tile['text'] }}</p>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="features" class="row g-4 mt-2">
                    @foreach (match ($locale) {
                        'fr' => [
                            ['icon' => 'monitor-cog', 'title' => 'Configuration', 'text' => 'Mise en route de Smart TV et d appareils associes.'],
                            ['icon' => 'download', 'title' => 'Installation guidee', 'text' => 'Aide concrete pour installer et organiser vos apps.'],
                            ['icon' => 'shield-check', 'title' => 'Processus fiable', 'text' => 'Des etapes claires avec validation et suivi humain.'],
                            ['icon' => 'messages-square', 'title' => 'Support technique', 'text' => 'Une equipe disponible avant et apres la commande.'],
                        ],
                        'es' => [
                            ['icon' => 'monitor-cog', 'title' => 'Configuracion', 'text' => 'Puesta en marcha de Smart TV y equipos relacionados.'],
                            ['icon' => 'download', 'title' => 'Instalacion guiada', 'text' => 'Ayuda practica para instalar y ordenar tus apps.'],
                            ['icon' => 'shield-check', 'title' => 'Proceso fiable', 'text' => 'Pasos claros con validacion y seguimiento humano.'],
                            ['icon' => 'messages-square', 'title' => 'Soporte tecnico', 'text' => 'Un equipo disponible antes y despues del pedido.'],
                        ],
                        'ar' => [
                            ['icon' => 'monitor-cog', 'title' => 'Ø¥Ø¹Ø¯Ø§Ø¯ Ø§Ù„Ø£Ø¬Ù‡Ø²Ø©', 'text' => 'ØªØ¬Ù‡ÙŠØ² Ø§Ù„ØªÙ„ÙØ§Ø² Ø§Ù„Ø°ÙƒÙŠ ÙˆØ§Ù„Ø£Ø¬Ù‡Ø²Ø© Ø§Ù„Ù…Ø±ØªØ¨Ø·Ø© Ø¨Ù‡.'],
                            ['icon' => 'download', 'title' => 'ØªØ«Ø¨ÙŠØª Ø§Ù„ØªØ·Ø¨ÙŠÙ‚Ø§Øª', 'text' => 'Ù…Ø³Ø§Ø¹Ø¯Ø© Ø¹Ù…Ù„ÙŠØ© ÙÙŠ Ø§Ù„ØªØ«Ø¨ÙŠØª ÙˆØ§Ù„Ø®Ø·ÙˆØ§Øª Ø§Ù„Ø£ÙˆÙ„Ù‰.'],
                            ['icon' => 'shield-check', 'title' => 'ØªÙˆØ¬ÙŠÙ‡ Ø¢Ù…Ù†', 'text' => 'ØªØ¹Ù„ÙŠÙ…Ø§Øª ÙˆØ§Ø¶Ø­Ø© Ù…Ø¹ Ù…ØªØ§Ø¨Ø¹Ø© Ù…Ø±ÙŠØ­Ø© ÙˆØ¢Ù…Ù†Ø©.'],
                            ['icon' => 'messages-square', 'title' => 'Ø¯Ø¹Ù… ØªÙ‚Ù†ÙŠ', 'text' => 'ÙØ±ÙŠÙ‚ Ø¨Ø´Ø±ÙŠ ÙŠØ±Ø§ÙÙ‚Ùƒ Ù‚Ø¨Ù„ ÙˆØ¨Ø¹Ø¯ Ø§Ù„Ø·Ù„Ø¨.'],
                        ],
                        default => [
                            ['icon' => 'monitor-cog', 'title' => 'Device setup', 'text' => 'Smart TV and connected-device configuration done with guidance.'],
                            ['icon' => 'download', 'title' => 'App guidance', 'text' => 'Practical help for installation, login flow, and first-time setup.'],
                            ['icon' => 'shield-check', 'title' => 'Trusted process', 'text' => 'Clear support steps with secure payment follow-up.'],
                            ['icon' => 'messages-square', 'title' => 'Technical support', 'text' => 'A real support team stays available before and after your order.'],
                        ],
                    } as $tile)
                        <div class="col-md-6 col-xl-3">
                            <article class="surface-card feature-card home-feature-card p-4 reveal-up">
                                <span class="chip-icon mb-3"><i data-lucide="{{ $tile['icon'] }}" class="icon-sm"></i></span>
                                <h3 class="h4 text-body-rif mb-3">{{ $tile['title'] }}</h3>
                                <p class="text-soft-rif mb-0">{{ $tile['text'] }}</p>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @php
        $trustSignals = match ($locale) {
            'fr' => [
                'headline' => 'Clarte, verification humaine et accompagnement responsable.',
                'items' => ['Partout au Maroc', 'Support humain', 'Paiement verifie', 'Processus transparent'],
                'disclaimer' => 'Nous ne fournissons ni n hebergeons de contenu media. Nous aidons uniquement a la configuration des appareils, a l installation utile et au support technique.',
            ],
            'es' => [
                'headline' => 'Claridad, revision humana y un proceso que genera confianza.',
                'items' => ['En todo Marruecos', 'Soporte humano', 'Pago verificado', 'Proceso transparente'],
                'disclaimer' => 'No proporcionamos ni alojamos contenido multimedia. Solo ayudamos con configuracion de dispositivos, instalacion util y soporte tecnico.',
            ],
            'ar' => [
                'headline' => 'ÙˆØ¶ÙˆØ­ ÙÙŠ Ø§Ù„Ø®Ø·ÙˆØ§ØªØŒ ÙˆÙ…Ø±Ø§Ø¬Ø¹Ø© Ø¨Ø´Ø±ÙŠØ©ØŒ ÙˆØªØ¬Ø±Ø¨Ø© Ù…Ø¨Ù†ÙŠØ© Ø¹Ù„Ù‰ Ø§Ù„Ø«Ù‚Ø©.',
                'items' => ['Ù…Ø±Ø§ÙƒØ´ØŒ Ø§Ù„Ù…ØºØ±Ø¨', 'Ø¯Ø¹Ù… Ø¨Ø´Ø±ÙŠ', 'Ø¯ÙØ¹ Ù…ÙˆØ«Ù‚', 'Ø¹Ù…Ù„ÙŠØ© ÙˆØ§Ø¶Ø­Ø©'],
                'disclaimer' => 'Ù†Ø­Ù† Ù„Ø§ Ù†ÙˆÙØ± ÙˆÙ„Ø§ Ù†Ø³ØªØ¶ÙŠÙ Ø£ÙŠ Ù…Ø­ØªÙˆÙ‰ Ø¥Ø¹Ù„Ø§Ù…ÙŠ. Ù†Ø­Ù† Ù†Ø³Ø§Ø¹Ø¯ ÙÙ‚Ø· ÙÙŠ Ø¥Ø¹Ø¯Ø§Ø¯ Ø§Ù„Ø£Ø¬Ù‡Ø²Ø©ØŒ Ø¶Ø¨Ø· Ø§Ù„ØªØ·Ø¨ÙŠÙ‚Ø§ØªØŒ ÙˆØ§Ù„Ø¯Ø¹Ù… Ø§Ù„ØªÙ‚Ù†ÙŠ.',
            ],
            default => [
                'headline' => 'Clear steps, human review, and a support process built for trust.',
                'items' => ['Across Morocco', 'Human guidance', 'Verified payment flow', 'Transparent process'],
                'disclaimer' => 'We do not provide or host media content. We only assist with device configuration, app setup, and technical support.',
            ],
        };
    @endphp

    <section class="section-space">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="surface-card trust-strip-shell p-4 p-lg-5 reveal-up">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-5">
                        <span class="section-kicker mb-3">{{ $locale === 'ar' ? 'Ø¥Ø´Ø§Ø±Ø§Øª Ø§Ù„Ø«Ù‚Ø©' : ($locale === 'fr' ? 'Signaux de confiance' : ($locale === 'es' ? 'Senales de confianza' : 'Trust signals')) }}</span>
                        <h2 class="h2 text-body-rif mb-3">{{ $trustSignals['headline'] }}</h2>
                        <p class="text-soft-rif mb-0">{{ $trustSignals['disclaimer'] }}</p>
                    </div>
                    <div class="col-lg-7">
                        <div class="trust-strip-grid">
                            @foreach ($trustSignals['items'] as $signal)
                                <article class="trust-strip-item">
                                    <i data-lucide="badge-check" class="icon-sm"></i>
                                    <span>{{ $signal }}</span>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-space-lg">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="section-shell p-4 p-lg-5">
                <div class="text-center mx-auto mb-5 reveal-up" style="max-width: 760px;">
                    <span class="section-kicker mb-3">{{ $ui['payment_kicker'] }}</span>
                    <h2 class="section-title text-body-rif mb-3">{{ $ui['payment_title'] }}</h2>
                    <p class="text-soft-rif fs-5 mb-0">{{ $ui['payment_description'] }}</p>
                </div>
                <article class="payment-featured mb-4 reveal-up">
                    <div class="payment-featured-badge">{{ $ui['payment_badge'] }}</div>
                    <div class="payment-featured-inner">
                        <div class="payment-logo-wrap payment-logo-wrap-xl">
                            <img src="{{ asset($paymentLogos['paddle']) }}" alt="Paddle" class="img-fluid payment-logo-image payment-logo-image-paddle">
                        </div>
                        <p class="payment-featured-note mb-0">{{ $ui['payment_note'] }}</p>
                    </div>
                </article>
                <div class="payment-partners-head reveal-up">
                    <span class="payment-partners-label">{{ $ui['payment_local'] }}</span>
                </div>
                <div class="payment-logo-grid mb-4">
                    @foreach (['cih', 'attijari', 'boa', 'chaabi', 'saham', 'cashplus'] as $paymentKey)
                        <article class="payment-logo-card reveal-up">
                            <div class="payment-logo-wrap">
                                <img src="{{ asset($paymentLogos[$paymentKey]) }}" alt="{{ strtoupper($paymentKey) }} logo" class="img-fluid payment-logo-image">
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="payment-trust-row">
                    @foreach (match ($locale) {
                        'fr' => ['Verification manuelle', 'Confirmation WhatsApp', 'Etapes claires'],
                        'es' => ['Validacion manual', 'Confirmacion por WhatsApp', 'Pasos claros'],
                        'ar' => ['Ù…Ø±Ø§Ø¬Ø¹Ø© ÙŠØ¯ÙˆÙŠØ© Ù„Ù„ØªØ­ÙˆÙŠÙ„', 'ØªØ£ÙƒÙŠØ¯ Ø¹Ø¨Ø± ÙˆØ§ØªØ³Ø§Ø¨', 'Ø®Ø·ÙˆØ§Øª Ø¯ÙØ¹ ÙˆØ§Ø¶Ø­Ø©'],
                        default => ['Manual transfer review', 'WhatsApp confirmation', 'Clear payment steps'],
                    } as $trustPoint)
                        <span class="payment-trust-pill reveal-up">
                            <i data-lucide="badge-check" class="icon-sm"></i>
                            <span>{{ $trustPoint }}</span>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @php
        $howWeWork = match ($locale) {
            'fr' => [
                'kicker' => 'Comment nous travaillons',
                'title' => 'Un parcours simple en quatre etapes.',
                'items' => [
                    ['step' => '01', 'title' => 'Demande', 'text' => 'Vous nous expliquez votre appareil, votre besoin et votre contexte.'],
                    ['step' => '02', 'title' => 'Revue', 'text' => 'Nous verifions la demande, le package, et la methode de paiement adaptee.'],
                    ['step' => '03', 'title' => 'Guidage et setup', 'text' => 'Nous vous accompagnons pour la configuration, l installation utile et les verifications.'],
                    ['step' => '04', 'title' => 'Suivi', 'text' => 'Le client garde un point de contact clair pour les questions et ajustements.'],
                ],
            ],
            'es' => [
                'kicker' => 'Como trabajamos',
                'title' => 'Un proceso simple en cuatro pasos.',
                'items' => [
                    ['step' => '01', 'title' => 'Solicitud', 'text' => 'Nos explicas tu dispositivo, necesidad y contexto.'],
                    ['step' => '02', 'title' => 'Revision', 'text' => 'Revisamos el caso, el paquete adecuado y el metodo de pago.'],
                    ['step' => '03', 'title' => 'Guia y configuracion', 'text' => 'Te acompanamos en la configuracion, instalacion util y verificaciones.'],
                    ['step' => '04', 'title' => 'Seguimiento', 'text' => 'Mantienes un punto de contacto claro para dudas y ajustes.'],
                ],
            ],
            'ar' => [
                'kicker' => 'ÙƒÙŠÙ Ù†Ø¹Ù…Ù„',
                'title' => 'Ø±Ø­Ù„Ø© Ø¨Ø³ÙŠØ·Ø© Ù…Ù† Ø£Ø±Ø¨Ø¹ Ù…Ø±Ø§Ø­Ù„ ÙˆØ§Ø¶Ø­Ø©.',
                'items' => [
                    ['step' => '01', 'title' => 'Ø§Ù„Ø·Ù„Ø¨', 'text' => 'ØªØ´Ø±Ø­ Ù„Ù†Ø§ Ø§Ù„Ø¬Ù‡Ø§Ø² ÙˆØ§Ù„Ø§Ø­ØªÙŠØ§Ø¬ ÙˆØ§Ù„Ø³ÙŠØ§Ù‚ Ø§Ù„Ø¹Ù…Ù„ÙŠ Ø§Ù„Ù…Ø·Ù„ÙˆØ¨.'],
                    ['step' => '02', 'title' => 'Ø§Ù„Ù…Ø±Ø§Ø¬Ø¹Ø©', 'text' => 'Ù†Ø±Ø§Ø¬Ø¹ Ø§Ù„Ø­Ø§Ù„Ø© ÙˆÙ†Ø­Ø¯Ø¯ Ø§Ù„Ø¨Ø§Ù‚Ø© Ø§Ù„Ù…Ù†Ø§Ø³Ø¨Ø© ÙˆØ·Ø±ÙŠÙ‚Ø© Ø§Ù„Ø¯ÙØ¹ Ø§Ù„Ù…Ù„Ø§Ø¦Ù…Ø©.'],
                    ['step' => '03', 'title' => 'Ø§Ù„Ø¥Ø±Ø´Ø§Ø¯ ÙˆØ§Ù„Ø¥Ø¹Ø¯Ø§Ø¯', 'text' => 'Ù†Ø±Ø§ÙÙ‚Ùƒ ÙÙŠ Ø§Ù„Ø¥Ø¹Ø¯Ø§Ø¯ ÙˆØ¶Ø¨Ø· Ø§Ù„ØªØ·Ø¨ÙŠÙ‚Ø§Øª ÙˆØ§Ù„Ø®Ø·ÙˆØ§Øª Ø§Ù„Ø¹Ù…Ù„ÙŠØ© Ø§Ù„Ù…Ù‡Ù…Ø©.'],
                    ['step' => '04', 'title' => 'Ø§Ù„Ù…ØªØ§Ø¨Ø¹Ø©', 'text' => 'ÙŠØ¨Ù‚Ù‰ Ù„Ø¯ÙŠÙƒ Ù…Ø³Ø§Ø± ÙˆØ§Ø¶Ø­ Ù„Ù„ØªÙˆØ§ØµÙ„ ÙˆØ§Ù„Ø§Ø³ØªÙØ³Ø§Ø± ÙˆØ§Ù„ØªØ­Ø³ÙŠÙ† Ø¹Ù†Ø¯ Ø§Ù„Ø­Ø§Ø¬Ø©.'],
                ],
            ],
            default => [
                'kicker' => 'How we work',
                'title' => 'A clean four-step process built for confidence.',
                'items' => [
                    ['step' => '01', 'title' => 'Request', 'text' => 'You tell us about your device, your goal, and the setup context.'],
                    ['step' => '02', 'title' => 'Review', 'text' => 'We review the request, recommend the right package, and confirm the payment path.'],
                    ['step' => '03', 'title' => 'Guidance and setup', 'text' => 'We guide the configuration, app setup, and key technical checks.'],
                    ['step' => '04', 'title' => 'Follow-up', 'text' => 'You keep a clear support contact for questions, refinements, and practical help.'],
                ],
            ],
        };
    @endphp

    <section class="section-space">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="surface-card p-4 p-lg-5 reveal-up">
                <div class="text-center mx-auto mb-5" style="max-width: 760px;">
                    <span class="section-kicker mb-3">{{ $howWeWork['kicker'] }}</span>
                    <h2 class="section-title text-body-rif mb-3">{{ $howWeWork['title'] }}</h2>
                </div>
                <div class="row g-4">
                    @foreach ($howWeWork['items'] as $item)
                        <div class="col-md-6 col-xl-3">
                            <article class="workflow-card home-workflow-card h-100">
                                <span class="workflow-step-number">{{ $item['step'] }}</span>
                                <h3 class="h4 text-body-rif mt-3 mb-2">{{ $item['title'] }}</h3>
                                <p class="text-soft-rif mb-0">{{ $item['text'] }}</p>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @php
        $planFamilies = match ($locale) {
            'fr' => [
                [
                    'slug' => 'sup',
                    'title' => 'Basic / SUP',
                    'subtitle' => 'La formule simple pour demarrer proprement.',
                    'plans' => [
                        ['duration' => '3 mois', 'price' => '89', 'badge' => null, 'features' => ['Configuration de base', 'Aide a l installation', 'Support WhatsApp', 'Suivi simple']],
                        ['duration' => '6 mois', 'price' => '149', 'badge' => 'Populaire', 'features' => ['Multi-appareils', 'Verification du setup', 'Support plus long', 'Meilleur prix']],
                        ['duration' => '12 mois', 'price' => '199', 'badge' => 'Meilleure valeur', 'features' => ['Support annuel', 'Suivi regulier', 'Priorite douce', 'Economies annuelles']],
                    ],
                ],
                [
                    'slug' => 'max',
                    'title' => 'Advanced / MAX',
                    'subtitle' => 'Un meilleur equilibre pour les besoins plus suivis.',
                    'plans' => [
                        ['duration' => '3 mois', 'price' => '149', 'badge' => null, 'features' => ['Setup avance', 'Aide multi-appareils', 'Assistance pratique', 'Priorite plus forte']],
                        ['duration' => '6 mois', 'price' => '249', 'badge' => 'Recommande', 'features' => ['Optimisation durable', 'Diagnostic technique', 'Suivi etendu', 'Confort longue duree']],
                        ['duration' => '12 mois', 'price' => '449', 'badge' => null, 'features' => ['Accompagnement annuel', 'Stabilite renforcee', 'Support continu', 'Meilleure couverture']],
                    ],
                ],
                [
                    'slug' => 'trex',
                    'title' => 'Premium / TREX',
                    'subtitle' => 'La formule la plus forte pour un suivi complet.',
                    'plans' => [
                        ['duration' => '3 mois', 'price' => '249', 'badge' => null, 'features' => ['Support premium', 'Suivi detaille', 'Aide avancee', 'Traitement prioritaire']],
                        ['duration' => '6 mois', 'price' => '349', 'badge' => 'Best seller', 'features' => ['Support intensif', 'Assistance longue', 'Revue technique', 'Plus de tranquillite']],
                        ['duration' => '12 mois', 'price' => '599', 'badge' => 'Annuel', 'features' => ['Support complet', 'Suivi annuel', 'Priorite maximale', 'Couverture etendue']],
                    ],
                ],
            ],
            'es' => [
                [
                    'slug' => 'sup',
                    'title' => 'Basic / SUP',
                    'subtitle' => 'La opcion simple para empezar con claridad.',
                    'plans' => [
                        ['duration' => '3 meses', 'price' => '89', 'badge' => null, 'features' => ['Configuracion base', 'Ayuda de instalacion', 'Soporte por WhatsApp', 'Seguimiento simple']],
                        ['duration' => '6 meses', 'price' => '149', 'badge' => 'Popular', 'features' => ['Multi-dispositivo', 'Revision del setup', 'Soporte mas largo', 'Mejor precio']],
                        ['duration' => '12 meses', 'price' => '199', 'badge' => 'Mejor valor', 'features' => ['Soporte anual', 'Seguimiento regular', 'Prioridad suave', 'Ahorro anual']],
                    ],
                ],
                [
                    'slug' => 'max',
                    'title' => 'Advanced / MAX',
                    'subtitle' => 'Mejor equilibrio para clientes que necesitan mas apoyo.',
                    'plans' => [
                        ['duration' => '3 meses', 'price' => '149', 'badge' => null, 'features' => ['Setup avanzado', 'Ayuda multi-dispositivo', 'Asistencia practica', 'Mayor prioridad']],
                        ['duration' => '6 meses', 'price' => '249', 'badge' => 'Recomendado', 'features' => ['Optimizacion duradera', 'Diagnostico tecnico', 'Seguimiento extendido', 'Mas comodidad']],
                        ['duration' => '12 meses', 'price' => '449', 'badge' => null, 'features' => ['Acompanamiento anual', 'Mas estabilidad', 'Soporte continuo', 'Cobertura mas fuerte']],
                    ],
                ],
                [
                    'slug' => 'trex',
                    'title' => 'Premium / TREX',
                    'subtitle' => 'La opcion mas fuerte para seguimiento completo.',
                    'plans' => [
                        ['duration' => '3 meses', 'price' => '249', 'badge' => null, 'features' => ['Soporte premium', 'Seguimiento detallado', 'Ayuda avanzada', 'Tratamiento prioritario']],
                        ['duration' => '6 meses', 'price' => '349', 'badge' => 'Mas vendido', 'features' => ['Soporte intensivo', 'Asistencia larga', 'Revision tecnica', 'Mas tranquilidad']],
                        ['duration' => '12 meses', 'price' => '599', 'badge' => 'Anual', 'features' => ['Soporte completo', 'Seguimiento anual', 'Prioridad maxima', 'Cobertura extendida']],
                    ],
                ],
            ],
            'ar' => [
                [
                    'slug' => 'sup',
                    'title' => 'Basic / SUP',
                    'subtitle' => 'Ø§Ù„Ø®ÙŠØ§Ø± Ø§Ù„Ø¨Ø³ÙŠØ· Ù„Ù„Ø¨Ø¯Ø§ÙŠØ© Ø¨Ø´ÙƒÙ„ ÙˆØ§Ø¶Ø­ ÙˆÙ…Ø±ØªØ¨.',
                    'plans' => [
                        ['duration' => '3 Ø£Ø´Ù‡Ø±', 'price' => '89', 'badge' => null, 'features' => ['Ø¥Ø¹Ø¯Ø§Ø¯ Ø£Ø³Ø§Ø³ÙŠ', 'Ù…Ø³Ø§Ø¹Ø¯Ø© ÙÙŠ Ø§Ù„ØªØ«Ø¨ÙŠØª', 'Ø¯Ø¹Ù… ÙˆØ§ØªØ³Ø§Ø¨', 'Ù…ØªØ§Ø¨Ø¹Ø© Ø¨Ø³ÙŠØ·Ø©']],
                        ['duration' => '6 Ø£Ø´Ù‡Ø±', 'price' => '149', 'badge' => 'Ø§Ù„Ø£ÙƒØ«Ø± Ø·Ù„Ø¨Ø§', 'features' => ['Ø£Ø¬Ù‡Ø²Ø© Ù…ØªØ¹Ø¯Ø¯Ø©', 'Ù…Ø±Ø§Ø¬Ø¹Ø© Ø§Ù„Ø¥Ø¹Ø¯Ø§Ø¯', 'Ø¯Ø¹Ù… Ø£Ø·ÙˆÙ„', 'Ø³Ø¹Ø± Ø£ÙØ¶Ù„']],
                        ['duration' => '12 Ø´Ù‡Ø±Ø§', 'price' => '199', 'badge' => 'Ø£ÙØ¶Ù„ Ù‚ÙŠÙ…Ø©', 'features' => ['Ø¯Ø¹Ù… Ø³Ù†ÙˆÙŠ', 'Ù…ØªØ§Ø¨Ø¹Ø© Ù…Ù†ØªØ¸Ù…Ø©', 'Ø£ÙˆÙ„ÙˆÙŠØ© Ù‡Ø§Ø¯Ø¦Ø©', 'ØªÙˆÙÙŠØ± Ø³Ù†ÙˆÙŠ']],
                    ],
                ],
                [
                    'slug' => 'max',
                    'title' => 'Advanced / MAX',
                    'subtitle' => 'ØªÙˆØ§Ø²Ù† Ø£ÙØ¶Ù„ Ù„Ù„Ø¹Ù…Ù„Ø§Ø¡ Ø§Ù„Ø°ÙŠÙ† ÙŠØ­ØªØ§Ø¬ÙˆÙ† Ù…ØªØ§Ø¨Ø¹Ø© Ø£Ù‚ÙˆÙ‰.',
                    'plans' => [
                        ['duration' => '3 Ø£Ø´Ù‡Ø±', 'price' => '149', 'badge' => null, 'features' => ['Ø¥Ø¹Ø¯Ø§Ø¯ Ù…ØªÙ‚Ø¯Ù…', 'Ø¯Ø¹Ù… Ù…ØªØ¹Ø¯Ø¯ Ø§Ù„Ø£Ø¬Ù‡Ø²Ø©', 'Ù…Ø³Ø§Ø¹Ø¯Ø© Ø¹Ù…Ù„ÙŠØ©', 'Ø£ÙˆÙ„ÙˆÙŠØ© Ø£Ø¹Ù„Ù‰']],
                        ['duration' => '6 Ø£Ø´Ù‡Ø±', 'price' => '249', 'badge' => 'Ù…ÙˆØµÙ‰ Ø¨Ù‡', 'features' => ['ØªØ­Ø³ÙŠÙ† Ù…Ø³ØªÙ…Ø±', 'ØªØ´Ø®ÙŠØµ ØªÙ‚Ù†ÙŠ', 'Ù…ØªØ§Ø¨Ø¹Ø© Ù…Ù…ØªØ¯Ø©', 'Ø±Ø§Ø­Ø© Ø£ÙƒØ¨Ø±']],
                        ['duration' => '12 Ø´Ù‡Ø±Ø§', 'price' => '449', 'badge' => null, 'features' => ['Ù…Ø±Ø§ÙÙ‚Ø© Ø³Ù†ÙˆÙŠØ©', 'Ø§Ø³ØªÙ‚Ø±Ø§Ø± Ø£Ù‚ÙˆÙ‰', 'Ø¯Ø¹Ù… Ù…Ø³ØªÙ…Ø±', 'ØªØºØ·ÙŠØ© Ø£ÙØ¶Ù„']],
                    ],
                ],
                [
                    'slug' => 'trex',
                    'title' => 'Premium / TREX',
                    'subtitle' => 'Ø§Ù„Ø®ÙŠØ§Ø± Ø§Ù„Ø£Ù‚ÙˆÙ‰ Ù„Ù…Ù† ÙŠØ±ÙŠØ¯ Ù…ØªØ§Ø¨Ø¹Ø© ÙƒØ§Ù…Ù„Ø© ÙˆÙ…Ø³ØªÙ…Ø±Ø©.',
                    'plans' => [
                        ['duration' => '3 Ø£Ø´Ù‡Ø±', 'price' => '249', 'badge' => null, 'features' => ['Ø¯Ø¹Ù… Ø¨Ø±ÙŠÙ…ÙŠÙˆÙ…', 'Ù…ØªØ§Ø¨Ø¹Ø© Ù…ÙØµÙ„Ø©', 'Ù…Ø³Ø§Ø¹Ø¯Ø© Ù…ØªÙ‚Ø¯Ù…Ø©', 'Ø£ÙˆÙ„ÙˆÙŠØ© ÙƒØ§Ù…Ù„Ø©']],
                        ['duration' => '6 Ø£Ø´Ù‡Ø±', 'price' => '349', 'badge' => 'Ø§Ù„Ø£ÙƒØ«Ø± Ù…Ø¨ÙŠØ¹Ø§', 'features' => ['Ø¯Ø¹Ù… Ù…ÙƒØ«Ù', 'Ù…Ø±Ø§ÙÙ‚Ø© Ø£Ø·ÙˆÙ„', 'Ù…Ø±Ø§Ø¬Ø¹Ø© ØªÙ‚Ù†ÙŠØ©', 'Ø·Ù…Ø£Ù†ÙŠÙ†Ø© Ø£ÙƒØ¨Ø±']],
                        ['duration' => '12 Ø´Ù‡Ø±Ø§', 'price' => '599', 'badge' => 'Ø³Ù†ÙˆÙŠ', 'features' => ['Ø¯Ø¹Ù… Ø´Ø§Ù…Ù„', 'Ù…ØªØ§Ø¨Ø¹Ø© Ø³Ù†ÙˆÙŠØ©', 'Ø£Ø¹Ù„Ù‰ Ø£ÙˆÙ„ÙˆÙŠØ©', 'ØªØºØ·ÙŠØ© Ù…ÙˆØ³Ø¹Ø©']],
                    ],
                ],
            ],
            default => [
                [
                    'slug' => 'sup',
                    'title' => 'Basic / SUP',
                    'subtitle' => 'A simple starting option with clear guidance.',
                    'plans' => [
                        ['duration' => '3 Months', 'price' => '89', 'badge' => null, 'features' => ['Basic setup', 'Installation help', 'WhatsApp support', 'Simple follow-up']],
                        ['duration' => '6 Months', 'price' => '149', 'badge' => 'Popular', 'features' => ['Multi-device help', 'Setup review', 'Longer support', 'Better value']],
                        ['duration' => '12 Months', 'price' => '199', 'badge' => 'Best value', 'features' => ['Annual support', 'Regular follow-up', 'Gentle priority', 'Yearly savings']],
                    ],
                ],
                [
                    'slug' => 'max',
                    'title' => 'Advanced / MAX',
                    'subtitle' => 'A stronger balance for clients who need more support.',
                    'plans' => [
                        ['duration' => '3 Months', 'price' => '149', 'badge' => null, 'features' => ['Advanced setup', 'Multi-device guidance', 'Practical help', 'Higher priority']],
                        ['duration' => '6 Months', 'price' => '249', 'badge' => 'Recommended', 'features' => ['Longer optimization', 'Technical diagnosis', 'Extended follow-up', 'More comfort']],
                        ['duration' => '12 Months', 'price' => '449', 'badge' => null, 'features' => ['Annual assistance', 'Stronger stability', 'Ongoing support', 'Better coverage']],
                    ],
                ],
                [
                    'slug' => 'trex',
                    'title' => 'Premium / TREX',
                    'subtitle' => 'The strongest option for deeper long-term support.',
                    'plans' => [
                        ['duration' => '3 Months', 'price' => '249', 'badge' => null, 'features' => ['Premium support', 'Detailed follow-up', 'Advanced help', 'Priority handling']],
                        ['duration' => '6 Months', 'price' => '349', 'badge' => 'Best seller', 'features' => ['Intensive support', 'Longer assistance', 'Technical review', 'More peace of mind']],
                        ['duration' => '12 Months', 'price' => '599', 'badge' => 'Annual', 'features' => ['Full support', 'Year-round follow-up', 'Highest priority', 'Extended coverage']],
                    ],
                ],
            ],
        };
        $initialFamilySlug = 'sup';
    @endphp

    <section id="plans" class="section-space-lg">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="text-center mx-auto mb-5 reveal-up" style="max-width: 760px;">
                <span class="section-kicker mb-3">{{ $ui['package_kicker'] }}</span>
                <h2 class="section-title text-body-rif mb-3">{{ $ui['package_title'] }}</h2>
                <p class="text-soft-rif fs-5 mb-0">{{ $ui['package_description'] }}</p>
            </div>
            <div class="pack-switcher reveal-up" data-pack-switcher data-pack-default="{{ $initialFamilySlug }}">
                <div class="pack-toggle-bar mb-4" role="tablist" aria-label="Package families">
                    @foreach ($planFamilies as $family)
                        <button type="button" class="pack-toggle-btn {{ $family['slug'] === $initialFamilySlug ? 'is-active' : '' }}" data-pack-toggle="{{ $family['slug'] }}">
                            <span class="pack-toggle-logo-wrap"><strong>{{ strtoupper($family['slug']) }}</strong></span>
                            <span class="pack-toggle-copy">
                                <strong>{{ $family['title'] }}</strong>
                                <small>{{ $family['subtitle'] }}</small>
                            </span>
                        </button>
                    @endforeach
                </div>
                @foreach ($planFamilies as $family)
                    <article class="surface-card family-pricing-shell p-4 p-lg-5 pack-panel {{ $family['slug'] === $initialFamilySlug ? 'is-active' : '' }}" data-pack-panel="{{ $family['slug'] }}">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4 mb-4">
                            <div class="family-pricing-brand">
                                <span class="family-pricing-logo-wrap d-inline-flex align-items-center justify-content-center">{{ strtoupper($family['slug']) }}</span>
                                <div>
                                    <div class="text-soft-rif small text-uppercase fw-bold mb-2">{{ $ui['package_kicker'] }}</div>
                                    <h3 class="h2 text-body-rif mb-2">{{ $family['title'] }}</h3>
                                    <p class="text-soft-rif mb-0">{{ $family['subtitle'] }}</p>
                                </div>
                            </div>
                            <a href="{{ $primaryCta }}" class="btn-rif-outline family-pricing-cta">{{ $locale === 'fr' ? 'Choisir' : ($locale === 'es' ? 'Elegir' : ($locale === 'ar' ? 'Ø§Ø®ØªÙŠØ§Ø±' : 'Choose')) }}</a>
                        </div>
                        <div class="row g-3">
                            @foreach ($family['plans'] as $index => $plan)
                                <div class="col-md-6 col-xl-4">
                                    <article class="service-plan-card {{ $index === 1 ? 'service-plan-card-featured' : '' }}">
                                        @if ($plan['badge'])
                                            <span class="service-plan-badge">{{ $plan['badge'] }}</span>
                                        @endif
                                        <div class="service-plan-head">
                                            <div>
                                                <span class="service-plan-label">{{ strtoupper($family['slug']) }}</span>
                                                <h3 class="service-plan-name">{{ $plan['duration'] }}</h3>
                                            </div>
                                        </div>
                                        <div class="service-plan-price">{{ $plan['price'] }} <span>MAD</span></div>
                                        <ul class="service-plan-features">
                                            @foreach ($plan['features'] as $feature)
                                                <li>
                                                    <span class="family-plan-check"><i data-lucide="check" class="icon-sm"></i></span>
                                                    <span>{{ $feature }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <a href="{{ $primaryCta }}" class="{{ $index === 1 ? 'btn-rif-primary' : 'btn-rif-secondary' }} w-100 mt-auto">{{ $locale === 'fr' ? 'Continuer' : ($locale === 'es' ? 'Continuar' : ($locale === 'ar' ? 'Ù…ØªØ§Ø¨Ø¹Ø©' : 'Continue')) }}</a>
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
                        @foreach (match ($locale) {
                            'fr' => [
                                ['icon' => 'sparkles', 'title' => 'Onboarding propre', 'text' => 'Une premiere impression plus claire et plus professionnelle.'],
                                ['icon' => 'messages-square', 'title' => 'Support humain', 'text' => 'Une vraie conversation pour les questions pratiques.'],
                                ['icon' => 'shield-check', 'title' => 'Confiance', 'text' => 'Paiement, verification et suivi presentes avec clarte.'],
                                ['icon' => 'refresh-cw', 'title' => 'Suivi lisible', 'text' => 'Le client sait toujours ou il en est.'],
                            ],
                            'es' => [
                                ['icon' => 'sparkles', 'title' => 'Inicio ordenado', 'text' => 'Una primera impresion mas clara y profesional.'],
                                ['icon' => 'messages-square', 'title' => 'Soporte humano', 'text' => 'Conversacion real para resolver dudas practicas.'],
                                ['icon' => 'shield-check', 'title' => 'Confianza', 'text' => 'Pago, revision y seguimiento presentados con claridad.'],
                                ['icon' => 'refresh-cw', 'title' => 'Seguimiento simple', 'text' => 'El cliente siempre entiende su siguiente paso.'],
                            ],
                            'ar' => [
                                ['icon' => 'sparkles', 'title' => 'Ø¨Ø¯Ø§ÙŠØ© Ù…Ø±ØªØ¨Ø©', 'text' => 'Ø±Ø­Ù„Ø© Ø¹Ù…ÙŠÙ„ ÙˆØ§Ø¶Ø­Ø© Ù…Ù† Ø§Ù„ØµÙØ­Ø© Ø§Ù„Ø£ÙˆÙ„Ù‰ Ø¥Ù„Ù‰ Ø§Ù„Ø¯Ø¹Ù….'],
                                ['icon' => 'messages-square', 'title' => 'Ø¯Ø¹Ù… Ø¨Ø´Ø±ÙŠ', 'text' => 'Ù…Ø­Ø§Ø¯Ø«Ø© Ø­Ù‚ÙŠÙ‚ÙŠØ© ÙˆÙ…ØªØ§Ø¨Ø¹Ø© Ø¹Ù…Ù„ÙŠØ© Ø¹Ø¨Ø± ÙˆØ§ØªØ³Ø§Ø¨.'],
                                ['icon' => 'shield-check', 'title' => 'Ø«Ù‚Ø© ÙˆØ£Ù…Ø§Ù†', 'text' => 'Ù…Ø±Ø§Ø¬Ø¹Ø© ÙŠØ¯ÙˆÙŠØ© ÙˆØ®Ø·ÙˆØ§Øª Ø¯ÙØ¹ ÙˆØ¯Ø¹Ù… Ù…ÙÙ‡ÙˆÙ…Ø©.'],
                                ['icon' => 'refresh-cw', 'title' => 'Ù…ØªØ§Ø¨Ø¹Ø© Ø¨Ø³ÙŠØ·Ø©', 'text' => 'ØªÙ‚Ø¯Ù… ÙˆØ§Ø¶Ø­ ÙÙŠ ÙƒÙ„ Ù…Ø±Ø­Ù„Ø© Ø¯ÙˆÙ† Ø§Ø±ØªØ¨Ø§Ùƒ.'],
                            ],
                            default => [
                                ['icon' => 'sparkles', 'title' => 'Professional onboarding', 'text' => 'A stronger first impression from the moment a client lands on the site.'],
                                ['icon' => 'messages-square', 'title' => 'Human support', 'text' => 'Real conversation for setup, payment, and practical questions.'],
                                ['icon' => 'shield-check', 'title' => 'Trust-first flow', 'text' => 'Clear guidance for payment, confirmation, and technical follow-up.'],
                                ['icon' => 'refresh-cw', 'title' => 'Simple next steps', 'text' => 'Clients always know what happens next and where to click.'],
                            ],
                        } as $benefit)
                            <div class="col-md-6">
                                <article class="surface-card benefit-card p-4 reveal-up">
                                    <span class="chip-icon mb-3" style="background: rgba(214,0,58,0.12); color: var(--rif-red);"><i data-lucide="{{ $benefit['icon'] }}" class="icon-sm"></i></span>
                                    <h3 class="h4 text-body-rif mb-3">{{ $benefit['title'] }}</h3>
                                    <p class="text-soft-rif mb-0">{{ $benefit['text'] }}</p>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="benefits-copy reveal-up">
                        <span class="section-kicker mb-3" style="background: rgba(122,199,12,0.12); color: var(--rif-green);">{{ $ui['benefits_kicker'] }}</span>
                        <h2 class="section-title narrative-title text-body-rif mb-3">{{ $ui['benefits_title'] }}</h2>
                        <p class="text-soft-rif fs-5 mb-0">{{ $ui['benefits_description'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $testimonials = match ($locale) {
            'fr' => [
                ['name' => 'Samir', 'role' => 'Client au Maroc', 'quote' => 'Le parcours etait clair du debut a la fin, surtout pour le paiement et la mise en route.'],
                ['name' => 'Leila', 'role' => 'Support local', 'quote' => 'J ai apprecie le ton calme, la verification humaine, et la rapidite des retours.'],
                ['name' => 'Omar', 'role' => 'Client prive', 'quote' => 'On comprend enfin quoi faire, a quel moment, et avec qui parler si quelque chose bloque.'],
            ],
            'es' => [
                ['name' => 'Samir', 'role' => 'Cliente en Marruecos', 'quote' => 'El proceso fue claro de principio a fin, sobre todo en pago y configuracion.'],
                ['name' => 'Leila', 'role' => 'Soporte local', 'quote' => 'Me gusto el tono profesional, la revision humana y la rapidez del seguimiento.'],
                ['name' => 'Omar', 'role' => 'Cliente privado', 'quote' => 'Por fin una experiencia donde entiendes que hacer, cuando hacerlo y con quien hablar.'],
            ],
            'ar' => [
                ['name' => 'Ø³Ù…ÙŠØ±', 'role' => 'Ø¹Ù…ÙŠÙ„ Ù…Ù† Ù…Ø±Ø§ÙƒØ´', 'quote' => 'ÙƒÙ„ Ø´ÙŠØ¡ ÙƒØ§Ù† ÙˆØ§Ø¶Ø­Ù‹Ø§ Ù…Ù† Ø§Ù„Ø¨Ø¯Ø§ÙŠØ© Ø­ØªÙ‰ Ø§Ù„Ù†Ù‡Ø§ÙŠØ©ØŒ Ø®ØµÙˆØµÙ‹Ø§ ÙÙŠ Ø®Ø·ÙˆØ§Øª Ø§Ù„Ø¯ÙØ¹ ÙˆØ§Ù„Ø¥Ø¹Ø¯Ø§Ø¯.'],
                ['name' => 'Ù„ÙŠÙ„Ù‰', 'role' => 'Ù…ØªØ§Ø¨Ø¹Ø© Ù…Ø­Ù„ÙŠØ©', 'quote' => 'Ø£Ø¹Ø¬Ø¨Ù†ÙŠ Ø§Ù„Ù‡Ø¯ÙˆØ¡ ÙÙŠ Ø§Ù„ÙˆØ§Ø¬Ù‡Ø© ÙˆØ·Ø±ÙŠÙ‚Ø© Ø§Ù„Ù…Ø±Ø§Ø¬Ø¹Ø© Ø§Ù„Ø¨Ø´Ø±ÙŠØ© ÙˆØ³Ø±Ø¹Ø© Ø§Ù„ØªÙØ§Ø¹Ù„.'],
                ['name' => 'Ø¹Ù…Ø±', 'role' => 'Ø¹Ù…ÙŠÙ„ Ø®Ø§Øµ', 'quote' => 'Ø£Ø®ÙŠØ±Ù‹Ø§ ØªØ¬Ø±Ø¨Ø© ØªØ´Ø±Ø­ Ù„Ùƒ Ù…Ø§ Ø§Ù„Ø°ÙŠ ÙŠØ¬Ø¨ ÙØ¹Ù„Ù‡ ÙˆÙ…ØªÙ‰ ÙˆÙ…Ø¹ Ù…Ù† ØªØªÙˆØ§ØµÙ„ Ø¹Ù†Ø¯ Ø§Ù„Ø­Ø§Ø¬Ø©.'],
            ],
            default => [
                ['name' => 'Samir', 'role' => 'Client in Morocco', 'quote' => 'The whole journey felt clear from the first step, especially around payment review and setup.'],
                ['name' => 'Leila', 'role' => 'Local support client', 'quote' => 'I liked the calm tone, the human review, and the way every step was explained.'],
                ['name' => 'Omar', 'role' => 'Private client', 'quote' => 'It finally feels like a service site that explains what happens next instead of confusing the client.'],
            ],
        };
        $faqItems = match ($locale) {
            'fr' => [
                ['q' => 'Que propose exactement Rifi Media ?', 'a' => 'Nous proposons l aide a la configuration, l accompagnement applicatif, l assistance technique et le suivi humain.'],
                ['q' => 'Recevez-vous des paiements internationaux ?', 'a' => 'Oui, via Paddle pour les cartes internationales, avec verification manuelle pour les transferts locaux.'],
                ['q' => 'Fournissez-vous du contenu media ?', 'a' => 'Non. Nous ne fournissons ni n hebergeons de contenu. Nous aidons uniquement a la configuration et au support technique.'],
                ['q' => 'Comment se passe le suivi apres commande ?', 'a' => 'Le client garde un tableau de bord, un contact humain et un historique clair des etapes.'],
            ],
            'es' => [
                ['q' => 'Que ofrece exactamente Rifi Media?', 'a' => 'Ofrecemos ayuda de configuracion, guia de aplicaciones, asistencia tecnica y seguimiento humano.'],
                ['q' => 'Aceptan pagos internacionales?', 'a' => 'Si, mediante Paddle para tarjetas internacionales, con revision manual para transferencias locales.'],
                ['q' => 'Proporcionan contenido multimedia?', 'a' => 'No. No proporcionamos ni alojamos contenido. Solo ayudamos con configuracion y soporte tecnico.'],
                ['q' => 'Como funciona el seguimiento despues del pedido?', 'a' => 'El cliente conserva un panel, un contacto humano y un historial claro de pasos.'],
            ],
            'ar' => [
                ['q' => 'Ù…Ø§ Ø§Ù„Ø°ÙŠ ØªÙ‚Ø¯Ù…Ù‡ Rifi Media Ø¨Ø§Ù„Ø¶Ø¨Ø·ØŸ', 'a' => 'Ù†Ù‚Ø¯Ù… Ø§Ù„Ù…Ø³Ø§Ø¹Ø¯Ø© ÙÙŠ Ø¥Ø¹Ø¯Ø§Ø¯ Ø§Ù„Ø£Ø¬Ù‡Ø²Ø©ØŒ ÙˆØ¶Ø¨Ø· Ø§Ù„ØªØ·Ø¨ÙŠÙ‚Ø§ØªØŒ ÙˆØ§Ù„Ù…ØªØ§Ø¨Ø¹Ø© Ø§Ù„ØªÙ‚Ù†ÙŠØ©ØŒ ÙˆØ§Ù„Ø¯Ø¹Ù… Ø§Ù„Ø¨Ø´Ø±ÙŠ Ø§Ù„ÙˆØ§Ø¶Ø­.'],
                ['q' => 'Ù‡Ù„ ØªÙ‚Ø¨Ù„ÙˆÙ† Ø§Ù„Ø¯ÙØ¹ Ø§Ù„Ø¯ÙˆÙ„ÙŠØŸ', 'a' => 'Ù†Ø¹Ù…ØŒ Ø¹Ø¨Ø± Paddle Ù„Ù„Ø¨Ø·Ø§Ù‚Ø§Øª Ø§Ù„Ø¯ÙˆÙ„ÙŠØ©ØŒ Ù…Ø¹ Ù…Ø±Ø§Ø¬Ø¹Ø© ÙŠØ¯ÙˆÙŠØ© Ù„Ù„ØªØ­ÙˆÙŠÙ„Ø§Øª Ø§Ù„Ù…Ø­Ù„ÙŠØ©.'],
                ['q' => 'Ù‡Ù„ ØªÙˆÙØ±ÙˆÙ† Ù…Ø­ØªÙˆÙ‰ Ø¥Ø¹Ù„Ø§Ù…ÙŠÙ‹Ø§ØŸ', 'a' => 'Ù„Ø§. Ù†Ø­Ù† Ù„Ø§ Ù†ÙˆÙØ± ÙˆÙ„Ø§ Ù†Ø³ØªØ¶ÙŠÙ Ø£ÙŠ Ù…Ø­ØªÙˆÙ‰ØŒ Ø¨Ù„ Ù†Ù‚Ø¯Ù… ÙÙ‚Ø· Ø®Ø¯Ù…Ø§Øª Ø§Ù„Ø¥Ø¹Ø¯Ø§Ø¯ ÙˆØ§Ù„Ø¯Ø¹Ù… Ø§Ù„ØªÙ‚Ù†ÙŠ.'],
                ['q' => 'ÙƒÙŠÙ ØªØªÙ… Ø§Ù„Ù…ØªØ§Ø¨Ø¹Ø© Ø¨Ø¹Ø¯ Ø§Ù„Ø·Ù„Ø¨ØŸ', 'a' => 'ÙŠØ­ØµÙ„ Ø§Ù„Ø¹Ù…ÙŠÙ„ Ø¹Ù„Ù‰ Ù„ÙˆØ­Ø© Ù…ØªØ§Ø¨Ø¹Ø© ÙˆÙ…Ø³Ø§Ø± ÙˆØ§Ø¶Ø­ Ù„Ù„ØªÙˆØ§ØµÙ„ ÙˆØ³Ø¬Ù„ Ù…Ù†Ø¸Ù… Ù„Ù„Ø­Ø§Ù„Ø© ÙˆØ§Ù„Ø®Ø·ÙˆØ§Øª.'],
            ],
            default => [
                ['q' => 'What does Rifi Media actually provide?', 'a' => 'We provide device setup help, app guidance, technical troubleshooting, and practical follow-up from a real team.'],
                ['q' => 'Do you accept international payments?', 'a' => 'Yes. International card payments can be reviewed through Paddle, while local transfers are confirmed manually.'],
                ['q' => 'Do you provide media content?', 'a' => 'No. We do not provide or host media content. We only assist with device configuration, app setup, and technical support.'],
                ['q' => 'What happens after the order is placed?', 'a' => 'The client gets a clearer workflow, payment status visibility, and human guidance for the next technical steps.'],
            ],
        };
    @endphp

    <section class="section-space">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="surface-card p-4 p-lg-5 h-100 reveal-up">
                        <span class="section-kicker mb-3">{{ $locale === 'ar' ? 'Ø¢Ø±Ø§Ø¡ Ø§Ù„Ø¹Ù…Ù„Ø§Ø¡' : ($locale === 'fr' ? 'Temoignages' : ($locale === 'es' ? 'Testimonios' : 'Testimonials')) }}</span>
                        <h2 class="section-title text-body-rif mb-4">{{ $locale === 'ar' ? 'Ø«Ù‚Ø© ØªÙØ¨Ù†Ù‰ Ù…Ù† Ø®Ù„Ø§Ù„ ØªØ¬Ø±Ø¨Ø© ÙˆØ§Ø¶Ø­Ø©.' : ($locale === 'fr' ? 'La confiance passe par une experience claire.' : ($locale === 'es' ? 'La confianza empieza con una experiencia clara.' : 'Trust starts with a clearer client experience.')) }}</h2>
                        <div class="d-grid gap-3">
                            @foreach ($testimonials as $testimonial)
                                <article class="surface-card p-4">
                                    <p class="text-body-rif fs-5 mb-3">â€œ{{ $testimonial['quote'] }}â€</p>
                                    <div class="fw-semibold text-body-rif">{{ $testimonial['name'] }}</div>
                                    <div class="text-soft-rif small">{{ $testimonial['role'] }}</div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="surface-card p-4 p-lg-5 h-100 reveal-up">
                        <span class="section-kicker mb-3">{{ $locale === 'ar' ? 'Ø§Ù„Ø£Ø³Ø¦Ù„Ø© Ø§Ù„Ø´Ø§Ø¦Ø¹Ø©' : ($locale === 'fr' ? 'FAQ' : ($locale === 'es' ? 'FAQ' : 'FAQ')) }}</span>
                        <h2 class="section-title text-body-rif mb-4">{{ $locale === 'ar' ? 'Ø¥Ø¬Ø§Ø¨Ø§Øª Ø³Ø±ÙŠØ¹Ø© Ø¹Ù„Ù‰ Ø£Ù‡Ù… Ø§Ù„Ø£Ø³Ø¦Ù„Ø©.' : ($locale === 'fr' ? 'Des reponses claires aux questions importantes.' : ($locale === 'es' ? 'Respuestas claras a las preguntas mas importantes.' : 'Clear answers to the questions clients ask most.')) }}</h2>
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
            </div>
        </div>
    </section>

    @php
        $resourceLinks = match ($locale) {
            'fr' => [
                'kicker' => 'Explorer le site',
                'title' => 'Pages utiles pour comprendre nos services, notre approche et nos politiques.',
                'items' => [
                    ['title' => 'Services', 'text' => 'Decouvrez nos services de configuration et d assistance.', 'url' => route('pages.services')],
                    ['title' => 'A propos', 'text' => 'Comprenez notre approche, notre ton et notre methode de support.', 'url' => route('pages.about')],
                    ['title' => 'Contact', 'text' => 'Contactez l equipe pour une aide avant ou apres la commande.', 'url' => route('pages.contact')],
                    ['title' => 'Centre de confiance', 'text' => 'Consultez les politiques de confidentialite, securite et remboursement.', 'url' => route('legal.index')],
                ],
            ],
            'es' => [
                'kicker' => 'Explorar el sitio',
                'title' => 'Paginas utiles para entender nuestros servicios, nuestro enfoque y nuestras politicas.',
                'items' => [
                    ['title' => 'Servicios', 'text' => 'Descubre nuestros servicios de configuracion y asistencia.', 'url' => route('pages.services')],
                    ['title' => 'Nosotros', 'text' => 'Conoce nuestro enfoque, tono y metodo de soporte.', 'url' => route('pages.about')],
                    ['title' => 'Contacto', 'text' => 'Habla con el equipo antes o despues de tu pedido.', 'url' => route('pages.contact')],
                    ['title' => 'Centro legal', 'text' => 'Consulta privacidad, seguridad, reembolsos y reglas del servicio.', 'url' => route('legal.index')],
                ],
            ],
            'ar' => [
                'kicker' => 'Ø§Ø³ØªÙƒØ´Ù Ø§Ù„Ù…ÙˆÙ‚Ø¹',
                'title' => 'ØµÙØ­Ø§Øª Ù…ÙÙŠØ¯Ø© Ù„ÙÙ‡Ù… Ø§Ù„Ø®Ø¯Ù…Ø§Øª ÙˆØ·Ø±ÙŠÙ‚Ø© Ø§Ù„Ø¹Ù…Ù„ ÙˆØ§Ù„Ø³ÙŠØ§Ø³Ø§Øª Ø¨Ø´ÙƒÙ„ Ø£ÙˆØ¶Ø­.',
                'items' => [
                    ['title' => 'Ø§Ù„Ø®Ø¯Ù…Ø§Øª', 'text' => 'ØªØ¹Ø±Ù Ø¹Ù„Ù‰ Ø®Ø¯Ù…Ø§Øª Ø§Ù„Ø¥Ø¹Ø¯Ø§Ø¯ ÙˆØ§Ù„Ù…Ø³Ø§Ø¹Ø¯Ø© Ø§Ù„ØªÙ‚Ù†ÙŠØ©.', 'url' => route('pages.services')],
                    ['title' => 'Ù…Ù† Ù†Ø­Ù†', 'text' => 'Ø§Ù‚Ø±Ø£ Ø¹Ù† Ù†Ù‡Ø¬ Ø§Ù„ÙØ±ÙŠÙ‚ ÙˆØ·Ø±ÙŠÙ‚Ø© Ø§Ù„Ù…ØªØ§Ø¨Ø¹Ø© ÙˆØ§Ù„Ø¯Ø¹Ù….', 'url' => route('pages.about')],
                    ['title' => 'Ø§Ù„ØªÙˆØ§ØµÙ„', 'text' => 'ØªÙˆØ§ØµÙ„ Ù…Ø¹ Ø§Ù„ÙØ±ÙŠÙ‚ Ù‚Ø¨Ù„ Ø§Ù„Ø·Ù„Ø¨ Ø£Ùˆ Ø¨Ø¹Ø¯Ù‡.', 'url' => route('pages.contact')],
                    ['title' => 'Ù…Ø±ÙƒØ² Ø§Ù„Ø«Ù‚Ø©', 'text' => 'Ø±Ø§Ø¬Ø¹ Ø§Ù„Ø®ØµÙˆØµÙŠØ© ÙˆØ§Ù„Ø£Ù…Ø§Ù† ÙˆØ´Ø±ÙˆØ· Ø§Ù„Ø®Ø¯Ù…Ø© ÙˆØ§Ù„Ø§Ø³ØªØ±Ø¬Ø§Ø¹.', 'url' => route('legal.index')],
                ],
            ],
            default => [
                'kicker' => 'Explore the site',
                'title' => 'Useful pages that explain our services, team approach, and trust policies more clearly.',
                'items' => [
                    ['title' => 'Services', 'text' => 'Review our setup, guidance, and technical support services.', 'url' => route('pages.services')],
                    ['title' => 'About', 'text' => 'Learn how the team approaches setup, support, and follow-up.', 'url' => route('pages.about')],
                    ['title' => 'Contact', 'text' => 'Talk to the team before or after placing an order.', 'url' => route('pages.contact')],
                    ['title' => 'Trust center', 'text' => 'Read our privacy, security, refund, and service policies.', 'url' => route('legal.index')],
                ],
            ],
        };
    @endphp

    <section class="section-space">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="surface-card p-4 p-lg-5 reveal-up">
                <div class="text-center mx-auto mb-4" style="max-width: 760px;">
                    <span class="section-kicker mb-3">{{ $resourceLinks['kicker'] }}</span>
                    <h2 class="section-title text-body-rif mb-0">{{ $resourceLinks['title'] }}</h2>
                </div>
                <div class="row g-3">
                    @foreach ($resourceLinks['items'] as $item)
                        <div class="col-md-6 col-xl-3">
                            <a href="{{ $item['url'] }}" class="surface-card benefit-card d-block h-100 p-4 text-decoration-none reveal-up">
                                <h3 class="h4 text-body-rif mb-2">{{ $item['title'] }}</h3>
                                <p class="text-soft-rif mb-0">{{ $item['text'] }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="support" class="section-space pb-5">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="support-banner p-4 p-lg-5 reveal-up">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="hero-kicker mb-3">{{ $ui['support_kicker'] }}</span>
                        <h2 class="section-title text-body-rif mb-3">{{ $ui['support_title'] }}</h2>
                        <p class="text-soft-rif fs-5 mb-0">{{ $ui['support_description'] }}</p>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-lg-end">
                            <a href="{{ $secondaryCta }}" class="btn-rif-secondary" target="_blank" rel="noopener">{{ $ui['support_primary'] }}</a>
                            <a href="#plans" class="btn-rif-outline">{{ $ui['support_secondary'] }}</a>
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

    const revealItems = document.querySelectorAll('.reveal-up');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        revealItems.forEach(function (item, index) {
            item.style.transitionDelay = (index % 6) * 60 + 'ms';
            observer.observe(item);
        });
    } else {
        revealItems.forEach(function (item) {
            item.classList.add('is-visible');
        });
    }
});
</script>
@endpush

