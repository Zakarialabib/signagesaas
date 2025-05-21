<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlanSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $plans = [
            [
                'id'            => Str::uuid(),
                'name'          => 'Free',
                'slug'          => 'free',
                'description'   => 'Perfect for small businesses or individuals starting with digital signage.',
                'price_monthly' => 0.00,
                'price_yearly'  => 0.00,
                'features'      => [
                    'Up to 3 devices',
                    'Up to 5 screens',
                    'Basic content management',
                    'Standard support',
                    'Community forum access',
                ],
                'max_devices'      => 3,
                'max_screens'      => 5,
                'max_users'        => 2,
                'max_storage_mb'   => 1024,
                'max_bandwidth_mb' => 5120,
                'is_active'        => true,
                'is_public'        => true,
                'sort_order'       => 1,
            ],
            [
                'id'            => Str::uuid(),
                'name'          => 'Basic',
                'slug'          => 'basic',
                'description'   => 'Ideal for growing businesses managing multiple displays.',
                'price_monthly' => 29.99,
                'price_yearly'  => 299.90,
                'features'      => [
                    'Up to 10 devices',
                    'Up to 20 screens',
                    'Advanced content management',
                    'Basic scheduling',
                    'Email support',
                    'Mobile app access',
                ],
                'max_devices'      => 10,
                'max_screens'      => 20,
                'max_users'        => 5,
                'max_storage_mb'   => 5120,
                'max_bandwidth_mb' => 10240,
                'is_active'        => true,
                'is_public'        => true,
                'sort_order'       => 2,
            ],
            [
                'id'            => Str::uuid(),
                'name'          => 'Pro',
                'slug'          => 'pro',
                'description'   => 'For advanced users and businesses with complex signage needs.',
                'price_monthly' => 99.99,
                'price_yearly'  => 999.90,
                'features'      => [
                    'Up to 50 devices',
                    'Up to 100 screens',
                    'Advanced scheduling & automation',
                    'Content analytics',
                    'Priority email & chat support',
                    'Custom templates',
                    'API access',
                ],
                'max_devices'      => 50,
                'max_screens'      => 100,
                'max_users'        => 15,
                'max_storage_mb'   => 20480,
                'max_bandwidth_mb' => 51200,
                'is_active'        => true,
                'is_public'        => true,
                'sort_order'       => 3,
            ],
        ];

        // Insert plans only if they don't already exist (by slug)
        foreach ($plans as $plan) {
            $plan['features'] = json_encode($plan['features']);

            if ( ! DB::table('plans')->where('slug', $plan['slug'])->exists()) {
                DB::table('plans')->insert($plan);
            }
        }
    }
}
