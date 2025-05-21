<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Adds modal management functionality to Livewire components
 */
trait WithModals
{
    /** Active modals */
    public array $activeModals = [];

    /** Modal data */
    public array $modalData = [];

    /** Initialize the modals trait */
    public function initializeModals(): void
    {
        $this->activeModals = [];
        $this->modalData = [];
    }

    /**
     * Open a modal
     *
     * @param string $name Modal name
     * @param array $data Data to pass to the modal
     */
    public function openModal(string $name, array $data = []): void
    {
        $this->activeModals[$name] = true;
        $this->modalData[$name] = $data;

        // Call a specific method for this modal if it exists
        $method = 'open'.ucfirst($name).'Modal';

        if (method_exists($this, $method)) {
            $this->$method($data);
        }
    }

    /**
     * Close a modal
     *
     * @param string $name Modal name
     */
    public function closeModal(string $name): void
    {
        $this->activeModals[$name] = false;

        // Call a specific method for this modal if it exists
        $method = 'close'.ucfirst($name).'Modal';

        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    /**
     * Toggle a modal
     *
     * @param string $name Modal name
     * @param array $data Data to pass to the modal if opening
     */
    public function toggleModal(string $name, array $data = []): void
    {
        if ($this->isModalOpen($name)) {
            $this->closeModal($name);
        } else {
            $this->openModal($name, $data);
        }
    }

    /**
     * Check if a modal is open
     *
     * @param string $name Modal name
     * @return bool True if the modal is open
     */
    public function isModalOpen(string $name): bool
    {
        return $this->activeModals[$name] ?? false;
    }

    /**
     * Get modal data
     *
     * @param string $name Modal name
     * @return array Modal data
     */
    public function getModalData(string $name): array
    {
        return $this->modalData[$name] ?? [];
    }

    /** Reset all modals */
    public function resetModals(): void
    {
        $this->activeModals = [];
        $this->modalData = [];
    }
}
