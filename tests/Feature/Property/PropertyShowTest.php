<?php

declare(strict_types=1);

use App\Models\Apartment;
use App\Models\City;
use App\Models\Facility;
use App\Models\FacilityCategory;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

test('property show loads property correctly', function (): void {
    $owner = User::factory()->owner()->create();
    $cityId = City::value('id');

    $property = Property::factory()->create([
        'owner_id' => $owner->id,
        'city_id' => $cityId,
    ]);

    $apartments = collect([
        ['name' => 'Large apartment', 'capacity_adults' => 3, 'capacity_children' => 2],
        ['name' => 'Mid size apartment', 'capacity_adults' => 2, 'capacity_children' => 1],
        ['name' => 'Small apartment', 'capacity_adults' => 1, 'capacity_children' => 0],
    ])->map(fn($data) => Apartment::factory()->create(array_merge($data, ['property_id' => $property->id])));

    $facility = Facility::create([
        'category_id' => FacilityCategory::create(['name' => 'Some category'])->id,
        'name' => 'Some facility',
    ]);

    /** @phpstan-ignore variable.undefined */
    $this->getJson(route('property.show', $property->id))
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(3, 'apartments')
        ->assertJsonPath('name', $property->name);

    /** @phpstan-ignore variable.undefined */
    $this->getJson(route('property.show', $property->id) . '?adults=2&children=1')
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(2, 'apartments')
        ->assertJsonPath('name', $property->name)
        ->assertJsonCount(0, 'apartments.1.facilities');

    /** @phpstan-ignore variable.undefined */
    $this->getJson("/api/v1/search?city={$cityId}&adults=2&children=1")
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonPath('0.apartments.0.facilities', null);
});
