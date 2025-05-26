<?php

declare(strict_types=1);

namespace App\Livewire\Content\Templates\Components;

use App\Tenant\Models\Template;
use Livewire\Component;
use Livewire\Attributes\On;

final class LivePreview extends Component
{
    public Template $template;
    public array $zoneContents = [];
    public bool $isFullscreen = false;
    public bool $isPlaying = false;
    public bool $isQuickPreview = false;
    public array $playback = [
        'currentZone' => null,
        'timeLeft'    => 0,
    ];

    public array $previewSettings = [
        'transitionSpeed' => 500,
        'autoAdvance'     => true,
        'showControls'    => true,
        'loopPlayback'    => true,
    ];

    protected $listeners = [
        'refresh-preview'          => '$refresh',
        'content-updated'          => 'handleContentUpdate',
        'preview-settings-changed' => 'updatePreviewSettings',
    ];

    public function mount(Template $template): void
    {
        $this->template = $template;
        $this->loadZoneContents();
    }

    private function loadZoneContents(): void
    {
        $layout = $this->template->layout;

        foreach ($layout['zones'] ?? [] as $zoneId => $zone) {
            if (isset($zone['content_id'])) {
                $this->zoneContents[$zoneId] = [
                    'content'  => $this->template->contents()->find($zone['content_id']),
                    'settings' => $zone['settings'] ?? [],
                ];
            }
        }
    }

    #[On('content-updated')]
    public function handleContentUpdate($data): void
    {
        $this->loadZoneContents();
    }

    public function toggleFullscreen(): void
    {
        $this->isFullscreen = ! $this->isFullscreen;
    }

    public function togglePlayback(): void
    {
        $this->isPlaying = ! $this->isPlaying;

        if ($this->isPlaying) {
            $this->startPlayback();
        }
    }

    public function toggleQuickPreview(): void
    {
        $this->isQuickPreview = ! $this->isQuickPreview;

        if ($this->isQuickPreview) {
            $this->previewSettings['showControls'] = false;
            $this->isPlaying = true;
            $this->startPlayback();
        } else {
            $this->isPlaying = false;
        }
    }

    public function updatePreviewSettings(array $settings): void
    {
        $this->previewSettings = array_merge($this->previewSettings, $settings);

        if ($this->isPlaying) {
            $this->startPlayback(); // Restart playback with new settings
        }
    }

    private function startPlayback(): void
    {
        if ( ! $this->isPlaying || empty($this->zoneContents)) {
            return;
        }

        // Start with the first zone
        $firstZone = array_key_first($this->zoneContents);
        $this->playback = [
            'currentZone' => $firstZone,
            'timeLeft'    => $this->zoneContents[$firstZone]['settings']['duration'] ?? 10,
        ];

        $this->dispatch('preview-content-changed', [
            'zoneId'  => $firstZone,
            'content' => $this->zoneContents[$firstZone]['content'],
        ]);
    }

    public function advanceZone(): void
    {
        if ( ! $this->isPlaying || empty($this->zoneContents)) {
            return;
        }

        $zones = array_keys($this->zoneContents);
        $currentIndex = array_search($this->playback['currentZone'], $zones);

        // If we're at the end and looping is enabled, go back to start
        if ($currentIndex === count($zones) - 1 && $this->previewSettings['loopPlayback']) {
            $nextZone = $zones[0];
        }
        // If we're at the end and looping is disabled, stop playback
        elseif ($currentIndex === count($zones) - 1) {
            $this->isPlaying = false;

            return;
        }
        // Otherwise, move to next zone
        else {
            $nextZone = $zones[$currentIndex + 1];
        }

        $this->playback = [
            'currentZone' => $nextZone,
            'timeLeft'    => $this->zoneContents[$nextZone]['settings']['duration'] ?? 10,
        ];

        $this->dispatch('preview-content-changed', [
            'zoneId'  => $nextZone,
            'content' => $this->zoneContents[$nextZone]['content'],
        ]);
    }

    public function getZoneStyle(array $zone): string
    {
        $transition = $this->isQuickPreview ? "transition: all {$this->previewSettings['transitionSpeed']}ms ease-in-out;" : '';

        return "left: {$zone['x']}%; 
                top: {$zone['y']}%; 
                width: {$zone['width']}%; 
                height: {$zone['height']}%;
                background: ".($zone['settings']['background'] ?? '#ffffff').';
                padding: '.($zone['settings']['padding'] ?? '0').';
                border-radius: '.($zone['settings']['border-radius'] ?? '0').';'.
                $transition;
    }

    public function render()
    {
        return view('livewire.content.templates.components.live-preview', [
            'showControls' => $this->previewSettings['showControls'] && ! $this->isQuickPreview,
        ]);
    }
}
