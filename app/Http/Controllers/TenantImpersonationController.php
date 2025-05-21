<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Tenant\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

final class TenantImpersonationController extends Controller
{
    /** Initiate tenant impersonation */
    public function impersonate(Request $request, $tenantId, string $signature)
    {
        // Find the tenant first (use explicit find since route model binding might not work)
        $tenant = Tenant::find($tenantId);

        if ( ! $tenant) {
            Log::error('Impersonation failed: Tenant not found', ['tenant_id' => $tenantId]);

            return redirect()->back()->with('error', 'Tenant not found');
        }

        // Verify the signature to ensure security
        $expectedSignature = hash_hmac('sha256', $tenant->id, config('app.key'));

        if ( ! hash_equals($expectedSignature, $signature)) {
            Log::error('Impersonation failed: Invalid signature', ['tenant_id' => $tenant->id]);
            abort(403, 'Invalid signature');
        }

        // Store the tenant ID in session for auto-login in tenant domains
        Session::put('impersonated_tenant', $tenant->id);

        // Get the primary domain for this tenant
        $domain = $tenant->domains()->first();

        if ( ! $domain) {
            Log::error('Impersonation failed: No domain configured', ['tenant_id' => $tenant->id]);

            return redirect()->back()->with('error', 'Tenant has no domains configured');
        }

        Log::info('Impersonation initiated', [
            'tenant_id'   => $tenant->id,
            'tenant_name' => $tenant->name,
            'domain'      => $domain->domain,
        ]);

        // Redirect to tenant domain with auto-login route
        $protocol = request()->secure() ? 'https://' : 'http://';

        return redirect("{$protocol}{$domain->domain}/auto-login");
    }

    /** End tenant impersonation */
    public function endImpersonation(Request $request)
    {
        $tenantId = Session::get('impersonated_tenant');

        if ($tenantId) {
            Log::info('Impersonation ended', ['tenant_id' => $tenantId]);
        }

        Session::forget('impersonated_tenant');

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Tenant impersonation ended');
    }
}
