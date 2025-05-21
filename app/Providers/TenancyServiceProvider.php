<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Events;
use App\Tenant\Models\Tenant;
use App\Tenant\Routes\RouteServiceProvider;
use Livewire\Livewire;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

final class TenancyServiceProvider extends ServiceProvider
{
    public static function events(): array
    {
        return [
            // Handle permission cache per tenant
            Events\TenancyBootstrapped::class => [
                function (Events\TenancyBootstrapped $event) {
                    $permissionRegistrar = app(\Spatie\Permission\PermissionRegistrar::class);
                    $permissionRegistrar->cacheKey = 'spatie.permission.cache.tenant.'.$event->tenancy->tenant->getTenantKey();
                },
            ],

            Events\TenancyEnded::class => [
                function (Events\TenancyEnded $event) {
                    $permissionRegistrar = app(\Spatie\Permission\PermissionRegistrar::class);
                    $permissionRegistrar->cacheKey = 'spatie.permission.cache';
                },
            ],
        ];
    }

    public function register(): void
    {
        // Register the tenant-specific route service provider
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        // Configure Livewire for tenancy
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/livewire/update', $handle)
                ->middleware([
                    'web',
                    'universal',
                    InitializeTenancyByDomain::class,
                ]);
        });

        // Configure tenant routes
        $this->mapTenantRoutes();

        // Register tenant initialization events
        $this->registerTenantEvents();

        // Register device API routes - these run on all domains
        Route::middleware([
            'api',
        ])->prefix('api/device')
            ->group(base_path('routes/device-api.php'));
    }

    protected function mapTenantRoutes(): void
    {
        // Tenant web routes
        Route::middleware([
            'web',
            InitializeTenancyByDomain::class,
            PreventAccessFromCentralDomains::class,
            \App\Http\Middleware\TenantImpersonationMiddleware::class,
        ])->group(base_path('routes/tenant.php'));

        // Tenant API routes
        Route::middleware([
            'api',
            InitializeTenancyByDomain::class,
            PreventAccessFromCentralDomains::class,
        ])->prefix('api/v1')
            ->group(base_path('routes/tenant-api.php'));
    }

    protected function registerTenantEvents(): void
    {
        Event::listen(Events\TenancyInitialized::class, function (Events\TenancyInitialized $event) {
            $tenant = $event->tenancy->tenant;

            // Set tenant settings
            if (isset($tenant->settings['timezone'])) {
                config(['app.timezone' => $tenant->settings['timezone']]);
            }

            if (isset($tenant->settings['locale'])) {
                app()->setLocale($tenant->settings['locale']);
            }
        });
    }
}
