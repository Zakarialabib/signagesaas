<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Tenant\Models\User;
use App\Tenant\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Facades\Tenancy;

final class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenantsData = [
            [
                'id'     => 'demo',
                'name'   => 'Demo Company',
                'email'  => 'admin@demo.com',
                'plan'   => 'pro',
                'domain' => 'demo.'.config('app.domain'),
                'users'  => [
                    [
                        'name'     => 'Admin User',
                        'email'    => 'admin@demo.com',
                        'password' => 'password',
                        'role'     => 'admin',
                    ],
                    [
                        'name'     => 'Test User',
                        'email'    => 'user@demo.'.config('app.domain'),
                        'password' => 'password',
                        'role'     => 'editor',
                    ],
                    [
                        'name'     => 'Manager User',
                        'email'    => 'manager@demo.'.config('app.domain'),
                        'password' => 'password',
                        'role'     => 'viewer',
                    ],
                ],
                'settings' => $this->getDefaultSettings(),
            ],
            [
                'id'     => 'test',
                'name'   => 'Test Company',
                'email'  => 'admin@test.com',
                'plan'   => 'basic',
                'domain' => 'test.'.config('app.domain'),
                'users'  => [
                    [
                        'name'     => 'Admin User',
                        'email'    => 'admin@test.com',
                        'password' => 'password',
                        'role'     => 'admin',
                    ],
                    [
                        'name'     => 'Test User',
                        'email'    => 'user@test.'.config('app.domain'),
                        'password' => 'password',
                        'role'     => 'editor',
                    ],
                ],
                'settings' => $this->getDefaultSettings(),
            ],
        ];

        foreach ($tenantsData as $tenantData) {
            // Check if tenant already exists
            $existingTenant = Tenant::find($tenantData['id']);

            if ($existingTenant) {
                $this->command->info("Tenant {$tenantData['id']} already exists. Skipping creation.");

                continue;
            }

            // Create the tenant using Eloquent to get the model instance
            $tenant = Tenant::create([
                'id'       => $tenantData['id'],
                'name'     => $tenantData['name'],
                'email'    => $tenantData['email'],
                'plan'     => $tenantData['plan'],
                'settings' => $tenantData['settings'],
                'data'     => [
                    'settings' => $tenantData['settings'],
                ],
            ]);

            // Check if domain already exists
            $domainExists = DB::table('domains')->where('domain', $tenantData['domain'])->exists();

            if ( ! $domainExists) {
                // Create the domain with correct tenant_id (string)
                $tenant->domains()->create([
                    'domain'    => $tenantData['domain'],
                    'tenant_id' => $tenant->id,
                ]);
            }

            // Initialize tenancy context for this tenant
            Tenancy::initialize($tenant);

            foreach ($tenantData['users'] as $userData) {
                // Check if user already exists
                $userExists = User::where('email', $userData['email'])
                    ->where('tenant_id', $tenant->id)
                    ->exists();

                if ( ! $userExists) {
                    $user = User::create([
                        'name'      => $userData['name'],
                        'email'     => $userData['email'],
                        'password'  => Hash::make($userData['password']),
                        'role'      => $userData['role'],
                        'tenant_id' => $tenant->id,
                    ]);

                    // Assign role using Spatie's permissions
                    $role = $userData['role'] ?? 'viewer';
                    $user->assignRole($role);
                }
            }

            // 1. Create a template for this tenant
            $template = \App\Tenant\Models\Template::create([
                'name'        => 'Default Tenant Template',
                'description' => 'A default template for tenant '.$tenant->name,
                'category'    => \App\Enums\TemplateCategory::RETAIL,
                'status'      => \App\Enums\TemplateStatus::PUBLISHED,
                'layout'      => [
                    'type'    => 'grid',
                    'columns' => 1,
                    'rows'    => 1,
                    'gap'     => '0',
                ],
                'styles' => [
                    'font-family'      => 'Inter, sans-serif',
                    'background-color' => '#ffffff',
                    'color'            => '#000000',
                ],
                'default_duration' => 30,
                'settings'         => [
                    'transition'          => 'fade',
                    'transition_duration' => 500,
                    'refresh_interval'    => 300,
                ],
                'tenant_id' => $tenant->id,
            ]);

            // 2. Create a device for this tenant
            $device = \App\Tenant\Models\Device::create([
                'name'          => 'Tenant Device',
                'description'   => 'Default device for tenant '.$tenant->name,
                'status'        => \App\Enums\DeviceStatus::ONLINE,
                'type'          => \App\Enums\DeviceType::SMART_TV,
                'hardware_id'   => \Illuminate\Support\Str::uuid(),
                'hardware_info' => ['model' => 'Default Model'],
                'os_version'    => 'Default OS',
                'app_version'   => '1.0.0',
                'last_ping_at'  => now(),
                'settings'      => [
                    'orientation' => 'landscape',
                    'resolution'  => '1920x1080',
                    'volume'      => 50,
                    'brightness'  => 80,
                    'auto_update' => true,
                ],
                'location' => [
                    'building' => 'Main',
                    'floor'    => '1',
                    'area'     => 'Default',
                ],
                'registration_code' => \Illuminate\Support\Str::random(64),
                'tenant_id'         => $tenant->id,
            ]);

            // 3. Create a screen for this device and template
            $orientation = $device->settings['orientation'] ?? 'landscape';
            $screen = \App\Tenant\Models\Screen::create([
                'device_id'   => $device->id,
                'name'        => $device->name.' Screen',
                'description' => 'Main screen for '.$device->name,
                'status'      => $device->status === 'online' ? \App\Enums\ScreenStatus::ACTIVE : \App\Enums\ScreenStatus::INACTIVE,
                'orientation' => $orientation === 'landscape' ? \App\Enums\ScreenOrientation::LANDSCAPE : \App\Enums\ScreenOrientation::PORTRAIT,
                'resolution'  => $device->settings['resolution'] ?? '1920x1080',
                'settings'    => [
                    'volume'              => $device->settings['volume'] ?? 100,
                    'brightness'          => $device->settings['brightness'] ?? 100,
                    'transition'          => 'fade',
                    'transition_duration' => 500,
                    'refresh_interval'    => 300,
                ],
                // 'metadata' => [
                //     'last_content_update' => now(),
                //     'last_screenshot'     => null,
                //     'uptime'              => $device->status === 'online' ? rand(3600, 86400) : 0,
                // ],
                'template_id' => $template->id,
                'tenant_id'   => $tenant->id,
            ]);
            // --- End merged seeding logic ---

            // 4. Create a layout for this tenant, linked to the template
            $layout = \App\Tenant\Models\Layout::create([
                'tenant_id'    => $tenant->id,
                'name'         => 'Default Layout',
                'description'  => 'Default layout for tenant '.$tenant->name,
                'template_id'  => $template->id,
                'aspect_ratio' => '16:9',
                'status'       => 'active',
                'metadata'     => [],
                'settings'     => [
                    'background_color'      => '#ffffff',
                    'grid_enabled'          => true,
                    'grid_size'             => 10,
                    'snap_to_grid'          => true,
                    'responsive_scaling'    => true,
                    'maintain_aspect_ratio' => true,
                ],
                'style_data' => [],
            ]);

            // 5. Create a zone for the layout
            $zone = \App\Tenant\Models\Zone::create([
                'tenant_id' => $tenant->id,
                'layout_id' => $layout->id,
                'name'      => 'Main Zone',
                'type'      => 'main',
                'x'         => 0,
                'y'         => 0,
                'width'     => 1,
                'height'    => 1,
                'order'     => 1,
                'settings'  => [
                    'transition_effect'   => 'fade',
                    'transition_duration' => 1000,
                    'content_fit'         => 'contain',
                    'background_color'    => 'transparent',
                ],
                'style_data'   => [],
                'content_type' => 'html',
                'metadata'     => [],
            ]);

            // 6. Create a content item for the template and zone
            $content = \App\Tenant\Models\Content::create([
                'tenant_id'    => $tenant->id,
                'screen_id'    => $screen->id,
                'name'         => 'Welcome Content',
                'description'  => 'Welcome content for tenant '.$tenant->name,
                'type'         => \App\Enums\ContentType::HTML,
                'status'       => \App\Enums\ContentStatus::ACTIVE,
                'content_data' => [
                    'title'   => 'Welcome',
                    'message' => 'Thank you for visiting.',
                    'html'    => $this->getHtmlExample(),
                ],
                'template_id' => $template->id,
                'duration'    => 30,
                'metadata'    => [
                    'created_by'       => 'system',
                    'last_modified_by' => 'system',
                    'version'          => '1.0.0',
                ],
                'settings' => [
                    'transition'          => 'fade',
                    'transition_duration' => 500,
                    'refresh_interval'    => 300,
                ],
            ]);

            // Attach content to the zone (if you have a pivot table/relationship)
            $zone->contents()->attach($content->id, [
                'order'    => 1,
                'duration' => 30,
                'settings' => ['transition' => 'fade', 'transition_duration' => 500],
            ]);

            // Attach content to the screen as well
            $content->screens()->attach($device->screens()->first()?->id ?? null, [
                'order'    => 1,
                'duration' => 30,
            ]);

            Tenancy::end();
        }
    }

    /**
     * Get default tenant settings.
     *
     * @return array
     */
    private function getDefaultSettings(): array
    {
        return [
            // General settings
            'siteName'     => config('app.name', 'SignageSaaS'),
            'contactEmail' => config('mail.from.address', 'hello@signagesaas.com'),
            'timezone'     => config('app.timezone', 'UTC'),
            'dateFormat'   => 'Y-m-d',
            'timeFormat'   => 'H:i',

            // Appearance settings
            'locale'          => 'en',
            'primaryColor'    => '#4f46e5',
            'secondaryColor'  => '#9ca3af',
            'darkModeDefault' => false,

            // Content settings
            'defaultScreenDuration' => 15,
            'defaultTransition'     => 'fade',

            // Notification settings
            'notificationsEnabled'       => true,
            'deviceOfflineAlerts'        => true,
            'contentUpdateNotifications' => true,
            'securityAlerts'             => true,
        ];
    }

    // html example

    public function getHtmlExample(): string
    {
        return view('tenant.html-example')->render();
    }
}
