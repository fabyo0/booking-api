<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

final class PropertiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_owner_has_access_to_properties_feature()
    {
        $owner = User::factory()->create();
        $owner->assignRole(roles: $this->getRole(RoleEnum::OWNER->value));

        $this->actingAs($owner)
            ->getJson(route('property.index'))
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_user_does_not_have_access_to_properties_feature()
    {
        $user = User::factory()->create()->assignRole(roles: $this->getRole(RoleEnum::USER->value));
        $response = $this->actingAs($user)->getJson(route('property.index'));

        $response->assertStatus(403);
    }
}
