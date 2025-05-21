<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.super-admin')]
#[Title('SuperAdmin Dashboard')]
final class Dashboard extends Component
{
    public function mount(): void
    {
        // Ensure the user is authenticated as a SuperAdmin
        if ( ! Auth::guard('superadmin')->check()) {
            $this->redirect(route('superadmin.login'));
        }
    }

    public function render()
    {
        return view('livewire.super-admin.dashboard');
    }
}
