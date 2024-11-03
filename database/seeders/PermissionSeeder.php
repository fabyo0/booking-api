<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        //TODO: Separate role, user, and admin seeders
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permission
        Permission::firstOrCreate(['name' => 'properties-manage']);
        Permission::firstOrCreate(['name' => 'bookings-manage']);
        Permission::firstOrCreate(['name' => 'manage-users']);

        // Roles
        $ownerRole = Role::firstOrCreate(['name' => RoleEnum::OWNER->value]);
        $ownerRole->givePermissionTo('properties-manage');

        $userRole = Role::firstOrCreate(['name' => RoleEnum::USER->value]);
        $userRole->givePermissionTo('bookings-manage');

        $adminRole = Role::firstOrCreate(['name' => RoleEnum::ADMINISTRATOR->value]);
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
