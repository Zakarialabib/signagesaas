<?php

declare(strict_types=1);

namespace App\Livewire\Content;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use App\Tenant\Models\Content;
use App\Tenant\Models\Screen;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;

final class ContentEdit extends Component
{
    use WithFileUploads;

    #[Locked]
    public ?Content $content = null;

    public bool $editContentModal = false;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:1000')]
    public ?string $description = null;

    #[Validate('required|string')]
    public string $type = 'image';

    #[Validate('required|uuid|exists:screens,id')]
    public string $screen_id = '';

    #[Validate('required|string|in:active,inactive')]
    public string $status = 'active';

    #[Validate('required|integer|min:5|max:300')]
    public int $duration = 10;

    #[Validate('nullable|integer|min:0')]
    public ?int $order = 0;

    #[Validate('nullable|date')]
    public ?string $start_date = null;

    #[Validate('nullable|date|after_or_equal:start_date')]
    public ?string $end_date = null;

    #[Validate('nullable|image|max:10240')] // 10MB max
    public $image_file = null;

    #[Validate('nullable|string|url|max:2048')]
    public ?string $url = null;

    #[Validate('nullable|string')]
    public ?string $html_content = null;

    #[Validate('nullable|string|url|max:2048')]
    public ?string $feed_url = null; // For RSS

    #[Validate('nullable|string|max:255')]
    public ?string $location = null; // For Weather

    #[Validate('nullable|string|max:255')]
    public ?string $platform = null; // For Social

    #[Validate('nullable|string|max:255')]
    public ?string $handle = null; // For Social

    #[Validate('nullable|string|url|max:2048')]
    public ?string $calendar_url = null; // For Calendar

    #[Validate('nullable|string')]
    public ?string $custom_html = null;

    #[Validate('nullable|array')]
    public ?array $settings = null;

    #[Locked]
    public string $screenId = '';

    public bool $showAdvancedSettings = false;

    #[On('editContentModal')]
    public function openModal(string $id): void
    {
        $this->content = Content::with('screen')->findOrFail($id);
        $this->authorize('update', $this->content);

        $this->rules['type'] = 'required|string|in:' . implode(',', ContentType::values());

        $this->loadContentData();
        $this->editContentModal = true;
    }

    private function loadContentData(): void
    {
        if ( ! $this->content) {
            return;
        }

        $this->screen_id = $this->content->screen_id;
        $this->name = $this->content->name;
        $this->description = $this->content->description;
        $this->type = $this->content->type->value;
        $this->status = $this->content->status->value;
        $this->duration = $this->content->duration;
        $this->order = $this->content->order;
        $this->start_date = $this->content->start_date ? $this->content->start_date->format('Y-m-d') : null;
        $this->end_date = $this->content->end_date ? $this->content->end_date->format('Y-m-d') : null;
        $data = $this->content->content_data ?? [];
        $this->settings = $this->content->settings;

        $this->image_file = null;
        $this->url = null;
        $this->html_content = null;
        $this->feed_url = null;
        $this->location = null;
        $this->platform = null;
        $this->handle = null;
        $this->calendar_url = null;
        $this->custom_html = null;

        if ($this->type !== ContentType::PRODUCT_LIST->value && $this->type !== ContentType::MENU->value) {
            switch ($this->type) {
                case ContentType::IMAGE->value:
                    $this->url = $data['url'] ?? null;
                    break;
                case ContentType::VIDEO->value:
                case ContentType::URL->value:
                    $this->url = $data['url'] ?? null;
                    break;
                case ContentType::HTML->value:
                case ContentType::CUSTOM->value:
                    $this->html_content = $data['html'] ?? null;
                    break;
                case ContentType::RSS->value:
                    $this->feed_url = $data['feed_url'] ?? null;
                    break;
                case ContentType::WEATHER->value:
                    $this->location = $data['location'] ?? null;
                    break;
                case ContentType::SOCIAL->value:
                    $this->platform = $data['platform'] ?? null;
                    $this->handle = $data['handle'] ?? null;
                    break;
                case ContentType::CALENDAR->value:
                    $this->calendar_url = $data['calendar_url'] ?? null;
                    break;
            }
        }
    }

    public function render()
    {
        return view('livewire.content.content-edit', [
            'contentTypes' => ContentType::options(),
            'statuses'     => ContentStatus::options(),
            'screens'      => Screen::where('status', 'active')
                ->with('device')
                ->get(),
        ]);
    }

    public function updateContent(): void
    {
        if ( ! $this->content) {
            return;
        }

        $this->authorize('update', $this->content);

        if ($this->type === ContentType::PRODUCT_LIST->value || $this->type === ContentType::MENU->value) {
            $this->closeModal();
            return;
        }
        
        $this->rules['type'] = 'required|string|in:' . implode(',', ContentType::values());
        $validated = $this->validate();

        $contentDataForUpdate = $this->content->content_data ?? [];

        switch ($validated['type']) {
            case ContentType::IMAGE->value:
                if ($this->image_file) {
                    $path = $this->image_file->store('content/images', 'public');
                    $contentDataForUpdate['url'] = Storage::url($path);
                    $contentDataForUpdate['path'] = $path;
                }
                break;
            case ContentType::VIDEO->value:
            case ContentType::URL->value:
                $contentDataForUpdate['url'] = $validated['url'] ?? $this->url;
                break;
            case ContentType::HTML->value:
            case ContentType::CUSTOM->value:
                $contentDataForUpdate['html'] = $validated['html_content'] ?? $this->html_content;
                break;
            case ContentType::RSS->value:
                $contentDataForUpdate['feed_url'] = $validated['feed_url'] ?? $this->feed_url;
                break;
            case ContentType::WEATHER->value:
                $contentDataForUpdate['location'] = $validated['location'] ?? $this->location;
                break;
            case ContentType::SOCIAL->value:
                $contentDataForUpdate['platform'] = $validated['platform'] ?? $this->platform;
                $contentDataForUpdate['handle'] = $validated['handle'] ?? $this->handle;
                break;
            case ContentType::CALENDAR->value:
                $contentDataForUpdate['calendar_url'] = $validated['calendar_url'] ?? $this->calendar_url;
                break;
        }

        $updateData = [
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'type'        => $validated['type'],
            'screen_id'   => $validated['screen_id'],
            'status'      => $validated['status'],
            'duration'    => $validated['duration'],
            'order'       => $validated['order'],
            'start_date'  => $validated['start_date'],
            'end_date'    => $validated['end_date'],
            'content_data' => $contentDataForUpdate,
            'settings'    => $validated['settings'] ?? $this->settings,
        ];
        
        $this->content->update($updateData);

        $this->dispatch('content-updated');
        $this->editContentModal = false;
        session()->flash('message', 'Content updated successfully.');
        $this->resetFields();
    }
    
    private function resetFields(): void
    {
        $this->reset([
            'name', 'description', 'type', 'screen_id', 'status', 'duration', 'order',
            'start_date', 'end_date', 'image_file', 'url', 'html_content', 'feed_url',
            'location', 'platform', 'handle', 'calendar_url', 'custom_html', 'settings'
        ]);
        $this->content = null;
        $this->type = 'image';
    }

    public function closeModal(): void
    {
        $this->editContentModal = false;
        $this->resetFields();
    }
}
