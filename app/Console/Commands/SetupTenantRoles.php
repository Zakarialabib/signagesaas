<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Tenant\Models\Tenant;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Stancl\Tenancy\Facades\Tenancy;

class SetupTenantRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:setup-roles {tenant : The tenant ID to set up roles for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up default roles and permissions for a tenant';

    /** Execute the console command. */
    public function handle()
    {
        $tenantId = $this->argument('tenant');
        $tenant = Tenant::find($tenantId);

        if ( ! $tenant) {
            $this->error("Tenant not found: {$tenantId}");

            return 1;
        }

        $this->info("Setting up roles and permissions for tenant: {$tenant->name} ({$tenant->id})");

        // Initialize tenancy
        Tenancy::initialize($tenant);

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $editorRole = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $viewerRole = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);

        // Create permissions
        $permissions = [
            // User management
            'users.view'         => 'View users',
            'users.create'       => 'Create users',
            'users.edit'         => 'Edit users',
            'users.delete'       => 'Delete users',
            'users.manage_roles' => 'Manage user roles',

            // Device management
            'devices.view'   => 'View devices',
            'devices.create' => 'Create devices',
            'devices.edit'   => 'Edit devices',
            'devices.delete' => 'Delete devices',

            // Screen management
            'screens.view'   => 'View screens',
            'screens.create' => 'Create screens',
            'screens.edit'   => 'Edit screens',
            'screens.delete' => 'Delete screens',

            // Content management
            'content.view'   => 'View content',
            'content.create' => 'Create content',
            'content.edit'   => 'Edit content',
            'content.delete' => 'Delete content',

            // Schedule management
            'schedules.view'   => 'View schedules',
            'schedules.create' => 'Create schedules',
            'schedules.edit'   => 'Edit schedules',
            'schedules.delete' => 'Delete schedules',

            // Settings
            'settings.view' => 'View settings',
            'settings.edit' => 'Edit settings',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['description' => $description]
            );
        }

        // Assign permissions to roles

        // Admin role has all permissions
        $adminRole->syncPermissions(array_keys($permissions));

        // Manager role
        $managerRole->syncPermissions([
            'users.view',
            'users.create',
            'users.edit',
            'devices.view',
            'devices.create',
            'devices.edit',
            'devices.delete',
            'screens.view',
            'screens.create',
            'screens.edit',
            'screens.delete',
            'content.view',
            'content.create',
            'content.edit',
            'content.delete',
            'schedules.view',
            'schedules.create',
            'schedules.edit',
            'schedules.delete',
            'settings.view',
        ]);

        // Editor role
        $editorRole->syncPermissions([
            'devices.view',
            'screens.view',
            'content.view',
            'content.create',
            'content.edit',
            'schedules.view',
            'schedules.create',
            'schedules.edit',
        ]);

        // Viewer role
        $viewerRole->syncPermissions([
            'devices.view',
            'screens.view',
            'content.view',
            'schedules.view',
        ]);

        // Assign admin role to any admin users based on legacy role field
        $adminUsers = \App\Tenant\Models\User::where('tenant_id', $tenant->id)
            ->where('role', 'admin')
            ->get();

        foreach ($adminUsers as $user) {
            $user->assignRole('admin');
            $this->info("Assigned admin role to user: {$user->name} ({$user->email})");
        }

        // End tenancy
        Tenancy::end();

        $this->info('✅ Roles and permissions set up successfully!');

        return 0;
    }
}
