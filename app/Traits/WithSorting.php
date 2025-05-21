<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Adds sorting functionality to Livewire components with tables
 */
trait WithSorting
{
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public string $defaultSortField = 'created_at';
    public string $defaultSortDirection = 'desc';

    /**
     * Initialize the sorting trait
     *
     * @param string $field The default field to sort by
     * @param string $direction The default sort direction (asc or desc)
     */
    public function initializeSorting(string $field = 'created_at', string $direction = 'desc'): void
    {
        $this->defaultSortField = $field;
        $this->defaultSortDirection = $direction;
        $this->sortField = $field;
        $this->sortDirection = $direction;
    }

    /**
     * Sort by a specific field
     *
     * @param string $field The field to sort by
     */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        // Reset pagination when sorting changes
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    /** Reset sorting to default values */
    public function resetSorting(): void
    {
        $this->sortField = $this->defaultSortField;
        $this->sortDirection = $this->defaultSortDirection;
    }

    /**
     * Get the sort icon for a column
     *
     * @param string $field The field to check
     * @return string|null The sort icon HTML or null if not sorted
     */
    public function getSortIcon(string $field): ?string
    {
        if ($this->sortField !== $field) {
            return null;
        }

        return $this->sortDirection === 'asc'
            ? '<svg class="h-3 w-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 3l-8 10h16l-8-10z"/></svg>'
            : '<svg class="h-3 w-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 17l8-10H2l8 10z"/></svg>';
    }
}
