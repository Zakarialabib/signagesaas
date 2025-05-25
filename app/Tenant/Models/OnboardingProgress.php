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
        'custom_steps',
        'completed_at',
        'first_widget_content_created',
        'widget_content_assigned_to_template',
    ];

    public static array $stepExamples = [
        OnboardingStep::PROFILE_COMPLETED->value => [
            'Upload a high-resolution logo (recommended size: 400x400px) in PNG or SVG format',
            'Add essential business details: company name, address, phone, and email',
            'Configure regional settings: timezone, date format, and preferred language',
            'Customize your organization\'s color scheme for branded content',
            'Set up notification preferences for system alerts and updates',
        ],
        OnboardingStep::FIRST_DEVICE_REGISTERED->value => [
            '**What is a Device?** A device is the physical hardware (like a TV, tablet, or specialized player) that will display your digital signage content.',
            '**Why Register?** Registering links your physical device to your account, allowing you to manage content and settings remotely.',
            '**Device Types:**
             - Smart TVs with built-in browser
             - Android TV boxes or tablets
             - Raspberry Pi with our optimized image
             - Windows Mini-PCs or laptops
             - BrightSign players',
            '**Setup Requirements:**
             - Stable internet connection
             - Power supply for 24/7 operation
             - HDMI connection to display
             - Our player app installed',
            '**Best Practices:**
             - Position devices for optimal visibility
             - Ensure proper ventilation
             - Use wired internet when possible
             - Configure auto-recovery settings',
        ],
        OnboardingStep::FIRST_SCREEN_CREATED->value => [
            '**What is a Screen?** A screen is your digital canvas - it defines how content will be arranged and displayed on your physical device.',
            '**Device vs. Screen Relationship:**
             - One device can show multiple screens
             - Example: A café TV showing breakfast menu in morning, lunch specials in afternoon
             - Screens can be scheduled to change automatically',
            '**Screen Layouts:**
             - Full-screen for single content
             - Split-screen for multiple zones
             - L-shaped layouts for main content + ticker
             - Picture-in-picture for overlays',
            '**Screen Zones:**
             - Main content area
             - News/social media ticker
             - Weather widget
             - Time and date display
             - Emergency message overlay',
            '**Screen Management:**
             - Preview before publishing
             - Test on different resolutions
             - Schedule screen transitions
             - Monitor screen performance',
        ],
        OnboardingStep::FIRST_CONTENT_UPLOADED->value => [
            '**What is Content?** Content includes any media or information you want to display:
             - Images (JPG, PNG, SVG)
             - Videos (MP4, WebM)
             - Web pages
             - Live data feeds',
            '**Content Organization:**
             - Create content libraries
             - Tag content for easy finding
             - Set content expiration dates
             - Track content usage',
            '**Templates Overview:**
             - Pre-designed layouts for common uses
             - Customizable for your brand
             - Time-saving starting points
             - Consistent look across screens',
            '**Template Categories:**
             - Menu boards
             - Corporate communications
             - Retail promotions
             - Event schedules
             - Social media walls',
            '**Content Best Practices:**
             - Use high-resolution assets
             - Keep text readable
             - Consider viewing distance
             - Test content in real conditions',
        ],
        OnboardingStep::FIRST_SCHEDULE_CREATED->value => [
            'Create schedules to automate content playback on your screens',
            'Set specific days and times for content to be displayed',
            'Prioritize content by assigning different priority levels',
            'Schedule content to play during business hours only',
            'Create recurring schedules for regular content updates',
            'Use the calendar view to manage multiple schedules',
            'Schedule different content for different times of the day',
            'Set up holiday schedules in advance',
            'Use schedule templates for common content rotations',
            'Monitor schedule status and make adjustments as needed',
        ],
        OnboardingStep::FIRST_USER_INVITED->value => [
            'Define user roles: Admin, Content Manager, Publisher, or Viewer',
            'Set up content approval workflows with multiple review stages',
            'Configure access permissions for different content libraries and screens',
            'Enable team collaboration features for content creation',
            'Set up audit logging for content and system changes',
        ],
        OnboardingStep::SUBSCRIPTION_SETUP->value => [
            'Compare plans based on: number of displays, storage, and advanced features',
            'Enable enterprise features: API access, custom integrations, and SSO',
            'Set up automated billing with detailed usage reporting',
            'Configure backup payment methods for uninterrupted service',
            'Review and optimize subscription based on actual usage patterns',
        ],
        OnboardingStep::VIEWED_ANALYTICS->value => [
            'Track content performance metrics: views, engagement time, and interaction rates',
            'Monitor system health: device uptime, connectivity, and content delivery',
            'Generate custom reports for stakeholders with visualizations',
            'Analyze audience behavior patterns for content optimization',
            'Set up automated performance alerts and weekly digest reports',
        ],
        'first_widget_content_created' => [
            "Navigate to Content > Content Library.",
            "Click 'Add Widget Content'.",
            "Choose a widget type like 'Digital Menu Board' or 'Retail Product Showcase'.",
            "Fill in the specific details and save your new widget content."
        ],
        'widget_content_assigned_to_template' => [
            "Go to Templates and open a template in the Configurator.",
            "Select a zone, or add a new one.",
            "Set the zone's 'Type' to 'Widget' and choose the specific 'Widget Type' (e.g., Menu Widget).",
            "Click 'Assign Content' and select your widget content, or create new content for the zone."
        ],
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
        'custom_steps'            => 'array',
        'completed_at'            => 'datetime',
        'first_widget_content_created' => 'boolean',
        'widget_content_assigned_to_template' => 'boolean',
    ];

    // Completion methods
    public function markProfileCompleted(): bool
    {
        $this->profile_completed = true;
        $this->checkForCompletion();

        return $this->save();
    }

    public function markFirstDeviceRegistered(): bool
    {
        $this->first_device_registered = true;
        $this->checkForCompletion();

        return $this->save();
    }

    public function markFirstContentUploaded(): bool
    {
        $this->first_content_uploaded = true;
        $this->checkForCompletion();

        return $this->save();
    }

    public function markFirstScreenCreated(): bool
    {
        $this->first_screen_created = true;
        $this->checkForCompletion();

        return $this->save();
    }

    public function markFirstScheduleCreated(): bool
    {
        $this->first_schedule_created = true;
        $this->checkForCompletion();

        return $this->save();
    }

    public function markFirstUserInvited(): bool
    {
        $this->first_user_invited = true;
        $this->checkForCompletion();

        return $this->save();
    }

    public function markSubscriptionSetup(): bool
    {
        $this->subscription_setup = true;
        $this->checkForCompletion();

        return $this->save();
    }

    public function markViewedAnalytics(): bool
    {
        $this->viewed_analytics = true;
        $this->checkForCompletion();

        return $this->save();
    }

    public function markCustomStep(string $step, bool $value = true): bool
    {
        $steps = $this->custom_steps ?? [];
        $steps[$step] = $value;
        $this->custom_steps = $steps;
        $this->checkForCompletion();

        return $this->save();
    }

    // Status check methods
    public function isComplete(): bool
    {
        return $this->completed_at !== null;
    }

    public function getRequiredSteps(): array
    {
        // return array_map(fn (OnboardingStep $step) => $step->value, OnboardingStep::getRequiredSteps());
        // Manual merge of existing logic with new steps, assuming OnboardingStep enum might not be updated yet.
        return [
            'profile_completed',
            'first_device_registered',
            'first_content_uploaded',
            'first_screen_created',
            'first_schedule_created',
            'first_user_invited',
            // 'subscription_setup', // Example: Assuming this might be optional for some tenants
            // 'viewed_analytics',   // Example: Assuming this might be optional
            'first_widget_content_created',
            'widget_content_assigned_to_template',
        ];
    }

    public function getCompletedStepsCount(): int
    {
        $count = 0;
        $steps = $this->getRequiredSteps();

        foreach ($steps as $step) {
            if ($this->$step) {
                $count++;
            }
        }

        return $count;
    }

    public function getCompletionPercentage(): float
    {
        $total = count($this->getRequiredSteps());
        $completed = $this->getCompletedStepsCount();

        if ($total === 0) {
            return 100.0;
        }

        return round(($completed / $total) * 100, 2);
    }

    public function getNextStep(): ?string
    {
        $steps = $this->getRequiredSteps();

        foreach ($steps as $step) {
            if ( ! $this->$step) {
                return $step;
            }
        }

        return null;
    }

    public function getNextStepDescription(): ?string
    {
        $nextStep = $this->getNextStep();

        if ( ! $nextStep) {
            return null;
        }

        return OnboardingStep::from($nextStep)->getDescription();
    }

    // Private methods
    private function checkForCompletion(): void
    {
        if ($this->isComplete()) {
            return;
        }

        $requiredSteps = $this->getRequiredSteps();
        $allComplete = true;

        foreach ($requiredSteps as $step) {
            if ( ! $this->$step) {
                $allComplete = false;

                break;
            }
        }

        if ($allComplete && ! $this->completed_at) {
            $this->completed_at = now();
        }
    }
}
