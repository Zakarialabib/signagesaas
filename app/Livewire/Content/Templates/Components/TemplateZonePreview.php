<?php

declare(strict_types=1);

namespace App\Livewire\Content\Templates\Components;

use App\Enums\ContentType;
use App\Tenant\Models\Content;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class TemplateZonePreview extends Component
{
    public array $zone;
    public ?Content $content = null;
    public string $previewMode = 'edit'; // 'edit' or 'preview'
    public array $contentTypes;

    public function mount(array $zone): void
    {
        $this->zone = $zone;
        $this->contentTypes = ContentType::options();
    }

    #[Computed]
    public function hasContent(): bool
    {
        return isset($this->content);
    }

    public function togglePreviewMode(): void
    {
        $this->previewMode = $this->previewMode === 'edit' ? 'preview' : 'edit';
    }

    public function render()
    {
        return view('livewire.content.templates.components.template-zone-preview');
    }
}
