<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Tenant\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

final class RolesAndPermissionsSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        // Reset cached roles and permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Create permissions by resource
        $this->createScreenPermissions();
        $this->createDevicePermissions();
        $this->createContentPermissions();
        $this->createSchedulePermissions();
        $this->createUserPermissions();
        $this->createSettingPermissions();

        // Create roles and assign permissions
        $this->createRoles();
    }

    /** Create permissions for screen management */
    private function createScreenPermissions(): void
    {
        $permissions = [
            'screens.view'    => 'View screens',
            'screens.create'  => 'Create screens',
            'screens.edit'    => 'Edit screens',
            'screens.delete'  => 'Delete screens',
            'screens.publish' => 'Publish screens',
            'screens.preview' => 'Preview screens',
        ];

        $this->createPermissions($permissions);
    }

    /** Create permissions for device management */
    private function createDevicePermissions(): void
    {
        $permissions = [
            'devices.view'    => 'View devices',
            'devices.create'  => 'Create devices',
            'devices.edit'    => 'Edit devices',
            'devices.delete'  => 'Delete devices',
            'devices.control' => 'Control devices',
            'devices.restart' => 'Restart devices',
        ];

        $this->createPermissions($permissions);
    }

    /** Create permissions for content management */
    private function createContentPermissions(): void
    {
        $permissions = [
            'content.view'    => 'View content',
            'content.create'  => 'Create content',
            'content.edit'    => 'Edit content',
            'content.delete'  => 'Delete content',
            'content.publish' => 'Publish content',
        ];

        $this->createPermissions($permissions);
    }

    /** Create permissions for schedule management */
    private function createSchedulePermissions(): void
    {
        $permissions = [
            'schedules.view'     => 'View schedules',
            'schedules.create'   => 'Create schedules',
            'schedules.edit'     => 'Edit schedules',
            'schedules.delete'   => 'Delete schedules',
            'schedules.activate' => 'Activate schedules',
        ];

        $this->createPermissions($permissions);
    }

    /** Create permissions for user management */
    private function createUserPermissions(): void
    {
        $permissions = [
            'users.view'         => 'View users',
            'users.create'       => 'Create users',
            'users.edit'         => 'Edit users',
            'users.delete'       => 'Delete users',
            'users.manage_roles' => 'Manage user roles',
        ];

        $this->createPermissions($permissions);
    }

    /** Create permissions for setting management */
    private function createSettingPermissions(): void
    {
        $permissions = [
            'settings.view'         => 'View settings',
            'settings.edit'         => 'Edit settings',
            'settings.billing'      => 'Manage billing',
            'settings.subscription' => 'Manage subscription',
        ];

        $this->createPermissions($permissions);
    }

    /** Helper to create permissions from a mapping */
    private function createPermissions(array $permissions): void
    {
        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['description' => $description]
            );
        }
    }

    /** Create roles and assign permissions */
    private function createRoles(): void
    {
        // Admin role - gets everything
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        // Editor role - can manage content but with limitations
        $editorRole = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $editorRole->givePermissionTo([
            'screens.view', 'screens.create', 'screens.edit', 'screens.preview',
            'content.view', 'content.create', 'content.edit', 'content.publish',
            'schedules.view', 'schedules.create', 'schedules.edit',
            'devices.view',
        ]);

        // Viewer role - can only view and preview
        $viewerRole = Role::create(['name' => 'viewer', 'guard_name' => 'web']);
        $viewerRole->givePermissionTo([
            'screens.view', 'screens.preview',
            'content.view',
            'schedules.view',
            'devices.view',
        ]);
    }
}
