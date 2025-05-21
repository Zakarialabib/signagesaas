<?php

declare(strict_types=1);

namespace App\Livewire\Layout;

use App\Tenant\Models\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class LayoutManager extends Component
{
    use WithPagination;

    public string $name = '';
    public string $description = '';
    public array $dimensions = [
        'width'  => 1920,
        'height' => 1080,
    ];
    public array $settings = [];
    public bool $is_active = true;
    public ?Layout $editing = null;

    protected $rules = [
        'name'              => 'required|string|max:255',
        'description'       => 'nullable|string',
        'dimensions.width'  => 'required|integer|min:1',
        'dimensions.height' => 'required|integer|min:1',
        'is_active'         => 'boolean',
    ];

    public function render()
    {
        $layouts = Layout::query()
            ->latest()
            ->paginate(10);

        return view('livewire.layout.layout-manager', [
            'layouts' => $layouts,
        ]);
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'dimensions', 'settings', 'is_active', 'editing']);
    }

    public function store(): void
    {
        $this->validate();

        Layout::create([
            'name'        => $this->name,
            'description' => $this->description,
            'dimensions'  => $this->dimensions,
            'settings'    => $this->settings,
            'is_active'   => $this->is_active,
        ]);

        $this->reset();
        $this->dispatch('layout-saved');
    }

    public function edit(Layout $layout): void
    {
        $this->editing = $layout;
        $this->name = $layout->name;
        $this->description = $layout->description ?? '';
        $this->dimensions = $layout->dimensions;
        $this->settings = $layout->settings ?? [];
        $this->is_active = $layout->is_active;
    }

    public function update(): void
    {
        if ( ! $this->editing) {
            return;
        }

        $this->validate();

        $this->editing->update([
            'name'        => $this->name,
            'description' => $this->description,
            'dimensions'  => $this->dimensions,
            'settings'    => $this->settings,
            'is_active'   => $this->is_active,
        ]);

        $this->reset();
        $this->dispatch('layout-updated');
    }

    public function delete(Layout $layout): void
    {
        $layout->delete();
        $this->dispatch('layout-deleted');
    }
}
