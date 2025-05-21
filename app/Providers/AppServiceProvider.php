<?php

declare(strict_types=1);

namespace App\Providers;

use App\Tenant\Models\Tenant;
use App\Observers\TenantObserver;
use App\Support\SettingsHelper;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /** Register any application services. */
    public function register(): void
    {
        // Register SettingsHelper as a singleton
        $this->app->singleton('settings', function ($app) {
            return new SettingsHelper();
        });
    }

    /** Bootstrap any application services. */
    public function boot(): void
    {
        // Register model observers
        Tenant::observe(TenantObserver::class);

        // Register Livewire Components
        Livewire::component('components.settings-nav-bar', \App\Livewire\Components\SettingsNavBar::class);
        Livewire::component('settings.tenant-settings-manager', \App\Livewire\Settings\TenantSettingsManager::class);
    }
}
