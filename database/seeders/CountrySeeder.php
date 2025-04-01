<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

final class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Country::create([
            'name' => 'United States',
            'lat' => 37.09024,
            'long' => -95.712891,
        ]);
        Country::create([
            'name' => 'United Kingdom',
            'lat' => 55.378051,
            'long' => -3.435973,
        ]);
    }
}
