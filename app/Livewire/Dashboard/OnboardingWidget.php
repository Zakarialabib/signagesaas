<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Tenant\Models\OnboardingProgress;
use Illuminate\Support\Collection;
use Livewire\Component;

final class OnboardingWidget extends Component
{
    public ?OnboardingProgress $onboardingProgress = null;
    public Collection $steps;
    public bool $dismissed = false;
    public ?string $contextStepKey = null;

    public function mount(?string $contextStepKey = null): void
    {
        $this->contextStepKey = $contextStepKey;
        // Get or create the onboarding progress
        $this->onboardingProgress = OnboardingProgress::firstOrCreate(
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

        // Build steps collection
        $this->buildSteps();
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
        ]);
    }

    public function markStepComplete(string $key): void
    {
        if ( ! property_exists($this->onboardingProgress, $key)) {
            return;
        }

        // Set the property dynamically
        $method = 'mark'.str_replace('_', '', ucwords($key, '_')).'Completed';

        if (method_exists($this->onboardingProgress, $method)) {
            $this->onboardingProgress->$method();
            $this->buildSteps();
        }
    }

    public function getProgress(): int
    {
        return $this->onboardingProgress->getCompletedStepsCount();
    }

    public function getTotalSteps(): int
    {
        return count($this->onboardingProgress->getRequiredSteps());
    }

    public function getProgressPercentage(): int
    {
        return (int) $this->onboardingProgress->getCompletionPercentage();
    }

    public function isComplete(): bool
    {
        return $this->onboardingProgress->isComplete();
    }

    public function getNextIncompleteStep(): ?array
    {
        foreach ($this->steps as $step) {
            if ( ! $step['completed'] && in_array($step['key'], $this->onboardingProgress->getRequiredSteps())) {
                return $step;
            }
        }

        return null;
    }

    public function dismiss(): void
    {
        $this->dismissed = true;
    }

    public function getContextStep(): ?array
    {
        if ( ! $this->contextStepKey) {
            return null;
        }

        return $this->steps->firstWhere('key', $this->contextStepKey);
    }

    public function render()
    {
        return view('livewire.dashboard.onboarding-widget');
    }
}
