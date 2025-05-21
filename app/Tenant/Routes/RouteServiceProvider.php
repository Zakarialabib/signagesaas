<?php

declare(strict_types=1);

namespace App\Tenant\Routes;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RouteServiceProvider extends ServiceProvider
{
    /** Register route model bindings for tenant models. */
    public function boot(): void
    {
        $this->registerTenantModelBindings();

        parent::boot();
    }

    /**
     * Registers route model bindings using tenant models instead of central models
     * Uses Laravel's Route::model() to bind wildcards to tenant model instances
     */
    protected function registerTenantModelBindings(): void
    {
        $tenantModelPath = app_path('Tenant/Models');

        if ( ! File::isDirectory($tenantModelPath)) {
            return;
        }

        $files = File::files($tenantModelPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $modelName = $file->getFilenameWithoutExtension();
                $modelClass = "App\\Tenant\\Models\\{$modelName}";

                // Only register if the class exists
                if (class_exists($modelClass)) {
                    // For basic route model binding
                    // Routes like /users/{user} will resolve {user} to the tenant's User model
                    $parameter = Str::snake($modelName);
                    Route::model($parameter, $modelClass);

                    // Also bind with the plural form for convenience
                    // Routes like /users/{users} will also resolve to the tenant's User model
                    $parameterPlural = Str::plural($parameter);

                    if ($parameter !== $parameterPlural) {
                        Route::model($parameterPlural, $modelClass);
                    }
                }
            }
        }
    }
}
