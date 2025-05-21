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
    public function run(): void
    {
        // Create a screen for each device
        Device::all()->each(function (Device $device) {
            $orientation = $device->settings['orientation'] ?? 'landscape';

            Screen::create([
                'device_id'   => $device->id,
                'name'        => "{$device->name} Screen",
                'description' => "Main screen for {$device->name}",
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
