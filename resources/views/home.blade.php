@extends('layouts.app')

@section('title', __('site.home.title'))
@section('meta_description', __('site.home.meta_description'))
@section('body_class', 'page-home')

@section('content')
    @php
        $locale = app()->getLocale();
        $isArabic = $locale === 'ar';
        $brandName = data_get(trans('site.brand'), 'name', 'RIF Media');
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
                'hero_eyebrow' => 'خدمة إعداد أجهزة احترافية',
                'hero_title_top' => 'إعداد صحيح',
                'hero_title_bottom' => 'ودعم يبقى معك',
                'hero_description' => 'نساعدك في إعداد التلفاز الذكي والتطبيقات والحسابات والمتابعة التقنية بخطوات واضحة وواجهة سهلة وفريق دعم حقيقي.',
                'hero_tagline' => 'من أول إعداد إلى آخر متابعة، نبقي التجربة مرتبة وآمنة وسهلة الفهم.',
                'hero_points' => ['إجراء آمن', 'متابعة عبر واتساب', 'إرشاد تقني واضح'],
                'hero_stats' => ['إعداد احترافي', 'استجابة يومية', 'TV / Mobile / Tablet'],
                'payment_kicker' => 'خيارات الدفع',
                'payment_title' => 'دفع واضح وآمن للعملاء المحليين والدوليين.',
                'payment_description' => 'نوفر دفعا دوليا آمنا عبر Paddle مع مراجعة يدوية للتحويلات البنكية المحلية.',
                'payment_badge' => 'دفع دولي',
                'payment_note' => 'بطاقة دولية مع تأكيد رقمي سريع.',
                'payment_local' => 'شركاء التحويل المحلي',
                'package_kicker' => 'باقات الخدمة',
                'package_title' => 'اختر باقة الدعم والإعداد المناسبة لك.',
                'package_description' => 'أسعار واضحة لخدمات الإعداد، المساعدة في التثبيت، والمتابعة التقنية المستمرة.',
                'benefits_kicker' => 'لماذا يختارنا العملاء',
                'benefits_title' => 'واجهة أنظف، ودعم أوضح، وتجربة أكثر راحة.',
                'benefits_description' => 'نبني الخدمة حول الوضوح والثقة وسهولة الخطوات، حتى يعرف العميل دائما ما هي الخطوة التالية.',
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
                                                    ['icon' => 'monitor-cog', 'title' => 'إعداد الأجهزة', 'text' => 'تجهيز التلفاز الذكي والأجهزة المرتبطة به.'],
                                                    ['icon' => 'download', 'title' => 'تثبيت التطبيقات', 'text' => 'مساعدة عملية في التثبيت والخطوات الأولى.'],
                                                    ['icon' => 'shield-check', 'title' => 'توجيه آمن', 'text' => 'تعليمات واضحة مع متابعة مريحة وآمنة.'],
                                                    ['icon' => 'messages-square', 'title' => 'دعم تقني', 'text' => 'فريق بشري يرافقك قبل وبعد الطلب.'],
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
                            ['icon' => 'monitor-cog', 'title' => 'إعداد الأجهزة', 'text' => 'تجهيز التلفاز الذكي والأجهزة المرتبطة به.'],
                            ['icon' => 'download', 'title' => 'تثبيت التطبيقات', 'text' => 'مساعدة عملية في التثبيت والخطوات الأولى.'],
                            ['icon' => 'shield-check', 'title' => 'توجيه آمن', 'text' => 'تعليمات واضحة مع متابعة مريحة وآمنة.'],
                            ['icon' => 'messages-square', 'title' => 'دعم تقني', 'text' => 'فريق بشري يرافقك قبل وبعد الطلب.'],
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
                        'ar' => ['مراجعة يدوية للتحويل', 'تأكيد عبر واتساب', 'خطوات دفع واضحة'],
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
                    'subtitle' => 'الخيار البسيط للبداية بشكل واضح ومرتب.',
                    'plans' => [
                        ['duration' => '3 أشهر', 'price' => '89', 'badge' => null, 'features' => ['إعداد أساسي', 'مساعدة في التثبيت', 'دعم واتساب', 'متابعة بسيطة']],
                        ['duration' => '6 أشهر', 'price' => '149', 'badge' => 'الأكثر طلبا', 'features' => ['أجهزة متعددة', 'مراجعة الإعداد', 'دعم أطول', 'سعر أفضل']],
                        ['duration' => '12 شهرا', 'price' => '199', 'badge' => 'أفضل قيمة', 'features' => ['دعم سنوي', 'متابعة منتظمة', 'أولوية هادئة', 'توفير سنوي']],
                    ],
                ],
                [
                    'slug' => 'max',
                    'title' => 'Advanced / MAX',
                    'subtitle' => 'توازن أفضل للعملاء الذين يحتاجون متابعة أقوى.',
                    'plans' => [
                        ['duration' => '3 أشهر', 'price' => '149', 'badge' => null, 'features' => ['إعداد متقدم', 'دعم متعدد الأجهزة', 'مساعدة عملية', 'أولوية أعلى']],
                        ['duration' => '6 أشهر', 'price' => '249', 'badge' => 'موصى به', 'features' => ['تحسين مستمر', 'تشخيص تقني', 'متابعة ممتدة', 'راحة أكبر']],
                        ['duration' => '12 شهرا', 'price' => '449', 'badge' => null, 'features' => ['مرافقة سنوية', 'استقرار أقوى', 'دعم مستمر', 'تغطية أفضل']],
                    ],
                ],
                [
                    'slug' => 'trex',
                    'title' => 'Premium / TREX',
                    'subtitle' => 'الخيار الأقوى لمن يريد متابعة كاملة ومستمرة.',
                    'plans' => [
                        ['duration' => '3 أشهر', 'price' => '249', 'badge' => null, 'features' => ['دعم بريميوم', 'متابعة مفصلة', 'مساعدة متقدمة', 'أولوية كاملة']],
                        ['duration' => '6 أشهر', 'price' => '349', 'badge' => 'الأكثر مبيعا', 'features' => ['دعم مكثف', 'مرافقة أطول', 'مراجعة تقنية', 'طمأنينة أكبر']],
                        ['duration' => '12 شهرا', 'price' => '599', 'badge' => 'سنوي', 'features' => ['دعم شامل', 'متابعة سنوية', 'أعلى أولوية', 'تغطية موسعة']],
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
                            <a href="{{ $primaryCta }}" class="btn-rif-outline family-pricing-cta">{{ $locale === 'fr' ? 'Choisir' : ($locale === 'es' ? 'Elegir' : ($locale === 'ar' ? 'اختيار' : 'Choose')) }}</a>
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
                                        <a href="{{ $primaryCta }}" class="{{ $index === 1 ? 'btn-rif-primary' : 'btn-rif-secondary' }} w-100 mt-auto">{{ $locale === 'fr' ? 'Continuer' : ($locale === 'es' ? 'Continuar' : ($locale === 'ar' ? 'متابعة' : 'Continue')) }}</a>
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
                                ['icon' => 'sparkles', 'title' => 'بداية مرتبة', 'text' => 'رحلة عميل واضحة من الصفحة الأولى إلى الدعم.'],
                                ['icon' => 'messages-square', 'title' => 'دعم بشري', 'text' => 'محادثة حقيقية ومتابعة عملية عبر واتساب.'],
                                ['icon' => 'shield-check', 'title' => 'ثقة وأمان', 'text' => 'مراجعة يدوية وخطوات دفع ودعم مفهومة.'],
                                ['icon' => 'refresh-cw', 'title' => 'متابعة بسيطة', 'text' => 'تقدم واضح في كل مرحلة دون ارتباك.'],
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
                'kicker' => 'استكشف الموقع',
                'title' => 'صفحات مفيدة لفهم الخدمات وطريقة العمل والسياسات بشكل أوضح.',
                'items' => [
                    ['title' => 'الخدمات', 'text' => 'تعرف على خدمات الإعداد والمساعدة التقنية.', 'url' => route('pages.services')],
                    ['title' => 'من نحن', 'text' => 'اقرأ عن نهج الفريق وطريقة المتابعة والدعم.', 'url' => route('pages.about')],
                    ['title' => 'التواصل', 'text' => 'تواصل مع الفريق قبل الطلب أو بعده.', 'url' => route('pages.contact')],
                    ['title' => 'مركز الثقة', 'text' => 'راجع الخصوصية والأمان وشروط الخدمة والاسترجاع.', 'url' => route('legal.index')],
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
