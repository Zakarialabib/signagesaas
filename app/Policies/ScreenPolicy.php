<?php

declare(strict_types=1);

namespace App\Policies;

use App\Tenant\Models\Screen;
use App\Tenant\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class ScreenPolicy
{
    use HandlesAuthorization;

    /** Determine whether the user can view any models. */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view screens
    }

    /** Determine whether the user can view the model. */
    public function view(User $user, Screen $screen): bool
    {
        return true; // All authenticated users can view a screen
    }

    /** Determine whether the user can create models. */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create screens
    }

    /** Determine whether the user can update the model. */
    public function update(User $user, Screen $screen): bool
    {
        return true; // All authenticated users can update screens
    }

    /** Determine whether the user can delete the model. */
    public function delete(User $user, Screen $screen): bool
    {
        return true; // All authenticated users can delete screens
    }

    /** Determine whether the user can restore the model. */
    public function restore(User $user, Screen $screen): bool
    {
        return true; // All authenticated users can restore screens
    }

    /** Determine whether the user can permanently delete the model. */
    public function forceDelete(User $user, Screen $screen): bool
    {
        return true; // All authenticated users can force delete screens
    }
}
