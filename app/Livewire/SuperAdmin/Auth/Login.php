<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

#[Layout('layouts.auth')]
final class Login extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required')]
    public string $password = '';

    public bool $remember = false;

    public function mount(): void
    {
        // Redirect if already logged in
        if (Auth::guard('superadmin')->check()) {
            $this->redirect(route('superadmin.dashboard'));
        }
    }

    public function login(): void
    {
        $credentials = $this->validate();

        if (Auth::guard('superadmin')->attempt($credentials, $this->remember)) {
            session()->regenerate();
            $this->redirect(route('superadmin.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    public function render()
    {
        return view('livewire.super-admin.auth.login');
    }
}
