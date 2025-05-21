<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        // Create a default super admin if none exists
        if (SuperAdmin::count() === 0) {
            SuperAdmin::create([
                'name'     => 'Super Admin',
                'email'    => 'superadmin@example.com',
                'password' => Hash::make('password'),
            ]);

            $this->command->info('Created default super admin: superadmin@example.com / password');
        }
    }
}
