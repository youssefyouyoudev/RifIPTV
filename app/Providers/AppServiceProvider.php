<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('auth-login', function (Request $request) {
            return Limit::perMinute(6)->by(Str::lower((string) $request->input('email')).'|'.$request->ip());
        });

        RateLimiter::for('auth-register', function (Request $request) {
            return Limit::perHour(8)->by($request->ip());
        });

        RateLimiter::for('password-recovery', function (Request $request) {
            return Limit::perMinute(3)->by(Str::lower((string) $request->input('email')).'|'.$request->ip());
        });

        RateLimiter::for('support-flow', function (Request $request) {
            return Limit::perMinute(12)->by((string) optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('admin-workflow', function (Request $request) {
            return Limit::perMinute(30)->by((string) optional($request->user())->id ?: $request->ip());
        });
    }
}
