<?php

declare(strict_types=1);

namespace App\Providers;

use App\Support\SettingsHelper;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /** Register services. */
    public function register(): void
    {
        // Register SettingsHelper as a singleton
        $this->app->singleton('settings', function ($app) {
            return new SettingsHelper();
        });
    }

    /** Bootstrap services. */
    public function boot(): void
    {
        // We'll handle settings application in middleware now
        // This avoids loading settings before we know the tenant context
    }
}
