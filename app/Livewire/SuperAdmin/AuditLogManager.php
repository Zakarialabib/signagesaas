<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Tenant\Models\AuditLog;
use App\Tenant\Models\Tenant;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Computed;

#[Layout('layouts.super-admin')]
final class AuditLogManager extends Component
{
    use WithPagination;

    #[Validate('nullable|string')]
    public ?string $search = null;

    #[Validate('nullable|string')]
    public ?string $tenantFilter = null;

    #[Validate('nullable|string')]
    public ?string $actionFilter = null;

    #[Validate('nullable|string')]
    public ?string $entityTypeFilter = null;

    #[Validate('nullable|date')]
    public ?string $startDate = null;

    #[Validate('nullable|date|after_or_equal:startDate')]
    public ?string $endDate = null;

    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 25;

    public function mount(): void
    {
        // $this->authorize('viewAny', AuditLog::class);

        // Set default date range to last 7 days if not set
        if ( ! $this->startDate) {
            $this->startDate = now()->subDays(7)->format('Y-m-d');
        }

        if ( ! $this->endDate) {
            $this->endDate = now()->format('Y-m-d');
        }
    }

    public function render()
    {
        $query = AuditLog::query()
            ->with(['tenant', 'user'])
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('description', 'like', "%{$this->search}%")
                        ->orWhereHas('user', function ($uq) {
                            $uq->where('name', 'like', "%{$this->search}%")
                                ->orWhere('email', 'like', "%{$this->search}%");
                        })
                        ->orWhereHas('tenant', function ($tq) {
                            $tq->where('name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->tenantFilter, function ($query) {
                return $query->where('tenant_id', $this->tenantFilter);
            })
            ->when($this->actionFilter, function ($query) {
                return $query->where('action', $this->actionFilter);
            })
            ->when($this->entityTypeFilter, function ($query) {
                return $query->where('entity_type', $this->entityTypeFilter);
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                return $query->whereBetween('created_at', [
                    $this->startDate.' 00:00:00',
                    $this->endDate.' 23:59:59',
                ]);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.super-admin.audit-log-manager', [
            'auditLogs'         => $query->paginate($this->perPage),
            'tenants'           => $this->getTenantOptions(),
            'actionOptions'     => $this->getActionOptions(),
            'entityTypeOptions' => $this->getEntityTypeOptions(),
        ]);
    }

    #[Computed]
    public function getTenantOptions()
    {
        return Tenant::orderBy('name')->get()->mapWithKeys(function ($tenant) {
            return [$tenant->id => $tenant->name];
        });
    }

    #[Computed]
    public function getActionOptions()
    {
        return AuditLog::select('action')
            ->distinct()
            ->pluck('action')
            ->mapWithKeys(function ($action) {
                return [$action => ucfirst($action)];
            });
    }

    #[Computed]
    public function getEntityTypeOptions()
    {
        return AuditLog::select('entity_type')
            ->distinct()
            ->pluck('entity_type')
            ->mapWithKeys(function ($type) {
                return [$type => ucfirst($type)];
            });
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
        $this->reset(['search', 'tenantFilter', 'actionFilter', 'entityTypeFilter']);
        $this->startDate = now()->subDays(7)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->resetPage();
    }

    public function setDateRange(string $range): void
    {
        $now = now();

        switch ($range) {
            case 'today':
                $this->startDate = $now->format('Y-m-d');
                $this->endDate = $now->format('Y-m-d');

                break;
            case 'yesterday':
                $yesterday = $now->subDay();
                $this->startDate = $yesterday->format('Y-m-d');
                $this->endDate = $yesterday->format('Y-m-d');

                break;
            case 'week':
                $this->startDate = $now->subDays(7)->format('Y-m-d');
                $this->endDate = $now->addDays(7)->format('Y-m-d');

                break;
            case 'month':
                $this->startDate = $now->subDays(30)->format('Y-m-d');
                $this->endDate = $now->addDays(30)->format('Y-m-d');

                break;
            case 'year':
                $this->startDate = $now->subYear()->format('Y-m-d');
                $this->endDate = $now->format('Y-m-d');

                break;
        }
    }

    public function exportLogs(): void
    {
        $this->authorize('export', AuditLog::class);

        // Export implementation would go here
        // Could use Laravel Excel or CSV export functionality

        session()->flash('flash.banner', 'Audit logs exported successfully.');
        session()->flash('flash.bannerStyle', 'success');
    }
}
