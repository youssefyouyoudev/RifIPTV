@extends('layouts.app')

@section('title', 'Rifi Media | Smart TV, Device Setup & Technical Support in Morocco')
@section('meta_description', 'Rifi Media helps clients across Morocco with Smart TV setup, app installation guidance, connected device troubleshooting, account onboarding, and human technical support.')
@section('canonical', route('home'))
@section('body_class', 'page-home')

@php
    $brandName = data_get(trans('site.brand'), 'name', 'Rifi Media');
    $heroLogoLarge = asset('/public/images/rifmedia-logo-512.png');
    $heroLogoCompact = asset('/public/images/rifmedia-logo-320.png');
    $whatsappUrl = config('seo.whatsapp_url', 'https://wa.me/212663323824');
    $supportEmail = config('seo.contact_email', 'contact@rifimedia.com');
    $supportHours = config('seo.support_hours', 'Monday to Saturday, 09:00 to 22:00');
    $serviceRegion = config('seo.service_region', 'Morocco');
    $plans = collect(config('support_plans.plans', []));
    $faqItems = [
        ['q' => 'What does Rifi Media actually do?', 'a' => 'We help clients with Smart TV setup, app guidance, account onboarding, troubleshooting, and practical technical follow-up.'],
        ['q' => 'Do you provide or host media content?', 'a' => 'No. We do not provide or host media content. We only assist with device configuration, app setup, and technical support.'],
        ['q' => 'Do you work across Morocco?', 'a' => 'Yes. We support clients across Morocco, with additional local relevance for Marrakech.'],
        ['q' => 'How are payments handled?', 'a' => 'International card checkout can be handled through Paddle, while local bank transfers are reviewed manually before the support flow continues.'],
    ];
    $faqSchema = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => collect($faqItems)->map(fn (array $item) => ['@type' => 'Question', 'name' => $item['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']]])->all()];
    $pageSchema = ['@context' => 'https://schema.org', '@type' => 'WebPage', 'name' => 'Rifi Media | Smart TV, Device Setup & Technical Support in Morocco', 'description' => 'Rifi Media helps clients across Morocco with Smart TV setup, app installation guidance, connected device troubleshooting, account onboarding, and human technical support.', 'url' => route('home'), 'inLanguage' => app()->getLocale(), 'primaryImageOfPage' => asset('images/hero-light.png')];
    $plansSchema = ['@context' => 'https://schema.org', '@type' => 'ItemList', 'name' => 'Support plans', 'itemListElement' => $plans->values()->map(fn ($plan, $index) => ['@type' => 'ListItem', 'position' => $index + 1, 'name' => $plan['name']])->all()];
    $paymentLogos = [
        ['src' => asset('/public/images/payment-paddle.jpg'), 'alt' => 'Paddle payment logo', 'width' => 600, 'height' => 315],
        ['src' => asset('/public/images/payment-cih-bank.jpg'), 'alt' => 'CIH Bank logo', 'width' => 569, 'height' => 429],
        ['src' => asset('/public/images/payment-attijariwafa-bank.png'), 'alt' => 'Attijariwafa Bank logo', 'width' => 331, 'height' => 284],
        ['src' => asset('/public/images/payment-bank-of-africa.png'), 'alt' => 'Bank of Africa logo', 'width' => 225, 'height' => 225],
        ['src' => asset('/public/images/payment-chaabi-bank.png'), 'alt' => 'Chaabi Bank logo', 'width' => 267, 'height' => 189],
        ['src' => asset('/public/images/payment-cashplus.png'), 'alt' => 'Cash Plus logo', 'width' => 1920, 'height' => 1080],
    ];
@endphp

@push('preloads')
    <link rel="preload" as="image" href="{{ $heroLogoLarge }}" imagesrcset="{{ $heroLogoCompact }} 320w, {{ $heroLogoLarge }} 512w" imagesizes="(min-width: 1200px) 320px, (min-width: 768px) 280px, 220px" fetchpriority="high">
@endpush

@section('structured_data')
    <script type="application/ld+json">{!! json_encode($pageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($plansSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endsection

@section('content')
    <section class="section-space">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="page-hero-shell reveal-up">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="section-kicker mb-3">Morocco device setup service</span>
                        <h1 class="display-hero text-body-rif mb-3">Smart TV, Device Setup &amp; Technical Support in Morocco</h1>
                        <p class="hero-supporting-copy text-soft-rif mb-4">Rifi Media helps clients set up devices, install apps, organize accounts, troubleshoot issues, and continue with calmer follow-up support from a real team.</p>
                        <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                            <a href="{{ route('pages.services') }}" class="btn-rif-secondary">View Services</a>
                            <a href="{{ $whatsappUrl }}" class="btn-rif-outline" target="_blank" rel="noopener">Contact Support on WhatsApp</a>
                        </div>
                        <div class="hero-mini-points">
                            <span class="hero-mini-pill">{{ $serviceRegion }}</span>
                            <span class="hero-mini-pill">Marrakech support relevance</span>
                            <span class="hero-mini-pill">{{ $supportHours }}</span>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="page-summary-card hero-brand-card">
                            <img src="{{ $heroLogoLarge }}" srcset="{{ $heroLogoCompact }} 320w, {{ $heroLogoLarge }} 512w" sizes="(min-width: 1200px) 280px, 220px" alt="{{ $brandName }} logo" class="hero-brand-logo" width="512" height="512" fetchpriority="high" decoding="async">
                            <div class="hero-brand-copy">
                                <h2 class="h3 text-body-rif mb-2">{{ $brandName }}</h2>
                                <p class="text-soft-rif mb-0">A Morocco-based support brand for Smart TV setup, device onboarding, app guidance, troubleshooting, and post-purchase follow-up.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-space pt-0">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="surface-card p-4 p-lg-5 reveal-up">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <span class="section-kicker mb-3">Who this is for</span>
                        <h2 class="section-title text-body-rif mb-4">Support built for real device setup needs.</h2>
                        <div class="row g-3">
                            <div class="col-md-6"><article class="page-feature-card p-4 h-100"><h3 class="h4 text-body-rif mb-2">Smart TV users</h3><p class="text-soft-rif mb-0">For first-time setup, essential settings, and app organization on connected screens.</p></article></div>
                            <div class="col-md-6"><article class="page-feature-card p-4 h-100"><h3 class="h4 text-body-rif mb-2">Android device users</h3><p class="text-soft-rif mb-0">For boxes, phones, and tablets that need clearer onboarding and guided checks.</p></article></div>
                            <div class="col-md-6"><article class="page-feature-card p-4 h-100"><h3 class="h4 text-body-rif mb-2">Tablet and mobile users</h3><p class="text-soft-rif mb-0">For app setup, account organization, and login guidance on portable devices.</p></article></div>
                            <div class="col-md-6"><article class="page-feature-card p-4 h-100"><h3 class="h4 text-body-rif mb-2">Clients who need troubleshooting</h3><p class="text-soft-rif mb-0">For compatibility issues, setup confusion, common errors, and post-setup questions.</p></article></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="page-action-card p-4 h-100">
                            <span class="section-kicker mb-3">Trust block</span>
                            <div class="page-quickfacts-list">
                                <div><span>Business</span><strong>{{ $brandName }}</strong></div>
                                <div><span>Email</span><strong>{{ $supportEmail }}</strong></div>
                                <div><span>WhatsApp</span><strong>+212 663 323 824</strong></div>
                                <div><span>Service area</span><strong>{{ $serviceRegion }}</strong></div>
                                <div><span>Support hours</span><strong>{{ $supportHours }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-space pt-0">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="surface-card p-4 p-lg-5 reveal-up">
                <div class="text-center mx-auto mb-4" style="max-width: 760px;">
                    <span class="section-kicker mb-3">Services</span>
                    <h2 class="section-title text-body-rif mb-0">Four clear service areas with less repetition and stronger intent.</h2>
                </div>
                <div class="row g-3">
                    <div class="col-md-6 col-xl-3"><article class="page-feature-card p-4 h-100"><h3 class="h4 text-body-rif mb-2">Device Setup</h3><p class="text-soft-rif mb-0">Structured help for Smart TV, tablets, mobile devices, and connected home environments.</p></article></div>
                    <div class="col-md-6 col-xl-3"><article class="page-feature-card p-4 h-100 benefit-card-emphasis"><h3 class="h4 text-body-rif mb-2">App Installation Assistance</h3><p class="text-soft-rif mb-0">Hands-on help for installing, organizing, and checking the right apps for the device you use.</p></article></div>
                    <div class="col-md-6 col-xl-3"><article class="page-feature-card p-4 h-100"><h3 class="h4 text-body-rif mb-2">Troubleshooting</h3><p class="text-soft-rif mb-0">Practical help for setup errors, compatibility checks, and common technical blockers.</p></article></div>
                    <div class="col-md-6 col-xl-3"><article class="page-feature-card p-4 h-100"><h3 class="h4 text-body-rif mb-2">Follow-Up Support</h3><p class="text-soft-rif mb-0">Clear post-purchase guidance so the client understands what changed and what comes next.</p></article></div>
                </div>
                <div class="internal-links-row mt-4">
                    <a href="{{ route('pages.service', 'smart-tv-setup-morocco') }}" class="internal-link-chip">Smart TV setup Morocco</a>
                    <a href="{{ route('pages.service', 'app-installation-help-morocco') }}" class="internal-link-chip">App installation help Morocco</a>
                    <a href="{{ route('pages.service', 'device-troubleshooting-morocco') }}" class="internal-link-chip">Device troubleshooting Morocco</a>
                    <a href="{{ route('pages.service', 'technical-support-marrakech') }}" class="internal-link-chip">Technical support Marrakech</a>
                    <a href="{{ route('pages.service', 'account-setup-help-morocco') }}" class="internal-link-chip">Account setup help Morocco</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section-space pt-0">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="surface-card p-4 p-lg-5 reveal-up">
                <div class="text-center mx-auto mb-4" style="max-width: 760px;">
                    <span class="section-kicker mb-3">How it works</span>
                    <h2 class="section-title text-body-rif mb-0">A simple four-step support flow from request to follow-up.</h2>
                </div>
                <div class="row g-3">
                    <div class="col-md-6 col-xl-3"><article class="workflow-step-card is-active h-100 p-4"><div class="workflow-step-number mb-3">1</div><h3 class="h4 text-body-rif mb-2">Tell us your device or setup need</h3><p class="text-soft-rif mb-0">Share the device you use, the issue you face, or the setup stage that needs help.</p></article></div>
                    <div class="col-md-6 col-xl-3"><article class="workflow-step-card is-active h-100 p-4"><div class="workflow-step-number mb-3">2</div><h3 class="h4 text-body-rif mb-2">We review and recommend the right support option</h3><p class="text-soft-rif mb-0">The team checks your case and suggests the most suitable support plan.</p></article></div>
                    <div class="col-md-6 col-xl-3"><article class="workflow-step-card is-active h-100 p-4"><div class="workflow-step-number mb-3">3</div><h3 class="h4 text-body-rif mb-2">We guide setup and technical checks</h3><p class="text-soft-rif mb-0">You get clear steps for configuration, app organization, and practical troubleshooting.</p></article></div>
                    <div class="col-md-6 col-xl-3"><article class="workflow-step-card is-active h-100 p-4"><div class="workflow-step-number mb-3">4</div><h3 class="h4 text-body-rif mb-2">We stay available for follow-up</h3><p class="text-soft-rif mb-0">After setup, support remains available when you need extra clarification.</p></article></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-space pt-0">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="surface-card p-4 p-lg-5 reveal-up">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="section-kicker mb-3">Trust &amp; transparency</span>
                        <h2 class="section-title text-body-rif mb-3">Clear positioning for clients, hosts, and payment reviewers.</h2>
                        <ul class="workflow-list-check">
                            <li><span class="family-plan-check"><i data-lucide="check" class="icon-xs"></i></span><span>We do not host or provide media content.</span></li>
                            <li><span class="family-plan-check"><i data-lucide="check" class="icon-xs"></i></span><span>We provide technical setup and support services only.</span></li>
                            <li><span class="family-plan-check"><i data-lucide="check" class="icon-xs"></i></span><span>Users are responsible for complying with local laws.</span></li>
                            <li><span class="family-plan-check"><i data-lucide="check" class="icon-xs"></i></span><span>Support is human-reviewed.</span></li>
                            <li><span class="family-plan-check"><i data-lucide="check" class="icon-xs"></i></span><span>Billing and payment steps are explained clearly before follow-up starts.</span></li>
                        </ul>
                    </div>
                    <div class="col-lg-5">
                        <div class="page-summary-card h-100">
                            <span class="section-kicker mb-3">Secure payment options</span>
                            <h3 class="h3 text-body-rif mb-3">Clear paths for local and international clients.</h3>
                            <p class="text-soft-rif mb-4">International card checkout can be handled through Paddle, while local transfers are reviewed manually with human confirmation.</p>
                            <div class="payment-logos-grid payment-logos-grid-home">
                                @foreach ($paymentLogos as $index => $logo)
                                    <div class="payment-logo-wrap {{ $index === 0 ? 'payment-logo-wrap-featured' : '' }}">
                                        <img src="{{ $logo['src'] }}" alt="{{ $logo['alt'] }}" width="{{ $logo['width'] }}" height="{{ $logo['height'] }}" class="payment-logo-image img-fluid" loading="{{ $index === 0 ? 'eager' : 'lazy' }}" decoding="async">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-space pt-0">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="surface-card p-4 p-lg-5 reveal-up">
                <div class="text-center mx-auto mb-4" style="max-width: 820px;">
                    <span class="section-kicker mb-3">Support plans</span>
                    <h2 class="section-title text-body-rif mb-3">Choose a support plan based on setup scope, response level, and follow-up.</h2>
                    <p class="text-soft-rif fs-5 mb-0">Plans are framed as support levels with clearer scope, assisted-device coverage, response handling, and follow-up expectations.</p>
                </div>
                <div class="row g-4">
                    @foreach ($plans as $plan)
                        @php($featuredPrice = collect($plan['prices'])->firstWhere('featured', true) ?? $plan['prices'][0])
                        <div class="col-lg-4">
                            <article class="family-plan-mini h-100 {{ $plan['slug'] === 'advanced' ? 'family-plan-tone-popular' : ($plan['slug'] === 'premium' ? 'family-plan-tone-value' : '') }}">
                                @if (! empty($plan['highlight']))
                                    <span class="family-plan-badge {{ $plan['slug'] === 'advanced' ? 'family-plan-badge-popular' : 'family-plan-badge-value' }}">{{ $plan['highlight'] }}</span>
                                @endif
                                <span class="family-plan-label">{{ $plan['label'] }}</span>
                                <h3 class="family-plan-name">{{ $plan['name'] }}</h3>
                                <p class="family-plan-subtitle">{{ $plan['summary'] }}</p>
                                <div class="service-plan-meta-grid">
                                    <div><span>Scope</span><strong>{{ $plan['scope'] }}</strong></div>
                                    <div><span>Devices</span><strong>{{ $plan['devices'] }}</strong></div>
                                    <div><span>Response</span><strong>{{ $plan['response'] }}</strong></div>
                                    <div><span>Follow-up</span><strong>{{ $plan['follow_up'] }}</strong></div>
                                </div>
                                <div class="family-plan-price">{{ $featuredPrice['price'] }}<span>MAD</span></div>
                                <div class="duration-chip-row">
                                    @foreach ($plan['prices'] as $price)
                                        <span class="duration-chip {{ $price['featured'] ? 'is-featured' : '' }}">{{ $price['months'] }} months</span>
                                    @endforeach
                                </div>
                                <ul class="family-plan-benefits">
                                    @foreach ($plan['features'] as $feature)
                                        <li><span class="family-plan-check"><i data-lucide="check" class="icon-xs"></i></span><span>{{ $feature }}</span></li>
                                    @endforeach
                                </ul>
                                <div class="d-flex flex-column flex-sm-row gap-3 mt-auto">
                                    <a href="{{ route('pages.packages') }}" class="btn-rif-secondary w-100">Choose support plan</a>
                                    <a href="{{ $whatsappUrl }}" class="btn-rif-outline w-100" target="_blank" rel="noopener">Talk to support</a>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="section-space pt-0">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="surface-card p-4 p-lg-5 reveal-up">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="section-kicker mb-3">Local trust</span>
                        <h2 class="section-title text-body-rif mb-3">A support brand built for clients across Morocco.</h2>
                        <div class="local-trust-stack">
                            <article class="page-feature-card p-4"><h3 class="h4 text-body-rif mb-2">Service area</h3><p class="text-soft-rif mb-0">Morocco-wide service with stronger local intent support for Marrakech.</p></article>
                            <article class="page-feature-card p-4"><h3 class="h4 text-body-rif mb-2">WhatsApp availability</h3><p class="text-soft-rif mb-0">Support requests can continue through WhatsApp when clients need faster clarification.</p></article>
                            <article class="page-feature-card p-4"><h3 class="h4 text-body-rif mb-2">Secure billing explanation</h3><p class="text-soft-rif mb-0">Clients see how card or bank-transfer review works before the setup process continues.</p></article>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="page-action-card p-4 h-100">
                            <span class="section-kicker mb-3">Need help?</span>
                            <h2 class="h3 text-body-rif mb-3">Talk to the team before you place an order.</h2>
                            <p class="text-soft-rif mb-4">If you need help choosing a plan, understanding payment steps, or preparing your device, the team can review your case first.</p>
                            <div class="d-flex flex-column gap-3">
                                <a href="{{ $whatsappUrl }}" class="btn-rif-secondary" target="_blank" rel="noopener">Talk to support</a>
                                <a href="{{ route('pages.contact') }}" class="btn-rif-outline">Contact page</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-space pt-0">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="surface-card p-4 p-lg-5 reveal-up">
                <div class="text-center mx-auto mb-4" style="max-width: 760px;">
                    <span class="section-kicker mb-3">FAQ</span>
                    <h2 class="section-title text-body-rif mb-0">Specific answers to the questions clients ask most.</h2>
                </div>
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
    </section>

    <section class="section-space pt-0 pb-5">
        <div class="container-xxl px-3 px-md-4 px-lg-5">
            <div class="support-banner p-4 p-lg-5 reveal-up">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="hero-kicker mb-3">Final step</span>
                        <h2 class="section-title text-body-rif mb-3">Start with the right support plan and continue with a real team.</h2>
                        <p class="text-soft-rif fs-5 mb-0">Use the packages page to compare support levels clearly, then continue through WhatsApp or checkout based on your preferred flow.</p>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-lg-end">
                            <a href="{{ route('pages.packages') }}" class="btn-rif-secondary">Choose support plan</a>
                            <a href="{{ $whatsappUrl }}" class="btn-rif-outline" target="_blank" rel="noopener">Talk to support</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
