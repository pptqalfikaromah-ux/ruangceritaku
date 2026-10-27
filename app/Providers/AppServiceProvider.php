<?php

namespace App\Providers;

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
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        if (config('app.env') === 'production' || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) || str_contains(request()->header('host', ''), 'vercel.app')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
