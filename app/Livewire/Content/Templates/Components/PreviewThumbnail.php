<?php

declare(strict_types=1);

namespace App\Livewire\Content\Templates\Components;

use App\Tenant\Models\Content;
use Livewire\Component;

final class PreviewThumbnail extends Component
{
    public Content $content;
    public string $size = 'md';
    public bool $showDetails = true;
    public ?string $overlay = null;

    public function mount(Content $content, string $size = 'md', bool $showDetails = true, ?string $overlay = null): void
    {
        $this->content = $content;
        $this->size = $size;
        $this->showDetails = $showDetails;
        $this->overlay = $overlay;
    }

    public function getSizeClasses(): string
    {
        return match ($this->size) {
            'sm' => 'h-24 w-24',
            'lg' => 'h-48 w-48',
            default => 'h-32 w-32'
        };
    }

    public function render()
    {
        return view('livewire.content.templates.components.preview-thumbnail');
    }
}
