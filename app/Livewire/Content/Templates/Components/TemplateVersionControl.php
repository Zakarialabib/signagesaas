<?php

declare(strict_types=1);

namespace App\Livewire\Content\Templates\Components;

use App\Tenant\Models\Template;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

final class TemplateVersionControl extends Component
{
    use WithPagination;

    public Template $template;
    public bool $showVersionHistory = false;
    public bool $showVersionCreate = false;
    public ?string $versionDescription = null;

    public function mount(Template $template): void
    {
        $this->template = $template;
    }

    #[Computed]
    public function versions(): Collection
    {
        return $this->template->versions()
            ->orderByDesc('version')
            ->get();
    }

    #[Computed]
    public function variations(): Collection
    {
        return $this->template->variations()
            ->orderByDesc('created_at')
            ->get();
    }

    public function createVersion(): void
    {
        // Gate::authorize('update', $this->template);

        $this->validate([
            'versionDescription' => 'required|string|max:255',
        ]);

        $latestVersion = $this->template->latestVersion();
        $newVersionNumber = $latestVersion->version + 1;

        $newVersion = $this->template->replicate();
        $newVersion->parent_id = $this->template->id;
        $newVersion->version = $newVersionNumber;
        $newVersion->description = $this->versionDescription;
        $newVersion->is_variation = false;
        $newVersion->save();

        $this->showVersionCreate = false;
        $this->versionDescription = null;

        $this->dispatch('version-created', template: $newVersion);
    }

    public function createVariation(): void
    {
        // Gate::authorize('update', $this->template);

        $variation = $this->template->replicate();
        $variation->parent_id = $this->template->id;
        $variation->version = $this->template->version;
        $variation->is_variation = true;
        $variation->name = $this->template->name.' (Variation)';
        $variation->save();

        $this->dispatch('variation-created', template: $variation);
    }

    public function render()
    {
        return view('livewire.content.templates.components.template-version-control');
    }
}
