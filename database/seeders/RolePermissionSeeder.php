<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all roles
        $superAdmin = Role::where('name', 'super_admin')->first();
        $admin = Role::where('name', 'admin')->first();
        $manager = Role::where('name', 'manager')->first();
        $staff = Role::where('name', 'staff')->first();
        $viewer = Role::where('name', 'viewer')->first();

        // Super Admin - All permissions
        $superAdmin->permissions()->attach(Permission::all());

        // Admin - All operational permissions except user management and system settings
        $adminPermissions = Permission::whereNotIn('module', ['users', 'settings'])->get();
        $admin->permissions()->attach($adminPermissions);

        // Manager - Stock management, sales, customer management, and basic reports
        $managerPermissions = Permission::whereIn('module', [
            'dashboard',
            'stationary-items',
            'godowns',
            'customers',
            'schools',
            'students',
            'sales',
            'stationary-awards',
            'procurements',
            'stock-movements',
            'reports'
        ])->whereNotIn('name', [
            'stationary-items.delete',
            'godowns.delete',
            'customers.delete',
            'schools.delete',
            'students.delete',
            'sales.delete',
            'stationary-awards.delete',
            'procurements.delete',
            'stock-movements.delete',
            'reports.advanced'
        ])->get();
        $manager->permissions()->attach($managerPermissions);

        // Staff - Basic operations and view permissions
        $staffPermissions = Permission::whereIn('module', [
            'dashboard',
            'stationary-items',
            'customers',
            'students',
            'sales',
            'stationary-awards',
            'stock-movements',
            'reports'
        ])->whereIn('name', [
            'dashboard.view',
            'stationary-items.view',
            'stationary-items.edit',
            'customers.view',
            'customers.edit',
            'students.view',
            'students.edit',
            'sales.view',
            'sales.create',
            'stationary-awards.view',
            'stationary-awards.edit',
            'stock-movements.view',
            'stock-movements.create',
            'reports.view'
        ])->get();
        $staff->permissions()->attach($staffPermissions);

        // Viewer - Read-only permissions
        $viewerPermissions = Permission::whereIn('name', [
            'dashboard.view',
            'stationary-items.view',
            'godowns.view',
            'customers.view',
            'schools.view',
            'students.view',
            'sales.view',
            'stationary-awards.view',
            'procurements.view',
            'stock-movements.view',
            'reports.view'
        ])->get();
        $viewer->permissions()->attach($viewerPermissions);
    }
}




