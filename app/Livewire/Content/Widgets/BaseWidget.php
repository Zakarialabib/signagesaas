<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Component;

abstract class BaseWidget extends Component
{
    public array $data = [];
    public array $schema = [];
    
    public function mount(array $data = []): void
    {
        $this->data = $data;
        $this->schema = app(WidgetRegistry::class)->getSchema(static::class);
    }
    
    abstract public function render();
}