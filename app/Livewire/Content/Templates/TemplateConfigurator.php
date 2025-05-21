<?php

declare(strict_types=1);

namespace App\Livewire\Content\Templates;

use App\Tenant\Models\Template;
use App\Tenant\Models\Content;
use App\Enums\ContentType;
use Livewire\Component;
use Livewire\Attributes\On;

final class TemplateConfigurator extends Component
{
    public Template $template;
    public array $zoneSettings = [];
    public array $zoneContent = [];
    public bool $snapToGrid = true;
    public int $gridSize = 5;

    protected $listeners = [
        'content-assigned' => 'handleContentAssigned',
        'zone-updated'     => 'handleZoneUpdated',
        'zone-added'       => 'handleZoneAdded',
        'zone-deleted'     => 'handleZoneDeleted',
    ];

    public function mount(Template $template): void
    {
        $this->template = $template;
        $this->loadZoneSettings();
    }

    private function loadZoneSettings(): void
    {
        foreach ($this->template->layout['zones'] ?? [] as $zoneId => $zone) {
            $this->zoneSettings[$zoneId] = $zone['settings'] ?? [
                'duration'      => 10,
                'transition'    => 'fade',
                'background'    => '#ffffff',
                'padding'       => '0',
                'border-radius' => '0',
            ];
            $this->zoneContent[$zoneId] = Content::find($zone['content_id'] ?? null);
        }
    }

    public function getZoneContentTypes(string $zoneId): array
    {
        $zone = $this->template->layout['zones'][$zoneId] ?? null;

        if ( ! $zone) {
            return ContentType::cases();
        }

        return match ($zone['type']) {
            'image'    => [ContentType::IMAGE],
            'video'    => [ContentType::VIDEO],
            'text'     => [ContentType::TEXT, ContentType::HTML],
            'calendar' => [ContentType::CALENDAR],
            'weather'  => [ContentType::WEATHER],
            'social'   => [ContentType::SOCIAL],
            default    => ContentType::cases(),
        };
    }

    #[On('content-assigned')]
    public function handleContentAssigned($data): void
    {
        $zoneId = $data['zoneId'];
        $contentId = $data['contentId'];

        $layout = $this->template->layout;
        $layout['zones'][$zoneId]['content_id'] = $contentId;

        $this->template->update(['layout' => $layout]);
        $this->zoneContent[$zoneId] = Content::find($contentId);

        $this->dispatch('zone-content-updated', [
            'zoneId'    => $zoneId,
            'contentId' => $contentId,
        ]);
    }

    public function updateZoneSettings(string $zoneId): void
    {
        $layout = $this->template->layout;
        $layout['zones'][$zoneId]['settings'] = $this->zoneSettings[$zoneId];

        $this->template->update(['layout' => $layout]);

        $this->dispatch('notify', [
            'type'    => 'success',
            'message' => 'Zone settings updated.',
        ]);
    }

    public function addZone(): void
    {
        $layout = $this->template->layout;
        $newZoneId = 'zone_'.(count($layout['zones'] ?? []) + 1);

        $layout['zones'][$newZoneId] = [
            'id'       => $newZoneId,
            'name'     => 'New Zone',
            'type'     => 'content',
            'x'        => 0,
            'y'        => 0,
            'width'    => 50,
            'height'   => 50,
            'settings' => [
                'duration'      => 10,
                'transition'    => 'fade',
                'background'    => '#ffffff',
                'padding'       => '0',
                'border-radius' => '0',
            ],
        ];

        $this->template->update(['layout' => $layout]);
        $this->loadZoneSettings();

        $this->dispatch('zone-added', [
            'zoneId' => $newZoneId,
        ]);
    }

    public function deleteZone(string $zoneId): void
    {
        $layout = $this->template->layout;
        unset($layout['zones'][$zoneId]);

        $this->template->update(['layout' => $layout]);
        unset($this->zoneSettings[$zoneId], $this->zoneContent[$zoneId]);

        $this->dispatch('zone-deleted', [
            'zoneId' => $zoneId,
        ]);
    }

    public function updateZonePosition(string $zoneId, float $x, float $y, float $width, float $height): void
    {
        if ($this->snapToGrid) {
            $x = round($x / $this->gridSize) * $this->gridSize;
            $y = round($y / $this->gridSize) * $this->gridSize;
            $width = round($width / $this->gridSize) * $this->gridSize;
            $height = round($height / $this->gridSize) * $this->gridSize;
        }

        $layout = $this->template->layout;
        $layout['zones'][$zoneId]['x'] = $x;
        $layout['zones'][$zoneId]['y'] = $y;
        $layout['zones'][$zoneId]['width'] = $width;
        $layout['zones'][$zoneId]['height'] = $height;

        $this->template->update(['layout' => $layout]);

        $this->dispatch('zone-position-updated', [
            'zoneId'   => $zoneId,
            'position' => compact('x', 'y', 'width', 'height'),
        ]);
    }

    public function toggleSnapToGrid(): void
    {
        $this->snapToGrid = ! $this->snapToGrid;
    }

    public function updateGridSize(int $size): void
    {
        $this->gridSize = max(1, min(20, $size));
    }

    public function render()
    {
        return view('livewire.content.templates.template-configurator', [
            'zones' => $this->template->layout['zones'] ?? [],
        ]);
    }
}
