<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Procurement;
use App\Models\StationaryItem;
use App\Models\Godown;

class ProcurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stationaryItems = StationaryItem::all();
        $godowns = Godown::all();

        if ($stationaryItems->isEmpty() || $godowns->isEmpty()) {
            $this->command->warn('No stationary items or godowns found. Skipping procurement seeding.');
            return;
        }

        $companies = [
            'ABC Stationery Supplies',
            'XYZ Office Solutions',
            'Premium Paper Co.',
            'Global Writing Instruments',
            'Eco-Friendly Stationery',
            'Professional Office Supplies',
            'Quality Paper Products',
            'Modern Stationery Hub',
        ];

        $salespersons = [
            'Rajesh Kumar',
            'Priya Sharma',
            'Amit Patel',
            'Sneha Singh',
            'Vikram Malhotra',
            'Anjali Gupta',
            'Rahul Verma',
            'Meera Kapoor',
        ];

        $statuses = ['pending', 'received', 'cancelled'];
        $paymentTypes = ['cash', 'credit'];

        for ($i = 1; $i <= 20; $i++) {
            $stationaryItem = $stationaryItems->random();
            $godown = $godowns->random();
            $quantity = rand(10, 500);
            $unitCost = rand(5, 200);
            $totalCost = $quantity * $unitCost;

            Procurement::create([
                'stationary_item_id' => $stationaryItem->id,
                'godown_id' => $godown->id,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'total_cost' => $totalCost,
                'company' => $companies[array_rand($companies)],
                'salesperson' => $salespersons[array_rand($salespersons)],
                'payment_type' => $paymentTypes[array_rand($paymentTypes)],
                'procurement_date' => now()->subDays(rand(1, 365)),
                'invoice_number' => 'INV-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'notes' => rand(0, 1) ? 'Sample procurement note for testing purposes.' : null,
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }
}














