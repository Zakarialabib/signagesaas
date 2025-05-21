<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * Trait HasPermissions
 *
 * This trait can be used in Livewire components to easily check permissions
 */
trait HasPermissions
{
    /** Check if the current user has a specific permission */
    public function hasPermission(string $permission): bool
    {
        // Super admins have all permissions
        if (Auth::guard('superadmin')->check()) {
            return true;
        }

        return Auth::user()?->hasPermissionTo($permission) ?? false;
    }

    /** Check if the current user has any of the given permissions */
    public function hasAnyPermission(array $permissions): bool
    {
        // Super admins have all permissions
        if (Auth::guard('superadmin')->check()) {
            return true;
        }

        return Auth::user()?->hasAnyPermission($permissions) ?? false;
    }

    /** Check if the current user has all of the given permissions */
    public function hasAllPermissions(array $permissions): bool
    {
        // Super admins have all permissions
        if (Auth::guard('superadmin')->check()) {
            return true;
        }

        return Auth::user()?->hasAllPermissions($permissions) ?? false;
    }

    /** Authorize a permission or throw an exception */
    public function authorizePermission(string $permission, string $message = null): void
    {
        if ( ! $this->hasPermission($permission)) {
            $this->throwUnauthorizedException($message ?? "You don't have permission to perform this action.");
        }
    }

    /** Throw an unauthorized exception */
    protected function throwUnauthorizedException(string $message): void
    {
        throw new \Illuminate\Auth\Access\AuthorizationException($message);
    }
}
