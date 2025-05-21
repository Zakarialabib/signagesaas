<?php

declare(strict_types=1);

namespace App\Traits;

use Livewire\Attributes\Url;

/**
 * Adds search functionality to Livewire components
 */
trait WithSearch
{
    #[Url]
    public string $search = '';

    public int $searchDebounce = 300;
    public array $searchFields = [];

    /**
     * Initialize the search trait
     *
     * @param array $fields Fields to search in
     * @param int $debounce Debounce time in milliseconds
     */
    public function initializeSearch(array $fields = [], int $debounce = 300): void
    {
        $this->searchFields = $fields;
        $this->searchDebounce = $debounce;
    }

    /** Reset the search */
    public function resetSearch(): void
    {
        $this->search = '';

        // Reset pagination when search changes
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    /**
     * Apply the search to a query builder
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param ?array $fields Fields to search in (defaults to $this->searchFields)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applySearch($query, ?array $fields = null)
    {
        if (empty($this->search)) {
            return $query;
        }

        $searchFields = $fields ?? $this->searchFields;

        if (empty($searchFields)) {
            return $query;
        }

        return $query->where(function ($q) use ($searchFields) {
            foreach ($searchFields as $field) {
                $q->orWhere($field, 'like', '%'.$this->search.'%');
            }
        });
    }

    /** Handle updating the search property */
    public function updatedSearch(): void
    {
        // Reset pagination when search changes
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }
}
