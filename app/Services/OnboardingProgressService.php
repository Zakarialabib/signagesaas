<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\OnboardingStepCompleted;
use App\Tenant\Models\OnboardingProgress;
use Illuminate\Support\Facades\Auth;

final class OnboardingProgressService
{
    public function completeStep(OnboardingProgress $progress, string $step): void
    {
        // Ensure the authenticated user is authorized to update this progress
        // if (Auth::user()->cannot('update', $progress)) {
        //     // Or throw an AuthorizationException
        //     return;
        // }

        if ($progress->{$step} === false) {
            $progress->update([$step => true]);
            event(new OnboardingStepCompleted($progress, $step, Auth::id()));
        }
    }
} 