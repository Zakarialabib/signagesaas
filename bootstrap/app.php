<?php

declare(strict_types=1);

use App\Http\Middleware\SetLocale;
use App\Http\Middleware\TenantImpersonationMiddleware;
use App\Http\Middleware\TenantSettingsMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/tenant-api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Core middleware stack
        $middleware->web(append: [
            // InitializeTenancyByDomain::class,
            SetLocale::class,
            TenantImpersonationMiddleware::class,
            TenantSettingsMiddleware::class,
        ]);

        $middleware->group('universal', []);

        // Named middleware
        $middleware->alias([
            'guest'           => App\Http\Middleware\RedirectIfAuthenticated::class,
            'tenant'          => InitializeTenancyByDomain::class,
            'superadmin'      => App\Http\Middleware\SuperAdminMiddleware::class,
            'device.auth'     => App\Http\Middleware\DeviceAuthentication::class,
            'permission'      => App\Http\Middleware\CheckPermission::class,
            'tenant.settings' => TenantSettingsMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })->create();
