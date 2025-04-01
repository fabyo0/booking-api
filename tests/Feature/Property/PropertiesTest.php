<?php

use App\Models\City;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

test('property owner has access to properties feature', function () {
    $owner = \App\Models\User::factory()->owner()->create();

    /** @phpstan-ignore variable.undefined */
    $this->actingAs($owner)
        ->getJson(route('property.index'))
        ->assertStatus(Response::HTTP_OK);
});

test('user does not have access to properties feature', function () {
    $user = \App\Models\User::factory()->user()->create();

    /** @phpstan-ignore variable.undefined */
    $this->actingAs($user)->getJson(route('property.index'))
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

test('property owner can add property', function () {

    $owner = \App\Models\User::factory()->owner()->create();

    /** @phpstan-ignore variable.undefined */
    $this->actingAs($owner)->postJson(route('property.store'), [
        'name' => 'My property',
        'city_id' => City::value('id'),
        'address_street' => 'Street Address 1',
        'address_postcode' => '12345',
    ])
        ->assertSuccessful()
        ->assertJsonFragment(['name' => 'My property']);
});

test('property owner can add photo to property', function () {
    Storage::fake();

    $owner = User::factory()->owner()->create();

    $cityId = City::value('id');
    $property = Property::factory()->create([
        'owner_id' => $owner->id,
        'city_id' => $cityId,
    ]);

    /** @phpstan-ignore variable.undefined */
    $photoOne = $this->actingAs($owner)->postJson(route('property-photo', $property->id), [
        'photo' => UploadedFile::fake()->image('photo1.png'),
    ]);

    /** @phpstan-ignore variable.undefined */
    $photoTwo = $this->actingAs($owner)->postJson(route('property-photo', $property->id), [
        'photo' => UploadedFile::fake()->image('photo2.png'),
    ]);

    $newPosition = $photoOne->json('position') + 1;

    /** @phpstan-ignore variable.undefined */
    $this->actingAs($owner)->postJson('/api/v1/owner/' . $property->id . '/photos/1/reorder/' . $newPosition)
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonFragment(['newPosition' => $newPosition]);

    // Check Database
    /** @phpstan-ignore variable.undefined */
    $this->assertDatabaseHas('media', ['file_name' => 'photo1.png', 'position' => $photoTwo->json('position')]);
    /** @phpstan-ignore variable.undefined */
    $this->assertDatabaseHas('media', ['file_name' => 'photo2.png', 'position' => $photoOne->json('position')]);
});

test('property owner can reorder photos in property', function () {
    Storage::fake();

    $owner = User::factory()->owner()->create();

    $cityId = City::value('id');
    $property = Property::factory()->create([
        'owner_id' => $owner->id,
        'city_id' => $cityId,
    ]);

    /** @phpstan-ignore variable.undefined */
    $photoOne = $this->actingAs($owner)->postJson(route('property-photo', $property->id), [
        'photo' => UploadedFile::fake()->image('photo1.png'),
    ]);

    /** @phpstan-ignore variable.undefined */
    $photoTwo = $this->actingAs($owner)->postJson(route('property-photo', $property->id), [
        'photo' => UploadedFile::fake()->image('photo2.png'),
    ]);

    $newPosition = $photoOne->json('position') + 1;

    /** @phpstan-ignore variable.undefined */
    $response = $this->actingAs($owner)->postJson('/api/v1/owner/' . $property->id . '/photos/1/reorder/' . $newPosition);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonFragment(['newPosition' => $newPosition]);

    // Check Database
    /** @phpstan-ignore variable.undefined */
    $this->assertDatabaseHas('media', ['file_name' => 'photo1.png', 'position' => $photoTwo->json('position')]);
    /** @phpstan-ignore variable.undefined */
    $this->assertDatabaseHas('media', ['file_name' => 'photo2.png', 'position' => $photoOne->json('position')]);
});
