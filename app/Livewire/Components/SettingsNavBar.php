<?php

declare(strict_types=1);

namespace App\Livewire\Components;

use Livewire\Component;

class SettingsNavBar extends Component
{
    /** The current active settings page. */
    public string $active = 'general';

    /** Initialize the component. */
    public function mount(string $active = 'general'): void
    {
        $this->active = $active;
    }

    /** Get the settings navigation items. */
    public function getNavItems(): array
    {
        return [
            [
                'name'  => 'general',
                'label' => __('General'),
                'icon'  => 'cog',
                'route' => 'settings.general',
            ],
            [
                'name'  => 'profile',
                'label' => __('Profile'),
                'icon'  => 'user',
                'route' => 'settings.profile',
            ],
            [
                'name'  => 'users',
                'label' => __('Users & Permissions'),
                'icon'  => 'users',
                'route' => 'settings.users',
            ],
            [
                'name'  => 'subscription',
                'label' => __('Subscription'),
                'icon'  => 'credit-card',
                'route' => 'settings.subscription',
            ],
        ];
    }

    /** Render the component. */
    public function render()
    {
        return view('livewire.components.settings-nav-bar', [
            'navItems' => $this->getNavItems(),
        ]);
    }
}
