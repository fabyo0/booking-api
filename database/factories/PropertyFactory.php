<?php

namespace Database\Factories;

use App\Enums\RoleEnum;
use App\Models\City;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public $model = Property::class;

    public function definition(): array
    {
        return [
            'owner_id' => User::factory()->create()->assignRole(roles: RoleEnum::OWNER->label()),
            'name' => fake()->text(20),
            'city_id' => City::value('id'),
            'address_street' => fake()->streetAddress(),
            'address_postcode' => fake()->postcode(),
            'lat' => fake()->latitude(),
            'long' => fake()->longitude(),
        ];
    }
}
