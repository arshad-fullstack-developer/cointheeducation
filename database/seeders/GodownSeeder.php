<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Godown;

class GodownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $godowns = [
            [
                'name' => 'Main Warehouse',
                'location' => 'Mumbai, Maharashtra',
                'description' => 'Primary storage facility for all stationary items',
                'manager_name' => 'Rajesh Kumar',
                'contact_number' => '+91 98765 43210',
                'email' => 'rajesh@mainwarehouse.com',
                'status' => 'active',
            ],
            [
                'name' => 'North Branch Godown',
                'location' => 'Delhi, NCR',
                'description' => 'Storage facility for northern region distribution',
                'manager_name' => 'Priya Sharma',
                'contact_number' => '+91 98765 43211',
                'email' => 'priya@northbranch.com',
                'status' => 'active',
            ],
            [
                'name' => 'South Branch Godown',
                'location' => 'Bangalore, Karnataka',
                'description' => 'Storage facility for southern region distribution',
                'manager_name' => 'Amit Patel',
                'contact_number' => '+91 98765 43212',
                'email' => 'amit@southbranch.com',
                'status' => 'active',
            ],
            [
                'name' => 'East Branch Godown',
                'location' => 'Kolkata, West Bengal',
                'description' => 'Storage facility for eastern region distribution',
                'manager_name' => 'Sneha Das',
                'contact_number' => '+91 98765 43213',
                'email' => 'sneha@eastbranch.com',
                'status' => 'active',
            ],
            [
                'name' => 'West Branch Godown',
                'location' => 'Ahmedabad, Gujarat',
                'description' => 'Storage facility for western region distribution',
                'manager_name' => 'Vikram Singh',
                'contact_number' => '+91 98765 43214',
                'email' => 'vikram@westbranch.com',
                'status' => 'active',
            ],
            [
                'name' => 'Central Godown',
                'location' => 'Bhopal, Madhya Pradesh',
                'description' => 'Central storage facility for regional distribution',
                'manager_name' => 'Meera Joshi',
                'contact_number' => '+91 98765 43215',
                'email' => 'meera@centralgodown.com',
                'status' => 'active',
            ],
            [
                'name' => 'Express Godown',
                'location' => 'Pune, Maharashtra',
                'description' => 'Quick access storage for urgent orders',
                'manager_name' => 'Rahul Verma',
                'contact_number' => '+91 98765 43216',
                'email' => 'rahul@expressgodown.com',
                'status' => 'active',
            ],
            [
                'name' => 'Seasonal Storage',
                'location' => 'Nagpur, Maharashtra',
                'description' => 'Storage for seasonal items and bulk orders',
                'manager_name' => 'Anjali Desai',
                'contact_number' => '+91 98765 43217',
                'email' => 'anjali@seasonalstorage.com',
                'status' => 'active',
            ],
        ];

        foreach ($godowns as $godown) {
            Godown::create($godown);
        }

        $this->command->info('Godowns seeded successfully!');
    }
}
