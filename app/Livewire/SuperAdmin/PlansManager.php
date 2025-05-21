<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Tenant\Models\Plan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Locked;
use Illuminate\Support\Str;

#[Layout('layouts.super-admin')]
final class PlansManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    // Create/Edit Plan Modal State
    public bool $showPlanModal = false;

    #[Locked]
    public bool $editingPlan = false;

    // Delete Confirmation Modal
    public bool $showDeleteModal = false;

    #[Locked]
    public ?string $plan_id = null;

    // Plan Form Properties
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|max:255|alpha_dash')]
    public string $slug = '';

    #[Validate('nullable|string|max:1000')]
    public ?string $description = null;

    #[Validate('required|numeric|min:0')]
    public float $price_monthly = 0;

    #[Validate('required|numeric|min:0')]
    public float $price_yearly = 0;

    #[Validate('required|integer|min:0')]
    public int $max_devices = 1;

    #[Validate('required|integer|min:0')]
    public int $max_screens = 5;

    #[Validate('required|integer|min:0')]
    public int $max_users = 3;

    #[Validate('required|integer|min:0')]
    public int $max_storage_mb = 1024;

    #[Validate('required|integer|min:0')]
    public int $max_bandwidth_mb = 5120;

    #[Validate('nullable|array')]
    public array $features = [];

    #[Validate('boolean')]
    public bool $is_active = true;

    #[Validate('boolean')]
    public bool $is_public = true;

    #[Validate('integer|min:0')]
    public int $sort_order = 0;

    public function mount(): void
    {
        // $this->authorize('viewAny', Plan::class);
    }

    public function render()
    {
        $plans = Plan::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('slug', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%"))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.super-admin.plans-manager', [
            'plans' => $plans,
        ]);
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function createPlan(): void
    {
        $this->authorize('create', Plan::class);
        $this->resetForm();
        $this->editingPlan = false;
        $this->showPlanModal = true;
    }

    public function editPlan(string $planId): void
    {
        $this->resetForm();

        // Simulated data for the plan
        $this->plan_id = $planId;
        $this->name = 'Professional Plan';
        $this->slug = 'professional';
        $this->description = 'For medium businesses';
        $this->price_monthly = 49.99;
        $this->price_yearly = 499.99;
        $this->max_devices = 10;
        $this->max_screens = 25;
        $this->max_users = 5;
        $this->max_storage_mb = 10240;
        $this->max_bandwidth_mb = 51200;
        $this->is_active = true;
        $this->is_public = true;
        $this->sort_order = 2;

        $this->editingPlan = true;
        $this->showPlanModal = true;
    }

    public function savePlan(): void
    {
        $this->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price_monthly'    => 'required|numeric|min:0',
            'price_yearly'     => 'required|numeric|min:0',
            'max_devices'      => 'required|integer|min:0',
            'max_screens'      => 'required|integer|min:0',
            'max_users'        => 'required|integer|min:0',
            'max_storage_mb'   => 'required|integer|min:0',
            'max_bandwidth_mb' => 'required|integer|min:0',
            'sort_order'       => 'required|integer|min:0',
        ]);

        if ($this->editingPlan) {
            $this->authorize('update', $this->editingPlan);
            $this->editingPlan->update([
                'name'             => $this->name,
                'slug'             => $this->slug,
                'description'      => $this->description,
                'price_monthly'    => $this->price_monthly,
                'price_yearly'     => $this->price_yearly,
                'max_devices'      => $this->max_devices,
                'max_screens'      => $this->max_screens,
                'max_users'        => $this->max_users,
                'max_storage_mb'   => $this->max_storage_mb,
                'max_bandwidth_mb' => $this->max_bandwidth_mb,
                'sort_order'       => $this->sort_order,
                'is_active'        => $this->is_active,
                'is_public'        => $this->is_public,
            ]);

            session()->flash('flash.banner', 'Plan updated successfully.');
        } else {
            $this->authorize('create', Plan::class);
            Plan::create([
                'name'             => $this->name,
                'slug'             => $this->slug,
                'description'      => $this->description,
                'price_monthly'    => $this->price_monthly,
                'price_yearly'     => $this->price_yearly,
                'max_devices'      => $this->max_devices,
                'max_screens'      => $this->max_screens,
                'max_users'        => $this->max_users,
                'max_storage_mb'   => $this->max_storage_mb,
                'max_bandwidth_mb' => $this->max_bandwidth_mb,
                'sort_order'       => $this->sort_order,
                'is_active'        => $this->is_active,
                'is_public'        => $this->is_public,
            ]);

            session()->flash('flash.banner', 'Plan created successfully.');
        }

        session()->flash('flash.bannerStyle', 'success');
        $this->dispatch('plan-saved');
        $this->resetForm();
    }

    public function confirmDelete(string $planId): void
    {
        $this->plan_id = $planId;
        $this->showDeleteModal = true;
    }

    public function deletePlan(): void
    {
        if ( ! $this->plan_id) {
            return;
        }

        $plan = Plan::findOrFail($this->plan_id);
        $this->authorize('delete', $plan);

        // Check if plan has active subscriptions
        if ($plan->subscriptions()->exists()) {
            session()->flash('flash.banner', 'Cannot delete a plan with active subscriptions.');
            session()->flash('flash.bannerStyle', 'danger');
            $this->showDeleteModal = false;
            $this->plan_id = null;

            return;
        }

        $plan->delete();

        session()->flash('flash.banner', 'Plan deleted successfully.');
        session()->flash('flash.bannerStyle', 'success');

        $this->showDeleteModal = false;
        $this->plan_id = null;
    }

    public function resetForm(): void
    {
        $this->reset([
            'name', 'slug', 'description', 'price_monthly', 'price_yearly',
            'max_devices', 'max_screens', 'max_users', 'max_storage_mb',
            'max_bandwidth_mb', 'features', 'is_active', 'is_public', 'sort_order',
            'showPlanModal', 'editingPlan',
        ]);
    }

    #[On('plan-saved')]
    public function handlePlanSaved(): void
    {
        $this->resetPage();
    }

    public function addFeature(string $feature): void
    {
        if ( ! in_array($feature, $this->features)) {
            $this->features[] = $feature;
        }
    }

    public function removeFeature(string $feature): void
    {
        $this->features = array_filter($this->features, fn ($f) => $f !== $feature);
    }

    public function updatedName(string $value): void
    {
        $this->slug = Str::slug($value);
    }
}
