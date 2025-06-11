<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Tenant;
use App\Tenant\Models\Plan;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
final class Home extends Component
{
    public string $billingCycle = 'monthly';
    public Collection $plans;

    public function mount()
    {
        $tenant = Tenant::isInitiated();

        // If user is authenticated, redirect to dashboard regardless of subdomain
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        
        // If on subdomain but not authenticated, redirect to login
        if ($tenant) {
            return redirect()->route('login');
        }
        
        // Only visitors on main domain can access home page
        // Fetch active and public plans for main domain visitors
        $this->plans = Plan::where('is_active', true)
            ->where('is_public', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function setBillingCycle(string $cycle): void
    {
        $this->billingCycle = $cycle;
    }

    public function render()
    {
        return view('livewire.pages.home');
    }
}
