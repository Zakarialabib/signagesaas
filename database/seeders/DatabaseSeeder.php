<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** Seed the application's database. */
    public function run(): void
    {
        // Run seeders
        $this->call([
            SuperAdminSeeder::class,
            RolesAndPermissionsSeeder::class,
            TenantDatabaseSeeder::class,
            PlanSeeder::class,
            OnboardingStepSeeder::class,
            // DeviceSeeder::class,
            // ScreenSeeder::class,
            // TemplateSeeder::class,
            // ContentSeeder::class,
            // LayoutAndZoneSeeder::class,
        ]);
    }
}
