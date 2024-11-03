<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class BookingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_access_to_bookings_feature()
    {
        $user = User::factory()->create()->assignRole(roles:RoleEnum::USER->value);
        $response = $this->actingAs($user)->getJson(route('booking.index'));

        $response->assertStatus(200);
    }

    public function test_property_owner_does_not_have_access_to_bookings_feature()
    {
        $owner = User::factory()->create()->assignRole(roles:RoleEnum::USER->value);
        $response = $this->actingAs($owner)->getJson(route('booking.index'));

        $response->assertStatus(403);
    }
}
