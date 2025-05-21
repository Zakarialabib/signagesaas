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

        // Create layouts with zones, screens, devices, and content
        Layout::factory()
            ->count(5)
            ->state(['tenant_id' => $tenant->id, 'template_id' => $templates->random()->id])
            ->has(
                Zone::factory()
                    ->count(4)
                    ->state(function (array $attributes, Layout $layout) use ($tenant) {
                        return [
                            'tenant_id' => $tenant->id,
                            'layout_id' => $layout->id,
                        ];
                    })
            )
            ->create()
            ->each(function (Layout $layout) use ($tenant) {
                // For each layout, create devices and screens assigned to the layout
                $devices = Device::factory()
                    ->count(2)
                    ->state(['tenant_id' => $tenant->id])
                    ->create();

                $screens = Screen::factory()
                    ->count(2)
                    ->state(function (array $attributes) use ($layout, $tenant, $devices) {
                        return [
                            'tenant_id' => $tenant->id,
                            'layout_id' => $layout->id,
                            'device_id' => $devices->random()->id,
                        ];
                    })
                    ->create();

                // For each zone, create content and assign to zone/layout/screen
                foreach ($layout->zones as $zone) {
                    Content::factory()
                        ->count(2)
                        ->state(function (array $attributes) use ($zone, $layout, $tenant, $screens) {
                            return [
                                'zone_id'   => $zone->id,
                                'layout_id' => $layout->id,
                                'tenant_id' => $tenant->id,
                                'screen_id' => $screens->random()->id,
                            ];
                        })
                        ->create();
                }
            });

        // Create some standalone layouts without zones
        Layout::factory()
            ->count(3)
            ->state(['tenant_id' => $tenant->id, 'template_id' => $templates->random()->id])
            ->create();

        // Create some standalone zones
        Zone::factory()
            ->count(5)
            ->state(['tenant_id' => $tenant->id])
            ->create();
    }
}
