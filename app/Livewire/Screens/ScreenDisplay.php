<?php

declare(strict_types=1);

namespace App\Livewire\Screens;

use App\Tenant\Models\Screen;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

// #[Layout('layouts.fullscreen')]
final class ScreenDisplay extends Component
{
    /**
     * The ID of the screen to display
     */
    public ?string $screenId = null;
    
    /**
     * The screen data
     */
    public ?Screen $screen = null;
    
    /**
     * Auto refresh interval in seconds
     */
    public int $refreshInterval = 60;

    public ?string $activeContentId = null;
    public ?\App\Tenant\Models\Content $activeContent = null;
    public ?string $activeWidgetType = null;
    
    /**
     * Component initialization
     */
    public function mount($screenId = null)
    {
        $this->screenId = $screenId;
        $this->loadScreen();
    }
    
    /**
     * Load the screen data with active contents
     */
    public function loadScreen(): void
    {
        if (!$this->screenId) {
            $this->resetActiveContent();
            return;
        }
        
        try {
            $this->screen = Screen::with([
                'contents' => fn($query) => $query->where('status', 'active')->orderBy('order')
            ])->findOrFail($this->screenId);
            
            // Set refresh interval from screen settings if available
            if (isset($this->screen->settings['refresh_rate'])) {
                $this->refreshInterval = (int) $this->screen->settings['refresh_rate'];
            }

            if ($this->screen->contents->isNotEmpty()) {
                $this->updateActiveContent(0); // Set initial content
            } else {
                $this->resetActiveContent();
            }

        } catch (Exception $e) {
            Log::error('Error loading screen for display', [
                'screen_id' => $this->screenId,
                'error' => $e->getMessage()
            ]);
            
            $this->screen = null;
            $this->resetActiveContent();
        }
    }

    public function updateActiveContent(int $index): void
    {
        if ($this->screen && $this->screen->contents->has($index)) {
            $this->activeContent = $this->screen->contents[$index];
            $this->activeContentId = $this->activeContent->id;

            if ($this->activeContent->type->value === 'custom' && isset($this->activeContent->content_data['widget_type'])) {
                $this->activeWidgetType = $this->activeContent->content_data['widget_type'];
            } else {
                $this->activeWidgetType = null;
            }
        } else {
            $this->resetActiveContent();
        }
    }

    private function resetActiveContent(): void
    {
        $this->activeContent = null;
        $this->activeContentId = null;
        $this->activeWidgetType = null;
    }
    
    /**
     * Poll for screen updates
     */
    public function getListeners(): array
    {
        // Only set up polling if we have a screen loaded
        if ($this->screen) {
            return [
                '$refresh' => '$refresh', // Standard Livewire refresh listener
                'echo:screens.' . $this->screenId . ',ScreenUpdated' => 'handleScreenUpdate'
            ];
        }
        
        return [];
    }
    
    /**
     * Handle screen update events from broadcast
     */
    public function handleScreenUpdate(): void
    {
        $this->loadScreen();
        // Ensure active content is reset if screen becomes empty or content changes significantly
        if (!$this->screen || $this->screen->contents->isEmpty()) {
            $this->resetActiveContent();
        } elseif ($this->activeContentId && !$this->screen->contents->contains('id', $this->activeContentId)) {
            // If current active content is no longer part of the screen, reset to first.
            // Or, if an index was passed from Alpine, use that via updateActiveContent(index).
            // For now, loadScreen already calls updateActiveContent(0) or resetActiveContent.
        }
    }
    
    /**
     * Render the screen display
     */
    public function render(): View
    {
        return view('livewire.screens.screen-display', [
            'screen' => $this->screen,
        ]);
    }
}
