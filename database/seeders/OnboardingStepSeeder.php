<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Tenant\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OnboardingStepSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        // Define the standard onboarding steps
        $steps = [
            [
                'id'           => Str::uuid()->toString(),
                'name'         => 'Complete Your Profile',
                'key'          => 'profile',
                'description'  => 'Set up your organization profile and preferences.',
                'route_name'   => 'settings.profile',
                'route_params' => json_encode([]),
                'icon'         => 'user-circle',
                'sort_order'   => 1,
                'required'     => true,
                'is_active'    => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => Str::uuid()->toString(),
                'name'         => 'Register Your First Device',
                'key'          => 'first_device',
                'description'  => 'Connect your first display device to the platform.',
                'route_name'   => 'devices.create',
                'route_params' => json_encode([]),
                'icon'         => 'device-mobile',
                'sort_order'   => 2,
                'required'     => true,
                'is_active'    => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => Str::uuid()->toString(),
                'name'         => 'Upload Content',
                'key'          => 'first_content',
                'description'  => 'Upload your first content to display on your screens.',
                'route_name'   => 'content.create',
                'route_params' => json_encode([]),
                'icon'         => 'document',
                'sort_order'   => 3,
                'required'     => true,
                'is_active'    => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => Str::uuid()->toString(),
                'name'         => 'Create Screen',
                'key'          => 'first_screen',
                'description'  => 'Set up your first screen.',
                'route_name'   => 'screens.create',
                'route_params' => json_encode([]),
                'icon'         => 'desktop-computer',
                'sort_order'   => 4,
                'required'     => true,
                'is_active'    => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => Str::uuid()->toString(),
                'name'         => 'Create Schedule',
                'key'          => 'first_schedule',
                'description'  => 'Schedule content to display on your screens.',
                'route_name'   => 'schedules.create',
                'route_params' => json_encode([]),
                'icon'         => 'calendar',
                'sort_order'   => 5,
                'required'     => true,
                'is_active'    => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => Str::uuid()->toString(),
                'name'         => 'Invite Team Member',
                'key'          => 'first_user_invited',
                'description'  => 'Invite your team members to collaborate.',
                'route_name'   => 'settings.users.create',
                'route_params' => json_encode([]),
                'icon'         => 'users',
                'sort_order'   => 6,
                'required'     => false,
                'is_active'    => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => Str::uuid()->toString(),
                'name'         => 'Subscription Setup',
                'key'          => 'subscription_setup',
                'description'  => 'Choose the right plan for your organization.',
                'route_name'   => 'settings.subscription',
                'route_params' => json_encode([]),
                'icon'         => 'credit-card',
                'sort_order'   => 7,
                'required'     => false,
                'is_active'    => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => Str::uuid()->toString(),
                'name'         => 'View Analytics',
                'key'          => 'viewed_analytics',
                'description'  => 'Check the performance of your content and devices.',
                'route_name'   => 'dashboard.analytics',
                'route_params' => json_encode([]),
                'icon'         => 'chart-bar',
                'sort_order'   => 8,
                'required'     => false,
                'is_active'    => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ];

        // Insert the steps if they don't already exist (by key)
        foreach ($steps as $step) {
            if ( ! DB::table('onboarding_steps')->where('key', $step['key'])->exists()) {
                DB::table('onboarding_steps')->insert($step);
            }
        }

        // For each tenant, create initial onboarding progress if it doesn't exist
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            if ( ! DB::table('onboarding_progress')->where('tenant_id', $tenant->id)->exists()) {
                DB::table('onboarding_progress')->insert([
                    'id'                      => Str::uuid()->toString(),
                    'tenant_id'               => $tenant->id,
                    'profile_completed'       => false,
                    'first_device_registered' => false,
                    'first_content_uploaded'  => false,
                    'first_screen_created'    => false,
                    'first_schedule_created'  => false,
                    'first_user_invited'      => false,
                    'subscription_setup'      => false,
                    'viewed_analytics'        => false,
                    'custom_steps'            => json_encode([]),
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ]);
            }
        }
    }
}
