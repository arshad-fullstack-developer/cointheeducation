<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $staffRole = Role::where('name', 'staff')->first();
        $viewerRole = Role::where('name', 'viewer')->first();

        // Create Super Admin
        User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $superAdminRole->id,
            'status' => 'active',
        ]);

        // Create Admin
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin2@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'status' => 'active',
        ]);

        // Create Manager
        User::create([
            'name' => 'Department Manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role_id' => $managerRole->id,
            'status' => 'active',
        ]);

        // Create Staff
        User::create([
            'name' => 'Staff Member',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'role_id' => $staffRole->id,
            'status' => 'active',
        ]);

        // Create Viewer
        User::create([
            'name' => 'View Only User',
            'email' => 'viewer@example.com',
            'password' => Hash::make('password'),
            'role_id' => $viewerRole->id,
            'status' => 'active',
        ]);

        // Create additional test users for each role
        $this->createTestUsers($superAdminRole, 'super_admin');
        $this->createTestUsers($adminRole, 'admin');
        $this->createTestUsers($managerRole, 'manager');
        $this->createTestUsers($staffRole, 'staff');
        $this->createTestUsers($viewerRole, 'viewer');
    }

    /**
     * Create additional test users for a specific role
     */
    private function createTestUsers(Role $role, string $roleName): void
    {
        $userCount = 2; // Create 2 additional users per role

        for ($i = 1; $i <= $userCount; $i++) {
            User::create([
                'name' => ucfirst($roleName) . ' User ' . $i,
                'email' => $roleName . $i . '@example.com',
                'password' => Hash::make('password'),
                'role_id' => $role->id,
                'status' => 'active',
            ]);
        }
    }
}
