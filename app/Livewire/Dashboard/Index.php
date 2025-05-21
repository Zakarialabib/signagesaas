<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Tenant\Models\User;
use App\Tenant\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Dashboard')]
final class Index extends Component
{
    public User $user;
    public ?string $trialEndsAt = null;
    public string $currentPlan = 'free';
    public array $metrics = [];

    public function mount(): void
    {
        $this->user = Auth::user();
        $tenant = $this->user->tenant;

        if ($tenant instanceof Tenant) {
            // Set trial and plan info
            $this->currentPlan = $tenant->plan ?? 'free';

            // For demo purposes only - in a real app, this would come from a subscription model
            $trialEnd = now()->addDays(14);
            $this->trialEndsAt = $trialEnd->format('F j, Y');

            // Generate example metrics
            $this->metrics = [
                'total_devices'  => 10,
                'active_devices' => rand(3, 8),
                'total_screens'  => 25,
                'active_screens' => rand(10, 20),
                'storage_usage'  => [
                    'used'       => rand(100, 950), // MB
                    'total'      => 1000, // MB
                    'percentage' => rand(10, 95),
                ],
                'recent_activity' => $this->getRecentActivity(),
            ];
        }
    }

    private function getRecentActivity(): array
    {
        // Mock recent activity data
        $activities = [];
        $types = ['device_connected', 'content_updated', 'schedule_changed', 'user_login'];
        $descriptions = [
            'device_connected' => 'Device connected to network',
            'content_updated'  => 'Content updated by user',
            'schedule_changed' => 'Schedule changed for device',
            'user_login'       => 'User logged in',
        ];

        for ($i = 0; $i < 5; $i++) {
            $type = $types[array_rand($types)];
            $timestamp = now()->subHours(rand(1, 72));

            $activities[] = (object) [
                'type'        => $type,
                'description' => $descriptions[$type],
                'created_at'  => $timestamp,
                'user_id'     => $this->user->id,
            ];
        }

        // Sort by timestamp, most recent first
        usort($activities, function ($a, $b) {
            return $b->created_at <=> $a->created_at;
        });

        return $activities;
    }

    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
