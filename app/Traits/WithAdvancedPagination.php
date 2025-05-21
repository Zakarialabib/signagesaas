<?php

declare(strict_types=1);

namespace App\Traits;

use Livewire\WithPagination;
use Livewire\Attributes\Url;

/**
 * Adds enhanced pagination functionality to Livewire components
 */
trait WithAdvancedPagination
{
    use WithPagination;

    #[Url]
    public int $perPage = 10;

    /** Available per page options */
    public array $perPageOptions = [10, 25, 50, 100];

    /**
     * Initialize the pagination trait
     *
     * @param int $defaultPerPage Default items per page
     * @param array $perPageOptions Available per page options
     */
    public function initializePagination(int $defaultPerPage = 10, array $perPageOptions = [10, 25, 50, 100]): void
    {
        $this->perPage = $defaultPerPage;
        $this->perPageOptions = $perPageOptions;
    }

    /** Update the number of items per page */
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    /** Reset pagination to first page */
    public function resetPagination(): void
    {
        $this->resetPage();
    }

    /**
     * Get the start index for the current page
     *
     * @param int $total Total number of items
     * @return int The start index
     */
    public function getStartIndex(int $total): int
    {
        if ($total === 0) {
            return 0;
        }

        return (($this->page - 1) * $this->perPage) + 1;
    }

    /**
     * Get the end index for the current page
     *
     * @param int $total Total number of items
     * @return int The end index
     */
    public function getEndIndex(int $total): int
    {
        if ($total === 0) {
            return 0;
        }

        $end = $this->getStartIndex($total) + $this->perPage - 1;

        return min($end, $total);
    }

    /** Method called before page updates */
    public function updatingPage(): void
    {
        // Override in component if needed
    }

    /** Method called after page updates */
    public function updatedPage(): void
    {
        // Override in component if needed
    }
}
