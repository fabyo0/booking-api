<?php

declare(strict_types=1);

use App\Models\Apartment;
use App\Models\City;
use App\Models\Facility;
use App\Models\FacilityCategory;
use App\Models\Property;
use App\Models\User;

dataset('apartmentData', [
    ['Large apartment', 3, 2],
    ['Small apartment', 2, 1],
]);

test('apartment show loads apartment with facilities', function (): void {
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
    $apartment->facilities()->attach([
        $firstFacility->id, $secondFacility->id, $thirdFacility->id,
    ]);

    $expectedFacilityArray = [
        $firstCategory->name => [
            $firstFacility->name,
            $secondFacility->name,
        ],
        $secondCategory->name => [
            $thirdFacility->name,
        ],
    ];

    $this->getJson(route('apartment.show', $apartment->id))
        ->assertStatus(200)
        ->assertJsonPath('name', $apartment->name)
        ->assertJsonCount(2, 'facility_categories')
        ->assertJsonFragment($expectedFacilityArray);
});
