<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

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
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        // Fetch active and public plans
        $this->plans = Plan::where('is_active', true)
            ->where('is_public', true)
            ->orderBy('sort_order')
            ->get();
        // unthorize to get to home if authenticated redirect to dashboard instead
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
