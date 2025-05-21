<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

final class Logout extends Component
{
    public function logout(): void
    {
        Auth::guard('superadmin')->logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('superadmin.login'));
    }

    public function render()
    {
        return <<<'HTML'
            <button wire:click="logout" type="button" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-200">
                Logout
            </button>
            HTML;
    }
}
