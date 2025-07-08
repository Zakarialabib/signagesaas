<?php

declare(strict_types=1);

namespace App\Livewire\Zone;

use App\Tenant\Models\Zone;
use Livewire\Component;
use Livewire\WithPagination;

class ZoneManager extends Component
{
    use WithPagination;

    public ?Zone $editing = null;

    public string $name = '';
    public string $description = '';
    public string $type = 'GENERAL_AREA'; // Default place type
    public ?float $x = null;
    public ?float $y = null;
    public ?float $width = null;
    public ?float $height = null;
    public array $style_data = [];
    public array $metadata = [];

    // For managing style_data and metadata as strings in form
    public string $style_data_json = '{}';
    public string $metadata_json = '{}';


    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'        => 'required|string|max:100', // Consider a predefined list or Enum later
            'x'           => 'nullable|numeric',
            'y'           => 'nullable|numeric',
            'width'       => 'nullable|numeric|min:0',
            'height'      => 'nullable|numeric|min:0',
            'style_data_json' => ['nullable', 'json', function ($attribute, $value, $fail) {
                if (json_decode($value) === null && !empty($value)) {
                    $fail($attribute.' must be a valid JSON string.');
                }
            }],
            'metadata_json'   => ['nullable', 'json', function ($attribute, $value, $fail) {
                if (json_decode($value) === null && !empty($value)) {
                    $fail($attribute.' must be a valid JSON string.');
                }
            }],
        ];
    }

    public function mount(): void
    {
        $this->resetForm();
    }

    public function render()
    {
        $zones = Zone::query()
            // ->with('layout') // Layout relationship removed
            ->latest()
            ->paginate(10);

        return view('livewire.zone.zone-manager', [
            'zones'   => $zones,
        ]);
    }

    private function resetForm(): void
    {
        $this->resetValidation();
        $this->reset([
            'editing', 'name', 'description', 'type',
            'x', 'y', 'width', 'height',
            'style_data', 'metadata',
            'style_data_json', 'metadata_json'
        ]);
        $this->type = 'GENERAL_AREA'; // Reset to default
        $this->style_data_json = '{}';
        $this->metadata_json = '{}';
    }

    public function create(): void
    {
        $this->resetForm();
    }

    private function parseJsonFields(): void
    {
        $this->style_data = json_decode($this->style_data_json, true) ?: [];
        $this->metadata = json_decode($this->metadata_json, true) ?: [];
    }

    public function store(): void
    {
        $this->parseJsonFields();
        $this->validate();

        Zone::create([
            'name'        => $this->name,
            'description' => $this->description,
            'type'        => $this->type,
            'x'           => $this->x,
            'y'           => $this->y,
            'width'       => $this->width,
            'height'      => $this->height,
            'style_data'  => $this->style_data,
            'metadata'    => $this->metadata,
        ]);

        $this->dispatch('zone-saved');
        $this->resetForm(); // Reset form after saving
    }

    public function edit(Zone $zone): void
    {
        $this->resetValidation();
        $this->editing = $zone;
        $this->name = $zone->name;
        $this->description = $zone->description ?? '';
        $this->type = $zone->type;
        $this->x = $zone->x;
        $this->y = $zone->y;
        $this->width = $zone->width;
        $this->height = $zone->height;
        $this->style_data = $zone->style_data ?? [];
        $this->metadata = $zone->metadata ?? [];

        $this->style_data_json = json_encode($this->style_data, JSON_PRETTY_PRINT);
        $this->metadata_json = json_encode($this->metadata, JSON_PRETTY_PRINT);
    }

    public function update(): void
    {
        if ( ! $this->editing) {
            return;
        }
        $this->parseJsonFields();
        $this->validate();

        $this->editing->update([
            'name'        => $this->name,
            'description' => $this->description,
            'type'        => $this->type,
            'x'           => $this->x,
            'y'           => $this->y,
            'width'       => $this->width,
            'height'      => $this->height,
            'style_data'  => $this->style_data,
            'metadata'    => $this->metadata,
        ]);

        $this->dispatch('zone-updated');
        $this->resetForm(); // Reset form after updating
    }

    public function delete(Zone $zone): void
    {
        // Screens associated with this zone will have their zone_id set to null due to DB constraint.
        $zone->delete();
        $this->dispatch('zone-deleted');
    }

    // getLayoutZones() method removed as it's no longer relevant.
}
