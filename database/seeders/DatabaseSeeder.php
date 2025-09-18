<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            SiteSettingSeeder::class,
        ]);

        // Seed users with different roles
        $this->call([
            UserSeeder::class,
        ]);

        // Seed other data
        $this->call([
            StationaryItemSeeder::class,
            GodownSeeder::class,
            ProcurementSeeder::class,
            SchoolSeeder::class,
            StockMovementSeeder::class,
            CustomerSeeder::class,
            SaleSeeder::class,
            StudentSeeder::class,
            StationaryAwardSeeder::class,
        ]);
    }
}
