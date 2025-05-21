<?php

declare(strict_types=1);

namespace App\Policies;

use App\Tenant\Models\Content;
use App\Tenant\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class ContentPolicy
{
    use HandlesAuthorization;

    /** Determine whether the user can view any models. */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view content
    }

    /** Determine whether the user can view the model. */
    public function view(User $user, Content $content): bool
    {
        return true; // All authenticated users can view content
    }

    /** Determine whether the user can create models. */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create content
    }

    /** Determine whether the user can update the model. */
    public function update(User $user, Content $content): bool
    {
        return true; // All authenticated users can update content
    }

    /** Determine whether the user can delete the model. */
    public function delete(User $user, Content $content): bool
    {
        return true; // All authenticated users can delete content
    }

    /** Determine whether the user can restore the model. */
    public function restore(User $user, Content $content): bool
    {
        return true; // All authenticated users can restore content
    }

    /** Determine whether the user can permanently delete the model. */
    public function forceDelete(User $user, Content $content): bool
    {
        return true; // All authenticated users can force delete content
    }
}
