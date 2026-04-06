@extends('layouts.app')

@section('title', __('site.home.title'))
@section('meta_description', __('site.home.meta_description'))
@section('body_class', 'page-home')

@php
    $locale = app()->getLocale();
    $isArabic = $locale === 'ar';
    $brandName = 'Rifi Media';
    $heroLogo = asset('/public/images/rifmedia-logo-512.png');
    $heroLogoCompact = asset('/public/images/rifmedia-logo-320.png');
    $whatsappUrl = config('seo.whatsapp_url', 'https://wa.me/212663323824');
    $primaryCta = auth()->check() && Route::has('onboarding.show') ? route('onboarding.show') : route('register');
    $plans = collect(\App\Support\SupportPlanLocalizer::localize(config('support_plans.plans', []), $locale));
    $paymentLogos = [
        ['src' => asset('/public/images/payment-paddle.jpg'), 'alt' => 'Paddle logo', 'width' => 600, 'height' => 315, 'featured' => true],
        ['src' => asset('/public/images/payment-cih-bank.jpg'), 'alt' => 'CIH Bank logo', 'width' => 569, 'height' => 429],
        ['src' => asset('/public/images/payment-attijariwafa-bank.png'), 'alt' => 'Attijariwafa Bank logo', 'width' => 331, 'height' => 284],
        ['src' => asset('/public/images/payment-bank-of-africa.png'), 'alt' => 'Bank of Africa logo', 'width' => 225, 'height' => 225],
        ['src' => asset('/public/images/payment-chaabi-bank.png'), 'alt' => 'Chaabi Bank logo', 'width' => 267, 'height' => 189],
        ['src' => asset('/public/images/payment-saham-bank.webp'), 'alt' => 'Saham Bank logo', 'width' => 1080, 'height' => 1080],
        ['src' => asset('/public/images/payment-cashplus.png'), 'alt' => 'Cash Plus logo', 'width' => 1920, 'height' => 1080],
    ];

    $copy = match ($locale) {
        'ar' => [
            'eyebrow' => 'خدمة إعداد أجهزة احترافية',
            'title_top' => 'إعداد صحيح',
            'title_bottom' => 'ودعم يبقى معك',
            'description' => 'نساعدك في إعداد التلفاز الذكي والأجهزة، وتثبيت التطبيقات، وتنظيم الحسابات، ومتابعة الخطوات التقنية بشكل واضح ومريح.',
            'tagline' => 'واجهة أكثر ترتيبًا وثقة حتى تبدو رحلة الإعداد واضحة من أول خطوة.',
            'points' => ['إجراء آمن', 'متابعة عبر واتساب', 'إرشاد تقني واضح'],
            'stats' => ['إعداد احترافي', 'متابعة يومية', 'TV / Mobile / Tablet'],
            'tiles' => [
                ['icon' => 'monitor-cog', 'title' => 'إعداد الأجهزة', 'text' => 'إعداد التلفاز الذكي والأجهزة المرتبطة به بخطوات مفهومة.'],
                ['icon' => 'download', 'title' => 'تثبيت التطبيقات', 'text' => 'مساعدة عملية في التثبيت والتنظيم والخطوات الأولى.'],
                ['icon' => 'shield-check', 'title' => 'خطوات موثوقة', 'text' => 'عرض أوضح للدفع والمراجعة والمتابعة البشرية.'],
                ['icon' => 'messages-square', 'title' => 'دعم تقني', 'text' => 'فريق حقيقي يرافقك قبل الطلب وبعده.'],
            ],
            'payment_kicker' => 'خيارات الدفع',
            'payment_title' => 'دفع واضح وآمن للعملاء المحليين والدوليين.',
            'payment_text' => 'الدفع الدولي متاح عبر Paddle مع مراجعة يدوية للتحويلات البنكية المحلية.',
            'plans_kicker' => 'باقات الخدمة',
            'plans_title' => 'اختر باقة الدعم والإعداد المناسبة لك.',
            'plans_text' => 'باقات مرتبة حول عمق الإعداد، وسرعة الاستجابة، ومدة المتابعة.',
            'benefits_kicker' => 'لماذا يختارنا العملاء',
            'benefits_title' => 'واجهة أنظف، ودعم أوضح، وتجربة أكثر راحة.',
            'benefits_text' => 'نحاول أن تكون كل خطوة مفهومة، وكل تواصل بشري، وكل مرحلة أسهل من السابقة.',
            'faq_kicker' => 'الأسئلة الشائعة',
            'faq_title' => 'إجابات سريعة على أهم الأسئلة.',
            'support_kicker' => 'الدعم',
            'support_title' => 'هل تحتاج مساعدة قبل تنفيذ الطلب؟',
            'support_text' => 'تواصل مع الفريق إذا كنت تريد فهم الخطوات أو اختيار الباقة المناسبة لك.',
            'support_primary' => 'دعم واتساب',
            'support_secondary' => 'عرض الباقات',
        ],
        default => [
            'eyebrow' => 'Professional device setup service',
            'title_top' => 'Setup done right.',
            'title_bottom' => 'Support that stays with you.',
            'description' => 'We help clients configure devices, install useful apps, organize account access, and keep technical support clear from the first step onward.',
            'tagline' => 'A cleaner front-end experience that feels trustworthy, practical, and easy to follow.',
            'points' => ['Secure process', 'WhatsApp support', 'Clear technical guidance'],
            'stats' => ['Professional setup', 'Daily follow-up', 'TV / Mobile / Tablet'],
            'tiles' => [
                ['icon' => 'monitor-cog', 'title' => 'Device setup', 'text' => 'Smart TV and connected-device configuration done with guidance.'],
                ['icon' => 'download', 'title' => 'App guidance', 'text' => 'Practical help for installation, login flow, and first-time setup.'],
                ['icon' => 'shield-check', 'title' => 'Trusted process', 'text' => 'Clear support steps with secure payment follow-up.'],
                ['icon' => 'messages-square', 'title' => 'Technical support', 'text' => 'A real support team stays available before and after your order.'],
            ],
            'payment_kicker' => 'Payment options',
            'payment_title' => 'Secure payment options for local and international clients.',
            'payment_text' => 'International card payment is available through Paddle, while local transfers are reviewed and confirmed manually by support.',
            'plans_kicker' => 'Service packages',
            'plans_title' => 'Choose the support package that fits your setup needs.',
            'plans_text' => 'Three clear service plans built around setup guidance, troubleshooting, and reliable follow-up.',
            'benefits_kicker' => 'Why clients choose us',
            'benefits_title' => 'A cleaner interface, clearer support, and a calmer client experience.',
            'benefits_text' => 'Every section is designed to reduce confusion, build trust, and make the next step obvious.',
            'faq_kicker' => 'FAQ',
            'faq_title' => 'Clear answers to the questions clients ask most.',
            'support_kicker' => 'Support',
            'support_title' => 'Need help before you place an order?',
            'support_text' => 'Talk to our team if you need help choosing the right package or understanding the setup and payment process.',
            'support_primary' => 'WhatsApp support',
            'support_secondary' => 'View packages',
        ],
    };

    $faqItems = match ($locale) {
        'ar' => [
            ['q' => 'ما الذي تقدمه Rifi Media بالضبط؟', 'a' => 'نقدم المساعدة في إعداد الأجهزة، وضبط التطبيقات، والمتابعة التقنية، والدعم البشري الواضح.'],
            ['q' => 'هل توفرون محتوى إعلاميًا؟', 'a' => 'لا. نحن لا نوفر ولا نستضيف أي محتوى، بل نقدم فقط خدمات الإعداد والدعم التقني.'],
            ['q' => 'هل تعملون في كل المغرب؟', 'a' => 'نعم، نقدّم الخدمة على مستوى المغرب، مع حضور محلي أقوى في مراكش.'],
            ['q' => 'كيف يتم الدفع؟', 'a' => 'الدفع الدولي يتم عبر Paddle، أما التحويلات المحلية فتتم مراجعتها يدويًا قبل المتابعة.'],
        ],
        default => [
            ['q' => 'What does Rifi Media actually provide?', 'a' => 'We provide device setup help, app guidance, technical troubleshooting, and practical follow-up from a real team.'],
            ['q' => 'Do you provide media content?', 'a' => 'No. We do not provide or host media content. We only assist with device configuration, app setup, and technical support.'],
            ['q' => 'Do you work across Morocco?', 'a' => 'Yes. We support clients across Morocco, with additional local relevance for Marrakech.'],
            ['q' => 'How are payments handled?', 'a' => 'International card payments can be reviewed through Paddle, while local transfers are confirmed manually.'],
        ],
    };

    $webPageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => __('site.home.title'),
        'description' => __('site.home.meta_description'),
        'url' => request()->url(),
        'inLanguage' => app()->getLocale(),
        'primaryImageOfPage' => $heroLogo,
    ];
@endphp

@push('preloads')
    <link rel="preload" as="image" href="{{ $heroLogo }}" imagesrcset="{{ $heroLogoCompact }} 320w, {{ $heroLogo }} 512w" imagesizes="(min-width: 1200px) 320px, (min-width: 768px) 280px, 220px" fetchpriority="high">
@endpush

@section('structured_data')
    <script type="application/ld+json">{!! json_encode($webPageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endsection

@section('content')
    <section class="section-space">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="section-shell hero-shell">
                <div class="hero-stage home-hero-stage">
                    <div class="row g-4 g-xl-5 align-items-center hero-grid">
                        <div class="col-xl-5">
                            <div class="hero-copy {{ $isArabic ? 'hero-copy-ar' : '' }} reveal-up">
                                <span class="hero-kicker mb-3">{{ $copy['eyebrow'] }}</span>
                                <h1 class="hero-title {{ $isArabic ? 'hero-title-ar' : 'hero-title-compact' }} mb-4">
                                    <span class="d-block">{{ $copy['title_top'] }}</span>
                                    <span class="d-block accent">{{ $copy['title_bottom'] }}</span>
                                </h1>
                                <p class="hero-description text-muted-rif mb-4">{{ $copy['description'] }}</p>
                                <div class="hero-points d-flex flex-wrap gap-3 mb-4">
                                    @foreach ($copy['points'] as $point)
                                        <span class="hero-point"><i data-lucide="check-circle-2" class="icon-sm"></i><span>{{ $point }}</span></span>
                                    @endforeach
                                </div>
                                <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                                    <a href="{{ $primaryCta }}" class="btn-rif-primary">{{ __('site.home.cta') }}</a>
                                    <a href="{{ $whatsappUrl }}" class="btn-rif-outline" target="_blank" rel="noopener">{{ __('site.home.support.whatsapp') }}</a>
                                </div>
                                <div class="row g-3 hero-stats">
                                    @foreach ($copy['stats'] as $stat)
                                        <div class="col-sm-4">
                                            <div class="metric-pill p-3 text-center h-100"><span class="hero-stat-value">{{ $stat }}</span></div>
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
                                        <span class="hero-visual-badge hero-visual-brand">{{ $copy['eyebrow'] }}</span>
                                    </div>
                                    <div class="home-brand-layout">
                                        <div class="home-brand-card">
                                            <div class="home-brand-logo-shell">
                                                <img src="{{ $heroLogoCompact }}" srcset="{{ $heroLogoCompact }} 320w, {{ $heroLogo }} 512w" sizes="(min-width: 1200px) 320px, (min-width: 768px) 280px, 220px" alt="{{ $brandName }} logo" class="home-brand-logo" width="512" height="279" fetchpriority="high" loading="eager" decoding="async">
                                            </div>
                                            <p class="home-brand-tagline mb-0">{{ $copy['tagline'] }}</p>
                                        </div>
                                        <div class="home-brand-service-grid">
                                            @foreach ($copy['tiles'] as $tile)
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
                <div class="row g-4 mt-2">
                    @foreach ($copy['tiles'] as $tile)
                        <div class="col-md-6 col-xl-3">
                            <article class="surface-card feature-card home-feature-card p-4 reveal-up">
                                <span class="chip-icon mb-3"><i data-lucide="{{ $tile['icon'] }}" class="icon-sm"></i></span>
                                <h2 class="h4 text-body-rif mb-3">{{ $tile['title'] }}</h2>
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
                    <span class="section-kicker mb-3">{{ $copy['payment_kicker'] }}</span>
                    <h2 class="section-title text-body-rif mb-3">{{ $copy['payment_title'] }}</h2>
                    <p class="text-soft-rif fs-5 mb-0">{{ $copy['payment_text'] }}</p>
                </div>
                <article class="payment-featured mb-4 reveal-up">
                    <div class="payment-featured-badge">{{ $isArabic ? 'دولي' : 'International' }}</div>
                    <div class="payment-featured-inner">
                        <div class="payment-logo-wrap payment-logo-wrap-xl">
                            <img src="{{ $paymentLogos[0]['src'] }}" alt="{{ $paymentLogos[0]['alt'] }}" class="img-fluid payment-logo-image payment-logo-image-paddle" width="{{ $paymentLogos[0]['width'] }}" height="{{ $paymentLogos[0]['height'] }}" loading="lazy" decoding="async">
                        </div>
                    </div>
                </article>
                <div class="payment-logo-grid mb-4">
                    @foreach (array_slice($paymentLogos, 1) as $logo)
                        <article class="payment-logo-card reveal-up">
                            <div class="payment-logo-wrap">
                                <img src="{{ $logo['src'] }}" alt="{{ $logo['alt'] }}" class="img-fluid payment-logo-image" width="{{ $logo['width'] }}" height="{{ $logo['height'] }}" loading="lazy" decoding="async">
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="plans" class="section-space-lg">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="text-center mx-auto mb-5 reveal-up" style="max-width: 760px;">
                <span class="section-kicker mb-3">{{ $copy['plans_kicker'] }}</span>
                <h2 class="section-title text-body-rif mb-3">{{ $copy['plans_title'] }}</h2>
                <p class="text-soft-rif fs-5 mb-0">{{ $copy['plans_text'] }}</p>
            </div>
            <div class="pack-switcher reveal-up" data-pack-switcher data-pack-default="basic">
                <div class="pack-toggle-bar mb-4" role="tablist" aria-label="Package families">
                    @foreach ($plans as $plan)
                        <button type="button" class="pack-toggle-btn {{ $loop->first ? 'is-active' : '' }}" data-pack-toggle="{{ $plan['slug'] }}">
                            <span class="pack-toggle-logo-wrap"><strong>{{ $plan['code'] }}</strong></span>
                            <span class="pack-toggle-copy">
                                <strong>{{ $plan['label'] }} / {{ $plan['code'] }}</strong>
                                <small>{{ $plan['summary'] }}</small>
                            </span>
                        </button>
                    @endforeach
                </div>
                @foreach ($plans as $plan)
                    <article class="surface-card family-pricing-shell p-4 p-lg-5 pack-panel {{ $loop->first ? 'is-active' : '' }}" data-pack-panel="{{ $plan['slug'] }}">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4 mb-4">
                            <div class="family-pricing-brand">
                                <span class="family-pricing-logo-wrap d-inline-flex align-items-center justify-content-center">{{ $plan['code'] }}</span>
                                <div>
                                    <div class="text-soft-rif small text-uppercase fw-bold mb-2">{{ $copy['plans_kicker'] }}</div>
                                    <h2 class="h2 text-body-rif mb-2">{{ $plan['name'] }}</h2>
                                    <p class="text-soft-rif mb-0">{{ $plan['summary'] }}</p>
                                </div>
                            </div>
                            <a href="{{ $primaryCta }}" class="btn-rif-outline family-pricing-cta">{{ $plan['choose_cta'] }}</a>
                        </div>
                        <div class="row g-3">
                            @foreach ($plan['prices'] as $price)
                                <div class="col-md-6 col-xl-4">
                                    <article class="service-plan-card {{ $price['featured'] ? 'service-plan-card-featured' : '' }}">
                                        @if ($price['featured'])
                                            <span class="service-plan-badge">{{ $plan['featured_badge'] }}</span>
                                        @endif
                                        <div class="service-plan-head">
                                            <div>
                                                <span class="service-plan-label">{{ $plan['code'] }}</span>
                                                <h3 class="service-plan-name">{{ $price['duration_label'] }}</h3>
                                            </div>
                                        </div>
                                        <div class="service-plan-price">{{ $price['price'] }} <span>MAD</span></div>
                                        <ul class="service-plan-features">
                                            @foreach ($plan['features'] as $feature)
                                                <li><span class="family-plan-check"><i data-lucide="check" class="icon-sm"></i></span><span>{{ $feature }}</span></li>
                                            @endforeach
                                        </ul>
                                        <a href="{{ $primaryCta }}" class="{{ $price['featured'] ? 'btn-rif-primary' : 'btn-rif-secondary' }} w-100 mt-auto">{{ $plan['continue_cta'] }}</a>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-space">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="row g-4">
                        @foreach ($copy['tiles'] as $tile)
                            <div class="col-md-6">
                                <article class="surface-card benefit-card p-4 reveal-up">
                                    <span class="chip-icon mb-3" style="background: rgba(214,0,58,0.12); color: var(--rif-red);"><i data-lucide="{{ $tile['icon'] }}" class="icon-sm"></i></span>
                                    <h3 class="h4 text-body-rif mb-3">{{ $tile['title'] }}</h3>
                                    <p class="text-soft-rif mb-0">{{ $tile['text'] }}</p>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="benefits-copy reveal-up">
                        <span class="section-kicker mb-3" style="background: rgba(122,199,12,0.12); color: var(--rif-green);">{{ $copy['benefits_kicker'] }}</span>
                        <h2 class="section-title narrative-title text-body-rif mb-3">{{ $copy['benefits_title'] }}</h2>
                        <p class="text-soft-rif fs-5 mb-0">{{ $copy['benefits_text'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-space">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="surface-card p-4 p-lg-5 h-100 reveal-up">
                        <span class="section-kicker mb-3">{{ $copy['faq_kicker'] }}</span>
                        <h2 class="section-title text-body-rif mb-4">{{ $copy['faq_title'] }}</h2>
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
                <div class="col-lg-6">
                    <div class="support-banner p-4 p-lg-5 h-100 reveal-up">
                        <span class="hero-kicker mb-3">{{ $copy['support_kicker'] }}</span>
                        <h2 class="section-title text-body-rif mb-3">{{ $copy['support_title'] }}</h2>
                        <p class="text-soft-rif fs-5 mb-4">{{ $copy['support_text'] }}</p>
                        <div class="d-flex flex-column flex-sm-row gap-3">
                            <a href="{{ $whatsappUrl }}" class="btn-rif-secondary" target="_blank" rel="noopener">{{ $copy['support_primary'] }}</a>
                            <a href="#plans" class="btn-rif-outline">{{ $copy['support_secondary'] }}</a>
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
