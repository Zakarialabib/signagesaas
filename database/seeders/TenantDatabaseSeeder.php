<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContentType;
use App\Enums\ContentStatus;
use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Enums\ScreenOrientation;
use App\Enums\ScreenStatus;
use App\Enums\TemplateCategory;
use App\Enums\TemplateStatus;
use App\Tenant\Models\User;
use App\Tenant\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
                'settings'     => $this->getDefaultSettings(),
                'seed_options' => [
                    'create_default_template'    => true,
                    'create_default_device'      => true,
                    'create_default_screen'      => true,
                    'create_default_layout_zone' => true,
                    'create_default_content'     => true,
                ],
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
                'settings'     => $this->getDefaultSettings(),
                'seed_options' => [
                    'create_default_template'    => true,
                    'create_default_device'      => false,
                    'create_default_screen'      => false,
                    'create_default_layout_zone' => true,
                    'create_default_content'     => false,
                ],
            ],
        ];

        foreach ($tenantsData as $tenantData) {
            $existingTenant = Tenant::find($tenantData['id']);

            if ($existingTenant) {
                $this->command->info("Tenant {$tenantData['id']} already exists. Skipping creation of tenant and core domain/users.");
                Tenancy::initialize($existingTenant);
                $tenant = $existingTenant;
            } else {
                $this->command->info("Creating tenant {$tenantData['id']}.");
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

                $domainExists = DB::table('domains')->where('domain', $tenantData['domain'])->exists();

                if ( ! $domainExists) {
                    $tenant->domains()->create([
                        'domain'    => $tenantData['domain'],
                        'tenant_id' => $tenant->id,
                    ]);
                }

                Tenancy::initialize($tenant);

                foreach ($tenantData['users'] as $userData) {
                    $userExists = User::where('email', $userData['email'])
                        ->exists();

                    if ( ! $userExists) {
                        $user = User::create([
                            'name'     => $userData['name'],
                            'email'    => $userData['email'],
                            'password' => Hash::make($userData['password']),
                        ]);
                        $user->assignRole($userData['role'] ?? 'viewer');
                    }
                }
            }

            $seedOptions = $tenantData['seed_options'] ?? [];
            $template = null;
            $device = null;
            $layout = null;

            if ($seedOptions['create_default_template'] ?? false) {
                $template = \App\Tenant\Models\Template::firstOrCreate(
                    ['tenant_id' => $tenant->id, 'name' => 'Default Tenant Template'],
                    [
                        'description' => 'A default template for tenant '.$tenant->name,
                        'category'    => TemplateCategory::RETAIL->value,
                        'status'      => TemplateStatus::PUBLISHED->value,
                        'layout'      => [
                            'type' => 'grid', 'columns' => 1, 'rows' => 1, 'gap' => '0',
                        ],
                        'styles' => [
                            'font-family' => 'Inter, sans-serif', 'background-color' => '#ffffff', 'color' => '#000000',
                        ],
                        'default_duration' => 30,
                        'settings'         => [
                            'transition' => 'fade', 'transition_duration' => 500, 'refresh_interval' => 300,
                        ],
                    ]
                );
                $this->command->info(" Ensured default template for tenant {$tenant->id}.");
            }

            if (($seedOptions['create_default_device'] ?? false) && $tenant) {
                $device = \App\Tenant\Models\Device::firstOrCreate(
                    ['tenant_id' => $tenant->id, 'name' => 'Tenant Device'],
                    [
                        'description'   => 'Default device for tenant '.$tenant->name,
                        'status'        => DeviceStatus::ONLINE->value,
                        'type'          => DeviceType::SMART_TV->value,
                        'hardware_id'   => Str::uuid()->toString(),
                        'hardware_info' => ['model' => 'Default Model'],
                        'os_version'    => 'Default OS',
                        'app_version'   => '1.0.0',
                        'last_ping_at'  => now(),
                        'settings'      => [
                            'orientation' => 'landscape', 'resolution' => '1920x1080', 'volume' => 50, 'brightness' => 80, 'auto_update' => true,
                        ],
                        'location' => [
                            'building' => 'Main', 'floor' => '1', 'area' => 'Default',
                        ],
                        'registration_code' => Str::random(64),
                    ]
                );
                $this->command->info(" Ensured default device for tenant {$tenant->id}.");
            }

            if (($seedOptions['create_default_screen'] ?? false) && $device && $template && $tenant) {
                $orientationSetting = $device->settings['orientation'] ?? 'landscape';
                $screenOrientationValue = ($orientationSetting === 'landscape') ? ScreenOrientation::LANDSCAPE->value : ScreenOrientation::PORTRAIT->value;
                $screenStatusValue = ($device->status === DeviceStatus::ONLINE->value) ? ScreenStatus::ACTIVE->value : ScreenStatus::INACTIVE->value;

               $screen =  \App\Tenant\Models\Screen::firstOrCreate(
                    ['tenant_id' => $tenant->id, 'device_id' => $device->id, 'name' => $device->name.' Screen'],
                    [
                        'description' => 'Main screen for '.$device->name,
                        'status'      => $screenStatusValue,
                        'orientation' => $screenOrientationValue,
                        'resolution'  => $device->settings['resolution'] ?? '1920x1080',
                        'settings'    => [
                            'volume'     => $device->settings['volume'] ?? 100, 'brightness' => $device->settings['brightness'] ?? 100,
                            'transition' => 'fade', 'transition_duration' => 500, 'refresh_interval' => 300,
                        ],
                        'template_id' => $template->id,
                    ]
                );
                $this->command->info(" Ensured default screen for tenant {$tenant->id}.");
            }

            if (($seedOptions['create_default_layout_zone'] ?? false) && $template && $tenant) {
                $layout = \App\Tenant\Models\Layout::firstOrCreate(
                    ['tenant_id' => $tenant->id, 'template_id' => $template->id, 'name' => 'Default Layout'],
                    [
                        'description'  => 'Default layout for tenant '.$tenant->name,
                        'aspect_ratio' => '16:9',
                        'status'       => 'active',
                        'metadata'     => [],
                        'settings'     => [
                            'background_color' => '#ffffff', 'grid_enabled' => true, 'grid_size' => 10,
                            'snap_to_grid'     => true, 'responsive_scaling' => true, 'maintain_aspect_ratio' => true,
                        ],
                        'style_data' => [],
                    ]
                );
                $this->command->info(" Ensured default layout for tenant {$tenant->id}.");

                if ($layout) {
                    \App\Tenant\Models\Zone::firstOrCreate(
                        ['tenant_id' => $tenant->id, 'layout_id' => $layout->id, 'name' => 'Main Zone'],
                        [
                            'type'     => 'main', 'x' => 0, 'y' => 0, 'width' => 1, 'height' => 1, 'order' => 1,
                            'settings' => [
                                'transition_effect' => 'fade', 'transition_duration' => 1000, 'content_fit' => 'contain', 'background_color' => 'transparent',
                            ],
                            'style_data' => [], 'content_type' => 'html', 'metadata' => [],
                        ]
                    );
                    $this->command->info(" Ensured default zone for layout {$layout->id} in tenant {$tenant->id}.");
                }
            }

            if (($seedOptions['create_default_content'] ?? false) && $template && $tenant) {
                $mainZone = $layout ? $layout->zones()->where('name', 'Main Zone')->first() : null;

                if ($mainZone) {
                    \App\Tenant\Models\Content::firstOrCreate(
                        ['tenant_id' => $tenant->id, 'name' => 'Welcome Content'],
                        [
                            'type'         => ContentType::HTML->value,
                            'status'       => ContentStatus::ACTIVE->value,
                            'screen_id'     => $screen->id,
                            'description'  => 'Default welcome content for tenant '.$tenant->name,
                            'settings'     => ['is_fullscreen' => true, 'refresh_on_update' => true],
                            'metadata'     => ['author' => 'System'],
                            'content_data' => '<h1>Welcome to '.$tenant->name.'</h1><p>This is the default content for your tenant.</p>',
                            'template_id'  => $template->id,
                        ]
                    );
                    $this->command->info(" Ensured default content for tenant {$tenant->id}.");
                }
            }
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
            'branding' => [
                'logo_url'        => null,
                'primary_color'   => '#007bff',
                'secondary_color' => '#6c757d',
            ],
            'notifications' => [
                'email_alerts'   => true,
                'sms_alerts'     => false,
                'system_updates' => true,
            ],
            'localization' => [
                'timezone'         => 'UTC',
                'date_format'      => 'Y-m-d',
                'time_format'      => 'H:i:s',
                'default_language' => 'en',
            ],
            'security' => [
                'two_factor_auth'     => false,
                'password_policy'     => 'medium', // e.g., strong, medium, weak
                'session_timeout'     => 3600, // in seconds
                'ip_whitelist'        => [],
                'audit_log_retention' => 90, // in days
            ],
            'api' => [
                'enabled'            => true,
                'rate_limit_per_min' => 60,
                'key_management'     => true,
            ],
            'data_privacy' => [
                'data_retention_policy' => '1_year',
                'consent_management'    => false,
            ],
            'feature_flags' => [
                'enable_analytics'      => true,
                'enable_custom_reports' => false,
                'enable_beta_features'  => false,
            ],
            'theme' => [
                'mode'            => 'light', // light, dark, system
                'font_family'     => 'Inter, sans-serif',
                'font_size'       => '16px',
                'primary_palette' => [
                    'main'  => '#007bff',
                    'light' => '#58a6ff',
                    'dark'  => '#0056b3',
                    'text'  => '#ffffff',
                ],
                'secondary_palette' => [
                    'main'  => '#6c757d',
                    'light' => '#adb5bd',
                    'dark'  => '#495057',
                    'text'  => '#ffffff',
                ],
            ],
        ];
    }

    public function getHtmlExample(): string
    {
        return <<<HTML
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Welcome</title>
                <style>
                    body { font-family: 'Inter', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f0f0f0; color: #333; text-align: center; }
                    .container { padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
                    h1 { color: #007bff; }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>Welcome to Our Service!</h1>
                    <p>This is a default content item for your new tenant.</p>
                </div>
            </body>
            </html>
            HTML;
    }
}
