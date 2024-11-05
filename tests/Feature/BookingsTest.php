<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class BookingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_booking_user_has_access_to_booking_feature()
    {
        $user = User::factory()->user()->create();

        $this->actingAs($user)
            ->getJson(route('booking.index'))
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function test_booking_user_does_not_have_access_to_booking_feature()
    {
        $user = User::factory()->owner()->create();

        $this->actingAs($user)
            ->getJson(route('booking.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
