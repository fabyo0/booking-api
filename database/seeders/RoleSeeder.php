<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Administrator', 'guard_name' => 'api']);
        Role::create(['name' => 'Property Owner', 'guard_name' => 'api']);
        Role::create(['name' => 'Simple User', 'guard_name' => 'api']);
    }
}
