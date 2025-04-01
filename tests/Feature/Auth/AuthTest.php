<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\RoleEnum as Role;


uses(RefreshDatabase::class);

test('registration fails with admin role', closure: function () {
    $userData = [
        'name' => 'Valid Name',
        'email' => 'john@example.com',
        'password' => 'eV9Rkl31E:%e',
        'password_confirmation' => 'eV9Rkl31E:%e',
        'role_id' => Role::ADMINISTRATOR,
    ];
    /** @phpstan-ignore variable.undefined */
    $this->postJson(uri: route('auth.register'), data: $userData)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

test('registration succeeds with owner role', function () {
    /** @phpstan-ignore variable.undefined */
    $this->postJson(route('auth.register'), [
        'name' => 'Valid name',
        'email' => 'valid@email.com',
        'password' => 'ValidPassword',
        'password_confirmation' => 'ValidPassword',
        'role_id' => Role::OWNER
    ])->assertStatus(200)->assertJsonStructure([
        'access_token',
    ]);

});

test('registration succeeds with user role', function () {
    /** @phpstan-ignore variable.undefined */
    $this->postJson(route('auth.register'), [
        'name' => 'Valid name',
        'email' => 'valid@email.com',
        'password' => 'ValidPassword',
        'password_confirmation' => 'ValidPassword',
        'role_id' => Role::USER
    ])->assertStatus(200)->assertJsonStructure([
        'access_token',
    ]);
});

it('should return token with valid credentials', function () {
    User::factory()->owner()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);
    /** @phpstan-ignore variable.undefined */
    $this->postJson(route('auth.login'), [
        'email' => 'test@example.com',
        'password' => 'password123',
    ])->assertJsonStructure(['access_token']);
});

it('return error with invalid credentials', function () {
    User::factory()->user()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    // Acting login
    /** @phpstan-ignore variable.undefined */
    $response = $this->postJson(route('auth.login'), [
        'email' => 'test@example.com',
        'password' => 'wrongpassword!',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            'message' => 'The provided credentials are incorrect.',
        ]);
});
