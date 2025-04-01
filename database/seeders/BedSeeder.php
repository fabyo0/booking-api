<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Bed;
use Illuminate\Database\Seeder;

final class BedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bed::factory(10)->create();
    }
}
