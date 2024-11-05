<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\FacilityCategory;
use Illuminate\Database\Seeder;

final class FacilityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FacilityCategory::create(['name' => 'Bedroom']);
        FacilityCategory::create(['name' => 'Kitchen']);
        FacilityCategory::create(['name' => 'Bathroom']);
        FacilityCategory::create(['name' => 'Room Amenities']);
        FacilityCategory::create(['name' => 'General']);
        FacilityCategory::create(['name' => 'Media & Technology']);
    }
}
