<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Tenant\Models\Tenant;
use App\Tenant\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

final class TenantImpersonationMiddleware
{
    /** Handle an incoming request. */
    public function handle(Request $request, Closure $next): Response
    {
        // Handle auto login on tenant domains when impersonation is active
        if ($request->route()?->getName() === 'tenant.auto-login') {
            $tenantId = Session::get('impersonated_tenant');

            // Get the current tenant from the domain
            $domain = $request->getHost();
            $tenant = Tenant::query()
                ->whereHas('domains', function ($query) use ($domain) {
                    $query->where('domain', $domain);
                })
                ->first();

            if ($tenant && $tenantId && $tenant->id === $tenantId) {
                // Find an admin user to impersonate
                $adminUser = User::where('tenant_id', $tenantId)
                    ->where('role', 'admin')
                    ->first() ?? User::where('tenant_id', $tenantId)->first();

                if ($adminUser) {
                    Auth::login($adminUser);

                    return redirect()->route('dashboard');
                }
            }
        }

        // Check if we're already on a tenant domain and need to auto-login
        $impersonatedTenantId = Session::get('impersonated_tenant');

        if ($impersonatedTenantId && ! Auth::check()) {
            // Get the current tenant from the domain
            $domain = $request->getHost();
            $tenant = Tenant::query()
                ->whereHas('domains', function ($query) use ($domain) {
                    $query->where('domain', $domain);
                })
                ->first();

            // If we're on the correct tenant's domain
            if ($tenant && $tenant->id === $impersonatedTenantId) {
                // Find the first admin user to impersonate
                $adminUser = User::where('tenant_id', $tenant->id)
                    ->where('role', 'admin')
                    ->first() ?? User::where('tenant_id', $tenant->id)->first();

                if ($adminUser) {
                    Auth::login($adminUser);
                }
            }
        }

        return $next($request);
    }
}
