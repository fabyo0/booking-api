<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_registration_fails_with_admin_role()
    {
        $response = $this->postJson('api/auth/register', [
            'name' => 'doe',
            'email' => 'doe@hotmail.com',
            'password' => 'ny]#Tt858D$z',
            'password_confirmation' => 'ny]#Tt858D$z',
            'role_id' => Role::ROLE_ADMINISTRATOR,
        ]);

        $response->assertStatus(422);
    }

    public function test_registration_succeeds_with_owner_role()
    {
        $response = $this->postJson('api/auth/register', [
            'name' => 'doe',
            'email' => 'doe@hotmail.com',
            'password' => 'ny]#Tt858D$z',
            'password_confirmation' => 'ny]#Tt858D$z',
            'role_id' => Role::ROLE_OWNER,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'token',
        ]);
    }

    public function test_registration_succeeds_with_user_role()
    {
        $response = $this->postJson('api/auth/register', [
            'name' => 'doe',
            'email' => 'doe@hotmail.com',
            'password' => 'ny]#Tt858D$z',
            'password_confirmation' => 'ny]#Tt858D$z',
            'role_id' => Role::ROLE_USER,
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'token',
        ]);
    }
}
