<?php

declare(strict_types=1);

namespace App\Livewire\Screens;

use App\Tenant\Models\Screen;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.fullscreen')]
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
    
    /**
     * Component initialization
     */
    public function mount(string $screenId = null): void
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
        } catch (Exception $e) {
            Log::error('Error loading screen for display', [
                'screen_id' => $this->screenId,
                'error' => $e->getMessage()
            ]);
            
            $this->screen = null;
        }
    }
    
    /**
     * Poll for screen updates
     */
    public function getListeners(): array
    {
        // Only set up polling if we have a screen loaded
        if ($this->screen) {
            return [
                '$refresh' => '$refresh',
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
