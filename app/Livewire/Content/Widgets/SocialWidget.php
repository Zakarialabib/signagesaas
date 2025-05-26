<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Component;
use Livewire\Attributes\Locked;

final class SocialWidget extends Component
{
    #[Locked]
    public array $settings;

    public array $posts = [];

    public function mount(array $settings = []): void
    {
        $this->settings = $settings;

        // In a real implementation, these would come from social media APIs
        // For demo, we'll use static data
        $this->posts = [
            [
                'user'     => 'JaneDoe',
                'avatar'   => '👩',
                'content'  => 'Just launched our new product! Check it out at example.com #excited',
                'time'     => '30m ago',
                'likes'    => '42',
                'comments' => '12',
            ],
            [
                'user'     => 'TechGuru',
                'avatar'   => '🤓',
                'content'  => 'The future of AI is here. These new developments are changing everything we know about machine learning.',
                'time'     => '2h ago',
                'likes'    => '128',
                'comments' => '24',
            ],
            [
                'user'     => 'TravelBug',
                'avatar'   => '✈️',
                'content'  => 'Beautiful sunset views from Bali today. Nature never fails to amaze me! #travel #bali',
                'time'     => '5h ago',
                'likes'    => '89',
                'comments' => '15',
            ],
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.widgets.social-widget', [
            'posts' => $this->posts,
        ]);
    }
}
