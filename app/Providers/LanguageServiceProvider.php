<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

final class LanguageServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        // Set the application locale from session
        if (session()->has('locale')) {
            App::setLocale(session('locale'));
        }

        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Set fallback locale
        App::setFallbackLocale('en');

        // Set supported locales
        config(['app.supported_locales' => ['en', 'ar']]);
    }
}
