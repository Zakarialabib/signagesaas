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
        $this->steps = collect([
            [
                'key'         => 'profile_completed',
                'title'       => 'Complete your profile',
                'description' => 'Set up your organization profile with logo and contact information.',
                'completed'   => $this->onboardingProgress->profile_completed,
                'route'       => 'settings.profile',
                'icon'        => 'user-circle',
                'examples'    => OnboardingProgress::$stepExamples['profile_completed'],
            ],
            [
                'key'         => 'first_device_registered',
                'title'       => 'Register your first device',
                'description' => 'Add a digital signage device to your account.',
                'completed'   => $this->onboardingProgress->first_device_registered,
                'route'       => 'devices.index',
                'icon'        => 'device-tablet',
                'examples'    => OnboardingProgress::$stepExamples['first_device_registered'],
            ],
            [
                'key'         => 'first_content_uploaded',
                'title'       => 'Upload your first content',
                'description' => 'Add images, videos, or create content using our editor.',
                'completed'   => $this->onboardingProgress->first_content_uploaded,
                'route'       => 'content.index',
                'icon'        => 'photo',
                'examples'    => OnboardingProgress::$stepExamples['first_content_uploaded'],
            ],
            [
                'key'         => 'first_screen_created',
                'title'       => 'Create your first screen',
                'description' => 'Define a screen layout with zones for your content.',
                'completed'   => $this->onboardingProgress->first_screen_created,
                'route'       => 'screens.index',
                'icon'        => 'desktop-computer',
                'examples'    => OnboardingProgress::$stepExamples['first_screen_created'],
            ],
            [
                'key'         => 'first_schedule_created',
                'title'       => 'Create a schedule',
                'description' => 'Schedule when your content should play on your screens.',
                'completed'   => $this->onboardingProgress->first_schedule_created,
                'route'       => 'schedules.index',
                'icon'        => 'calendar',
                'examples'    => OnboardingProgress::$stepExamples['first_schedule_created'],
            ],
            [
                'key'         => 'first_user_invited',
                'title'       => 'Invite team members',
                'description' => 'Add colleagues who will help manage your digital signage.',
                'completed'   => $this->onboardingProgress->first_user_invited,
                'route'       => 'settings.users',
                'icon'        => 'users',
                'examples'    => OnboardingProgress::$stepExamples['first_user_invited'],
            ],
            [
                'key'         => 'subscription_setup',
                'title'       => 'Review your subscription',
                'description' => 'Make sure your plan meets your digital signage needs.',
                'completed'   => $this->onboardingProgress->subscription_setup,
                'route'       => 'settings.subscription',
                'icon'        => 'credit-card',
                'examples'    => OnboardingProgress::$stepExamples['subscription_setup'],
            ],
            [
                'key'         => 'viewed_analytics',
                'title'       => 'View analytics',
                'description' => 'Check out how your digital signage is performing.',
                'completed'   => $this->onboardingProgress->viewed_analytics,
                'route'       => 'dashboard.analytics',
                'icon'        => 'chart-bar',
                'examples'    => OnboardingProgress::$stepExamples['viewed_analytics'],
            ],
            [
                'key'         => 'first_widget_content_created',
                'title'       => 'Create Your First Widget Content',
                'description' => 'Use pre-built widget types like Menus or Product Showcases and add your own data.',
                'completed'   => $this->onboardingProgress->first_widget_content_created,
                'route'       => 'tenant.content.index', // Assuming 'content.index' is the route for ContentManager
                'icon'        => 'heroicon-o-puzzle-piece', 
                'examples'    => OnboardingProgress::$stepExamples['first_widget_content_created'],
            ],
            [
                'key'         => 'widget_content_assigned_to_template',
                'title'       => 'Add Widget Content to a Template',
                'description' => 'Assign your customized widget content to a zone in your screen templates.',
                'completed'   => $this->onboardingProgress->widget_content_assigned_to_template,
                'route'       => 'tenant.templates.index', // Assuming 'templates.index' is for TemplateManager
                'icon'        => 'heroicon-o-squares-plus', 
                'examples'    => OnboardingProgress::$stepExamples['widget_content_assigned_to_template'],
            ],
        ]);
//         $this->steps = collect(OnboardingStep::cases())->map(function (OnboardingStep $stepEnum) {
//             return [
//                 'key'         => $stepEnum->value,
//                 'title'       => $stepEnum->getTitle(),
//                 'description' => $stepEnum->getDescription(),
//                 'completed'   => $this->onboardingProgress?->{$stepEnum->value} ?? false,
//                 'route'       => $stepEnum->getRouteName(),
//                 'icon'        => $stepEnum->getIconName(),
//                 'cardData'    => $stepEnum->getCardData(), // Used by modal and potentially cards
//             ];
//         });
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
        // Ensure getRequiredSteps() returns an array of strings
        $requiredSteps = $this->onboardingProgress->getRequiredSteps();
        if (!is_array($requiredSteps)) {
            // Handle error or return null, depending on desired behavior
            return null;
        }

        foreach ($this->steps as $step) {
            if ( ! $step['completed'] && in_array($step['key'], $requiredSteps, true)) {
                return $step;
            }
//         if (!$this->loaded) {
//             return null;
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
