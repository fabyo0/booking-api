<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Apartment;
use Illuminate\Database\Seeder;

final class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Apartment::factory()->create();
    }
}
