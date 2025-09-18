<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access with user management and system configuration capabilities.',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full operational access with reports and analytics, but no user management.',
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Department level access with stock management, sales, and customer management.',
            ],
            [
                'name' => 'staff',
                'display_name' => 'Staff',
                'description' => 'Operational level access with basic stock operations and sales transactions.',
            ],
            [
                'name' => 'viewer',
                'display_name' => 'Viewer',
                'description' => 'Read-only access to most modules with basic reports.',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}




