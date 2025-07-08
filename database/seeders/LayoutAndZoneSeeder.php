<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Tenant\Models\Layout;
use App\Tenant\Models\Zone;
use App\Tenant\Models\Screen;
use App\Tenant\Models\Content;
use App\Tenant\Models\Device;
use App\Tenant\Models\Template;
use App\Tenant\Models\Tenant;
use Illuminate\Database\Seeder;

class LayoutAndZoneSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create a tenant for demo purposes
        $tenant = Tenant::first() ?? Tenant::factory()->create();

        // Optionally, create some templates
        $templates = Template::factory()->count(3)->create(['tenant_id' => $tenant->id]);

        // Create some Layouts
        Layout::factory()
            ->count(5)
            ->state(['tenant_id' => $tenant->id, 'template_id' => $templates->random()->id])
            ->create()
            ->each(function (Layout $layout) use ($tenant) {
                // Devices and Screens are created more generically now,
                // ScreenSeeder will handle specific screen setups including zone assignment.
                Device::factory()
                    ->count(1) // Simpler: 1 device per layout for this seeder's purpose
                    ->has(Screen::factory()->state(['tenant_id' => $tenant->id])) // Screen attached to device
                    ->state(['tenant_id' => $tenant->id])
                    ->create();
                // Content creation directly linked to old zone structures is removed.
            });

        // Create some additional standalone layouts
        Layout::factory()
            ->count(3)
            ->state(['tenant_id' => $tenant->id, 'template_id' => $templates->random()->id])
            ->create();

        // Create some "Place Zones"
        $placeTypes = ['WALL_MOUNT', 'KIOSK', 'ROOM_ENTRANCE', 'SHELF_EDGE', 'GENERAL_AREA'];

        Zone::factory()->count(3)->sequence(
            [
                'name' => 'Lobby Main Display Area',
                'description' => 'Central area in the main lobby, high visibility.',
                'type' => 'WALL_MOUNT',
                'x' => 100, 'y' => 50, 'width' => 300, 'height' => 150,
                'style_data' => ['color' => '#3498db', 'icon' => 'tv_large'],
                'metadata' => ['floor' => 1, 'wing' => 'North'],
                'tenant_id' => $tenant->id,
            ],
            [
                'name' => 'Cafeteria Menu Board Zone',
                'description' => 'Above the main counter in the cafeteria.',
                'type' => 'CEILING_DISPLAY',
                'x' => 250, 'y' => 120, 'width' => 200, 'height' => 100,
                'style_data' => ['color' => '#e67e22'],
                'metadata' => ['floor' => 1, 'section' => 'Food Court'],
                'tenant_id' => $tenant->id,
            ],
            [
                'name' => 'Meeting Room A101 Entrance',
                'description' => 'Digital sign next to the door of Meeting Room A101.',
                'type' => 'ROOM_ENTRANCE',
                'x' => 50, 'y' => 200, 'width' => 50, 'height' => 75,
                'style_data' => ['color' => '#2ecc71', 'shape' => 'rectangle'],
                'metadata' => ['floor' => 2, 'room_capacity' => 10],
                'tenant_id' => $tenant->id,
            ]
        )->create();

        // Create some more generic Place Zones
        for ($i = 0; $i < 7; $i++) {
            Zone::factory()->state([
                'tenant_id' => $tenant->id,
                'type' => $placeTypes[array_rand($placeTypes)],
                'x' => rand(10, 500),
                'y' => rand(10, 500),
                'width' => rand(50, 200),
                'height' => rand(50, 150),
            ])->create();
        }
    }
}
