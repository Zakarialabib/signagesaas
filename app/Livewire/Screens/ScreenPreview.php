<?php

declare(strict_types=1);

namespace App\Livewire\Screens;

use App\Tenant\Models\Screen;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Attributes\On;

final class ScreenPreview extends Component
{
    #[Locked]
    public ?Screen $screen = null;

    public bool $showPreviewModal = false;

    #[On('showPreviewModal')]
    public function openModal(string $id): void
    {
        try {
            $this->showPreviewModal = true;
            $this->screen = Screen::with([
                'contents' => fn($query) => $query->where('status', 'active')->orderBy('order')
            ])->findOrFail($id);

            $this->authorize('view', $this->screen);
        } catch (Exception $e) {
            Log::error('Error loading screen for preview', [
                'screen_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('flash.banner', 'Error loading screen preview: ' . $e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
            $this->showPreviewModal = false;
        }
    }
    
    /**
     * Close the preview modal
     */
    public function closeModal(): void
    {
        $this->showPreviewModal = false;
        $this->screen = null;
    }
    
    /**
     * Check if the screen has active content
     */
    public function hasContent(): bool
    {
        return $this->screen && $this->screen->contents->where('status', 'active')->count() > 0;
    }

    public function render(): View
    {
        return view('livewire.screens.screen-preview', [
            'screen' => $this->screen,
        ]);
    }
}
