<?php

declare(strict_types=1);

namespace App\Livewire\Content;

use App\Enums\ContentStatus;
use App\Tenant\Models\Content;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;

final class ContentShow extends Component
{
    #[Locked]
    public ?Content $content = null;

    public bool $showContentModal = false;

    #[On('showContentModal')]
    public function openModal(string $id): void
    {
        $this->content = Content::with('screen.device')->findOrFail($id);
        $this->authorize('view', $this->content);
        $this->showContentModal = true;
    }

    public function render()
    {
        return view('livewire.content.content-show');
    }

    public function refreshContent(): void
    {
        if ( ! $this->content) {
            return;
        }

        $this->content->refresh();
        $this->content->load('screen.device');
    }

    public function closeModal(): void
    {
        $this->showContentModal = false;
        $this->content = null;
    }

    #[Computed()]
    public function isVideo(): bool
    {
        return $this->content && $this->content->type->value === 'video';
    }

    #[Computed()]
    public function isImage(): bool
    {
        return $this->content && $this->content->type->value === 'image';
    }

    #[Computed()]
    public function isHtml(): bool
    {
        return $this->content && $this->content->type->value === 'html';
    }

    #[Computed()]
    public function isUrl(): bool
    {
        return $this->content && $this->content->type->value === 'url';
    }

    public function toggleStatus(): void
    {
        if ( ! $this->content) {
            return;
        }

        $this->authorize('update', $this->content);

        // Toggle between active and inactive
        $this->content->status = $this->content->status === ContentStatus::ACTIVE
            ? ContentStatus::INACTIVE
            : ContentStatus::ACTIVE;

        $this->content->save();

        session()->flash('flash.banner', 'Content status updated to '.ucfirst($this->content->status->value));
        session()->flash('flash.bannerStyle', 'success');

        $this->refreshContent();
    }

    public function deleteContent(): void
    {
        if ( ! $this->content) {
            return;
        }

        $this->authorize('delete', $this->content);

        $contentName = $this->content->name;
        $this->content->delete();

        session()->flash('flash.banner', "Content '{$contentName}' deleted successfully.");
        session()->flash('flash.bannerStyle', 'success');

        // Redirect to content index
        $this->redirect(route('content.index'));
    }
}
