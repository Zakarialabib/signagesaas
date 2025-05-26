<?php

declare(strict_types=1);

namespace App\Events;

use App\Tenant\Models\OnboardingProgress;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OnboardingStepCompleted
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public OnboardingProgress $onboardingProgress;
    public string $step;
    public int $userId;

    public function __construct(OnboardingProgress $onboardingProgress, string $step, int $userId)
    {
        $this->onboardingProgress = $onboardingProgress;
        $this->step = $step;
        $this->userId = $userId;
    }
}
