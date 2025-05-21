<?php

declare(strict_types=1);

namespace App\Traits;

use Livewire\Attributes\Computed;

/**
 * Combined trait for Livewire components that display data tables
 * Includes pagination, sorting, searching, filtering, and bulk actions
 */
trait WithDataTable
{
    use WithAdvancedPagination;
    use WithSorting;
    use WithSearch;
    use WithFilters;
    use WithBulkActions;
    use WithModals;

    /**
     * Initialize the data table
     *
     * @param array $config Configuration options
     */
    public function initializeDataTable(array $config = []): void
    {
        // Extract config options or use defaults
        $perPage = $config['perPage'] ?? 10;
        $perPageOptions = $config['perPageOptions'] ?? [10, 25, 50, 100];
        $sortField = $config['sortField'] ?? 'created_at';
        $sortDirection = $config['sortDirection'] ?? 'desc';
        $searchFields = $config['searchFields'] ?? [];
        $searchDebounce = $config['searchDebounce'] ?? 300;
        $availableFilters = $config['availableFilters'] ?? [];

        // Initialize each trait
        $this->initializePagination($perPage, $perPageOptions);
        $this->initializeSorting($sortField, $sortDirection);
        $this->initializeSearch($searchFields, $searchDebounce);
        $this->initializeFilters($availableFilters);
        $this->initializeBulkActions();
        $this->initializeModals();
    }

    /**
     * Apply all the query modifiers (search, sort, filters)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyDataTableQuery($query)
    {
        // Apply search
        $query = $this->applySearch($query);

        // Apply filters
        $query = $this->applyFilters($query);

        // Apply sorting
        return $query->orderBy($this->sortField, $this->sortDirection);
    }

    /** Reset all table filters, search, and pagination */
    public function resetDataTable(): void
    {
        $this->resetSearch();
        $this->resetFilters();
        $this->resetSorting();
        $this->resetPagination();
        $this->resetBulkActions();
    }

    /**
     * Get table info text (e.g., "Showing 1-10 of 100 results")
     *
     * @param int $total Total number of items
     * @return string Table info text
     */
    #[Computed]
    public function tableInfo(int $total): string
    {
        if ($total === 0) {
            return 'Showing 0 results';
        }

        $start = $this->getStartIndex($total);
        $end = $this->getEndIndex($total);

        return "Showing {$start}-{$end} of {$total} results";
    }
}
