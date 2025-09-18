<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard permissions
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'module' => 'dashboard'],
            ['name' => 'dashboard.reports', 'display_name' => 'View Reports', 'module' => 'dashboard'],
            ['name' => 'dashboard.analytics', 'display_name' => 'View Analytics', 'module' => 'dashboard'],

            // Stationary Items permissions
            ['name' => 'stationary-items.view', 'display_name' => 'View Stationary Items', 'module' => 'stationary-items'],
            ['name' => 'stationary-items.create', 'display_name' => 'Create Stationary Items', 'module' => 'stationary-items'],
            ['name' => 'stationary-items.edit', 'display_name' => 'Edit Stationary Items', 'module' => 'stationary-items'],
            ['name' => 'stationary-items.delete', 'display_name' => 'Delete Stationary Items', 'module' => 'stationary-items'],

            // Godowns permissions
            ['name' => 'godowns.view', 'display_name' => 'View Godowns', 'module' => 'godowns'],
            ['name' => 'godowns.create', 'display_name' => 'Create Godowns', 'module' => 'godowns'],
            ['name' => 'godowns.edit', 'display_name' => 'Edit Godowns', 'module' => 'godowns'],
            ['name' => 'godowns.delete', 'display_name' => 'Delete Godowns', 'module' => 'godowns'],

            // Customers permissions
            ['name' => 'customers.view', 'display_name' => 'View Customers', 'module' => 'customers'],
            ['name' => 'customers.create', 'display_name' => 'Create Customers', 'module' => 'customers'],
            ['name' => 'customers.edit', 'display_name' => 'Edit Customers', 'module' => 'customers'],
            ['name' => 'customers.delete', 'display_name' => 'Delete Customers', 'module' => 'customers'],

            // Schools permissions
            ['name' => 'schools.view', 'display_name' => 'View Schools', 'module' => 'schools'],
            ['name' => 'schools.create', 'display_name' => 'Create Schools', 'module' => 'schools'],
            ['name' => 'schools.edit', 'display_name' => 'Edit Schools', 'module' => 'schools'],
            ['name' => 'schools.delete', 'display_name' => 'Delete Schools', 'module' => 'schools'],

            // Students permissions
            ['name' => 'students.view', 'display_name' => 'View Students', 'module' => 'students'],
            ['name' => 'students.create', 'display_name' => 'Create Students', 'module' => 'students'],
            ['name' => 'students.edit', 'display_name' => 'Edit Students', 'module' => 'students'],
            ['name' => 'students.delete', 'display_name' => 'Delete Students', 'module' => 'students'],

            // Sales permissions
            ['name' => 'sales.view', 'display_name' => 'View Sales', 'module' => 'sales'],
            ['name' => 'sales.create', 'display_name' => 'Create Sales', 'module' => 'sales'],
            ['name' => 'sales.edit', 'display_name' => 'Edit Sales', 'module' => 'sales'],
            ['name' => 'sales.delete', 'display_name' => 'Delete Sales', 'module' => 'sales'],

            // Stationary Awards permissions
            ['name' => 'stationary-awards.view', 'display_name' => 'View Stationary Awards', 'module' => 'stationary-awards'],
            ['name' => 'stationary-awards.create', 'display_name' => 'Create Stationary Awards', 'module' => 'stationary-awards'],
            ['name' => 'stationary-awards.edit', 'display_name' => 'Edit Stationary Awards', 'module' => 'stationary-awards'],
            ['name' => 'stationary-awards.delete', 'display_name' => 'Delete Stationary Awards', 'module' => 'stationary-awards'],

            // Procurements permissions
            ['name' => 'procurements.view', 'display_name' => 'View Procurements', 'module' => 'procurements'],
            ['name' => 'procurements.create', 'display_name' => 'Create Procurements', 'module' => 'procurements'],
            ['name' => 'procurements.edit', 'display_name' => 'Edit Procurements', 'module' => 'procurements'],
            ['name' => 'procurements.delete', 'display_name' => 'Delete Procurements', 'module' => 'procurements'],

            // Stock Movements permissions
            ['name' => 'stock-movements.view', 'display_name' => 'View Stock Movements', 'module' => 'stock-movements'],
            ['name' => 'stock-movements.create', 'display_name' => 'Create Stock Movements', 'module' => 'stock-movements'],
            ['name' => 'stock-movements.edit', 'display_name' => 'Edit Stock Movements', 'module' => 'stock-movements'],
            ['name' => 'stock-movements.delete', 'display_name' => 'Delete Stock Movements', 'module' => 'stock-movements'],

            // Reports permissions
            ['name' => 'reports.view', 'display_name' => 'View Reports', 'module' => 'reports'],
            ['name' => 'reports.export', 'display_name' => 'Export Reports', 'module' => 'reports'],
            ['name' => 'reports.advanced', 'display_name' => 'Advanced Reports', 'module' => 'reports'],

            // User Management permissions
            ['name' => 'users.view', 'display_name' => 'View Users', 'module' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'module' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'module' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'module' => 'users'],
            ['name' => 'users.assign-roles', 'display_name' => 'Assign User Roles', 'module' => 'users'],

            // System Settings permissions
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'module' => 'settings'],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'module' => 'settings'],
            ['name' => 'settings.system', 'display_name' => 'System Settings', 'module' => 'settings'],

            // Site Settings permissions
            ['name' => 'site-settings.view', 'display_name' => 'View Site Settings', 'module' => 'site-settings'],
            ['name' => 'site-settings.edit', 'display_name' => 'Edit Site Settings', 'module' => 'site-settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}

