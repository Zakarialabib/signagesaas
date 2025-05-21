<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Adds bulk action functionality to Livewire components with tables
 */
trait WithBulkActions
{
    /** Whether all items are selected */
    public bool $selectAll = false;

    /** Array of selected item IDs */
    public array $selected = [];

    /** Initialize the bulk actions trait */
    public function initializeBulkActions(): void
    {
        $this->resetBulkActions();
    }

    /** Reset bulk selections */
    public function resetBulkActions(): void
    {
        $this->selectAll = false;
        $this->selected = [];
    }

    /** Toggle select all */
    public function toggleSelectAll(): void
    {
        $this->selectAll = ! $this->selectAll;

        if ($this->selectAll) {
            // Load all IDs - should be implemented in the component
            $this->selected = $this->getAllIds() ?? [];
        } else {
            $this->selected = [];
        }
    }

    /**
     * Toggle selection of a single item
     *
     * @param string|int $id Item ID
     */
    public function toggleSelected($id): void
    {
        // Convert ID to string for consistency
        $id = (string) $id;

        if (in_array($id, $this->selected)) {
            $this->selected = array_diff($this->selected, [$id]);
            $this->selectAll = false;
        } else {
            $this->selected[] = $id;
            // If all IDs are selected, set selectAll to true
            $allIds = $this->getAllIds() ?? [];
            $this->selectAll = count($this->selected) === count($allIds);
        }
    }

    /**
     * Check if an item is selected
     *
     * @param string|int $id Item ID
     * @return bool True if the item is selected
     */
    public function isSelected($id): bool
    {
        return in_array((string) $id, $this->selected);
    }

    /**
     * Check if any items are selected
     *
     * @return bool True if any items are selected
     */
    public function hasSelected(): bool
    {
        return count($this->selected) > 0;
    }

    /**
     * Get the number of selected items
     *
     * @return int Number of selected items
     */
    public function getSelectedCount(): int
    {
        return count($this->selected);
    }

    /**
     * This should be implemented in the component
     * Should return an array of all item IDs
     *
     * @return array All item IDs
     */
    public function getAllIds(): array
    {
        // Should be implemented in the component
        // For example:
        // return Model::query()->pluck('id')->map(fn($id) => (string)$id)->toArray();
        return [];
    }
}
