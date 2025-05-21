<?php

declare(strict_types=1);

namespace App\Livewire\Content\Widgets;

use Livewire\Attributes\Locked;

final class MenuWidget extends BaseWidget
{
    #[Locked]
    public array $menu = [];

    #[Locked]
    public string $lastUpdated;

    public int $refreshInterval = 300; // 5 minutes
    public string $menuType = 'restaurant'; // restaurant, cafeteria, etc.
    public bool $showPrices = true;
    public bool $showCalories = true;
    public bool $showAllergens = true;
    public string $currency = '$';

    protected function loadData(): void
    {
        // Replace with your actual menu data source
        // Example: fetch from database, API, etc.
        /*
        try {
            $this->menu = Menu::where('type', $this->menuType)
                ->where('active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->with(['categories.items'])
                ->get()
                ->map(function ($category) {
                    return [
                        'name' => $category->name,
                        'description' => $category->description,
                        'items' => $category->items->map(function ($item) {
                            return [
                                'name' => $item->name,
                                'description' => $item->description,
                                'price' => $item->price,
                                'calories' => $item->calories,
                                'allergens' => $item->allergens,
                                'image' => $item->image_url,
                                'special' => $item->is_special,
                            ];
                        })->toArray(),
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            throw new \Exception('Error fetching menu data: ' . $e->getMessage());
        }
        */

        // Placeholder / Demo data
        $this->menu = [
            [
                'name' => 'Appetizers',
                'description' => 'Start your meal with these delicious options',
                'items' => [
                    [
                        'name' => 'Bruschetta',
                        'description' => 'Grilled bread rubbed with garlic and topped with diced tomatoes, fresh basil, and olive oil',
                        'price' => 8.99,
                        'calories' => 320,
                        'allergens' => ['gluten'],
                        'image' => 'bruschetta.jpg',
                        'special' => false
                    ],
                    [
                        'name' => 'Calamari',
                        'description' => 'Crispy fried squid served with marinara sauce',
                        'price' => 12.99,
                        'calories' => 450,
                        'allergens' => ['shellfish', 'gluten'],
                        'image' => 'calamari.jpg',
                        'special' => true
                    ]
                ]
            ],
            [
                'name' => 'Main Courses',
                'description' => 'Hearty entrees for every taste',
                'items' => [
                    [
                        'name' => 'Grilled Salmon',
                        'description' => 'Fresh Atlantic salmon with lemon butter sauce',
                        'price' => 24.99,
                        'calories' => 620,
                        'allergens' => ['fish'],
                        'image' => 'salmon.jpg',
                        'special' => false
                    ],
                    [
                        'name' => 'Beef Tenderloin',
                        'description' => 'Prime cut beef served with roasted vegetables',
                        'price' => 34.99,
                        'calories' => 850,
                        'allergens' => [],
                        'image' => 'beef.jpg',
                        'special' => true
                    ]
                ]
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet endings to your perfect meal',
                'items' => [
                    [
                        'name' => 'Tiramisu',
                        'description' => 'Classic Italian dessert with coffee-soaked ladyfingers',
                        'price' => 8.99,
                        'calories' => 420,
                        'allergens' => ['dairy', 'eggs', 'gluten'],
                        'image' => 'tiramisu.jpg',
                        'special' => false
                    ],
                    [
                        'name' => 'Chocolate Lava Cake',
                        'description' => 'Warm chocolate cake with a molten center',
                        'price' => 9.99,
                        'calories' => 550,
                        'allergens' => ['dairy', 'eggs', 'gluten'],
                        'image' => 'lava-cake.jpg',
                        'special' => true
                    ]
                ]
            ]
        ];

        $this->lastUpdated = now()->diffForHumans();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.content.widgets.menu-widget', [
            'title' => 'Menu',
            'category' => 'MENU',
            'icon' => '<svg class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>',
            'menu' => $this->menu,
            'lastUpdated' => $this->lastUpdated,
            'error' => $this->error,
            'isLoading' => $this->isLoading,
            'showPrices' => $this->showPrices,
            'showCalories' => $this->showCalories,
            'showAllergens' => $this->showAllergens,
            'currency' => $this->currency,
        ]);
    }
} 