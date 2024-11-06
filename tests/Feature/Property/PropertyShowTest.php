<?php

declare(strict_types=1);

namespace Tests\Feature\Property;

use App\Models\Apartment;
use App\Models\City;
use App\Models\Facility;
use App\Models\FacilityCategory;
use App\Models\Property;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class PropertyShowTest extends TestCase
{
    public function test_property_show_loads_property_correctly()
    {
        $owner = User::factory()->owner()->create();
        $cityId = City::value('id');

        $property = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);

        $largeApartment = Apartment::factory()->create([
            'name' => 'Large apartment',
            'property_id' => $property->id,
            'capacity_adults' => 3,
            'capacity_children' => 2,
        ]);
        $midSizeApartment = Apartment::factory()->create([
            'name' => 'Mid size apartment',
            'property_id' => $property->id,
            'capacity_adults' => 2,
            'capacity_children' => 1,
        ]);
        $smallApartment = Apartment::factory()->create([
            'name' => 'Small apartment',
            'property_id' => $property->id,
            'capacity_adults' => 1,
            'capacity_children' => 0,
        ]);

        $facilityCategory = FacilityCategory::create([
            'name' => 'Some category',
        ]);
        $facility = Facility::create([
            'category_id' => $facilityCategory->id,
            'name' => 'Some facility',
        ]);

        $response = $this->getJson(route('property.show', $property->id));
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3, 'apartments')
            ->assertJsonPath('name', $property->name);

        $response = $this->getJson(route('property.show', $property->id).'?adults=2&children=1')
            ->assertStatus(200)
            ->assertJsonCount(2, 'apartments')
            ->assertJsonPath('name', $property->name)
//           ->assertJsonPath('apartments.0.facilities.0.name', $facility->name)
            ->assertJsonCount(0, 'apartments.1.facilities');

        $response = $this->getJson('/api/v1/search?city='.$cityId.'&adults=2&children=1')
            ->assertStatus(200)
            ->assertJsonPath('0.apartments.0.facilities', null);

    }
}
