<?php

declare(strict_types=1);

namespace App\Enums;

enum OnboardingStep: string
{
    case PROFILE_COMPLETED = 'profile_completed';
    case FIRST_DEVICE_REGISTERED = 'first_device_registered';
    case FIRST_CONTENT_UPLOADED = 'first_content_uploaded';
    case FIRST_SCREEN_CREATED = 'first_screen_created';
    case FIRST_SCHEDULE_CREATED = 'first_schedule_created';
    case FIRST_USER_INVITED = 'first_user_invited';
    case SUBSCRIPTION_SETUP = 'subscription_setup';
    case VIEWED_ANALYTICS = 'viewed_analytics';
    case FIRST_WIDGET_CONTENT_CREATED = 'first_widget_content_created';
    case WIDGET_CONTENT_ASSIGNED_TO_TEMPLATE = 'widget_content_assigned_to_template';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getTitle(): string
    {
        return match ($this) {
            self::PROFILE_COMPLETED       => 'Complete Your Profile',
            self::FIRST_DEVICE_REGISTERED => 'Register Your First Device',
            self::FIRST_CONTENT_UPLOADED  => 'Upload Your First Content',
            self::FIRST_SCREEN_CREATED    => 'Create Your First Screen',
            self::FIRST_SCHEDULE_CREATED  => 'Create Your First Schedule',
            self::FIRST_USER_INVITED      => 'Invite Team Members',
            self::SUBSCRIPTION_SETUP      => 'Review Your Subscription',
            self::VIEWED_ANALYTICS        => 'View Analytics',
            self::FIRST_WIDGET_CONTENT_CREATED => 'Create Your First Widget Content',
            self::WIDGET_CONTENT_ASSIGNED_TO_TEMPLATE => 'Assign Widget to Template',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::PROFILE_COMPLETED       => 'Set up your organization profile with logo and contact information.',
            self::FIRST_DEVICE_REGISTERED => 'Add a digital signage device to your account.',
            self::FIRST_CONTENT_UPLOADED  => 'Add images, videos, or create content using our editor.',
            self::FIRST_SCREEN_CREATED    => 'Define a screen layout with zones for your content.',
            self::FIRST_SCHEDULE_CREATED  => 'Schedule when your content should play on your screens.',
            self::FIRST_USER_INVITED      => 'Add colleagues who will help manage your digital signage.',
            self::SUBSCRIPTION_SETUP      => 'Make sure your plan meets your digital signage needs.',
            self::VIEWED_ANALYTICS        => 'Check out how your digital signage is performing.',
            self::FIRST_WIDGET_CONTENT_CREATED => 'Design reusable content blocks for your templates.',
            self::WIDGET_CONTENT_ASSIGNED_TO_TEMPLATE => 'Incorporate your widget content into a screen template.',
        };
    }

    public function getRouteName(): string
    {
        return match ($this) {
            self::PROFILE_COMPLETED       => 'settings.profile',
            self::FIRST_DEVICE_REGISTERED => 'devices.index',
            self::FIRST_CONTENT_UPLOADED  => 'content.index',
            self::FIRST_SCREEN_CREATED    => 'screens.index',
            self::FIRST_SCHEDULE_CREATED  => 'schedules.index',
            self::FIRST_USER_INVITED      => 'settings.users',
            self::SUBSCRIPTION_SETUP      => 'settings.subscription',
            self::VIEWED_ANALYTICS        => 'dashboard.analytics',
            self::FIRST_WIDGET_CONTENT_CREATED => 'content.widgets',
            self::WIDGET_CONTENT_ASSIGNED_TO_TEMPLATE => 'templates.index',
        };
    }

    public function getIconName(): string
    {
        return match ($this) {
            self::PROFILE_COMPLETED       => 'user-circle',
            self::FIRST_DEVICE_REGISTERED => 'device-tablet',
            self::FIRST_CONTENT_UPLOADED  => 'photo',
            self::FIRST_SCREEN_CREATED    => 'desktop-computer',
            self::FIRST_SCHEDULE_CREATED  => 'calendar',
            self::FIRST_USER_INVITED      => 'users',
            self::SUBSCRIPTION_SETUP      => 'credit-card',
            self::VIEWED_ANALYTICS        => 'chart-bar',
            self::FIRST_WIDGET_CONTENT_CREATED => 'puzzle',
            self::WIDGET_CONTENT_ASSIGNED_TO_TEMPLATE => 'document-duplicate',
        };
    }

    public static function getRequiredSteps(): array
    {
        return [
            self::PROFILE_COMPLETED,
            self::FIRST_DEVICE_REGISTERED,
            self::FIRST_CONTENT_UPLOADED,
            self::FIRST_WIDGET_CONTENT_CREATED,
            self::FIRST_SCREEN_CREATED,
            self::WIDGET_CONTENT_ASSIGNED_TO_TEMPLATE,
        ];
    }

    public function getCardData(): array
    {
        return match ($this) {
            self::PROFILE_COMPLETED => [
                'imagePath' => 'images/onboarding/profile.svg',
                'features'  => [
                    'Upload your company logo (400x400px recommended).',
                    'Set organization name and contact details.',
                    'Configure timezone and regional settings.',
                    'Customize your branding colors and theme (if applicable).',
                ],
                'tips' => [
                    'Use a high-quality PNG or SVG logo.',
                    'Keep organization details updated for accurate reporting.',
                ],
                'bestPractices' => [
                    'Complete all profile sections.',
                    'Review and update information quarterly.',
                ],
            ],
            self::FIRST_DEVICE_REGISTERED => [
                'imagePath' => 'images/onboarding/device.svg',
                'features'  => [
                    'Register various types of display devices (Android, Windows, Raspberry Pi).',
                    'Assign a unique name and location to each device.',
                    'Note the hardware ID for specific device types if prompted.',
                    'Devices will initially appear as "inactive" until they connect.',
                ],
                'tips' => [
                    'Ensure devices have a stable network connection.',
                    'Use clear, unique names for easy identification.',
                ],
                'bestPractices' => [
                    'Maintain a physical inventory of your devices.',
                    'Regularly check device status in the dashboard.',
                ],
            ],
            self::FIRST_CONTENT_UPLOADED => [
                'imagePath' => 'images/onboarding/content.svg',
                'features'  => [
                    'Upload images, videos, or link to external URLs.',
                    'Create rich text or HTML content directly in the editor.',
                    'Organize content with names and descriptions.',
                    'Assign content to a screen for it to be scheduled and displayed.',
                ],
                'tips' => [
                    'Optimize image and video file sizes for faster loading on devices.',
                    'Use descriptive names for content items.',
                ],
                'bestPractices' => [
                    'Group related content or create a content calendar.',
                    'Regularly review and archive outdated content.',
                ],
            ],
            self::FIRST_WIDGET_CONTENT_CREATED => [
                'imagePath' => 'images/onboarding/widget-content.svg',
                'features' => [
                    'Create reusable blocks of content like menus, product lists, or custom HTML modules.',
                    'Define the structure and data for your widget.',
                    'Save widget content to be used across multiple templates and screens.',
                    'Update widget content once, and it reflects everywhere it\'s used.',
                ],
                'tips' => [
                    'Think modularly: what content pieces can be reused?',
                    'Keep widget data focused and well-structured.',
                ],
                'bestPractices' => [
                    'Use consistent naming conventions for widget content.',
                    'Preview widgets in templates to ensure correct display.',
                ],
            ],
            self::FIRST_SCREEN_CREATED => [
                'imagePath' => 'images/onboarding/screen.svg',
                'features'  => [
                    'Define a "screen" which represents a display configuration.',
                    'Associate a screen with a physical device.',
                    'Set screen resolution and orientation.',
                    'Screens are containers for layouts and content scheduling.',
                ],
                'tips' => [
                    'Screen names should be descriptive (e.g., "Lobby Display Left").',
                    'Ensure resolution matches the target device capabilities.',
                ],
                'bestPractices' => [
                    'Plan your screen layouts before creating them.',
                    'Group screens by location or purpose if you have many.',
                ],
            ],
            self::WIDGET_CONTENT_ASSIGNED_TO_TEMPLATE => [
                'imagePath' => 'images/onboarding/template-assign.svg',
                'features' => [
                    'Open a template in the Template Configurator.',
                    'Add or select a zone designated for widget content.',
                    'Choose your previously created widget content to assign to the zone.',
                    'Preview the template to see the widget in action.',
                ],
                'tips' => [
                    'Ensure the zone dimensions are appropriate for the widget content.',
                    'Some widgets might have specific zone type requirements.',
                ],
                'bestPractices' => [
                    'Use templates to maintain consistency across multiple screens.',
                    'Test widget assignments on different screen resolutions if applicable.',
                ],
            ],
            self::FIRST_SCHEDULE_CREATED => [
                'imagePath' => 'images/onboarding/schedule.svg',
                'features'  => [
                    'Create schedules to control when content plays on specific screens.',
                    'Define start/end dates and times for playback.',
                    'Select specific days of the week for recurring schedules.',
                    'Assign one or more content items to a schedule.',
                ],
                'tips' => [
                    'Use clear names for schedules (e.g., "Weekday Morning Ads").',
                    'Double-check timezones if managing devices in different locations.',
                ],
                'bestPractices' => [
                    'Plan your content playback strategy before creating many schedules.',
                    'Regularly review active schedules to ensure they are current.',
                ],
            ],
            self::FIRST_USER_INVITED => [
                'imagePath' => 'images/onboarding/team.svg',
                'features'  => [
                    'Invite colleagues to help manage your digital signage.',
                    'Assign roles to users (e.g., Admin, Editor, Viewer).',
                    'Control permissions based on roles.',
                    'Users will receive an email invitation to set up their account.',
                ],
                'tips' => [
                    'Use the principle of least privilege when assigning roles.',
                    'Ensure email addresses are correct for invitations.',
                ],
                'bestPractices' => [
                    'Regularly review user access and roles.',
                    'Educate team members on their responsibilities within the system.',
                ],
            ],
            self::SUBSCRIPTION_SETUP => [
                'imagePath' => 'images/onboarding/subscription.svg',
                'features'  => [
                    'Review available subscription plans and their features.',
                    'Understand limits for devices, users, storage, etc.',
                    'Select a plan that matches your current and anticipated needs.',
                    'Set up your billing information if upgrading from a trial or free tier.',
                ],
                'tips' => [
                    'Estimate your usage to choose the most cost-effective plan.',
                    'Check for annual billing options for potential discounts.',
                ],
                'bestPractices' => [
                    'Periodically review your subscription against your actual usage.',
                    'Keep billing information up to date to avoid service interruptions.',
                ],
            ],
            self::VIEWED_ANALYTICS => [
                'imagePath' => 'images/onboarding/analytics.svg',
                'features'  => [
                    'Track content playback counts and duration.',
                    'Monitor device uptime and connectivity status.',
                    'View bandwidth usage if applicable.',
                    'Gain insights into user activity within the platform.',
                ],
                'tips' => [
                    'Use date filters to narrow down analytics data.',
                    'Look for trends in content popularity or device issues.',
                ],
                'bestPractices' => [
                    'Regularly check analytics to optimize content strategy.',
                    'Use data to make informed decisions about network expansion or changes.',
                ],
            ],
        };
    }
}
