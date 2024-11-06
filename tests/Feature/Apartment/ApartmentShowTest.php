<?php

declare(strict_types=1);

namespace Tests\Feature\Apartment;

use App\Models\Apartment;
use App\Models\City;
use App\Models\Facility;
use App\Models\FacilityCategory;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class ApartmentShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test show apartments with facilities
     */
    public function test_apartment_show_loads_apartment_with_facilities(): void
    {
        $owner = User::factory()->owner()->create();
        $cityId = City::value('id');

        $property = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);

        $apartment = Apartment::factory()->create([
            'name' => 'Large apartment',
            'property_id' => $property->id,
            'capacity_adults' => 3,
            'capacity_children' => 2,
        ]);

        $firstCategory = FacilityCategory::create([
            'name' => 'First category',
        ]);

        $secondCategory = FacilityCategory::create([
            'name' => 'Second category',
        ]);

        $firstFacility = Facility::create([
            'category_id' => $firstCategory->id,
            'name' => 'First facility',
        ]);

        $secondFacility = Facility::create([
            'category_id' => $firstCategory->id,
            'name' => 'Second facility',
        ]);

        $thirdFacility = Facility::create([
            'category_id' => $secondCategory->id,
            'name' => 'Third facility',
        ]);

        $apartment->assignFacilities([
            $firstFacility->id, $secondFacility->id, $thirdFacility->id,
        ]);

        $response = $this->getJson(route('apartment.show', $apartment->id));
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('name', $apartment->name)
            ->assertJsonCount(2, 'facility_categories');

        //Expected Facilities Category
        $expectedFacilityArray = [
            $firstCategory->name => [
                $firstFacility->name,
                $secondFacility->name,
            ],
            $secondCategory->name => [
                $thirdFacility->name,
            ],
        ];

        $response->assertJsonFragment($expectedFacilityArray, 'facility_categories');
    }
}
