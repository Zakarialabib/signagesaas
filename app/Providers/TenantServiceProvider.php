<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Stancl\Tenancy\Events\TenancyInitialized;
use Stancl\Tenancy\Events\TenancyEnded;
use Illuminate\Support\Facades\Event;
use App\Tenant\Routes\RouteServiceProvider;

class TenantServiceProvider extends ServiceProvider
{
    /** Register services specific to tenant context */
    public function register(): void
    {
        // Register the tenant-specific route service provider
        $this->app->register(RouteServiceProvider::class);

        // Register tenant model bindings when tenancy is initialized
        Event::listen(TenancyInitialized::class, function () {
            $this->registerTenantModels();
        });

        // Clear bindings when tenancy ends
        Event::listen(TenancyEnded::class, function () {
            // Optional: Any cleanup needed when switching away from tenant
        });
    }

    /** Bootstrap any application services. */
    public function boot(): void
    {
        // Nothing to do here - we use events for initialization
    }

    /**
     * Register model bindings for tenant context
     * This method creates model bindings for all models in the App\Tenant\Models namespace
     */
    protected function registerTenantModels(): void
    {
        $tenantModelPath = app_path('Tenant/Models');

        // Skip if the directory doesn't exist
        if ( ! File::isDirectory($tenantModelPath)) {
            return;
        }

        // Get all PHP files in the tenant models directory
        $files = File::files($tenantModelPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $className = $file->getFilenameWithoutExtension();

                // Original model class
                $originalModel = "App\\Models\\{$className}";

                // Tenant model class
                $tenantModel = "App\\Tenant\\Models\\{$className}";

                // Check if both classes exist before binding
                if (class_exists($originalModel) && class_exists($tenantModel)) {
                    $this->app->bind($originalModel, function () use ($tenantModel) {
                        return app($tenantModel);
                    });

                    // Also bind without namespace for convenience (if it doesn't conflict)
                    $this->app->bind($className, function () use ($tenantModel) {
                        return app($tenantModel);
                    });
                }
            }
        }
    }
}
