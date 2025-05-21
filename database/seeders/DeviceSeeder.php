<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Enums\ScreenOrientation;
use App\Tenant\Models\Device;
use App\Tenant\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Stancl\Tenancy\Facades\Tenancy;
use Illuminate\Support\Carbon;

final class DeviceSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $tenant = tenant();

        if ( ! $tenant) {
            $this->command->info('No active tenant found. Seeding for all tenants...');
            $tenants = Tenant::all();

            foreach ($tenants as $tenant) {
                Tenancy::initialize($tenant);
                $this->seedDevicesForTenant($tenant);
                Tenancy::end();
            }
        } else {
            $this->seedDevicesForTenant($tenant);
        }
    }

    /** Seed devices for a specific tenant. */
    private function seedDevicesForTenant(Tenant $tenant): void
    {
        $devices = [
            [
                'name'          => 'Lobby Display 1',
                'description'   => 'Main lobby entrance display',
                'status'        => DeviceStatus::ONLINE,
                'type'          => 'raspberry-pi',
                'hardware_id'   => Str::uuid(),
                'hardware_info' => ['model' => 'Raspberry Pi 4 Model B'],
                'os_version'    => 'Raspberry Pi OS Lite',
                'app_version'   => '1.0.0',
                'last_ping_at'  => Carbon::now(),
                'settings'      => [
                    'orientation' => 'landscape',
                    'resolution'  => '1920x1080',
                    'volume'      => 75,
                    'brightness'  => 80,
                    'auto_update' => true,
                ],
                'location' => [
                    'building' => 'Main Building',
                    'floor'    => '1',
                    'area'     => 'Main Lobby',
                ],
            ],
            [
                'name'          => 'Cafeteria Menu Board',
                'description'   => 'Digital menu board above service counter',
                'status'        => DeviceStatus::ONLINE,
                'type'          => 'windows',
                'hardware_id'   => Str::uuid(),
                'hardware_info' => ['model' => 'Intel NUC'],
                'os_version'    => 'Windows 10 IoT Enterprise',
                'app_version'   => '1.0.0',
                'last_ping_at'  => Carbon::now(),
                'settings'      => [
                    'orientation' => 'landscape',
                    'resolution'  => '3840x2160',
                    'volume'      => 0,
                    'brightness'  => 90,
                    'auto_update' => true,
                ],
                'location' => [
                    'building' => 'Main Building',
                    'floor'    => '2',
                    'area'     => 'Cafeteria',
                ],
            ],
            [
                'name'          => 'Reception Portrait Display',
                'description'   => 'Welcome screen at reception desk',
                'status'        => DeviceStatus::ONLINE,
                'type'          => 'android',
                'hardware_id'   => Str::uuid(),
                'hardware_info' => ['model' => 'Samsung Smart Signage'],
                'os_version'    => 'Android 11',
                'app_version'   => '1.0.0',
                'last_ping_at'  => Carbon::now(),
                'settings'      => [
                    'orientation' => 'portrait',
                    'resolution'  => '1080x1920',
                    'volume'      => 50,
                    'brightness'  => 70,
                    'auto_update' => true,
                ],
                'location' => [
                    'building' => 'Main Building',
                    'floor'    => '1',
                    'area'     => 'Reception',
                ],
            ],
            [
                'name'          => 'Conference Room A Display',
                'description'   => 'Meeting room schedule display',
                'status'        => DeviceStatus::OFFLINE,
                'type'          => 'raspberry-pi',
                'hardware_id'   => Str::uuid(),
                'hardware_info' => ['model' => 'Raspberry Pi 4 Model B'],
                'os_version'    => 'Raspberry Pi OS Lite',
                'app_version'   => '1.0.0',
                'last_ping_at'  => Carbon::now()->subHours(2),
                'settings'      => [
                    'orientation' => 'landscape',
                    'resolution'  => '1920x1080',
                    'volume'      => 0,
                    'brightness'  => 85,
                    'auto_update' => true,
                ],
                'location' => [
                    'building' => 'Main Building',
                    'floor'    => '3',
                    'area'     => 'Conference Room A',
                ],
            ],
            [
                'name'          => 'Elevator Lobby Display',
                'description'   => 'Directory and news display in elevator lobby',
                'status'        => DeviceStatus::MAINTENANCE,
                'type'          => 'android',
                'hardware_id'   => Str::uuid(),
                'hardware_info' => ['model' => 'BrightSign XT1144'],
                'os_version'    => 'Android 10',
                'app_version'   => '1.0.0',
                'last_ping_at'  => Carbon::now()->subDays(1),
                'settings'      => [
                    'orientation' => 'landscape',
                    'resolution'  => '1920x1080',
                    'volume'      => 30,
                    'brightness'  => 75,
                    'auto_update' => true,
                ],
                'location' => [
                    'building' => 'Main Building',
                    'floor'    => '1',
                    'area'     => 'Elevator Lobby',
                ],
            ],
        ];

        foreach ($devices as $device) {
            $device['id'] = Str::uuid();
            $device['registration_code'] = Str::random(64);
            Device::create($device);
        }

        // Create one of each device type
        foreach (DeviceType::cases() as $type) {
            Device::factory()
                ->forTenant($tenant)
                ->state([
                    'type'   => $type,
                    'status' => DeviceStatus::ONLINE,
                ])
                ->create();
        }

        // Create devices with specific orientations
        Device::factory()
            ->count(2)
            ->forTenant($tenant)
            ->state([
                'orientation' => ScreenOrientation::LANDSCAPE,
                'status'      => DeviceStatus::ONLINE,
            ])
            ->create();

        Device::factory()
            ->count(2)
            ->forTenant($tenant)
            ->state([
                'orientation' => ScreenOrientation::PORTRAIT,
                'status'      => DeviceStatus::ONLINE,
            ])
            ->create();

        // Create specific test devices for each tenant
        Device::factory()->forTenant($tenant)->create([
            'id'           => Str::uuid(),
            'hardware_id'  => Str::uuid(),
            'name'         => 'Main Lobby Display',
            'type'         => DeviceType::SMART_TV,
            'status'       => DeviceStatus::ONLINE,
            'orientation'  => ScreenOrientation::LANDSCAPE,
            'last_ping_at' => Carbon::now(),
            'last_sync_at' => Carbon::now(),
            'location'     => json_encode([
                'lat'     => 40.7128,
                'lng'     => -74.0060,
                'address' => 'Main Lobby, 123 Corporate Drive',
            ]),
        ]);

        Device::factory()->forTenant($tenant)->create([
            'id'           => Str::uuid(),
            'hardware_id'  => Str::uuid(),
            'name'         => 'Reception Kiosk',
            'type'         => DeviceType::KIOSK,
            'status'       => DeviceStatus::ONLINE,
            'orientation'  => ScreenOrientation::PORTRAIT,
            'last_ping_at' => Carbon::now(),
            'last_sync_at' => Carbon::now(),
            'location'     => json_encode([
                'lat'     => 40.7128,
                'lng'     => -74.0060,
                'address' => 'Reception Area, 123 Corporate Drive',
            ]),
        ]);

        // Create a device with no screens to test empty state
        Device::factory()
            ->forTenant($tenant)
            ->create([
                'name'   => 'Empty Device - No Screens',
                'status' => DeviceStatus::PROVISIONING,
            ]);
    }
}
