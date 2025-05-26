<?php

declare(strict_types=1);

namespace App\Livewire\Screens;

use App\Tenant\Models\Layout;
use App\Tenant\Models\Screen;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout as LivewireLayout;
use Livewire\Attributes\Title;
use Exception;

#[LivewireLayout('layouts.app')]
#[Title('Screen Layouts & Zones')]
final class ScreenConcepts extends Component
{
    /**
     * All available layouts for the tenant (with zones loaded).
     *
     * @var \Illuminate\Database\Eloquent\Collection<int, Layout>
     */
    public $layouts;

    /**
     * The currently selected layout (with zones loaded).
     *
     * @var Layout|null
     */
    public $selectedLayout = null;

    /**
     * The zones for the selected layout (array for Alpine interop).
     *
     * @var array<int, array<string, mixed>>
     */
    public array $zones = [];

    /** The selected layout ID (string|null). */
    public ?string $selectedLayoutId = null;

    /** Mount the component and load layouts. */
    public function mount(?string $layoutId = null): void
    {
        $this->authorize('viewAny', Layout::class);
        $this->loadLayouts();

        if ($layoutId) {
            $this->selectLayout($layoutId);
        } elseif ($this->layouts->count() > 0) {
            $this->selectLayout($this->layouts->first()->id);
        } else {
            // Create a default layout if none exists
            $this->createDefaultLayout();
        }
    }

    /** Load layouts with optimization for performance */
    private function loadLayouts(): void
    {
        $this->layouts = Layout::with(['zones' => function ($query) {
            $query->orderBy('order');
        }])->get();
    }

    /** Create a default layout if none exists */
    private function createDefaultLayout(): void
    {
        try {
            DB::transaction(function () {
                $layout = Layout::create([
                    'tenant_id'    => tenant('id'),
                    'name'         => 'Default Layout',
                    'description'  => 'Default layout with main content and ticker zones',
                    'aspect_ratio' => '16:9',
                    'data'         => [],
                ]);

                // Add default zones
                $layout->zones()->createMany([
                    [
                        'name'   => 'Main Content',
                        'type'   => 'content',
                        'x'      => 0,
                        'y'      => 0,
                        'width'  => 100,
                        'height' => 80,
                        'order'  => 0,
                        'data'   => [],
                    ],
                    [
                        'name'   => 'Ticker',
                        'type'   => 'ticker',
                        'x'      => 0,
                        'y'      => 80,
                        'width'  => 100,
                        'height' => 20,
                        'order'  => 1,
                        'data'   => [],
                    ],
                ]);

                $this->loadLayouts();
                $this->selectLayout($layout->id);
            });
        } catch (Exception $e) {
            session()->flash('flash.banner', 'Error creating default layout: '.$e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
        }
    }

    /** Select a layout by ID and load its zones. */
    public function selectLayout(string $layoutId): void
    {
        try {
            $this->selectedLayoutId = $layoutId;
            $this->selectedLayout = Layout::with(['zones' => function ($query) {
                $query->orderBy('order');
            }])->find($layoutId);

            if ( ! $this->selectedLayout) {
                throw new Exception('Layout not found');
            }

            $this->zones = $this->selectedLayout->zones->map(function ($zone) {
                return $zone->toArray();
            })->toArray() ?? [];
        } catch (Exception $e) {
            session()->flash('flash.banner', 'Error loading layout: '.$e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
        }
    }

    /** Add a new zone to the current layout (not yet persisted). */
    public function addZone(): void
    {
        if ( ! $this->selectedLayout) {
            session()->flash('flash.banner', 'Please select a layout first.');
            session()->flash('flash.bannerStyle', 'danger');

            return;
        }

        $this->zones[] = [
            'name'   => 'New Zone',
            'type'   => 'content',
            'x'      => 0,
            'y'      => 0,
            'width'  => 50,
            'height' => 50,
            'order'  => count($this->zones),
            'data'   => [],
        ];
    }

    /** Update a zone's data by index. */
    public function updateZone(int $index, array $zoneData): void
    {
        if (isset($this->zones[$index])) {
            $this->zones[$index] = array_merge($this->zones[$index], $zoneData);
        }
    }

    /** Save the current layout's zones to the database. */
    public function saveLayout(): void
    {
        if ( ! $this->selectedLayout) {
            session()->flash('flash.banner', 'Please select or create a layout first.');
            session()->flash('flash.bannerStyle', 'danger');

            return;
        }

        try {
            DB::transaction(function () {
                if ( ! $this->selectedLayout) {
                    $this->selectedLayout = Layout::create([
                        'tenant_id'    => tenant('id'),
                        'name'         => 'New Layout',
                        'description'  => '',
                        'aspect_ratio' => '16:9',
                        'data'         => [],
                    ]);
                    $this->selectedLayoutId = $this->selectedLayout->id;
                }

                $this->selectedLayout->zones()->delete();

                foreach ($this->zones as $index => $zone) {
                    // Ensure order is properly set
                    $zone['order'] = $index;
                    $this->selectedLayout->zones()->create($zone);
                }

                $this->selectedLayout->refresh();
                $this->zones = $this->selectedLayout->zones->map(function ($zone) {
                    return $zone->toArray();
                })->toArray();

                $this->loadLayouts();

                session()->flash('flash.banner', 'Layout saved successfully.');
                session()->flash('flash.bannerStyle', 'success');
            });
        } catch (Exception $e) {
            session()->flash('flash.banner', 'Error saving layout: '.$e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
        }
    }

    /** Create a new empty layout. */
    public function createNewLayout(): void
    {
        try {
            DB::transaction(function () {
                $layout = Layout::create([
                    'tenant_id'    => tenant('id'),
                    'name'         => 'New Layout',
                    'description'  => 'Custom layout',
                    'aspect_ratio' => '16:9',
                    'data'         => [],
                ]);

                $this->loadLayouts();
                $this->selectLayout($layout->id);

                // Start with an empty main zone
                $this->zones = [
                    [
                        'name'   => 'Main Content',
                        'type'   => 'content',
                        'x'      => 0,
                        'y'      => 0,
                        'width'  => 100,
                        'height' => 100,
                        'order'  => 0,
                        'data'   => [],
                    ],
                ];

                $this->saveLayout();
            });
        } catch (Exception $e) {
            session()->flash('flash.banner', 'Error creating layout: '.$e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
        }
    }

    /** Render the unified screen concepts view. */
    public function render(): View
    {
        return view('livewire.screens.screen-concepts', [
            'layouts'        => $this->layouts,
            'zones'          => $this->zones,
            'selectedLayout' => $this->selectedLayout,
        ]);
    }
}
