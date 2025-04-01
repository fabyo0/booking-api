<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //TODO: Separate role, user, and admin seeders
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permission
        Permission::firstOrCreate(['name' => 'properties-manage']);
        Permission::firstOrCreate(['name' => 'bookings-manage']);
        Permission::firstOrCreate(['name' => 'manage-users']);

        // Roles
        $ownerRole = Role::firstOrCreate(['name' => RoleEnum::OWNER->label()]);
        $ownerRole->givePermissionTo('properties-manage');

        $userRole = Role::firstOrCreate(['name' => RoleEnum::USER->label()]);
        $userRole->givePermissionTo('bookings-manage');

        $adminRole = Role::firstOrCreate(['name' => RoleEnum::ADMINISTRATOR->label()]);
        $adminRole->givePermissionTo('manage-users');

        $owner = User::factory()->create([
            'name' => 'Owner User',
            'email' => 'owner@example.com',
            'password' => Hash::make('123'),
            'email_verified_at' => now(),
        ]);

        $owner->assignRole($ownerRole);

        $user = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('123'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($userRole);

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('123'),
            'email_verified_at' => now(),

        ]);
        $admin->assignRole($adminRole);
    }
}
