<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Log;
use Exception;

abstract class BaseWidget extends Component
{
    #[Locked]
    public array $settings = [];

    #[Locked]
    public string $category;

    #[Locked]
    public string $title;

    #[Locked]
    public string $icon;

    #[Locked]
    public bool $isLoading = false;

    #[Locked]
    public ?string $error = null;

    public ?array $previewContentData = null; // Added for preview functionality

    public int $refreshInterval = 0;

    public function mount(array $settings = [], string $title = '', string $category = '', string $icon = '', ?array $previewContentData = null): void
    {
        $this->settings = $settings;
        $this->title = $title;
        $this->category = $category;
        $this->icon = $icon;
        $this->previewContentData = $previewContentData; // Store preview data

        $this->initialize();
    }

    protected function initialize(): void
    {
        if ($this->previewContentData !== null) {
            // If preview data is provided, use it directly
            $this->error = null;
            $this->loadData(); // Concrete widgets will use this to populate from previewContentData
            $this->isLoading = false; // Data is from preview, so not loading
            return;
        }

        try {
            $this->isLoading = true;
            $this->error = null;
            $this->loadData();
        } catch (Exception $e) {
            $this->handleError($e);
        } finally {
            $this->isLoading = false;
        }
    }

    abstract protected function loadData(): void;

    protected function handleError(Exception $e): void
    {
        $this->error = $e->getMessage();
        Log::error("Widget Error [{$this->category}]: ".$e->getMessage(), [
            'exception' => $e,
            'settings'  => $this->settings,
        ]);
    }

    public function refresh(): void
    {
        $this->initialize();
    }

    protected function getRefreshInterval(): int
    {
        return $this->settings['refresh_interval'] ?? $this->refreshInterval;
    }

    public function placeholder(): string
    {
        return view('livewire.content.widgets.placeholder-widget', [
            'title'    => $this->title,
            'category' => $this->category,
            'icon'     => $this->icon,
        ])->render();
    }
}
