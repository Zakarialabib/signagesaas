<?php

declare(strict_types=1);

namespace App\Livewire\Content;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use App\Services\OnboardingProgressService;
use App\Tenant\Models\Content;
use App\Tenant\Models\OnboardingProgress;
use App\Tenant\Models\Screen;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

final class ContentCreate extends Component
{
    use WithFileUploads;

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

    // Custom content (HTML)
    #[Validate('nullable|string')]
    public ?string $custom_html = null;

    public bool $createContentModal = false;

    #[On('createContentModal')]
    public function openModal(): void
    {
        $this->createContentModal = true;
        $this->authorize('create', Content::class);
    }

    #[On('create-content-with-screen')]
    public function preSelectScreen(string $screenId): void
    {
        if ($screenId && Screen::where('id', $screenId)->exists()) {
            $this->screen_id = $screenId;
        }

        $this->openModal();
    }

    public function render()
    {
        return view('livewire.content.content-create', [
            'contentTypes'    => ContentType::options(),
            'contentStatuses' => ContentStatus::options(),
            'screens'         => Screen::where('status', 'active')
                ->with('device')
                ->get(),
        ]);
    }

    public function save(): void
    {
        $this->authorize('create', Content::class);

        $data = $this->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type'        => 'required|string|in:'.implode(',', ContentType::values()),
            'screen_id'   => 'required|uuid|exists:screens,id',
            'status'      => 'required|string|in:active,inactive',
            'duration'    => 'required|integer|min:5|max:300',
            'order'       => 'nullable|integer|min:0',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $contentData = [];

        switch ($this->type) {
            case ContentType::IMAGE->value:
                $this->validate(['image_file' => 'required|image|max:10240']);
                $path = $this->image_file->store('content/images', 'public');
                $contentData['url'] = Storage::url($path);
                $contentData['path'] = $path;

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

        $screen = Screen::findOrFail($this->screen_id);

        $content = new Content([
            'name'        => $this->name,
            'description' => $this->description,
            'type'        => $this->type,
            'screen_id'   => $this->screen_id,
            'status'      => $this->status,
            'duration'    => $this->duration,
            'order'       => $this->order,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'data'        => $contentData,
            'tenant_id'   => $screen->tenant_id,
        ]);

        $content->save();

        // Mark onboarding step as complete
        $onboardingProgress = OnboardingProgress::firstOrCreate(['tenant_id' => $content->tenant_id]);

        if ( ! $onboardingProgress->first_content_uploaded) {
            app(OnboardingProgressService::class)->completeStep($onboardingProgress, \App\Enums\OnboardingStep::FIRST_CONTENT_UPLOADED->value);
        }

        $this->dispatch('content-created');

        $this->reset([
            'name',
            'description',
            'type',
            'screen_id',
            'status',
            'duration',
            'order',
            'start_date',
            'end_date',
            'image_file',
            'url',
            'html_content',
            'feed_url',
            'location',
            'platform',
            'handle',
            'calendar_url',
            'custom_html',
        ]);
        $this->createContentModal = false;
    }

    public function closeModal(): void
    {
        $this->createContentModal = false;
        $this->reset([
            'name',
            'description',
            'type',
            'screen_id',
            'status',
            'duration',
            'order',
            'start_date',
            'end_date',
            'image_file',
            'url',
            'html_content',
            'feed_url',
            'location',
            'platform',
            'handle',
            'calendar_url',
            'custom_html',
        ]);
    }
}
