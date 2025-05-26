<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Enums\OnboardingStep;
use App\Services\OnboardingProgressService;
use App\Tenant\Models\OnboardingProgress;
use Illuminate\Support\Collection;
use Livewire\Component;

final class OnboardingWidget extends Component
{
    public ?OnboardingProgress $onboardingProgress = null;
    public Collection $steps;
    public bool $dismissed = false;
    public string $contextStepKey = '';
    public bool $loaded = false;

    public function mount(?string $contextStepKey = null): void
    {
        $this->contextStepKey = $contextStepKey ?? '';
        $this->steps = collect(); // Initialize steps as an empty collection
    }

    public function loadData(): void
    {
        $this->onboardingProgress = OnboardingProgress::firstOrCreate(
            ['tenant_id' => tenant('id')],
            // All steps default to false, OnboardingProgress model should handle defaults
        );
        $this->buildSteps();
        $this->loaded = true;
    }

    public function buildSteps(): void
    {
        $this->steps = collect(OnboardingStep::cases())->map(function (OnboardingStep $stepEnum) {
            return [
                'key'         => $stepEnum->value,
                'title'       => $stepEnum->getTitle(),
                'description' => $stepEnum->getDescription(),
                'completed'   => $this->onboardingProgress?->{$stepEnum->value} ?? false,
                'route'       => $stepEnum->getRouteName(),
                'icon'        => $stepEnum->getIconName(),
                'cardData'    => $stepEnum->getCardData(), // Used by modal and potentially cards
            ];
        });
    }

    public function markStepComplete(string $key): void
    {
        if (!$this->onboardingProgress || !property_exists($this->onboardingProgress, $key) || $this->onboardingProgress->{$key} === true) {
            return;
        }

        app(OnboardingProgressService::class)->completeStep($this->onboardingProgress, $key);
        // Refresh steps data after completion
        $this->onboardingProgress->refresh(); // Ensure we have the latest progress state
        $this->buildSteps();
    }

    public function getProgress(): int
    {
        if (!$this->loaded || !$this->onboardingProgress) {
            return 0;
        }
        return $this->onboardingProgress->getCompletedStepsCount();
    }

    public function getTotalSteps(): int
    {
        if (!$this->loaded) { // No need for onboardingProgress here as it relies on enum
            return 0;
        }
        return count(OnboardingStep::getRequiredSteps());
    }

    public function getProgressPercentage(): int
    {
        if (!$this->loaded || !$this->onboardingProgress) {
            return 0;
        }
        $totalRequired = $this->getTotalSteps();
        if ($totalRequired === 0) {
            return 100; // Or 0, depending on desired behavior if no steps are required
        }
        $completed = $this->getProgress();
        return (int) (($completed / $totalRequired) * 100);
    }

    public function isComplete(): bool
    {
        if (!$this->loaded || !$this->onboardingProgress) {
            return false;
        }
        return $this->onboardingProgress->isComplete(OnboardingStep::getRequiredSteps());
    }

    public function getNextIncompleteStep(): ?array
    {
        if (!$this->loaded) {
            return null;
        }
        // Filter steps to only include required ones, then find the first incomplete
        $requiredStepKeys = array_map(fn(OnboardingStep $step) => $step->value, OnboardingStep::getRequiredSteps());

        return $this->steps
            ->filter(fn($step) => in_array($step['key'], $requiredStepKeys) && !$step['completed'])
            ->first();
    }

    public function dismiss(): void
    {
        $this->dismissed = true;
    }

    public function getContextStep(): ?array
    {
        if (!$this->loaded || !$this->contextStepKey) {
            return null;
        }
        $contextStepData = $this->steps->firstWhere('key', $this->contextStepKey);

        // Show context modal only if the step is not completed
        if ($contextStepData && ($this->onboardingProgress?->{$this->contextStepKey} === false)) {
            return $contextStepData;
        }
        return null;
    }

    public function render()
    {
        return view('livewire.dashboard.onboarding-widget');
    }
}
