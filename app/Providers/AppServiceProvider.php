<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\Gate::define('admin', fn ($user) => (bool) $user->is_admin);

        RateLimiter::for('login-ip', fn (Request $request) => Limit::perMinute(10)->by($request->ip()));
        RateLimiter::for('registration', fn (Request $request) => Limit::perHour(3)->by($request->ip()));
        RateLimiter::for('password-email', fn (Request $request) => Limit::perHour(5)->by($request->ip()));
        RateLimiter::for('admin', fn (Request $request) => $request->isMethodSafe()
            ? Limit::perMinute(120)->by('read:'.$request->user()->id)
            : Limit::perMinute(30)->by('write:'.$request->user()->id));
        RateLimiter::for('donation-checkout', fn (Request $request) => [
            Limit::perMinute(10)->by('minute:'.$request->ip()),
            Limit::perHour(30)->by('hour:'.$request->ip()),
        ]);
        RateLimiter::for('payment-confirmation', function (Request $request) {
            $reference = $request->route('donation');
            $reference = $reference instanceof \App\Models\Donation ? $reference->reference : $reference;

            return [
                Limit::perMinute(20)->by('ip:'.$request->ip()),
                Limit::perMinute($request->isMethod('POST') ? 5 : 30)
                    ->by($request->method().':donation:'.$reference),
            ];
        });
        RateLimiter::for('kkiapay-webhook', fn (Request $request) => Limit::perMinute(120)->by($request->ip()));
    }
}
