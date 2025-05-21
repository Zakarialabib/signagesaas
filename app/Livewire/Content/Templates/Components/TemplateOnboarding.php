<?php

declare(strict_types=1);

namespace App\Livewire\Content\Templates\Components;

use Livewire\Component;

final class TemplateOnboarding extends Component
{
    public bool $showGuide = true;
    public int $currentStep = 0;

    private array $steps = [
        [
            'title'       => 'Welcome to Template Management',
            'description' => 'Learn how to create and manage dynamic digital signage templates.',
            'tips'        => [
                'Templates are made up of zones that can display different types of content',
                'Each zone can be customized with its own settings and schedule',
                'Preview your templates in real-time as you make changes',
            ],
        ],
        [
            'title'       => 'Understanding Zones',
            'description' => 'Zones are the building blocks of your templates.',
            'tips'        => [
                'Drag and resize zones to create your layout',
                'Each zone can display different types of content',
                'Set custom transitions and durations for each zone',
            ],
        ],
        [
            'title'       => 'Content Scheduling',
            'description' => 'Schedule different content to play at specific times.',
            'tips'        => [
                'Set up daily or weekly content rotations',
                'Schedule content based on time of day',
                'Preview scheduled content in the live preview',
            ],
        ],
        [
            'title'       => 'Template Versioning',
            'description' => 'Keep track of template changes and variations.',
            'tips'        => [
                'Save templates as drafts while making changes',
                'Clone templates to create variations',
                'Roll back to previous versions if needed',
            ],
        ],
    ];

    public function mount(): void
    {
        $this->showGuide = ! session()->has('template_onboarding_completed');
    }

    public function nextStep(): void
    {
        if ($this->currentStep < count($this->steps) - 1) {
            $this->currentStep++;
        } else {
            $this->completeGuide();
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 0) {
            $this->currentStep--;
        }
    }

    public function completeGuide(): void
    {
        session(['template_onboarding_completed' => true]);
        $this->showGuide = false;
    }

    public function skipGuide(): void
    {
        $this->completeGuide();
    }

    public function render()
    {
        return view('livewire.content.templates.components.template-onboarding', [
            'steps' => $this->steps,
        ]);
    }
}
