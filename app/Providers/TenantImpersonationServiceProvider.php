<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Tenant\Models\User;
use App\Tenant\Models\Tenant;

class TenantImpersonationServiceProvider extends ServiceProvider
{
    /** Register services. */
    public function register(): void
    {
    }

    /** Bootstrap services. */
    public function boot(): void
    {
        // Handle impersonation on every request
        $this->app->booted(function () {
            // Check if we're impersonating a tenant
            $impersonatedTenantId = Session::get('impersonated_tenant');

            if ($impersonatedTenantId && ! Auth::check()) {
                // Find the tenant
                $tenant = Tenant::find($impersonatedTenantId);

                if ($tenant) {
                    // Find first admin user for this tenant
                    $adminUser = User::where('tenant_id', $tenant->id)
                        ->where('role', 'admin')
                        ->first();

                    // If no admin found, try to get any user
                    if ( ! $adminUser) {
                        $adminUser = User::where('tenant_id', $tenant->id)->first();
                    }

                    // Log in as the user
                    if ($adminUser) {
                        Auth::login($adminUser);
                    }
                }
            }
        });
    }
}
