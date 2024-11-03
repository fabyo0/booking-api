<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\City;
use App\Models\Property;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class PropertiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_owner_has_access_to_properties_feature()
    {
        $owner = User::factory()->create();
        $owner->assignRole(RoleEnum::OWNER->label());

        $this->actingAs($owner)
            ->getJson(route('property.index'))
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_user_does_not_have_access_to_properties_feature()
    {
        $user = User::factory()->create()->assignRole(roles: RoleEnum::USER->label());
        $response = $this->actingAs($user)->getJson(route('property.index'));

        $response->assertStatus(403);
    }

    public function test_property_search_by_city_returns_corrects_results()
    {
        $owner = User::factory()->create()->assignRole(roles: RoleEnum::OWNER->label());

        $cities = City::take(2)->pluck('id');

        $propertyInCity = Property::factory()->create(['owner_id' => $owner->id, 'city_id' => $cities[0]]);
        $propertyInAnotherCity = Property::factory()->create(['owner_id' => $owner->id, 'city_id' => $cities[1]]);

        $response = $this->getJson('/api/search?city=' . $cities[0]);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['id' => $propertyInCity->id]);
    }
}
