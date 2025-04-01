<?php

declare(strict_types=1);

use App\Models\Apartment;
use App\Models\ApartmentPrice;
use App\Models\City;
use App\Models\Property;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('apartment calculates price for 1 day correctly', function () {
    $apartment = createApartment();
    ApartmentPrice::create([
        'apartment_id' => $apartment->id,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(10)->toDateString(),
        'price' => 100,
    ]);

    $totalPrice = $apartment->calculatePriceForDates(now()->toDateString(), now()
        ->toDateString());
    expect($totalPrice)
        ->toBeInt()
        ->toBe(100);
});

test('apartment calculates price for 2 days correctly', function () {
    $apartment = createApartment();
    ApartmentPrice::create([
        'apartment_id' => $apartment->id,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(10)->toDateString(),
        'price' => 100,
    ]);

    $totalPrice = $apartment->calculatePriceForDates(now()->toDateString(), now()
        ->toDateString());

    expect($totalPrice)
        ->toBeInt()
        ->toBe(100);
});


test('apartment calculates price for multiple ranges correctly', function () {
    $apartment = createApartment();

    ApartmentPrice::create([
        'apartment_id' => $apartment->id,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(2)->toDateString(),
        'price' => 100,
    ]);

    ApartmentPrice::create([
        'apartment_id' => $apartment->id,
        'start_date' => now()->addDays(3)->toDateString(),
        'end_date' => now()->addDays(10)->toDateString(),
        'price' => 90,
    ]);

    $totalPrice = $apartment->calculatePriceForDates(now()->toDateString(), now()->addDays(4)->toDateString());
    expect($totalPrice)->toBe((3 * 100) + (2 * 90));
});

test('property search filters by price', function () {
    $owner = User::factory()->owner()->create();
    $cityId = City::value('id');
    $property = Property::factory()->create([
        'owner_id' => $owner->id,
        'city_id' => $cityId,
    ]);
    $cheapApartment = Apartment::factory()->create([
        'name' => 'Cheap apartment',
        'property_id' => $property->id,
        'capacity_adults' => 2,
        'capacity_children' => 1,
    ]);
    $cheapApartment->prices()->create([
        'start_date' => now(),
        'end_date' => now()->addMonth(),
        'price' => 70,
    ]);
    $property2 = Property::factory()->create([
        'owner_id' => $owner->id,
        'city_id' => $cityId,
    ]);
    $expensiveApartment = Apartment::factory()->create([
        'name' => 'Mid size apartment',
        'property_id' => $property2->id,
        'capacity_adults' => 2,
        'capacity_children' => 1,
    ]);
    $expensiveApartment->prices()->create([
        'start_date' => now(),
        'end_date' => now()->addMonth(),
        'price' => 130,
    ]);

    /** @phpstan-ignore variable.undefined */
    $this->getJson(route('property.search') . '?city=' . $cityId . '&adults=2&children=1')
        ->assertStatus(Response::HTTP_OK);

    /** @phpstan-ignore variable.undefined */
    $this->getJson(route('property.search') . '?city=' . $cityId . '&adults=2&children=1&price_from=100')
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(3, 'properties');

    /** @phpstan-ignore variable.undefined */
    $this->getJson(route('property.search') . '?city=' . $cityId . '&adults=2&children=1&price_to=100')
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(3, 'properties');
});
