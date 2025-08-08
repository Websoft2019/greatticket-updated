<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;

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
        RateLimiter::for('global', function ($request) {
            return $request->user()->is_premium
                ? Limit::perMinute(100) // Premium users get 100 requests per minute
                : Limit::perMinute(20);  // Regular users get 20 requests per minute
        });

        // Paginator::useBootstrap();
        Paginator::defaultView('vendor.pagination.bootstrap-3');
    }
}
