<?php

declare(strict_types=1);

namespace App\Services;

use App\Tenant\Models\Tenant;
use App\Tenant\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final readonly class TenantManager
{
    public function create(array $data): Tenant
    {
        // Create the tenant
        $tenant = Tenant::create([
            'id'            => Str::ulid(),
            'name'          => $data['company_name'],
            'plan'          => 'trial',
            'trial_ends_at' => now()->addDays(14),
            'settings'      => [
                'timezone' => $data['timezone'] ?? config('app.timezone'),
                'locale'   => $data['locale'] ?? config('app.locale'),
            ],
            'data' => [],
        ]);

        // Create domain for tenant
        $domain = Str::slug($data['subdomain']).'.'.config('app.domain');
        $tenant->domains()->create(['domain' => $domain]);

        // Initialize the tenant (creates database, runs migrations)
        $tenant->initialize();

        // Switch to the tenant context to create the admin user
        tenancy()->initialize($tenant);

        // Create the admin user
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'admin',
        ]);

        // Switch back to central context
        tenancy()->end();

        return $tenant;
    }

    public function delete(Tenant $tenant): void
    {
        // Delete tenant's database and storage
        $tenant->delete();
    }
}
