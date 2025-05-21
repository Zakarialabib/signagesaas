<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Tenant\Models\Tenant;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.super-admin')]
#[Title('Manage Tenants')]
final class TenantsManager extends Component
{
    use WithPagination;

    // Filters and sorting
    public string $search = '';
    public string $planFilter = '';
    public string $statusFilter = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Form properties
    public bool $showTenantModal = false;
    public bool $showDetailsModal = false;
    public bool $showDeleteModal = false;
    public bool $showImpersonateConfirmation = false;
    public ?Tenant $selectedTenant = null;
    public bool $editingTenant = false;
    public ?string $tenantId = null;

    // Form fields
    public string $name = '';
    public string $email = '';
    public string $domain = '';
    public string $plan_id = '';

    // Cached data
    public array $plans = [];
    public array $statusOptions = [
        'active'    => 'Active',
        'inactive'  => 'Inactive',
        'trial'     => 'Trial',
        'suspended' => 'Suspended',
    ];

    /** Initialize the component */
    public function mount(): void
    {
        // Ensure the user is authenticated as a SuperAdmin
        if ( ! Auth::guard('superadmin')->check()) {
            $this->redirect(route('superadmin.login'));
        }

        $this->loadPlans();
    }

    /** Load available subscription plans */
    protected function loadPlans(): void
    {
        // In a real implementation, this would fetch from SubscriptionPlan model
        $this->plans = [
            'free'       => 'Free',
            'basic'      => 'Basic',
            'pro'        => 'Professional',
            'enterprise' => 'Enterprise',
        ];
    }

    /** Reset all filters to default values */
    public function resetFilters(): void
    {
        $this->search = '';
        $this->planFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    /** Change sort field and direction */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /** Open modal to create a new tenant */
    public function createTenant(): void
    {
        $this->resetForm();
        $this->editingTenant = false;
        $this->showTenantModal = true;
    }

    /** Open modal to edit an existing tenant */
    public function editTenant(string $tenantId): void
    {
        $tenant = Tenant::findOrFail($tenantId);
        $this->selectedTenant = $tenant;
        $this->tenantId = $tenantId;
        $this->name = $tenant->name;
        $this->email = $tenant->email;
        $this->domain = $tenant->domains->first()?->domain ?? '';
        $this->plan_id = $tenant->plan ?? 'basic';

        $this->editingTenant = true;
        $this->showTenantModal = true;
    }

    /** Save tenant data (create or update) */
    public function saveTenant(): void
    {
        $rules = [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'domain'  => 'required|string|max:255',
            'plan_id' => 'required|string|max:50',
        ];

        if ( ! $this->editingTenant) {
            // Add unique validation only for creation
            $rules['domain'] = 'required|string|max:255|unique:domains,domain';
        }

        $this->validate($rules);

        if ($this->editingTenant) {
            // Update existing tenant
            $tenant = Tenant::findOrFail($this->tenantId);
            $tenant->update([
                'name'  => $this->name,
                'email' => $this->email,
                'plan'  => $this->plan_id,
            ]);

            // Update domain if changed
            $domain = $tenant->domains->first();

            if ($domain && $domain->domain !== $this->domain) {
                $domain->update(['domain' => $this->domain]);
            } elseif ( ! $domain) {
                $tenant->domains()->create(['domain' => $this->domain]);
            }
        } else {
            // Create new tenant
            $tenantId = Str::slug($this->name);

            // Create the tenant
            $tenant = Tenant::create([
                'id'    => $tenantId,
                'name'  => $this->name,
                'email' => $this->email,
                'plan'  => $this->plan_id,
                'data'  => [
                    'settings' => [
                        'timezone' => 'UTC',
                        'language' => 'en',
                    ],
                ],
            ]);

            // Create the domain
            $tenant->domains()->create(['domain' => $this->domain]);
        }

        $this->resetForm();
        $this->showTenantModal = false;
    }

    /** Show confirmation modal for tenant deletion */
    public function confirmDelete(string $tenantId): void
    {
        $this->tenantId = $tenantId;
        $this->selectedTenant = Tenant::findOrFail($tenantId);
        $this->showDeleteModal = true;
    }

    /** Delete the tenant */
    public function deleteTenant(): void
    {
        $tenant = Tenant::findOrFail($this->tenantId);

        // Delete tenant domains first
        $tenant->domains()->delete();

        // Delete tenant
        $tenant->delete();

        $this->resetForm();
        $this->showDeleteModal = false;
    }

    /** Show tenant details in modal */
    public function viewTenantDetails(string $tenantId): void
    {
        $this->selectedTenant = Tenant::with(['domains'])
            ->withCount('users')
            ->findOrFail($tenantId);
        $this->showDetailsModal = true;
    }

    /** Navigate to subscription management for a tenant */
    public function manageSubscription(string $tenantId): void
    {
        $this->redirect(route('superadmin.tenant.subscription', ['tenant' => $tenantId]));
    }

    /** Show confirmation dialog for tenant impersonation */
    public function confirmImpersonate(string $tenantId): void
    {
        $this->tenantId = $tenantId;
        $this->selectedTenant = Tenant::findOrFail($tenantId);
        $this->showImpersonateConfirmation = true;
    }

    /** Impersonate a tenant */
    public function impersonateTenant(): void
    {
        $tenant = Tenant::findOrFail($this->tenantId);

        // Check if tenant has domains
        if ($tenant->domains->isEmpty()) {
            $this->showImpersonateConfirmation = false;
            $this->addError('impersonation', 'This tenant has no domains configured. Please add a domain first.');
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => 'Cannot impersonate tenant without domains',
            ]);

            return;
        }

        // Generate a signature for security
        $signature = hash_hmac('sha256', $tenant->id, config('app.key'));

        // Redirect to the direct impersonation route
        $this->redirect(route('direct.impersonate', [
            'tenant'    => $tenant->id,
            'signature' => $signature,
        ]));
    }

    /** Reset form fields */
    private function resetForm(): void
    {
        $this->tenantId = null;
        $this->name = '';
        $this->email = '';
        $this->domain = '';
        $this->plan_id = '';
        $this->selectedTenant = null;
        $this->editingTenant = false;
    }

    /** Render the component */
    public function render()
    {
        $query = Tenant::with(['domains'])
            ->withCount('users')
            ->when($this->search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('domains', function ($query) use ($search) {
                            $query->where('domain', 'like', "%{$search}%");
                        });
                });
            })
            ->when($this->planFilter, function ($query, $plan) {
                return $query->where('plan', $plan);
            })
            ->when($this->statusFilter, function ($query, $status) {
                // Implement status filtering based on your status attributes
                if ($status === 'active') {
                    return $query->where('is_active', true);
                } elseif ($status === 'inactive') {
                    return $query->where('is_active', false);
                }

                // Add other status filters as needed
                return $query;
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $tenants = $query->paginate(10);

        return view('livewire.super-admin.tenants-manager', [
            'tenants' => $tenants,
        ]);
    }
}
