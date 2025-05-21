<?php

declare(strict_types=1);

namespace App\Providers;

use App\Tenant\Models\Tenant;
use App\Tenant\Models\Content;
use App\Tenant\Models\Device;
use App\Tenant\Models\Screen;
use App\Policies\ContentPolicy;
use App\Policies\DevicePolicy;
use App\Policies\ScreenPolicy;
use App\Policies\TenantPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Device::class  => DevicePolicy::class,
        Screen::class  => ScreenPolicy::class,
        Content::class => ContentPolicy::class,
        Tenant::class  => TenantPolicy::class,
    ];

    /** Register any authentication / authorization services. */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
