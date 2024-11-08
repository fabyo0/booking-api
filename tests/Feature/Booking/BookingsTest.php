<?php

declare(strict_types=1);

namespace Tests\Feature\Booking;

use App\Models\Apartment;
use App\Models\Booking;
use App\Models\City;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class BookingsTest extends TestCase
{
    use RefreshDatabase;

    private function createApartment(): Apartment
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

    /**
     * @return void
     */
    public function test_booking_user_has_access_to_booking_feature()
    {
        $user = User::factory()->user()->create();

        $this->actingAs($user)
            ->getJson(route('bookings.index'))
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function test_booking_user_does_not_have_access_to_booking_feature()
    {
        $user = User::factory()->owner()->create();

        $this->actingAs($user)
            ->getJson(route('bookings.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_user_can_book_apartment_successfully_but_not_twice()
    {
        $user = User::factory()->user()->create();
        $apartment = $this->createApartment();

        $bookingParameters = [
            'apartment_id' => $apartment->id,
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(2),
            'guests_adults' => 2,
            'guests_children' => 1,
        ];

        $response = $this->actingAs($user)->postJson(uri: route('bookings.store'), data: $bookingParameters)
            ->assertStatus(Response::HTTP_CREATED);

        $response = $this->actingAs($user)->postJson(uri: route('bookings.store'), data: $bookingParameters)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $bookingParameters['start_date'] = now()->addDays(3);
        $bookingParameters['end_date'] = now()->addDays(4);
        $bookingParameters['guests_adults'] = 5;

        $response = $this->actingAs($user)->postJson(uri: route('bookings.store'), data: $bookingParameters)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function test_user_can_get_ony_their_bookings()
    {
        $user1 = User::factory()->user()->create();
        $user2 = User::factory()->user()->create();

        $apartment = $this->createApartment();

        $booking1 = Booking::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user1->id,
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(2),
            'guests_adults' => 1,
            'guests_children' => 0,
        ]);

        $booking2 = Booking::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user2->id,
            'start_date' => now()->addDays(3),
            'end_date' => now()->addDays(4),
            'guests_adults' => 2,
            'guests_children' => 1,
        ]);

        $response = $this->actingAs($user1)->getJson('/api/v1/user/bookings/')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1)
            ->assertJsonFragment(['guests_adults' => 1]);

        $response = $this->actingAs($user1)->getJson(route('bookings.index', $booking1->id))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1)
            ->assertJsonFragment(['guests_adults' => 1]);

        $response = $this->actingAs($user1)->getJson('/api/v1/user/bookings/' . $booking2->id)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_user_can_cancel_their_booking_but_still_view_it()
    {
        $user1 = User::factory()->user()->create();
        $user2 = User::factory()->user()->create();

        $apartment = $this->createApartment();
        $booking = Booking::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user1->id,
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(2),
            'guests_adults' => 1,
            'guests_children' => 0,
        ]);

        $response = $this->actingAs($user2)->deleteJson(route('bookings.destroy', $booking->id))
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $response = $this->actingAs($user1)->deleteJson(route('bookings.destroy', $booking->id))
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $response = $this->actingAs($user1)->getJson(route('bookings.index'))
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['cancelled_at' => now()->toDateString()]);


        /*$response = $this->actingAs($user1)->getJson(route('bookings.show', $booking->id))
            ->assertStatus(200)
            ->assertJsonFragment(['cancelled_at' => now()->toDateString()]);*/
    }
}
