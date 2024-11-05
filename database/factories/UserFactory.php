<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('123'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    public function admin()
    {
        return $this->state(fn (array $attributes): array => [])
            ->afterCreating(function (User $user): void {
                $user->assignRole(RoleEnum::ADMINISTRATOR->label());
            });
    }

    public function owner()
    {
        return $this->state(fn (array $attributes): array => [])
            ->afterCreating(function (User $user): void {
                $user->assignRole(RoleEnum::OWNER->label());
            });
    }

    public function user()
    {
        return $this->state(fn (array $attributes): array => [])
            ->afterCreating(function (User $user): void {
                $user->assignRole(RoleEnum::USER->label());
            });
    }
}
