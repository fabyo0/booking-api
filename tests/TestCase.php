<?php

namespace Tests;

use Database\Seeders\CitySeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\FacilityCategorySeeder;
use Database\Seeders\FacilitySeeder;
use Database\Seeders\GeoobjectSeeder;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

         $this->seed([
             CountrySeeder::class,
             CitySeeder::class,
             GeoobjectSeeder::class,
             PermissionSeeder::class,
             FacilityCategorySeeder::class,
             FacilitySeeder::class,
         ]);
    }
}
