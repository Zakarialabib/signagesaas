<?php

declare(strict_types=1);

namespace App\Livewire\Content\Templates;

use App\Tenant\Models\Template;
use App\Tenant\Models\Content;
use App\Enums\ContentType;
use App\Services\OnboardingProgressService;
use App\Tenant\Models\OnboardingProgress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

final class TemplateConfigurator extends Component
{
    public Template $template;
    public array $zoneSettings = [];
    public array $zoneContent = [];
    public bool $snapToGrid = true;
    public int $gridSize = 5;

    // Properties for generic content selector modal
    public bool $showGenericContentSelectorModal = false;
    public ?string $currentZoneIdForGenericSelector = null;

    // Properties for widget data info modal
    public bool $showWidgetDataInfoModal = false;
    public ?string $currentZoneIdForWidgetInfoModal = null;

    protected $listeners = [
        'content-assigned'   => 'handleContentAssigned',
        'zone-updated'       => 'handleZoneUpdated',
        'zone-added'         => 'handleZoneAdded',
        'zone-deleted'       => 'handleZoneDeleted',
        'widgetContentSaved' => 'handleWidgetContentSaved',
    ];

    public function mount(Template $template): void
    {
        $this->template = $template;
        $this->loadZoneSettings();
    }

    private function loadZoneSettings(): void
    {
        if (isset($this->template->layout['zones']) && is_array($this->template->layout['zones'])) {
            foreach ($this->template->layout['zones'] as $zoneId => $zone) {
                // Ensure $zoneId is a string, as it might be an integer if keys are numeric
                $zoneIdStr = (string) $zoneId;
                $this->zoneSettings[$zoneIdStr] = $zone['settings'] ?? [
                    'duration'      => 10,
                    'transition'    => 'fade',
                    'background'    => '#ffffff',
                    'padding'       => '0',
                    'border-radius' => '0',
                ];
                $this->zoneContent[$zoneIdStr] = Content::find($zone['content_id'] ?? null);
            }
        }
    }

    public function initiateContentSelection(string $zoneId): void
    {
        $zone = $this->template->layout['zones'][$zoneId] ?? null;

        if ($zone && isset($zone['widget_type']) && ! empty($zone['widget_type'])) {
            $this->dispatch(
                'openWidgetDataEditor',
                zoneId: $zoneId,
                widgetType: $zone['widget_type'],
                contentId: ($zone['content_id'] ?? null)
            )->to('App.Livewire.Content.Widgets.WidgetDataEditorModal');
            $this->showGenericContentSelectorModal = false; // Ensure other modal is closed
        } else {
            // Proceed with generic content selection
            $this->currentZoneIdForGenericSelector = $zoneId;
            $this->showGenericContentSelectorModal = true;
        }
    }

    public function closeGenericContentSelectorModal(): void
    {
        $this->showGenericContentSelectorModal = false;
        $this->currentZoneIdForGenericSelector = null;
    }

    // Methods for Widget Data Info Modal
    public function openWidgetInfoModal(string $zoneId): void
    {
        $this->currentZoneIdForWidgetInfoModal = $zoneId;
        $this->showWidgetDataInfoModal = true;
    }

    public function closeWidgetInfoModal(): void
    {
        $this->showWidgetDataInfoModal = false;
        $this->currentZoneIdForWidgetInfoModal = null;
    }

    public function getZoneContentTypes(string $zoneId): array
    {
        $zone = $this->template->layout['zones'][$zoneId] ?? null;

        if ( ! $zone) {
            return ContentType::cases();
        }

        // If a specific widget_type is defined, it might restrict content types further
        // or imply a specific data structure rather than a simple content type.
        // For now, we'll base it on the zone's 'type' if widget_type isn't driving selection.
        if (isset($zone['widget_type']) && ! empty($zone['widget_type'])) {
            // For widget zones, content type might be less relevant than the widget's own data.
            // Often, these might accept a generic 'Custom' or 'JSON' type, or a specific type
            // that the widget processor understands (e.g., 'ProductListWidget' might expect JSON data).
            // The subtask implies we're moving towards specialized forms, not generic content types for widgets.
            // For now, let's assume any content type is possible if it's a widget,
            // as the data structure is key. Or, return a specific type if known.
            // This part might need refinement based on how widgets are actually fed data.
            // For the generic selector path (non-widget), the original logic is fine.
        }

        return match ($zone['type'] ?? 'content') { // Default to 'content' if type isn't set
            'image'    => [ContentType::IMAGE],
            'video'    => [ContentType::VIDEO],
            'text'     => [ContentType::TEXT, ContentType::HTML],
            'calendar' => [ContentType::CALENDAR],
            'weather'  => [ContentType::WEATHER],
            'social'   => [ContentType::SOCIAL],
            'widget'   => ContentType::cases(), // Widgets might accept various underlying data types
            default    => ContentType::cases(),
        };
    }

    #[On('content-assigned')]
    public function handleContentAssigned($data): void // Can be called directly or by handleWidgetContentSaved
    {
        $zoneId = $data['zoneId'] ?? null; // Use null coalescing for safety
        $contentId = $data['contentId'] ?? null;

        if ( ! $zoneId || ! $contentId) {
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => 'Invalid data received for content assignment.',
            ]);

            return;
        }

        $layout = $this->template->layout;

        if (isset($layout['zones'][$zoneId])) {
            $layout['zones'][$zoneId]['content_id'] = $contentId;

            $this->template->update(['layout' => $layout]);
            $this->zoneContent[$zoneId] = Content::find($contentId);

            // NEW: Check and mark onboarding step
            // Ensure $content is loaded to check its type, or assume any assignment counts
            $assignedContent = $this->zoneContent[$zoneId];

            if ($assignedContent) { // Check if content was successfully assigned
                $onboardingProgress = OnboardingProgress::firstOrCreate(['tenant_id' => $this->template->tenant_id]); // Assuming template has tenant_id

                if ( ! $onboardingProgress->widget_content_assigned_to_template) {
                    // Optional: Check if $assignedContent is specifically widget content if desired
                    // if (isset($assignedContent->content_data['widget_type'])) {
                    $onboardingProgress->markWidgetContentAssignedToTemplateCompleted();
                    // }
                }
            }

            $this->dispatch('zone-content-updated', [
                'zoneId'    => $zoneId,
                'contentId' => $contentId,
            ]);

            // Close the generic selector modal if it was open for this zone
            if ($this->currentZoneIdForGenericSelector === $zoneId) {
                $this->closeGenericContentSelectorModal();
            }
        } else {
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => "Zone ID '{$zoneId}' not found in layout.",
            ]);
        }
    }

    public function handleWidgetContentSaved($data): void
    {
        // This method simply calls handleContentAssigned,
        // as the logic for updating the layout is the same.
        $this->handleContentAssigned($data);

        // Mark onboarding step as complete
        $tenantId = Auth::user()?->tenant_id ?? $this->template->tenant_id; // Ensure we have tenant context

        if ($tenantId) {
            $onboardingProgress = OnboardingProgress::firstOrCreate(['tenant_id' => $tenantId]);

            if ( ! $onboardingProgress->widget_content_assigned_to_template) {
                app(OnboardingProgressService::class)->completeStep($onboardingProgress, 'widget_content_assigned_to_template');
            }
        }
    }

    public function updateZoneSettings(string $zoneId): void
    {
        $layout = $this->template->layout;

        if (isset($layout['zones'][$zoneId], $this->zoneSettings[$zoneId])) {
            $layout['zones'][$zoneId]['settings'] = $this->zoneSettings[$zoneId];
            $this->template->update(['layout' => $layout]);

            $this->dispatch('notify', [
                'type'    => 'success',
                'message' => "Settings for zone '{$layout['zones'][$zoneId]['name']}' updated.",
            ]);
            // Optionally, dispatch an event if the live preview needs to react to setting changes
            $this->dispatch('zone-settings-updated', ['zoneId' => $zoneId, 'settings' => $this->zoneSettings[$zoneId]]);
        } else {
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => "Zone ID '{$zoneId}' not found for settings update.",
            ]);
        }
    }

    public function addZone(): void
    {
        $layout = $this->template->layout;
        $newZoneId = 'zone_'.(isset($layout['zones']) && is_array($layout['zones']) ? count($layout['zones']) : 0 + 1).'_'.time(); // Ensure unique ID

        if ( ! isset($layout['zones']) || ! is_array($layout['zones'])) {
            $layout['zones'] = [];
        }

        $defaultSettings = [
            'duration'      => 10,
            'transition'    => 'fade',
            'background'    => '#ffffff',
            'padding'       => '0px',
            'border-radius' => '0px',
        ];

        $newZoneData = [
            'id'                => $newZoneId,
            'name'              => 'New Zone '.((isset($layout['zones']) && is_array($layout['zones']) ? count($layout['zones']) : 0) + 1),
            'type'              => 'content',
            'x_percentage'      => 0,
            'y_percentage'      => 0,
            'width_percentage'  => 30,
            'height_percentage' => 20,
            'settings'          => $defaultSettings,
            'content_id'        => null,
            'widget_type'       => null,
        ];

        $layout['zones'][$newZoneId] = $newZoneData;
        $this->template->update(['layout' => $layout]);

        // Initialize settings and content for the new zone in the component's state
        $this->zoneSettings[$newZoneId] = $defaultSettings;
        $this->zoneContent[$newZoneId] = null;

        $this->dispatch('zone-added', ['zoneId' => $newZoneId, 'zone' => $newZoneData]);
        $this->dispatch('notify', ['type' => 'success', 'message' => 'New zone added.']);
    }

    public function updateZoneTypeProperties(string $zoneId, string $newType, ?string $newWidgetType): void
    {
        $layout = $this->template->layout;

        if (isset($layout['zones'][$zoneId])) {
            $layout['zones'][$zoneId]['type'] = $newType;
            $layout['zones'][$zoneId]['widget_type'] = ($newType === 'widget' && $newWidgetType) ? $newWidgetType : null;

            // If changing to a non-widget type, or widget_type is cleared, also clear content_id if it's widget-specific
            if ($newType !== 'widget' || ! $newWidgetType) {
                // Optionally clear content_id if it was widget-specific.
                // $layout['zones'][$zoneId]['content_id'] = null;
                // $this->zoneContent[$zoneId] = null;
            }

            $this->template->update(['layout' => $layout]);
            $this->loadZoneSettings(); // Reload zone settings to reflect potential changes
            $this->dispatch('notify', ['type' => 'success', 'message' => "Zone '{$layout['zones'][$zoneId]['name']}' type updated."]);
            $this->dispatch('zone-type-updated', ['zoneId' => $zoneId, 'newType' => $newType, 'newWidgetType' => $newWidgetType]);
        }
    }

    public function getAvailableWidgetTypesForZonesProperty(): array
    {
        // This could be fetched from a service, config, or defined directly
        // For now, let's mirror what WidgetTypeSelector might use (simplified)
        return [
            'MenuWidget'          => 'Menu Board',
            'RetailProductWidget' => 'Retail Products',
            'WeatherWidget'       => 'Weather Display',
            'ClockWidget'         => 'Clock Display',
            'AnnouncementWidget'  => 'Announcements',
            'RssFeedWidget'       => 'RSS Feed',
            'CalendarWidget'      => 'Calendar & Events',
            // Add other widget identifiers and their friendly names
        ];
    }

    public function deleteZone(string $zoneId): void
    {
        $layout = $this->template->layout;

        if (isset($layout['zones'][$zoneId])) {
            unset($layout['zones'][$zoneId]);
            $this->template->update(['layout' => $layout]);
            unset($this->zoneSettings[$zoneId], $this->zoneContent[$zoneId]);

            $this->dispatch('zone-deleted', ['zoneId' => $zoneId]);
        } else {
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => "Zone ID '{$zoneId}' not found for deletion.",
            ]);
        }
    }

    // Updated to use x_percentage, y_percentage etc.
    public function updateZonePosition(string $zoneId, float $x, float $y, float $width, float $height): void
    {
        // This method expects percentages. Ensure the calling JS provides percentages.
        if ($this->snapToGrid && $this->gridSize > 0) {
            $x = round($x / $this->gridSize) * $this->gridSize;
            $y = round($y / $this->gridSize) * $this->gridSize;
            $width = round($width / $this->gridSize) * $this->gridSize;
            $height = round($height / $this->gridSize) * $this->gridSize;
        }

        // Clamp values to prevent zones from going out of bounds or having negative dimensions
        $x = max(0, min($x, 100 - $width));
        $y = max(0, min($y, 100 - $height));
        $width = max($this->gridSize > 0 ? $this->gridSize : 5, min($width, 100)); // Min width of gridsize or 5%
        $height = max($this->gridSize > 0 ? $this->gridSize : 5, min($height, 100)); // Min height of gridsize or 5%

        $layout = $this->template->layout;

        if (isset($layout['zones'][$zoneId])) {
            $layout['zones'][$zoneId]['x_percentage'] = $x;
            $layout['zones'][$zoneId]['y_percentage'] = $y;
            $layout['zones'][$zoneId]['width_percentage'] = $width;
            $layout['zones'][$zoneId]['height_percentage'] = $height;

            $this->template->update(['layout' => $layout]);

            $this->dispatch('zone-position-updated', [
                'zoneId'   => $zoneId,
                'position' => [
                    'x_percentage'      => $x,
                    'y_percentage'      => $y,
                    'width_percentage'  => $width,
                    'height_percentage' => $height,
                ],
            ]);
        } else {
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => "Zone ID '{$zoneId}' not found for position update.",
            ]);
        }
    }

    public function toggleSnapToGrid(): void
    {
        $this->snapToGrid = ! $this->snapToGrid;
    }

    public function updateGridSize(string $size): void // Changed to string due to select value
    {
        $this->gridSize = max(1, min(20, (int) $size));
    }

    public function render()
    {
        // Ensure zones is always an array
        $zones = $this->template->layout['zones'] ?? [];

        if ( ! is_array($zones)) {
            $zones = [];
        }

        // Filter out any non-array zones just in case of data corruption
        $zones = array_filter($zones, function ($zone) {
            return is_array($zone) && isset($zone['id']);
        });

        // Re-key array if necessary, though using zone['id'] as key is typical in the component
        // For the view, passing $zones directly should be fine if it's keyed by zone ID.
        // If it's numerically indexed, ensure JS and Blade use correct references.
        // The current code seems to use $zoneId which is the key, so $zones should be associative.

        return view('livewire.content.templates.template-configurator', [
            'zones' => $zones,
        ]);
    }

    public function updateZoneTypeProperties(string $zoneId, string $newType, ?string $newWidgetType): void
    {
        $layout = $this->template->layout;

        if (isset($layout['zones'][$zoneId])) {
            $layout['zones'][$zoneId]['type'] = $newType;
            $layout['zones'][$zoneId]['widget_type'] = ($newType === 'widget' && $newWidgetType) ? $newWidgetType : null;

            // If changing to a non-widget type, or widget_type is cleared, also clear content_id if it's widget-specific
            if ($newType !== 'widget' || ! $newWidgetType) {
                // Optionally clear content_id if it was widget-specific.
                // $layout['zones'][$zoneId]['content_id'] = null;
                // $this->zoneContent[$zoneId] = null;
            }

            $this->template->update(['layout' => $layout]);
            $this->loadZoneSettings(); // Reload zone settings to reflect potential changes
            $this->dispatch('notify', ['type' => 'success', 'message' => "Zone '{$layout['zones'][$zoneId]['name']}' type updated."]);
            $this->dispatch('zone-type-updated', ['zoneId' => $zoneId, 'newType' => $newType, 'newWidgetType' => $newWidgetType]);
        }
    }

    public function getAvailableWidgetTypesForZonesProperty(): array
    {
        // This could be fetched from a service, config, or defined directly
        // For now, let's mirror what WidgetTypeSelector might use (simplified)
        return [
            'MenuWidget'          => 'Menu Board',
            'RetailProductWidget' => 'Retail Products',
            'WeatherWidget'       => 'Weather Display',
            'ClockWidget'         => 'Clock Display',
            'AnnouncementWidget'  => 'Announcements',
            'RssFeedWidget'       => 'RSS Feed',
            'CalendarWidget'      => 'Calendar & Events',
            // Add other widget identifiers and their friendly names
        ];
    }
}
