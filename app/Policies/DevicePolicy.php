<?php

declare(strict_types=1);

namespace App\Policies;

use App\Tenant\Models\Device;
use App\Tenant\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class DevicePolicy
{
    use HandlesAuthorization;

    /** Determine whether the user can view any models. */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view devices
    }

    /** Determine whether the user can view the model. */
    public function view(User $user, Device $device): bool
    {
        return true; // All authenticated users can view a device
    }

    /** Determine whether the user can create models. */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create devices
    }

    /** Determine whether the user can update the model. */
    public function update(User $user, Device $device): bool
    {
        return true; // All authenticated users can update devices
    }

    /** Determine whether the user can delete the model. */
    public function delete(User $user, Device $device): bool
    {
        return true; // All authenticated users can delete devices
    }

    /** Determine whether the user can restore the model. */
    public function restore(User $user, Device $device): bool
    {
        return true; // All authenticated users can restore devices
    }

    /** Determine whether the user can permanently delete the model. */
    public function forceDelete(User $user, Device $device): bool
    {
        return true; // All authenticated users can force delete devices
    }
}
