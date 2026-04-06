@extends('layouts.app')

@section('title', __('site.home.title'))
@section('meta_description', __('site.home.meta_description'))
@section('body_class', 'page-home')

@push('preloads')
    <link rel="preload" as="image" href="{{ asset('images/rifmedia-logo-512.png') }}" imagesrcset="{{ asset('images/rifmedia-logo-320.png') }} 320w, {{ asset('images/rifmedia-logo-512.png') }} 512w" imagesizes="(min-width: 1200px) 320px, (min-width: 768px) 280px, 220px" fetchpriority="high">
@endpush

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
        $heroLogo = asset('images/rifmedia-logo-512.png');
        $heroLogoCompact = asset('images/rifmedia-logo-320.png');
        $primaryCta = auth()->check() && Route::has('onboarding.show') ? route('onboarding.show') : route('register');
        $secondaryCta = config('seo.whatsapp_url', 'https://wa.me/212663323824');
        $paymentLogos = [
            'paddle' => ['path' => 'images/payment-paddle.jpg', 'width' => 600, 'height' => 315],
            'cih' => ['path' => 'images/payment-cih-bank.jpg', 'width' => 569, 'height' => 429],
            'attijari' => ['path' => 'images/payment-attijariwafa-bank.png', 'width' => 331, 'height' => 284],
            'boa' => ['path' => 'images/payment-bank-of-africa.png', 'width' => 225, 'height' => 225],
            'chaabi' => ['path' => 'images/payment-chaabi-bank.png', 'width' => 267, 'height' => 189],
            'saham' => ['path' => 'images/payment-saham-bank.webp', 'width' => 1080, 'height' => 1080],
            'cashplus' => ['path' => 'images/payment-cashplus.png', 'width' => 1920, 'height' => 1080],
        ];

        $ui = match ($locale) {
            'fr' => [
                'hero_eyebrow' => 'Service professionnel de configuration',
                'hero_title_top' => 'Une installation bien faite.',
                'hero_title_bottom' => 'Un support qui reste avec vous.',
                'hero_description' => 'Nous vous aidons a configurer vos appareils, installer vos applications utiles, organiser vos comptes et garder un support technique clair.',
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
                'hero_eyebrow' => 'خدمة إعداد أجهزة احترافية',
                'hero_title_top' => 'إعداد صحيح',
                'hero_title_bottom' => 'ودعم يبقى معك',
                'hero_description' => 'نساعدك في إعداد التلفاز الذكي والتطبيقات والحسابات والمتابعة التقنية بخطوات واضحة وواجهة سهلة وفريق دعم حقيقي.',
                'hero_tagline' => 'من أول إعداد إلى آخر متابعة، نبقي التجربة مرتبة وآمنة وسهلة الفهم.',
                'hero_points' => ['إجراء آمن', 'متابعة عبر واتساب', 'إرشاد تقني واضح'],
                'hero_stats' => ['إعداد احترافي', 'استجابة يومية', 'TV / Mobile / Tablet'],
                'payment_kicker' => 'خيارات الدفع',
                'payment_title' => 'دفع واضح وآمن للعملاء المحليين والدوليين.',
                'payment_description' => 'نوفر دفعًا دوليًا آمنًا عبر Paddle مع مراجعة يدوية للتحويلات البنكية المحلية.',
                'payment_badge' => 'دفع دولي',
                'payment_note' => 'بطاقة دولية مع تأكيد رقمي سريع.',
                'payment_local' => 'شركاء التحويل المحلي',
                'package_kicker' => 'باقات الخدمة',
                'package_title' => 'اختر باقة الدعم والإعداد المناسبة لك.',
                'package_description' => 'أسعار واضحة لخدمات الإعداد، والمساعدة في التثبيت، والمتابعة التقنية المستمرة.',
                'benefits_kicker' => 'لماذا يختارنا العملاء',
                'benefits_title' => 'واجهة أنظف، ودعم أوضح، وتجربة أكثر راحة.',
                'benefits_description' => 'نبني الخدمة حول الوضوح والثقة وسهولة الخطوات، حتى يعرف العميل دائمًا ما هي الخطوة التالية.',
                'support_kicker' => 'الدعم',
                'support_title' => 'هل تحتاج مساعدة قبل تنفيذ الطلب؟',
                'support_description' => 'تواصل معنا إذا كنت تحتاج مساعدة في اختيار الباقة المناسبة أو فهم خطوات الإعداد والدفع.',
                'support_primary' => 'دعم واتساب',
                'support_secondary' => 'عرض الباقات',
            ],
            default => [
                'hero_eyebrow' => 'Professional device setup service',
                'hero_title_top' => 'Setup done right.',
                'hero_title_bottom' => 'Support that stays with you.',
                'hero_description' => 'We help clients with Smart TV, device setup, app guidance, account organization, and technical support that stays clear from the first step onward.',
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

    {{-- content omitted for brevity in command --}}
@endsection
