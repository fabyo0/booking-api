<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            CitySeeder::class,
            GeoobjectSeeder::class,
            PermissionSeeder::class,
            ApartmentSeeder::class,
            PropertySeeder::class,
            FacilityCategorySeeder::class,
            FacilitySeeder::class,
        ]);
    }
}
