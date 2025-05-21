<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class BaseWidget extends Component
{
    public function __construct(
        public string $title = '',
        public string $category = '',
        public string $icon = '',
        public bool $isLoading = false,
        public ?string $error = null,
        public int $refreshInterval = 0,
        
    ) {
    }

    public function render(): View
    {
        return view('components.base-widget', [
            'title' => $this->title,
            'category' => $this->category,
            'icon' => $this->icon,
            'isLoading' => $this->isLoading,
            'error' => $this->error,
        ]);
    }
}
