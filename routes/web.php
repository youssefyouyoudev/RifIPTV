<?php

use App\Http\Controllers\AdminClientWorkflowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/locale/{locale}', function (string $locale, Request $request) {
    abort_unless(in_array($locale, config('app.supported_locales', ['en']), true), 404);

    $request->session()->put('locale', $locale);

    $target = url()->previous();
    $separator = str_contains($target, '?') ? '&' : '?';

    return redirect()->to($target.$separator.'lang='.$locale)
        ->withCookie(cookie()->forever('locale', $locale));
})->name('locale.switch');

Route::get('/checkout', function () {
    return auth()->check()
        ? redirect()->route('onboarding.show')
        : redirect()->route('register');
})->name('checkout');

Route::get('/legal', function () {
    return view('legal.index');
})->name('legal.index');

$legalRoutes = [
    'privacy-policy' => 'privacy',
    'terms-of-service' => 'terms',
    'security-safety' => 'security',
    'refund-policy' => 'refund',
    'cookie-policy' => 'cookies',
];

foreach ($legalRoutes as $slug => $pageKey) {
    Route::get("/legal/{$slug}", function () use ($pageKey) {
        abort_unless(is_array(trans("legal.pages.{$pageKey}")), 404);

        return view('legal.show', ['pageKey' => $pageKey]);
    })->name("legal.{$pageKey}");
}

Route::get('/sitemap.xml', function () use ($legalRoutes) {
    $supportedLocales = config('app.supported_locales', ['en']);
    $paths = [
        route('home', absolute: false),
        route('legal.index', absolute: false),
        ...array_map(fn (string $pageKey) => route("legal.{$pageKey}", absolute: false), array_values($legalRoutes)),
    ];

    return response()
        ->view('seo.sitemap', [
            'paths' => $paths,
            'supportedLocales' => $supportedLocales,
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
        'Sitemap: '.route('sitemap'),
    ];

    return response(implode(PHP_EOL, $lines), Response::HTTP_OK, [
        'Content-Type' => 'text/plain; charset=UTF-8',
    ]);
})->name('robots');

Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding.show');
    Route::post('/onboarding/plan', [OnboardingController::class, 'storePlan'])->name('onboarding.plan');
    Route::post('/onboarding/payment', [OnboardingController::class, 'storePayment'])->name('onboarding.payment');
    Route::get('/checkout/card', [OnboardingController::class, 'cardCheckout'])->name('checkout.card');
    Route::post('/checkout/card/confirm', [OnboardingController::class, 'confirmCardCheckout'])->name('checkout.card.confirm');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/admin/clients/{client}/workflow', [AdminClientWorkflowController::class, 'update'])
        ->name('admin.clients.workflow');
});

require __DIR__.'/auth.php';
