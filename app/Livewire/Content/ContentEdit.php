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

    #[Validate('required|string|in:image,video,html,url')]
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

    // Content type specific fields
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

    #[Validate('required|array')]
    public array $content_data = [];

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

        // Check if this content is a specialized widget type handled by WidgetDataEditorModal
        if (isset($this->content->content_data['widget_type']) &&
            in_array($this->content->content_data['widget_type'], [
                'MenuWidget', 
                'RetailProductWidget', 
                'WeatherWidget', 
                'ClockWidget', 
                'AnnouncementWidget',
                'RssFeedWidget',
                'CalendarWidget'
                // Add other recognized widget types here
            ])) {
            
            $this->dispatch('openWidgetDataEditor', 
                zoneId: null, // zoneId is null as we are editing content directly
                widgetType: $this->content->content_data['widget_type'], 
                contentId: $this->content->id
            )->to('App.Livewire.Content.Widgets.WidgetDataEditorModal');

            $this->editContentModal = false; // Prevent this modal from showing fully
            // Reset is important if the modal was already open with different content
            // However, openModal is typically called on a fresh instance or when modal is closed.
            // If issues arise with stale data, uncomment the reset.
            // $this->reset(); 
            return; 
        }

        // Proceed with loading data for standard content types if not delegated
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
        $this->start_date = $this->content->start_date ? $this->content->start_date->format('Y-m-d\TH:i') : null;
        $this->end_date = $this->content->end_date ? $this->content->end_date->format('Y-m-d\TH:i') : null;
        $data = $this->content->data ?? [];
        $this->settings = $this->content->settings;

        // Initialize type-specific properties
        $this->image_file = null;
        $this->url = null;
        $this->html_content = null;
        $this->feed_url = null;
        $this->location = null;
        $this->platform = null;
        $this->handle = null;
        $this->calendar_url = null;
        $this->custom_html = null;

        switch ($this->type) {
            case ContentType::IMAGE->value:
                $this->url = $data['url'] ?? null;

                break;
            case ContentType::VIDEO->value:
                $this->url = $data['url'] ?? null;

                break;
            case ContentType::HTML->value:
                $this->html_content = $data['html'] ?? null;

                break;
            case ContentType::URL->value:
                $this->url = $data['url'] ?? null;

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
            case ContentType::CUSTOM->value:
                $this->custom_html = $data['html'] ?? null;

                break;
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

        $validated = $this->validate();

        $contentData = [];

        switch ($this->type) {
            case ContentType::IMAGE->value:
                if ($this->image_file) {
                    $this->validate(['image_file' => 'required|image|max:10240']);
                    $path = $this->image_file->store('content/images', 'public');
                    $contentData['url'] = Storage::url($path);
                    $contentData['path'] = $path;
                } else {
                    $contentData['url'] = $this->url;
                }

                break;
            case ContentType::VIDEO->value:
                $this->validate(['url' => 'required|string|url|max:2048']);
                $contentData['url'] = $this->url;

                break;
            case ContentType::HTML->value:
                $this->validate(['html_content' => 'required|string']);
                $contentData['html'] = $this->html_content;

                break;
            case ContentType::URL->value:
                $this->validate(['url' => 'required|string|url|max:2048']);
                $contentData['url'] = $this->url;

                break;
            case ContentType::RSS->value:
                $this->validate(['feed_url' => 'required|string|url|max:2048']);
                $contentData['feed_url'] = $this->feed_url;

                break;
            case ContentType::WEATHER->value:
                $this->validate(['location' => 'required|string|max:255']);
                $contentData['location'] = $this->location;

                break;
            case ContentType::SOCIAL->value:
                $this->validate([
                    'platform' => 'required|string|max:255',
                    'handle'   => 'required|string|max:255',
                ]);
                $contentData['platform'] = $this->platform;
                $contentData['handle'] = $this->handle;

                break;
            case ContentType::CALENDAR->value:
                $this->validate(['calendar_url' => 'required|string|url|max:2048']);
                $contentData['calendar_url'] = $this->calendar_url;

                break;
            case ContentType::CUSTOM->value:
                $this->validate(['custom_html' => 'required|string']);
                $contentData['html'] = $this->custom_html;

                break;
        }

        $this->content->update([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'type'        => $validated['type'],
            'screen_id'   => $validated['screen_id'],
            'status'      => $validated['status'],
            'duration'    => $validated['duration'],
            'order'       => $validated['order'],
            'start_date'  => $validated['start_date'],
            'end_date'    => $validated['end_date'],
            'data'        => $contentData,
            'settings'    => $validated['settings'],
        ]);

        $this->dispatch('notify', [
            'title'   => 'Success!',
            'message' => 'Content updated successfully.',
            'type'    => 'success',
        ]);

        $this->dispatch('content-updated', id: $this->content->id);
        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->editContentModal = false;
        $this->reset([
            'name', 'description', 'type', 'status', 'duration',
            'order', 'start_date', 'end_date', 'image_file', 'url',
            'html_content', 'feed_url', 'location', 'platform', 'handle', 'calendar_url', 'custom_html', 'settings',
        ]);
        $this->content = null;
        $this->showAdvancedSettings = false;
    }
}
