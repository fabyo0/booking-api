<?php

use App\Models\Apartment;
use App\Models\Booking;
use App\Models\City;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

it('allows booking user to access booking feature', function () {
    $user = User::factory()->user()->create();
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user)
        ->getJson(route('bookings.index'))
        ->assertOk();
});

it('prevents owner from accessing booking feature', function () {
    $user = User::factory()->owner()->create();
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user)
        ->getJson(route('bookings.index'))
        ->assertForbidden();
});

it('allows user to book apartment but prevents double booking', function () {
    $user = User::factory()->user()->create();
    /** @phpstan-ignore variable.undefined */
    $apartment = createApartment();

    $bookingData = [
        'apartment_id' => $apartment->id,
        'start_date' => now()->addDay(),
        'end_date' => now()->addDays(2),
        'guests_adults' => 2,
        'guests_children' => 1,
    ];

    // First booking should succeed
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user)
        ->postJson(route('bookings.store'), $bookingData)
        ->assertCreated();

    // Same booking should fail
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user)
        ->postJson(route('bookings.store'), $bookingData)
        ->assertUnprocessable();

    // Booking with too many guests should fail
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user)
        ->postJson(route('bookings.store'), [
            ...$bookingData,
            'start_date' => now()->addDays(3),
            'end_date' => now()->addDays(4),
            'guests_adults' => 5,
        ])
        ->assertUnprocessable();
});

it('shows only user their own bookings', function () {
    $user1 = User::factory()->user()->create();
    $user2 = User::factory()->user()->create();
    /** @phpstan-ignore variable.undefined */
    $apartment = createApartment();

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

    // User1 should see only their booking
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user1)
        ->getJson('/api/v1/user/bookings/')
        ->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment(['guests_adults' => 1]);

    // User1 can access their booking details
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user1)
        ->getJson(route('bookings.index', $booking1->id))
        ->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment(['guests_adults' => 1]);

    // User1 cannot access User2's booking
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user1)
        ->getJson('/api/v1/user/bookings/' . $booking2->id)
        ->assertForbidden();
});

it('allows user to cancel their booking and still view it', function () {
    $user1 = User::factory()->user()->create();
    $user2 = User::factory()->user()->create();

    $apartment = createApartment();

    $booking = Booking::create([
        'apartment_id' => $apartment->id,
        'user_id' => $user1->id,
        'start_date' => now()->addDay(),
        'end_date' => now()->addDays(2),
        'guests_adults' => 1,
        'guests_children' => 0,
    ]);

    // Other user cannot cancel the booking
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user2)
        ->deleteJson(route('bookings.destroy', $booking->id))
        ->assertForbidden();

    // Owner can cancel their booking
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user1)
        ->deleteJson(route('bookings.destroy', $booking->id))
        ->assertNoContent();

    // Can still view cancelled booking
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user1)
        ->getJson(route('bookings.index'))
        ->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment(['cancelled_at' => now()->toDateString()]);
});

it('allows user to post rating for their booking', function () {
    $user1 = User::factory()->user()->create();
    $user2 = User::factory()->user()->create();

    /** @phpstan-ignore variable.undefined */
    $apartment = createApartment();

    $booking = Booking::factory()->create([
        'apartment_id' => $apartment->id,
        'user_id' => $user1->id,
        'start_date' => now()->addDay(),
        'end_date' => now()->addDays(2),
        'guests_adults' => 1,
        'guests_children' => 0,
    ]);

    // Other user cannot rate
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user2)
        ->putJson(route('bookings.update', $booking->id), [])
        ->assertForbidden();

    // Invalid rating
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user1)
        ->putJson(route('bookings.update', $booking->id), ['rating' => 11])
        ->assertUnprocessable();

    // Short comment
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user1)
        ->putJson(route('bookings.update', $booking->id), [
            'rating' => 5,
            'review_comment' => 'sort comment',
        ])
        ->assertUnprocessable();

    // Valid rating and comment
    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user1)
        ->putJson(route('bookings.update', $booking->id), [
            'rating' => 10,
            'review_comment' => 'Comment with a good length to be accepted.',
        ])
        ->assertOk();
});
