<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use App\Enums\OnboardingStep;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class OnboardingProgress extends Model
{
    use HasUuids;
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'profile_completed',
        'first_device_registered',
        'first_content_uploaded',
        'first_screen_created',
        'first_schedule_created',
        'first_user_invited',
        'subscription_setup',
        'viewed_analytics',
        'first_widget_content_created',
        'widget_content_assigned_to_template',
        'custom_steps',
        'completed_at',
    ];

    protected $casts = [
        'profile_completed'       => 'boolean',
        'first_device_registered' => 'boolean',
        'first_content_uploaded'  => 'boolean',
        'first_screen_created'    => 'boolean',
        'first_schedule_created'  => 'boolean',
        'first_user_invited'      => 'boolean',
        'subscription_setup'      => 'boolean',
        'viewed_analytics'        => 'boolean',
        'first_widget_content_created' => 'boolean',
        'widget_content_assigned_to_template' => 'boolean',
        'custom_steps'            => 'array',
        'completed_at'            => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            foreach (OnboardingStep::cases() as $step) {
                if (!isset($model->{$step->value})) {
                    $model->{$step->value} = false;
                }
            }
            if (!isset($model->custom_steps)) {
                $model->custom_steps = [];
            }
        });
    }

    public function markStepCompleted(string $stepKey, bool $completed = true): bool
    {
        if (property_exists($this, $stepKey) && is_bool($this->{$stepKey})) {
            $this->{$stepKey} = $completed;
            $this->checkForCompletion();
            return $this->save();
        }
        return $this->markCustomStep($stepKey, $completed);
    }

    public function markProfileCompleted(): bool
    {
        return $this->markStepCompleted(OnboardingStep::PROFILE_COMPLETED->value);
    }

    public function markFirstDeviceRegistered(): bool
    {
        return $this->markStepCompleted(OnboardingStep::FIRST_DEVICE_REGISTERED->value);
    }

    public function markFirstContentUploaded(): bool
    {
        return $this->markStepCompleted(OnboardingStep::FIRST_CONTENT_UPLOADED->value);
    }

    public function markFirstWidgetContentCreated(): bool
    {
        return $this->markStepCompleted(OnboardingStep::FIRST_WIDGET_CONTENT_CREATED->value);
    }

    public function markFirstScreenCreated(): bool
    {
        return $this->markStepCompleted(OnboardingStep::FIRST_SCREEN_CREATED->value);
    }

    public function markWidgetContentAssignedToTemplate(): bool
    {
        return $this->markStepCompleted(OnboardingStep::WIDGET_CONTENT_ASSIGNED_TO_TEMPLATE->value);
    }

    public function markFirstScheduleCreated(): bool
    {
        return $this->markStepCompleted(OnboardingStep::FIRST_SCHEDULE_CREATED->value);
    }

    public function markFirstUserInvited(): bool
    {
        return $this->markStepCompleted(OnboardingStep::FIRST_USER_INVITED->value);
    }

    public function markSubscriptionSetup(): bool
    {
        return $this->markStepCompleted(OnboardingStep::SUBSCRIPTION_SETUP->value);
    }

    public function markViewedAnalytics(): bool
    {
        return $this->markStepCompleted(OnboardingStep::VIEWED_ANALYTICS->value);
    }

    public function markCustomStep(string $step, bool $value = true): bool
    {
        $customSteps = $this->custom_steps ?? [];
        $customSteps[$step] = $value;
        $this->custom_steps = $customSteps;
        $this->checkForCompletion();

        return $this->save();
    }

    public function isComplete(?array $requiredStepsOverride = null): bool
    {
        $requiredSteps = $requiredStepsOverride ?? OnboardingStep::getRequiredSteps();
        foreach ($requiredSteps as $stepEnum) {
            if (!$this->{$stepEnum->value}) {
                return false;
            }
        }
        return true;
    }

    public function getRequiredSteps(): array
    {
        return OnboardingStep::getRequiredSteps();
    }

    public function getCompletedStepsCount(): int
    {
        $count = 0;
        $requiredSteps = OnboardingStep::getRequiredSteps();

        foreach ($requiredSteps as $stepEnum) {
            if ($this->{$stepEnum->value}) {
                $count++;
            }
        }

        return $count;
    }

    public function getCompletionPercentage(): float
    {
        $totalSteps = count(OnboardingStep::getRequiredSteps());
        if ($totalSteps === 0) {
            return 100.0;
        }
        $completedSteps = $this->getCompletedStepsCount();

        return round(($completedSteps / $totalSteps) * 100, 2);
    }

    public function getNextStep(): ?OnboardingStep
    {
        foreach (OnboardingStep::getRequiredSteps() as $stepEnum) {
            if ( ! $this->{$stepEnum->value}) {
                return $stepEnum;
            }
        }

        return null;
    }

    public function getNextStepTitle(): ?string
    {
        return $this->getNextStep()?->getTitle();
    }

    public function getNextStepDescription(): ?string
    {
        return $this->getNextStep()?->getDescription();
    }

    private function checkForCompletion(): void
    {
        if ( ! $this->completed_at && $this->isComplete()) {
            $this->completed_at = now();
        }
    }
}
