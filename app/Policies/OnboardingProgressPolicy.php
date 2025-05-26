<?php

declare(strict_types=1);

namespace App\Policies;

use App\Tenant\Models\OnboardingProgress;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class OnboardingProgressPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, OnboardingProgress $onboardingProgress): bool
    {
        return $user->id === $onboardingProgress->user_id || $user->isSuperAdmin();
    }

    public function create(User $user): bool
    {
        return true; // Or specific logic for who can create
    }

    public function update(User $user, OnboardingProgress $onboardingProgress): bool
    {
        return $user->id === $onboardingProgress->user_id;
    }

    public function delete(User $user, OnboardingProgress $onboardingProgress): bool
    {
        return $user->isSuperAdmin(); // Example: only super admins can delete
    }

    public function restore(User $user, OnboardingProgress $onboardingProgress): bool
    {
        return $user->isSuperAdmin(); // Example: only super admins can restore
    }

    public function forceDelete(User $user, OnboardingProgress $onboardingProgress): bool
    {
        return $user->isSuperAdmin(); // Example: only super admins can force delete
    }
}
