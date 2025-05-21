<?php

declare(strict_types=1);

namespace App\Policies;

use App\Tenant\Models\Tenant;
use App\Tenant\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any tenants.
     *
     * @param  User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user): bool
    {
        // For now, assume any authenticated user in a tenant can view settings
        return $user->tenant_id !== null;
    }

    /**
     * Determine whether the user can view the tenant.
     *
     * @param  User  $user
     * @param  Tenant  $tenant
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Tenant $tenant): bool
    {
        // User can only view their own tenant's settings
        return $user->tenant_id === $tenant->id;
    }

    /**
     * Determine whether the user can update the tenant.
     *
     * @param  User  $user
     * @param  Tenant  $tenant
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ?Tenant $tenant = null): bool
    {
        if ( ! $tenant) {
            $tenant = $user->tenant;
        }

        // User can only update their own tenant's settings
        // In a real app, you might want to restrict this to admin users
        return $user->tenant_id === $tenant->id && $user->role === 'admin';
    }
}
