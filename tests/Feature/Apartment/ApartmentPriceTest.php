<?php

declare(strict_types=1);

namespace Tests\Feature\Apartment;

use App\Models\Apartment;
use App\Models\ApartmentPrice;
use App\Models\City;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class ApartmentPriceTest extends TestCase
{
    use RefreshDatabase;

    private function create_apartment(): Apartment
    {
        $owner = User::factory()->owner()->create();
        $cityId = City::value('id');
        $property = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);

        return Apartment::create([
            'name' => 'Apartment',
            'property_id' => $property->id,
            'capacity_adults' => 3,
            'capacity_children' => 2,
        ]);
    }

    public function test_apartment_calculate_price_1_day_correctly()
    {
        $apartment = $this->create_apartment();
        ApartmentPrice::create([
            'apartment_id' => $apartment->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
            'price' => 100,
        ]);

        $totalPrice = $apartment->calculatePriceForDates(
            now()->toDateString(),
            now()->toDateString()
        );
        $this->assertEquals(100, $totalPrice);
    }

    public function test_apartment_calculate_price_2_day_correctly()
    {
        $apartment = $this->create_apartment();

        ApartmentPrice::create([
            'apartment_id' => $apartment->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
            'price' => 100,
        ]);

        $totalPrice = $apartment->calculatePriceForDates(
            startDate: now()->toDateString(),
            endDate: now()->addDay()->toDateString()
        );

        $this->assertEquals(200, $totalPrice);
    }

    public function test_apartment_calculate_price_multiple_ranges_correctly()
    {
        $apartment = $this->create_apartment();

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

        $totalPrice = $apartment->calculatePriceForDates(
            startDate: now()->toDateString(),
            endDate: now()->addDays(4)->toDateString()
        );
        $this->assertEquals((3 * 100) + (2 * 90), $totalPrice);
    }

    public function test_property_search_filters_by_price()
    {
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

        // No price range
        $response = $this->getJson(route('property.search').'?city='.$cityId.'&adults=2&children=1');
        $response->assertStatus(Response::HTTP_OK);

        // Min price one return
        $response = $this->getJson(route('property.search').'?city='.$cityId.'&adults=2&children=1&price_from=100');
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'properties');

        // Max price set one return
        $response = $this->getJson(route('property.search').'?city='.$cityId.'&adults=2&children=1&price_to=100');
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'properties');

        // Both min and max price set: 2 returned
        $response = $this->getJson(route('property.search').'?city='.$cityId.'&adults=2&children=1&price_from=50&price_to=150');
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'properties');

        // Both min and max price set narrow: 0 returned
        $response = $this->getJson(route('property.search').'?city='.$cityId.'&adults=2&children=1&price_from=80&price_to=100');
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(0, 'properties');
    }
}
