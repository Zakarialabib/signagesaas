<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Tenant\Models\AuditLog;
use App\Tenant\Models\User;
use App\Traits\HasPermissions;
use App\Traits\WithDataTable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Exception;

#[Layout('layouts.app')]
#[Title('User Management')]
final class UserManager extends Component
{
    use WithDataTable;
    use HasPermissions;

    // User form properties
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email|max:255')]
    public string $email = '';

    #[Rule('required|string|in:admin,manager,editor,viewer')]
    public string $role = 'viewer';

    #[Rule('nullable|string|min:8|max:255')]
    public ?string $password = null;

    // Permissions
    public $userPermissions = [];
    public Collection $availablePermissions;

    // Audit log properties
    public string $auditUserFilter = '';
    public string $auditActionFilter = '';
    public ?string $auditDateStart = null;
    public ?string $auditDateEnd = null;
    public Collection $auditLogs;
    public ?string $selectedUserId = null;
    public bool $canManageRoles = false;

    public array $userRoles = [];

    public Collection $roles;

    public function mount(): void
    {
        // Load tenant-scoped permissions and roles
        $this->availablePermissions = Permission::where('guard_name', 'web')->get();
        $this->roles = Role::where('guard_name', 'web')->get();

        // Initialize audit logs
        $this->auditLogs = collect([]);

        // Check if user can manage roles
        $this->canManageRoles = Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasPermissionTo('users.manage_roles'));

        // Initialize data table with configuration
        $this->initializeDataTable([
            'perPage'          => 10,
            'sortField'        => 'name',
            'sortDirection'    => 'asc',
            'searchFields'     => ['name', 'email'],
            'availableFilters' => [
                'role' => [
                    'field'    => 'role',
                    'operator' => '=',
                    'label'    => 'Role',
                    'options'  => [
                        'admin'   => 'Admin',
                        'manager' => 'Manager',
                        'editor'  => 'Editor',
                        'viewer'  => 'Viewer',
                    ],
                ],
            ],
        ]);
    }

    // User CRUD methods
    public function createUser(): void
    {
        $this->resetUserForm();
        $this->modalData['user'] = ['mode' => 'create'];
        $this->openModal('user');
    }

    public function editUser(string $userId): void
    {
        $user = User::findOrFail($userId);

        // Make sure user belongs to current tenant
        if ($user->tenant_id !== tenant('id')) {
            session()->flash('error', 'User not found or access denied.');

            return;
        }

        $this->selectedUserId = (string) $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->userPermissions = $user->permissions ?? [];
        $this->userRoles = $user->roles->pluck('name')->toArray();

        $this->modalData['user'] = [
            'mode' => 'edit',
            'id'   => $user->id,
        ];

        $this->openModal('user');
    }

    public function saveUser(): void
    {
        $mode = $this->modalData['user']['mode'] ?? 'create';

        if ($mode === 'create') {
            $this->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|max:255',
                'role'     => 'required|string|in:admin,manager,editor,viewer',
            ]);
        } else {
            $this->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|max:255|unique:users,email,'.$this->selectedUserId,
                'password' => 'nullable|string|min:8|max:255',
                'role'     => 'required|string|in:admin,manager,editor,viewer',
            ]);
        }

        DB::beginTransaction();

        try {
            if ($mode === 'create') {
                $user = User::create([
                    'tenant_id'   => tenant('id'),
                    'name'        => $this->name,
                    'email'       => $this->email,
                    'password'    => Hash::make($this->password),
                    'role'        => $this->role,
                    'permissions' => ! empty($this->userPermissions) ? $this->userPermissions : null,
                ]);

                // Assign roles from Spatie
                if ( ! empty($this->userRoles)) {
                    $user->syncRoles($this->userRoles);
                }

                // Log creation
                AuditLog::recordCreate('user', $user->id, [
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                ]);

                session()->flash('message', 'User created successfully.');
            } else {
                $user = User::find($this->selectedUserId);

                // Make sure user belongs to current tenant
                if ( ! $user || $user->tenant_id !== tenant('id')) {
                    session()->flash('error', 'User not found or access denied.');
                    DB::rollBack();
                    $this->closeModal('user');

                    return;
                }

                $oldData = [
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'role'        => $user->role,
                    'permissions' => $user->permissions,
                ];

                $updateData = [
                    'name'        => $this->name,
                    'email'       => $this->email,
                    'role'        => $this->role,
                    'permissions' => ! empty($this->userPermissions) ? $this->userPermissions : null,
                ];

                if ($this->password) {
                    $updateData['password'] = Hash::make($this->password);
                }

                $user->update($updateData);

                // Sync Spatie roles
                if ( ! empty($this->userRoles)) {
                    $user->syncRoles($this->userRoles);
                } else {
                    $user->roles()->detach();
                }

                // Log update
                AuditLog::recordUpdate('user', $user->id, $oldData, [
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'role'        => $user->role,
                    'permissions' => $user->permissions,
                ]);

                session()->flash('message', 'User updated successfully.');
            }

            DB::commit();
            $this->closeModal('user');
        } catch (Exception $e) {
            DB::rollBack();
            logger()->error('Error saving user: '.$e->getMessage());
            session()->flash('error', 'An error occurred while saving the user. Please try again.');
        }
    }

    public function deleteUser(string $userId): void
    {
        $this->selectedUserId = $userId;
        $this->openModal('confirmDelete');
    }

    public function confirmDelete(): void
    {
        $user = User::find($this->selectedUserId);

        if ( ! $user || $user->tenant_id !== tenant('id')) {
            session()->flash('error', 'User not found or access denied.');
            $this->closeModal('confirmDelete');

            return;
        }

        if (Auth::id() === $this->selectedUserId) {
            session()->flash('error', 'You cannot delete your own account.');
            $this->closeModal('confirmDelete');

            return;
        }

        DB::beginTransaction();

        try {
            // Log deletion
            AuditLog::recordDelete('user', $user->id, [
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ]);

            $user->delete();

            DB::commit();
            session()->flash('message', 'User deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            logger()->error('Error deleting user: '.$e->getMessage());
            session()->flash('error', 'An error occurred while deleting the user. Please try again.');
        }

        $this->closeModal('confirmDelete');
    }

    // Role methods
    public function assignRole(string $userId, string $role): void
    {
        $user = User::find($userId);

        if ( ! $user || $user->tenant_id !== tenant('id')) {
            session()->flash('error', 'User not found or access denied.');

            return;
        }

        $oldRole = $user->role;
        $user->update(['role' => $role]);

        // Log role change
        AuditLog::recordAction(
            'change_role',
            'user',
            $user->id,
            ['role' => $oldRole],
            ['role' => $role],
            "Changed user role from {$oldRole} to {$role}"
        );

        session()->flash('message', "Role updated to {$role} successfully.");
    }

    // Permission methods
    public function togglePermission(string $permission): void
    {
        if (in_array($permission, $this->userPermissions)) {
            $this->userPermissions = array_values(array_diff($this->userPermissions, [$permission]));
        } else {
            $this->userPermissions[] = $permission;
        }
    }

    // Role selection modal methods
    public function selectUser(string $userId): void
    {
        if ( ! $this->canManageRoles) {
            session()->flash('error', 'You do not have permission to manage roles.');

            return;
        }

        $this->selectedUserId = $userId;
        $user = User::findOrFail($userId);

        // Get the user's current roles
        $this->userRoles = $user->roles->pluck('name')->toArray();
        $this->openModal('role');
    }

    public function saveRoles(): void
    {
        if ( ! $this->canManageRoles) {
            session()->flash('error', 'You do not have permission to manage roles.');

            return;
        }

        $user = User::findOrFail($this->selectedUserId);

        // Make sure user belongs to current tenant
        if ($user->tenant_id !== tenant('id')) {
            session()->flash('error', 'User not found or access denied.');

            return;
        }

        // Sync the user's roles
        $user->syncRoles($this->userRoles);

        $this->closeModal('role');
        session()->flash('success', 'User roles updated successfully.');
    }

    public function toggleRole(string $role): void
    {
        if (in_array($role, $this->userRoles)) {
            $this->userRoles = array_diff($this->userRoles, [$role]);
        } else {
            $this->userRoles[] = $role;
        }
    }

    // Audit log methods
    public function viewAuditLogs(?string $userId = null): void
    {
        if ($userId) {
            $this->auditUserFilter = $userId;
        }

        $this->openModal('audit');
        $this->loadAuditLogs();
    }

    public function loadAuditLogs(): void
    {
        $query = AuditLog::query()
            ->where('entity_type', 'user')
            ->when($this->auditUserFilter, function ($query) {
                return $query->where('entity_id', $this->auditUserFilter);
            })
            ->when($this->auditActionFilter, function ($query) {
                return $query->where('action', $this->auditActionFilter);
            })
            ->when($this->auditDateStart && $this->auditDateEnd, function ($query) {
                return $query->whereBetween('created_at', [
                    $this->auditDateStart.' 00:00:00',
                    $this->auditDateEnd.' 23:59:59',
                ]);
            })
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(100);

        $this->auditLogs = $query->get();
    }

    // UI methods
    public function closeUserModal(): void
    {
        $this->resetUserForm();
    }

    public function resetUserForm(): void
    {
        $this->selectedUserId = null;
        $this->name = '';
        $this->email = '';
        $this->password = null;
        $this->role = 'viewer';
        $this->userPermissions = [];
        $this->userRoles = [];
        $this->resetValidation();
    }

    /** Override the getAllIds method from WithBulkActions trait */
    public function getAllIds(): array
    {
        return User::where('tenant_id', tenant('id'))
            ->when($this->search, function ($query) {
                return $this->applySearch($query, ['name', 'email']);
            })
            ->when($this->getFilter('role'), function ($query) {
                return $query->where('role', $this->getFilter('role'));
            })
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->toArray();
    }

    public function render()
    {
        // Query tenant-scoped users
        $usersQuery = User::where('tenant_id', tenant('id'));

        // Apply the data table query
        $usersQuery = $this->applyDataTableQuery($usersQuery);

        return view('livewire.settings.user-manager', [
            'users'          => $usersQuery->paginate($this->perPage),
            'roles'          => $this->roles,
            'canManageRoles' => $this->canManageRoles,
        ]);
    }
}
