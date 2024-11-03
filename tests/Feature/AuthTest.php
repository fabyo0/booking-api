<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RoleEnum as Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_registration_fails_with_admin_role()
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => 'Valid Name',
            'email' => 'john@example.com',
            'password' => 'eV9Rkl31E:%e',
            'password_confirmation' => 'eV9Rkl31E:%e',
            'role_id' => Role::ADMINISTRATOR,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return void
     */
    public function test_registration_success_with_owner_role()
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => 'Valid Name',
            'email' => 'john@example.com',
            'password' => 'eV9Rkl31E:%e',
            'password_confirmation' => 'eV9Rkl31E:%e',
            'role_id' => Role::OWNER,
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'access_token',
            ]);
    }

    /**
     * @return void
     */
    public function test_registration_success_with_user_role()
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => 'Valid Name',
            'email' => 'john@example.com',
            'password' => 'eV9Rkl31E:%e',
            'password_confirmation' => 'eV9Rkl31E:%e',
            'role_id' => Role::USER,
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'access_token',
            ]);
    }

    /**
     * @return void
     */
    public function test_should_return_token_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $user->assignRole(roles: $this->getRole( Role::OWNER->value));

        // Acting login
        $response = $this->postJson(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);
        $response->assertJsonStructure(['access_token']);
    }

    /**
     * @return void
     */
    /*public function test_should_return_error_with_invalid_credentials()
    {
       $user =  User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

       $user->assignRole(RoleEnum::USER->value);

        // Acting login
        $response = $this->postJson(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword!',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'message' => 'The provided credentials are incorrect.',
        ]);
    }*/
}
