<?php

declare(strict_types=1);

return [
    // App\Providers\AppServiceProvider::class,
    App\Providers\LanguageServiceProvider::class,
    App\Providers\SettingsServiceProvider::class,
    App\Providers\TenancyServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\TenantImpersonationServiceProvider::class,
    App\Providers\TenantServiceProvider::class,
    App\Tenant\Routes\RouteServiceProvider::class,

];
