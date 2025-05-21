<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Tenant\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Tenant\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'remember_token'    => Str::random(10),
            'role'              => fake()->randomElement(['user', 'manager']),
        ];
    }

    /** Configure the model factory. */
    public function configure(): static
    {
        return $this->afterMaking(function (User $user) {
            // Add any post-making configurations
        })->afterCreating(function (User $user) {
            // Add any post-creation configurations
        });
    }

    /** Set the user as admin. */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /** Set the user as manager. */
    public function manager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'manager',
        ]);
    }
}
