<?php

namespace Database\Factories;

use App\Models\Bed;
use App\Models\BedType;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bed>
 */
class BedFactory extends Factory
{
    protected $model = Bed::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_id' => Room::factory(),
            'bed_type_id' => BedType::factory(),
            'name' => $this->faker->sentence(3),
        ];
    }
}
