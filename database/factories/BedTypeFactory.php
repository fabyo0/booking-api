<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\BedType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BedType>
 */
final class BedTypeFactory extends Factory
{
    protected $model = BedType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
        ];
    }
}
