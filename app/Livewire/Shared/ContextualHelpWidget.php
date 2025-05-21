<?php

declare(strict_types=1);

namespace App\Livewire\Shared;

use App\Enums\OnboardingStep;
use App\Tenant\Models\OnboardingProgress;
use Livewire\Component;
use Livewire\Attributes\Computed;

final class ContextualHelpWidget extends Component
{
    public string $contextKey;
    public bool $isExpanded = false;
    public bool $hasBeenViewed = false;
    public ?array $step = null;

    public function mount(string $contextKey): void
    {
        $this->contextKey = $contextKey;
        $this->loadStepInformation();
    }

    private function loadStepInformation(): void
    {
        // Get the onboarding progress
        $onboardingProgress = OnboardingProgress::firstOrCreate(
            ['tenant_id' => tenant('id')],
            [
                'profile_completed'       => false,
                'first_device_registered' => false,
                'first_content_uploaded'  => false,
                'first_screen_created'    => false,
                'first_schedule_created'  => false,
                'first_user_invited'      => false,
                'subscription_setup'      => false,
                'viewed_analytics'        => false,
            ]
        );

        // Build step information
        $this->step = [
            'key'         => $this->contextKey,
            'title'       => OnboardingStep::from($this->contextKey)->getTitle(),
            'description' => OnboardingStep::from($this->contextKey)->getDescription(),
            'completed'   => $onboardingProgress->{$this->contextKey},
            'examples'    => OnboardingProgress::$stepExamples[$this->contextKey] ?? [],
        ];
    }

    public function toggle(): void
    {
        $this->isExpanded = ! $this->isExpanded;

        if ($this->isExpanded && ! $this->hasBeenViewed) {
            $this->hasBeenViewed = true;
            $this->dispatch('help-content-viewed', stepKey: $this->contextKey);
        }
    }

    #[Computed]
    public function getProgressPercentage(): int
    {
        if ( ! $this->hasBeenViewed) {
            return 0;
        }

        return 100;
    }

    public function render()
    {
        return view('livewire.shared.contextual-help-widget');
    }
}
