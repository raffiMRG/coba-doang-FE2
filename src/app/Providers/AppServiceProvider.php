<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // Container listens on plain :80 internally, so SERVER_PORT never
        // reflects Docker's external port mapping (e.g. :8000) — force
        // absolute URLs (incl. Vite asset tags) to use APP_URL instead of
        // request-detected host/port.
        if ($url = config('app.url')) {
            URL::forceRootUrl($url);
        }
    }
}
