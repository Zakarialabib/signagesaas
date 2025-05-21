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

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getTitle(): string
    {
        return match ($this) {
            self::PROFILE_COMPLETED       => 'Complete Your Profile',
            self::FIRST_DEVICE_REGISTERED => 'Understanding Devices',
            self::FIRST_CONTENT_UPLOADED  => 'Content & Templates Guide',
            self::FIRST_SCREEN_CREATED    => 'Working with Screens',
            self::FIRST_SCHEDULE_CREATED  => 'Creating Schedules',
            self::FIRST_USER_INVITED      => 'Team Management',
            self::SUBSCRIPTION_SETUP      => 'Subscription Overview',
            self::VIEWED_ANALYTICS        => 'Analytics & Reporting',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::PROFILE_COMPLETED       => 'Set up your organization profile with logo and contact information.',
            self::FIRST_DEVICE_REGISTERED => 'Learn about digital signage devices and how to set them up for optimal performance.',
            self::FIRST_CONTENT_UPLOADED  => 'Discover how to create, manage, and organize your digital signage content using our powerful template system.',
            self::FIRST_SCREEN_CREATED    => 'Understand how screens work and their relationship with physical devices and content.',
            self::FIRST_SCHEDULE_CREATED  => 'Learn to schedule content playback and create dynamic playlists for your screens.',
            self::FIRST_USER_INVITED      => 'Set up your team and manage their roles and permissions.',
            self::SUBSCRIPTION_SETUP      => 'Review and optimize your subscription plan for your digital signage needs.',
            self::VIEWED_ANALYTICS        => 'Track performance and gather insights about your digital signage network.',
        };
    }

    public static function getRequiredSteps(): array
    {
        return [
            self::PROFILE_COMPLETED,
            self::FIRST_DEVICE_REGISTERED,
            self::FIRST_CONTENT_UPLOADED,
            self::FIRST_SCREEN_CREATED,
            self::FIRST_SCHEDULE_CREATED,
        ];
    }

    public function getCardData(): array
    {
        return match ($this) {
            self::PROFILE_COMPLETED => [
                'imagePath' => 'images/onboarding/profile.svg',
                'features'  => [
                    'Upload your company logo (400x400px recommended)',
                    'Set organization name and contact details',
                    'Configure timezone and regional settings',
                    'Customize your branding colors and theme',
                ],
                'tips' => [
                    'Use a high-quality PNG or SVG logo for best results',
                    'Keep organization details updated for accurate reporting',
                    'Add multiple contact points for better team coordination',
                ],
                'bestPractices' => [
                    'Complete all profile sections for a professional appearance',
                    'Review and update information quarterly',
                    'Include emergency contact information',
                ],
            ],
            self::FIRST_DEVICE_REGISTERED => [
                'imagePath' => 'images/onboarding/device.svg',
                'features'  => [
                    'Support for various display devices',
                    'Easy device registration process',
                    'Remote device management',
                    'Health monitoring and alerts',
                ],
                'tips' => [
                    'Keep devices on a stable network connection',
                    'Use unique identifiers for easy device management',
                    'Install updates promptly for best performance',
                ],
                'bestPractices' => [
                    'Maintain a device inventory document',
                    'Set up monitoring alerts for device status',
                    'Plan for device maintenance and updates',
                ],
            ],
            self::FIRST_CONTENT_UPLOADED => [
                'imagePath' => 'images/onboarding/content.svg',
                'features'  => [
                    'Support for multiple content types',
                    'Dynamic template system',
                    'Content approval workflow',
                    'Media library management',
                ],
                'tips' => [
                    'Organize content into logical categories',
                    'Use templates for consistent branding',
                    'Optimize media files for faster loading',
                ],
                'bestPractices' => [
                    'Implement a content review process',
                    'Maintain content freshness with regular updates',
                    'Archive outdated content periodically',
                ],
            ],
            self::FIRST_SCREEN_CREATED => [
                'imagePath' => 'images/onboarding/screen.svg',
                'features'  => [
                    'Flexible screen layouts',
                    'Multi-zone content support',
                    'Screen grouping capabilities',
                    'Preview and testing tools',
                ],
                'tips' => [
                    'Group related screens for easier management',
                    'Test layouts before deployment',
                    'Consider viewing distance in design',
                ],
                'bestPractices' => [
                    'Document screen locations and specifications',
                    'Regular testing of screen displays',
                    'Plan for screen maintenance schedule',
                ],
            ],
            self::FIRST_SCHEDULE_CREATED => [
                'imagePath' => 'images/onboarding/schedule.svg',
                'features'  => [
                    'Flexible scheduling options',
                    'Recurring schedule support',
                    'Priority-based playback',
                    'Schedule templates',
                ],
                'tips' => [
                    'Plan content rotation frequency',
                    'Use schedule templates for efficiency',
                    'Set appropriate content durations',
                ],
                'bestPractices' => [
                    'Regular schedule review and updates',
                    'Document scheduling strategies',
                    'Monitor playback reports',
                ],
            ],
            self::FIRST_USER_INVITED => [
                'imagePath' => 'images/onboarding/team.svg',
                'features'  => [
                    'Role-based access control',
                    'Team collaboration tools',
                    'Activity logging',
                    'User permissions management',
                ],
                'tips' => [
                    'Define clear user roles',
                    'Set up approval workflows',
                    'Train team members on system use',
                ],
                'bestPractices' => [
                    'Regular permission audits',
                    'Document user roles and responsibilities',
                    'Maintain training documentation',
                ],
            ],
            self::SUBSCRIPTION_SETUP => [
                'imagePath' => 'images/onboarding/subscription.svg',
                'features'  => [
                    'Flexible plan options',
                    'Usage monitoring',
                    'Automatic billing',
                    'Plan comparison tools',
                ],
                'tips' => [
                    'Review usage patterns regularly',
                    'Plan for future scaling needs',
                    'Set up billing notifications',
                ],
                'bestPractices' => [
                    'Document subscription requirements',
                    'Regular plan optimization reviews',
                    'Monitor usage trends',
                ],
            ],
            self::VIEWED_ANALYTICS => [
                'imagePath' => 'images/onboarding/analytics.svg',
                'features'  => [
                    'Real-time analytics',
                    'Custom reporting',
                    'Performance metrics',
                    'Export capabilities',
                ],
                'tips' => [
                    'Set up key performance indicators',
                    'Schedule regular report reviews',
                    'Use insights for content optimization',
                ],
                'bestPractices' => [
                    'Regular analytics review sessions',
                    'Document insights and actions',
                    'Share reports with stakeholders',
                ],
            ],
        };
    }
}
