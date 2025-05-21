<?php

declare(strict_types=1);

namespace App\Traits;

use Livewire\Attributes\Url;

/**
 * Adds filtering functionality to Livewire components
 */
trait WithFilters
{
    /**
     * Array of active filters
     *
     * @var array
     */
    #[Url]
    public array $filters = [];

    /**
     * Available filter definitions
     *
     * @var array
     */
    public array $availableFilters = [];

    /**
     * Initialize the filters trait
     *
     * @param array $availableFilters Available filters configuration
     */
    public function initializeFilters(array $availableFilters = []): void
    {
        $this->availableFilters = $availableFilters;
    }

    /**
     * Apply a filter
     *
     * @param string $name Filter name
     * @param mixed $value Filter value
     */
    public function applyFilter(string $name, $value): void
    {
        if ($value === null || $value === '') {
            $this->removeFilter($name);

            return;
        }

        $this->filters[$name] = $value;

        // Reset pagination when filters change
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    /**
     * Remove a filter
     *
     * @param string $name Filter name
     */
    public function removeFilter(string $name): void
    {
        if (isset($this->filters[$name])) {
            unset($this->filters[$name]);

            // Reset pagination when filters change
            if (method_exists($this, 'resetPage')) {
                $this->resetPage();
            }
        }
    }

    /** Reset all filters */
    public function resetFilters(): void
    {
        $this->filters = [];

        // Reset pagination when filters change
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    /**
     * Apply filters to a query builder
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyFilters($query)
    {
        foreach ($this->filters as $name => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // Find the filter definition
            $filterDef = $this->availableFilters[$name] ?? null;

            if ( ! $filterDef) {
                continue;
            }

            // Apply the filter based on its type
            if (isset($filterDef['callback']) && is_callable($filterDef['callback'])) {
                // Custom callback
                $query = call_user_func($filterDef['callback'], $query, $value);
            } elseif (isset($filterDef['field'])) {
                // Simple field = value filter
                $field = $filterDef['field'];
                $operator = $filterDef['operator'] ?? '=';

                if ($operator === 'like') {
                    $value = '%'.$value.'%';
                }

                $query->where($field, $operator, $value);
            }
        }

        return $query;
    }

    /**
     * Get a filter's current value
     *
     * @param string $name Filter name
     * @param mixed $default Default value if filter is not set
     * @return mixed The filter value or default
     */
    public function getFilter(string $name, $default = null)
    {
        return $this->filters[$name] ?? $default;
    }

    /**
     * Check if a filter is active
     *
     * @param string $name Filter name
     * @return bool True if the filter is active
     */
    public function isFilterActive(string $name): bool
    {
        return isset($this->filters[$name]) && $this->filters[$name] !== null && $this->filters[$name] !== '';
    }
}
