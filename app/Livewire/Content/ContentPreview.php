<?php

declare(strict_types=1);

namespace App\Livewire\Content;

use App\Tenant\Models\Content;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;

class ContentPreview extends Component
{
    #[Locked]
    public ?Content $content = null;

    public bool $previewContentModal = false;

    #[On('preview-content')]
    public function openPreview(string $id): void
    {
        $this->content = Content::with('screen')->findOrFail($id);
        $this->previewContentModal = true;
    }

    public function closePreview(): void
    {
        $this->previewContentModal = false;
        $this->content = null;
    }

    public function render()
    {
        return view('livewire.content.content-preview');
    }
}
