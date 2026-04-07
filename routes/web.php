<?php

use App\Http\Controllers\AdminClientWorkflowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TelegramWebhookController;
use App\Support\SeoUrl;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/services', [PageController::class, 'services'])->name('pages.services');
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::get('/faq', [PageController::class, 'faq'])->name('pages.faq');
Route::get('/packages', [PageController::class, 'packages'])->name('pages.packages');
Route::get('/legal', [PageController::class, 'legacyLegal']);
Route::get('/trust-center', function () {
    return view('legal.index');
})->name('pages.trust');
Route::get('/trust-center/legal', fn () => redirect()->route('pages.trust', request()->query(), 301))->name('legal.index');

Route::get('/locale/{locale}', function (string $locale, Request $request) {
    abort_unless(in_array($locale, config('app.supported_locales', ['en']), true), 404);

    $request->session()->put('locale', $locale);

    $target = url()->previous();
    $separator = str_contains($target, '?') ? '&' : '?';

    return redirect()->to($target.$separator.'lang='.$locale)
        ->withCookie(cookie()->forever('locale', $locale));
})->name('locale.switch');

$serviceLandingSlugs = [
    'smart-tv-setup-morocco',
    'app-installation-help',
    'device-troubleshooting-morocco',
    'technical-support-morocco',
    'account-setup-help-morocco',
];

Route::get('/{slug}', [PageController::class, 'service'])
    ->whereIn('slug', $serviceLandingSlugs)
    ->name('pages.service');

$legacyServiceRedirects = [
    'app-installation-help-morocco' => 'app-installation-help',
    'technical-support-marrakech' => 'technical-support-morocco',
];

foreach ($legacyServiceRedirects as $legacySlug => $targetSlug) {
    Route::get("/{$legacySlug}", fn () => redirect()->route('pages.service', ['slug' => $targetSlug] + request()->query(), 301));
}

Route::get('/services/{slug}', function (string $slug) use ($serviceLandingSlugs, $legacyServiceRedirects) {
    $targetSlug = $legacyServiceRedirects[$slug] ?? $slug;
    abort_unless(in_array($targetSlug, $serviceLandingSlugs, true), 404);

    return redirect()->route('pages.service', ['slug' => $targetSlug] + request()->query(), 301);
});

$legalRoutes = [
    'privacy-policy' => 'privacy',
    'terms-of-service' => 'terms',
    'security-safety' => 'security',
    'refund-policy' => 'refund',
    'cookie-policy' => 'cookies',
];

foreach ($legalRoutes as $slug => $pageKey) {
    Route::get("/trust-center/{$slug}", function () use ($pageKey) {
        abort_unless(is_array(trans("legal.pages.{$pageKey}")), 404);

        return view('legal.show', ['pageKey' => $pageKey]);
    })->name("legal.{$pageKey}");
}

Route::get('/sitemap.xml', function () use ($legalRoutes) {
    $supportedLocales = config('app.supported_locales', ['en']);
    $paths = array_values(array_unique([
        route('home'),
        route('pages.services'),
        route('pages.service', 'smart-tv-setup-morocco'),
        route('pages.service', 'app-installation-help'),
        route('pages.service', 'device-troubleshooting-morocco'),
        route('pages.service', 'technical-support-morocco'),
        route('pages.service', 'account-setup-help-morocco'),
        route('pages.about'),
        route('pages.contact'),
        route('pages.faq'),
        route('pages.packages'),
        route('pages.trust'),
        ...array_map(fn (string $pageKey) => route("legal.{$pageKey}"), array_values($legalRoutes)),
    ]));

    $items = collect($paths)->map(fn (string $path) => [
        'loc' => SeoUrl::xDefault($path),
        'alternates' => SeoUrl::localeMap($path, $supportedLocales),
        'priority' => $path === route('home') ? '1.0' : '0.7',
    ])->all();

    return response()
        ->view('seo.sitemap', [
            'items' => $items,
        ])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Allow: /',
        'Disallow: /dashboard',
        'Disallow: /profile',
        'Disallow: /login',
        'Disallow: /register',
        'Disallow: /forgot-password',
        'Disallow: /reset-password',
        'Disallow: /onboarding',
        'Disallow: /checkout',
        'Disallow: /admin',
        'Disallow: /verify-email',
        'Disallow: /confirm-password',
        'Host: '.parse_url(config('app.url'), PHP_URL_HOST),
        'Sitemap: '.route('sitemap'),
    ];

    return response(implode(PHP_EOL, $lines), Response::HTTP_OK, [
        'Content-Type' => 'text/plain; charset=UTF-8',
    ]);
})->name('robots');

Route::get('/llms.txt', function () {
    $lines = [
        '# Rifi Media',
        '',
        'Rifi Media is a device setup and technical support website.',
        'We help clients with device configuration, app guidance, troubleshooting, setup follow-up, and payment support.',
        'We do not host, provide, or distribute media content.',
        '',
        'Public pages:',
        '- '.route('home'),
        '- '.route('pages.services'),
        '- '.route('pages.about'),
        '- '.route('pages.contact'),
        '- '.route('legal.index'),
        '',
        'Primary contact:',
        '- Email: '.config('seo.contact_email', 'contact@rifimedia.com'),
    ];

    if ($phone = config('seo.contact_phone')) {
        $lines[] = '- Phone: '.$phone;
    }

    return response(implode(PHP_EOL, $lines), Response::HTTP_OK, [
        'Content-Type' => 'text/plain; charset=UTF-8',
    ]);
})->name('llms');

Route::post('/telegram/webhook/{secret}', TelegramWebhookController::class)
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('telegram.webhook');

Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding.show');
    Route::get('/checkout', [OnboardingController::class, 'checkout'])->name('checkout');
    Route::post('/onboarding/plan', [OnboardingController::class, 'storePlan'])->middleware('throttle:support-flow')->name('onboarding.plan');
    Route::post('/onboarding/payment', [OnboardingController::class, 'storePayment'])->middleware('throttle:support-flow')->name('onboarding.payment');
    Route::get('/checkout/card', [OnboardingController::class, 'cardCheckout'])->name('checkout.card');
    Route::post('/checkout/card/confirm', [OnboardingController::class, 'confirmCardCheckout'])->middleware('throttle:support-flow')->name('checkout.card.confirm');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/admin/clients/{client}', [DashboardController::class, 'showAdminClient'])->name('admin.clients.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/admin/clients/{client}/workflow', [AdminClientWorkflowController::class, 'update'])
        ->middleware('throttle:admin-workflow')
        ->name('admin.clients.workflow');
});

require __DIR__.'/auth.php';
