<?php

declare(strict_types=1);

namespace App\Livewire\Screens;

use App\Tenant\Models\Device;
use App\Tenant\Models\Screen;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;

final class ScreenShow extends Component
{
    /**
     * The currently viewed screen
     */
    #[Locked]
    public ?Screen $screen = null;

    /**
     * Controls the visibility of the screen modal
     */
    public bool $showScreenModal = false;

    /**
     * Open the screen modal and load the screen with related data
     */
    #[On('showScreen')]
    public function openModal(string $id): void
    {
        try {
            $this->showScreenModal = true;
            $this->screen = Screen::with([
                'device', 
                'contents' => fn ($query) => $query->orderBy('order')
            ])->findOrFail($id);
            
            $this->authorize('view', $this->screen);
        } catch (Exception $e) {
            Log::error('Error loading screen', [
                'screen_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('flash.banner', 'Error loading screen: ' . $e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
            $this->showScreenModal = false;
        }
    }

    /**
     * Get all available devices
     */
    #[Computed]
    public function devices()
    {
        return Device::query()
            ->select(['id', 'name', 'status', 'type'])
            ->where('status', 'active')
            ->get();
    }

    /**
     * Render the screen show component
     */
    public function render(): View
    {
        return view('livewire.screens.screen-show');
    }

    /**
     * Refresh the screen data
     */
    public function refreshScreen(): void
    {
        if (!$this->screen) {
            return;
        }

        try {
            $this->screen->refresh();
            $this->screen->load(['device', 'contents' => fn ($query) => $query->orderBy('order')]);
        } catch (Exception $e) {
            Log::error('Error refreshing screen', [
                'screen_id' => $this->screen->id,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('flash.banner', 'Error refreshing screen data: ' . $e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
        }
    }

    /**
     * Get the preview URL for the screen
     */
    public function previewScreen(): string
    {
        if (!$this->screen) {
            return '#';
        }

        try {
            return $this->screen->getPreviewUrl();
        } catch (Exception $e) {
            Log::error('Error generating preview URL', [
                'screen_id' => $this->screen->id,
                'error' => $e->getMessage()
            ]);
            return '#';
        }
    }

    /**
     * Get the display URL for the screen
     */
    public function displayScreen(): string
    {
        if (!$this->screen) {
            return '#';
        }

        try {
            return $this->screen->getDisplayUrl();
        } catch (Exception $e) {
            Log::error('Error generating display URL', [
                'screen_id' => $this->screen->id,
                'error' => $e->getMessage()
            ]);
            return '#';
        }
    }

    /**
     * Get formatted resolution with aspect ratio
     */
    public function getFormattedResolution(): string
    {
        if (!$this->screen) {
            return 'N/A';
        }

        try {
            $dimensions = $this->screen->getDimensions();
            return "{$dimensions['width']} × {$dimensions['height']} ({$this->screen->getAspectRatio()})";
        } catch (Exception $e) {
            Log::error('Error formatting resolution', [
                'screen_id' => $this->screen->id,
                'error' => $e->getMessage()
            ]);
            return 'N/A';
        }
    }

    /**
     * Update the order of content items
     */
    public function setContentOrder(string $contentId, int $newOrder): void
    {
        if (!$this->screen) {
            return;
        }

        try {
            $this->authorize('update', $this->screen);

            $content = $this->screen->contents->firstWhere('id', $contentId);

            if (!$content) {
                return;
            }

            $content->update(['order' => $newOrder]);
            $this->refreshScreen();
            
            session()->flash('flash.banner', 'Content order updated successfully.');
            session()->flash('flash.bannerStyle', 'success');
        } catch (Exception $e) {
            Log::error('Error updating content order', [
                'screen_id' => $this->screen->id,
                'content_id' => $contentId,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('flash.banner', 'Error updating content order: ' . $e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
        }
    }

    /**
     * Toggle the status of a content item
     */
    public function toggleContentStatus(string $contentId): void
    {
        if (!$this->screen) {
            return;
        }

        try {
            $this->authorize('update', $this->screen);

            $content = $this->screen->contents->firstWhere('id', $contentId);

            if (!$content) {
                return;
            }

            $newStatus = $content->status->value === 'active' ? 'inactive' : 'active';
            $content->update(['status' => $newStatus]);

            $this->refreshScreen();
            
            $statusLabel = $newStatus === 'active' ? 'activated' : 'deactivated';
            session()->flash('flash.banner', "Content {$statusLabel} successfully.");
            session()->flash('flash.bannerStyle', 'success');
        } catch (Exception $e) {
            Log::error('Error toggling content status', [
                'screen_id' => $this->screen->id,
                'content_id' => $contentId,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('flash.banner', 'Error updating content status: ' . $e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
        }
    }
    
    /**
     * Close the modal
     */
    public function closeModal(): void
    {
        $this->showScreenModal = false;
        $this->screen = null;
    }
}
