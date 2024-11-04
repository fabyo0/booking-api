<?php

namespace Database\Factories;

use App\Models\Apartment;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Apartment>
 */
class ApartmentFactory extends Factory
{
    public $model = Apartment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'name' => $this->faker->unique()->word,
            'capacity_adults' => $this->faker->numberBetween(1, 5),
            'capacity_children' => $this->faker->numberBetween(0, 3),
        ];
    }
}
