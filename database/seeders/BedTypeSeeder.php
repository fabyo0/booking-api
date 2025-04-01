<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\BedType;
use Illuminate\Database\Seeder;

final class BedTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BedType::factory(10)->create();
    }
}
