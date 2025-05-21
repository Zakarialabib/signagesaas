<?php

declare(strict_types=1);

namespace App\Livewire\Zone;

use App\Tenant\Models\Layout;
use App\Tenant\Models\Zone;
use Livewire\Component;
use Livewire\WithPagination;

class ZoneManager extends Component
{
    use WithPagination;

    public string $name = '';
    public string $type = 'content';
    public ?int $layout_id = null;
    public array $position = [
        'x' => 0,
        'y' => 0,
    ];
    public array $dimensions = [
        'width'  => 300,
        'height' => 200,
    ];
    public array $settings = [];
    public bool $is_active = true;
    public ?Zone $editing = null;

    protected $rules = [
        'name'              => 'required|string|max:255',
        'type'              => 'required|string|in:content,widget',
        'layout_id'         => 'nullable|exists:layouts,id',
        'position.x'        => 'required|integer|min:0',
        'position.y'        => 'required|integer|min:0',
        'dimensions.width'  => 'required|integer|min:1',
        'dimensions.height' => 'required|integer|min:1',
        'is_active'         => 'boolean',
    ];

    public function render()
    {
        $zones = Zone::query()
            ->with('layout')
            ->latest()
            ->paginate(10);

        $layouts = Layout::where('is_active', true)->get();

        return view('livewire.zone.zone-manager', [
            'zones'   => $zones,
            'layouts' => $layouts,
        ]);
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['name', 'type', 'layout_id', 'position', 'dimensions', 'settings', 'is_active', 'editing']);
    }

    public function store(): void
    {
        $this->validate();

        Zone::create([
            'name'       => $this->name,
            'type'       => $this->type,
            'layout_id'  => $this->layout_id,
            'position'   => $this->position,
            'dimensions' => $this->dimensions,
            'settings'   => $this->settings,
            'is_active'  => $this->is_active,
        ]);

        $this->reset();
        $this->dispatch('zone-saved');
    }

    public function edit(Zone $zone): void
    {
        $this->editing = $zone;
        $this->name = $zone->name;
        $this->type = $zone->type;
        $this->layout_id = $zone->layout_id;
        $this->position = $zone->position;
        $this->dimensions = $zone->dimensions;
        $this->settings = $zone->settings ?? [];
        $this->is_active = $zone->is_active;
    }

    public function update(): void
    {
        if ( ! $this->editing) {
            return;
        }

        $this->validate();

        $this->editing->update([
            'name'       => $this->name,
            'type'       => $this->type,
            'layout_id'  => $this->layout_id,
            'position'   => $this->position,
            'dimensions' => $this->dimensions,
            'settings'   => $this->settings,
            'is_active'  => $this->is_active,
        ]);

        $this->reset();
        $this->dispatch('zone-updated');
    }

    public function delete(Zone $zone): void
    {
        $zone->delete();
        $this->dispatch('zone-deleted');
    }

    public function getLayoutZones(?int $layoutId = null): array
    {
        if ( ! $layoutId) {
            return [];
        }

        return Zone::where('layout_id', $layoutId)
            ->get()
            ->toArray();
    }
}
