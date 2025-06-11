<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use App\Models\Tenant;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.super-admin')]
#[Title('Create New Tenant')]
final class CreateTenant extends Component
{
    public string $name = '';
    public string $email = '';
    public string $domain = '';
    public string $plan_id = '';

    public array $plans = [];

    public function mount(): void
    {
        if ( ! Auth::guard('superadmin')->check()) {
            $this->redirect(route('superadmin.login'));
        }

        $this->loadPlans();
    }

    protected function loadPlans(): void
    {
        $this->plans = [
            'free'       => 'Free',
            'basic'      => 'Basic',
            'pro'        => 'Professional',
            'enterprise' => 'Enterprise',
        ];
    }

    public function saveTenant(): void
    {
        $rules = [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'domain'  => 'required|string|max:255|unique:domains,domain',
            'plan_id' => 'required|string|max:50',
        ];

        $this->validate($rules);

        $tenantId = Str::slug($this->name);

        $tenant = Tenant::create([
            'id'    => $tenantId,
            'name'  => $this->name,
            'email' => $this->email,
            'plan'  => $this->plan_id,
            'data'  => [
                'settings' => [
                    'timezone' => 'UTC',
                    'language' => 'en',
                ],
            ],
        ]);

        $tenant->domains()->create(['domain' => $this->domain]);

        session()->flash('success', 'Tenant created successfully!');

        $this->redirect(route('superadmin.tenants'));
    }

    public function render()
    {
        return view('livewire.super-admin.create-tenant');
    }
}