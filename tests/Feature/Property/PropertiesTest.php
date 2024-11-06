<?php

declare(strict_types=1);

namespace Tests\Feature\Property;

use App\Models\Apartment;
use App\Models\Bed;
use App\Models\BedType;
use App\Models\City;
use App\Models\Country;
use App\Models\Facility;
use App\Models\Geoobject;
use App\Models\Property;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

        $response = $this->getJson(route('property.search') . '?city=' . $cities[1]);

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['id' => $propertyInCity]);
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

    public function test_property_search_beds_list_all_cases()
    {
        $owner = User::factory()->owner()->create();

        $cityId = City::value('id');
        $roomTypes = RoomType::all();
        $bedTypes = BedType::all();

        $property = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);

        $apartment = Apartment::factory()->create([
            'name' => 'Small apartment',
            'property_id' => $property->id,
            'capacity_adults' => 1,
            'capacity_children' => 0,
        ]);

        //TODO: Check that bed list if empty if not beds
        $response = $this->getJson(route('property.search') . '?city=' . $cityId)
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonCount(1, '0.apartments')
            ->assertJsonPath('0.apartments.0.beds_list', '');

        //TODO: Create 1 room with bed
        $room = Room::create([
            'apartment_id' => $apartment->id,
            'room_type_id' => $roomTypes[0]->id,
            'name' => 'Bedroom',
        ]);

        Bed::create([
            'room_id' => $room->id,
            'bed_type_id' => $bedTypes[0]->id,
            'name' => 'Example Bed',
        ]);

        $response = $this->getJson(route('property.search') . '?city=' . $cityId)
            ->assertStatus(200)
            ->assertJsonPath('0.apartments.0.beds_list', '1 ' . $bedTypes[0]->name);

        //TODO: Add another bed to the same room
        $secondRoom = Bed::create([
            'room_id' => $room->id,
            'bed_type_id' => $bedTypes[0]->id,
            'name' => 'Example Bed',
        ]);

        $response = $this->getJson(route('property.search') . '?city=' . $cityId)
            ->assertStatus(200)
            ->assertJsonPath('0.apartments.0.beds_list', '2 ' . str($bedTypes[0]->name)->plural());

        // Add one bad second room no beds
        $secondRoom = Room::create([
            'apartment_id' => $apartment->id,
            'room_type_id' => $roomTypes[0]->id,
            'name' => 'Living room',
        ]);

        $response = $this->getJson(route('property.search') . '?city=' . $cityId)
            ->assertStatus(200)
            ->assertJsonPath('0.apartments.0.beds_list', '2 ' . str($bedTypes[0]->name)->plural());
    }

    public function test_property_search_returns_one_best_apartment_per_property()
    {
        $owner = User::factory()->owner()->create();

        $cityId = City::value('id');
        $roomTypes = RoomType::all();
        $bedTypes = BedType::all();

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

        $smallSizeApartment = Apartment::factory()->create([
            'name' => 'Mid size apartment',
            'property_id' => $property->id,
            'capacity_adults' => 1,
            'capacity_children' => 0,
        ]);

        $response = $this->getJson(route('property.search') . '?city=' . $cityId . '&adults=2&children=1');
        $response->assertStatus(200)
            ->assertJsonCount(1, '0.apartments')
            ->assertJsonPath('0.apartments.0.name', $midSizeApartment->name);
    }

    public function test_property_search_filter_by_facilities()
    {
        $owner = User::factory()->create();

        $cityId = City::value('id');

        $property = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);

        $property2 = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);

        Apartment::factory()->create([
            'name' => 'Mid size apartment',
            'property_id' => $property->id,
            'capacity_adults' => 2,
            'capacity_children' => 1,
        ]);

        Apartment::factory()->create([
            'name' => 'Mid size apartment',
            'property_id' => $property2->id,
            'capacity_adults' => 2,
            'capacity_children' => 1,
        ]);

        //No facilities exits
        $response = $this->getJson(route('property.search') . '?city=' . $cityId . '&adults=2&children=1');
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'properties');

        // Facility, 0 properties returned
        $facility = Facility::create(['name' => 'First facility']);
        $response = $this->getJson(route('property.search') . '?city=' . $cityId . '&adults=2&children=1&facilities[]=' . $facility->id);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(0, 'properties');

        // Attach facility to property, filter by facility, 1 property returned
        $property->facilities()->attach($facility->id);
        $response = $this->getJson(route('property.search') . '?city=' . $cityId . '&adults=2&children=1&facilities[]=' . $facility->id);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'properties');

        $property2->facilities()->attach($facility->id);
        $response = $this->getJson(route('property.search') . '?city=' . $cityId . '&adults=2&children=1&facilities[]=' . $facility->id);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'properties');
    }


    public function test_property_owner_can_add_photo_to_property()
    {
        Storage::fake();

        $owner = User::factory()->owner()->create();
        $cityId = City::value('id');

        $property = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
        ]);

        $response = $this->actingAs($owner)->postJson(route('property-photo', $property->id), [
            'photo' => UploadedFile::fake()->image('photo.png')
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'filename' => config('app.url') . '/storage/1/photo.png',
                'thumbnail' => config('app.url') . '/storage/1/conversions/photo-thumbnail.jpg',
            ]);
    }
}
