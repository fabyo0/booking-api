<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Apartment;
use App\Models\City;
use App\Models\Country;
use App\Models\Geoobject;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class PropertiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_owner_has_access_to_properties_feature()
    {
        $owner = User::factory()->owner()->create();

        $this->actingAs($owner)
            ->getJson(route('property.index'))
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_user_does_not_have_access_to_properties_feature()
    {
        $user = User::factory()->user()->create();
        $response = $this->actingAs($user)->getJson(route('property.index'));

        $response->assertStatus(403);
    }

    public function test_property_search_by_city_returns_corrects_results()
    {
        $owner = User::factory()->owner()->create();

        $cities = City::take(2)->pluck('id');

        $propertyInCity = Property::factory()->create(['owner_id' => $owner->id, 'city_id' => $cities[0]]);
        $propertyInAnotherCity = Property::factory()->create(['owner_id' => $owner->id, 'city_id' => $cities[1]]);

        $response = $this->getJson(route('property.search') . '?city=' . $cities[0]);

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['id' => $propertyInCity->id]);
    }

    public function test_property_search_by_country_returns_corrects_results()
    {
        $owner = User::factory()->owner()->create();

        $countries = Country::with(relations: 'city')->take(2)->get();

        $propertyInCity = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $countries[0]->city()->value('id'),
        ]);

        $propertyInAnotherCity = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $countries[1]->city()->value('id'),
        ]);

        $response = $this->getJson(route('property.search') . '?country=' . $countries[0]->id);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1)
            ->assertJsonFragment(['id' => $propertyInCity->id]);
    }

    public function test_property_search_by_geoobject_returns_correct_results()
    {
        $owner = User::factory()->owner()->create();
        $cityId = City::value('id');
        $geoObject = Geoobject::first();

        $propertyNear = Property::factory()->create(attributes: [
            'owner_id' => $owner->id,
            'city_id' => $cityId,
            'lat' => $geoObject->lat,
            'long' => $geoObject->long,
        ]);

        $response = $this->getJson(route('property.search') . '?geoobject=');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['id' => $propertyNear->id]);
    }

    public function test_property_search_by_capacity_returns_correct_results()
    {
        $owner = User::factory()->owner()->create();
        $cityId = City::value('id');

        $propertyWithSmallApartment = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);

        Apartment::factory()->create([
            'property_id' => $propertyWithSmallApartment,
            'capacity_adults' => 1,
            'capacity_children' => 0,
        ]);

        $propertyWithLargeApartment = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);

        Apartment::factory()->create([
            'property_id' => $propertyWithLargeApartment,
            'capacity_adults' => 3,
            'capacity_children' => 2,
        ]);

        $response = $this->getJson(route('property.search') . '?city=' . $cityId . '&adults=2&children=1');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1)
            ->assertJsonFragment(['id' => $propertyWithLargeApartment->id]);
    }

    public function test_property_search_by_capacity_returns_only_suitable_apartments()
    {
        $owner = User::factory()->owner()->create();
        $cityId = City::value('id');

        $property = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);

        $smallApartment = Apartment::factory()->create([
            'property_id' => $property->id,
            'capacity_adults' => 1,
            'capacity_children' => 0,
        ]);

        $largeApartment = Apartment::factory()->create([
            'property_id' => $property->id,
            'capacity_adults' => 2,
            'capacity_children' => 3,
        ]);

        $response = $this->getJson(route('property.search') . '?city=' . $cityId . '&adults=2&children=1');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1)
            ->assertJsonCount(1, '0.apartments')
            ->assertJsonPath('0.apartments.0.name', $largeApartment->name);
    }
}
