<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BookingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_booking_user_has_access_to_booking_feature()
    {
        $user = User::factory()->create();
        $user->assignRole($this->getRole(RoleEnum::USER->value));

        $this->actingAs($user)
            ->getJson(route('booking.index'))
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function test_booking_user_does_not_have_access_to_booking_feature()
    {
        $user = User::factory()->create();
        $user->assignRole($this->getRole(RoleEnum::OWNER->value));

        $this->actingAs($user)
            ->getJson(route('booking.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
