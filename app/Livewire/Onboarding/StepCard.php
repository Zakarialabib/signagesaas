<?php

declare(strict_types=1);

namespace App\Livewire\Onboarding;

use App\Enums\OnboardingStep;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class StepCard extends Component
{
    public OnboardingStep $step;
    public bool $completed = false;
    public string $route;

    public function mount(OnboardingStep $step, bool $completed = false, string $route = ''): void
    {
        $this->step = $step;
        $this->completed = $completed;
        $this->route = $route;
    }

    #[Computed]
    public function cardData(): array
    {
        return $this->step->getCardData();
    }

    public function isNextStep(): bool
    {
        // This should be implemented to check if this is the next step in sequence
        // For now, we'll consider the first incomplete step as the next step
        return ! $this->completed && ! $this->hasCompletedPreviousStep();
    }

    public function getActionText(): string
    {
        $actionVerb = match ($this->step) {
            OnboardingStep::PROFILE_COMPLETED       => 'Complete',
            OnboardingStep::FIRST_DEVICE_REGISTERED => 'Register',
            OnboardingStep::FIRST_CONTENT_UPLOADED  => 'Upload',
            OnboardingStep::FIRST_SCREEN_CREATED    => 'Create',
            OnboardingStep::FIRST_SCHEDULE_CREATED  => 'Create',
            OnboardingStep::FIRST_USER_INVITED      => 'Invite',
            OnboardingStep::SUBSCRIPTION_SETUP      => 'Review',
            OnboardingStep::VIEWED_ANALYTICS        => 'View',
        };

        return $actionVerb.' '.$this->step->getTitle();
    }

    private function hasCompletedPreviousStep(): bool
    {
        // This should be implemented to check if all previous steps are completed
        // For now, we'll return false
        return false;
    }

    public function render()
    {
        return view('livewire.onboarding.step-card');
    }
}
