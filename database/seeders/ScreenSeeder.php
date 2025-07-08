<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ScreenOrientation;
use App\Enums\ScreenStatus;
use App\Tenant\Models\Device;
use App\Tenant\Models\Screen;
use Illuminate\Database\Seeder;

class ScreenSeeder extends Seeder
{
use App\Tenant\Models\Zone; // Added
use Illuminate\Support\Facades\Log; // Added

class ScreenSeeder extends Seeder
{
    public function run(): void
    {
        $allTenantIds = Device::distinct()->pluck('tenant_id');

        foreach ($allTenantIds as $tenantId) {
            $zonesForTenant = Zone::where('tenant_id', $tenantId)->get();

            if ($zonesForTenant->isEmpty()) {
                Log::warning("ScreenSeeder: No zones found for tenant {$tenantId}. Screens for this tenant will be created without a zone assignment. Ensure ZoneSeeder or LayoutAndZoneSeeder runs first and creates zones for this tenant.");
            }

            Device::where('tenant_id', $tenantId)->each(function (Device $device) use ($zonesForTenant) {
                $orientation = $device->settings['orientation'] ?? 'landscape';
                $selectedZoneId = null;
                $zoneName = '';

                if ($zonesForTenant->isNotEmpty()) {
                    $selectedZone = $zonesForTenant->random();
                    $selectedZoneId = $selectedZone->id;
                    $zoneName = $selectedZone->name;
                }

                Screen::create([
                    'device_id'   => $device->id,
                    'tenant_id'   => $device->tenant_id, // Ensure tenant_id is set
                    'zone_id'     => $selectedZoneId,
                    'name'        => "{$device->name} Screen",
                    'description' => "Main screen for {$device->name}" . ($selectedZoneId ? " (located in {$zoneName})" : " (no specific zone assigned)"),
                    'status'      => $device->status === 'online' ? ScreenStatus::ACTIVE : ScreenStatus::INACTIVE,
                    'orientation' => $orientation === 'landscape' ? ScreenOrientation::LANDSCAPE : ScreenOrientation::PORTRAIT,
                    'resolution'  => $device->settings['resolution'] ?? '1920x1080',
                    'settings'    => [
                        'volume'              => $device->settings['volume'] ?? 100,
                        'brightness'          => $device->settings['brightness'] ?? 100,
                        'transition'          => 'fade',
                        'transition_duration' => 500,
                        'refresh_interval'    => 300,
                    ],
                    'metadata' => [
                        'last_content_update' => now(),
                        'last_screenshot'     => null,
                        'uptime'              => $device->status === 'online' ? rand(3600, 86400) : 0,
                    ],
                ]);
            });
        }
    }
}
