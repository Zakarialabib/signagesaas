<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Models\User;
use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.super-admin')]
#[Title('User Manager')]
class UsersManager extends Component
{
    use WithPagination;

    public $tenantId;
    public $tenant;
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public $showUserModal = false;
    public $editingUser = null;
    public $userName = '';
    public $userEmail = '';
    public $userRole = '';
    public $userPassword = '';

    protected $rules = [
        'userName' => 'required|string|max:255',
        'userEmail' => 'required|email|max:255',
        'userRole' => 'required|string|in:admin,user',
        'userPassword' => 'nullable|string|min:8',
    ];

    public function mount($tenantId = null)
    {
        if ($tenantId) {
            $this->tenantId = $tenantId;
            $this->tenant = Tenant::findOrFail($tenantId);
        }
    }

    public function render()
    {
        $query = User::query();

        if ($this->tenantId) {
            $query->where('tenant_id', $this->tenantId);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        $users = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        return view('livewire.super-admin.users-manager', [
            'users' => $users,
            'tenants' => Tenant::all(), // For tenant selection dropdown
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function selectTenant($tenantId)
    {
        $this->tenantId = $tenantId;
        $this->tenant = Tenant::findOrFail($tenantId);
        $this->resetPage();
    }

    public function resetTenantSelection()
    {
        $this->tenantId = null;
        $this->tenant = null;
        $this->resetPage();
    }

    public function createUser()
    {
        $this->resetUserForm();
        $this->showUserModal = true;
    }

    public function editUser(User $user)
    {
        $this->editingUser = $user;
        $this->userName = $user->name;
        $this->userEmail = $user->email;
        $this->userRole = $user->role;
        $this->showUserModal = true;
    }

    public function saveUser()
    {
        $this->validate();

        if ($this->editingUser) {
            $user = $this->editingUser;
            $user->name = $this->userName;
            $user->email = $this->userEmail;
            $user->role = $this->userRole;
            if (!empty($this->userPassword)) {
                $user->password = bcrypt($this->userPassword);
            }
            $user->save();
            session()->flash('message', 'User updated successfully.');
        } else {
            $user = User::create([
                'name' => $this->userName,
                'email' => $this->userEmail,
                'password' => bcrypt($this->userPassword),
                'tenant_id' => $this->tenantId, // Assign to selected tenant
                'role' => $this->userRole,
            ]);
            session()->flash('message', 'User created successfully.');
        }

        $this->showUserModal = false;
        $this->resetUserForm();
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        session()->flash('message', 'User deleted successfully.');
    }

    private function resetUserForm()
    {
        $this->editingUser = null;
        $this->userName = '';
        $this->userEmail = '';
        $this->userRole = '';
        $this->userPassword = '';
    }
}
