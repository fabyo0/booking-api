<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ApartmentType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApartmentType>
 */
final class ApartmentTypeFactory extends Factory
{
    protected $model = ApartmentType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
        ];
    }
}
