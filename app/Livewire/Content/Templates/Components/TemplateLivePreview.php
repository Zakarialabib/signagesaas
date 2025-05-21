<?php

declare(strict_types=1);

namespace App\Livewire\Content\Templates\Components;

use App\Tenant\Models\Template;
use Livewire\Component;
use Livewire\Attributes\Locked;

final class TemplateLivePreview extends Component
{
    public Template $template;

    #[Locked]
    public array $layout; // Expose layout (including zones) to Alpine

    public function mount(Template $template): void
    {
        $this->template = $template;
        $this->layout = $template->layout ?? [
            'zones' => [],
            'width' => 1920,
            'height' => 1080,
        ];
    }

    public function saveZones(array $zones): void
    {
        $this->template->layout['zones'] = $zones;
        $this->template->save();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Zones updated successfully.',
        ]);
    }

    public function render()
    {
        // You can optimize this to only fetch content relevant to the current tenant/user
        $allContent = \App\Tenant\Models\Content::select('id', 'name', 'type')->get()->toArray();

        return view('livewire.content.templates.components.template-live-preview', [
            'allContent' => $allContent,
        ]);
    }
}
