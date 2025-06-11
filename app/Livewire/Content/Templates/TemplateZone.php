<?php

namespace App\Livewire\Content\Templates;

use App\Tenant\Models\Content;
use Livewire\Component;

class TemplateZone extends Component
{
    public array $zone;
    public ?string $contentId = null;
    
    public function mount(array $zone): void
    {
        $this->zone = $zone;
    }
    
    public function assignContent(string $contentId): void
    {
        $this->contentId = $contentId;
        $this->emit('zoneUpdated', $this->zone['id'], $contentId);
    }
    
    public function render()
    {
        return view('livewire.content.templates.template-zone', [
            'content' => $this->contentId ? Content::find($this->contentId) : null,
        ]);
    }
}
