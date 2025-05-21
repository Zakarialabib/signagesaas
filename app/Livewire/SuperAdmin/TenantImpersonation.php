<?php

declare(strict_types=1);

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;

final class TenantImpersonation extends Component
{
    public bool $isImpersonating = false;

    public function mount(): void
    {
        $this->isImpersonating = Session::has('impersonated_tenant');
    }

    /** Determine if the admin is currently impersonating a tenant */
    #[Computed]
    public function isImpersonating(): bool
    {
        return Session::has('impersonated_tenant');
    }

    /** Stop impersonating the current tenant and redirect to the tenants management page */
    public function stopImpersonating(): void
    {
        Session::forget('impersonated_tenant');
        $this->isImpersonating = false;

        $this->redirect(route('superadmin.tenants'));
    }

    /** Render the component */
    public function render()
    {
        return view('livewire.super-admin.tenant-impersonation');
    }
}
