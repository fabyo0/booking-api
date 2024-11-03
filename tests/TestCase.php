<?php

namespace Tests;

use App\Enums\RoleEnum;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function getRole(int $role): Role
    {
        return Role::firstOrCreate(['name' => $role]);
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }
}
