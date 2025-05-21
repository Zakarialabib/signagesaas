<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Tenant\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

final class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'id'                => fake()->unique()->uuid(),
            'name'              => fake()->company(),
            'organization_name' => fake()->company(),
            'plan'              => fake()->randomElement(['free', 'basic', 'pro', 'enterprise']),
            'trial_ends_at'     => fake()->optional()->dateTimeBetween('now', '+30 days'),
            'settings'          => [
                'theme'    => fake()->randomElement(['light', 'dark', 'auto']),
                'language' => fake()->randomElement(['en', 'fr', 'ar']),
                'timezone' => fake()->timezone(),
            ],
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Tenant $tenant) {
            $tenant->domains()->create([
                'domain' => $tenant->id.'.signagesaas.test',
            ]);
        });
    }

    public function withTrial(): self
    {
        return $this->state([
            'trial_ends_at' => now()->addDays(30),
        ]);
    }

    public function withoutTrial(): self
    {
        return $this->state([
            'trial_ends_at' => null,
        ]);
    }
}
