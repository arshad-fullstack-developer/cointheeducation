<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StockMovement;
use App\Models\StationaryItem;
use App\Models\Godown;
use App\Models\User;

class StockMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing data
        $stationaryItems = StationaryItem::all();
        $godowns = Godown::all();
        $users = User::all();

        if ($stationaryItems->isEmpty() || $godowns->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Please run StationaryItemSeeder, GodownSeeder, and UserSeeder first!');
            return;
        }

        $movementTypes = ['transfer', 'adjustment', 'return'];
        $statuses = ['pending', 'completed', 'cancelled'];

        // Create 20 dummy stock movements
        for ($i = 1; $i <= 20; $i++) {
            $item = $stationaryItems->random();
            $fromGodown = $godowns->random();
            $toGodown = $godowns->random();
            $user = $users->random();
            $movementType = $movementTypes[array_rand($movementTypes)];
            $status = $statuses[array_rand($statuses)];

            // For adjustments, from and to godown should be the same
            if ($movementType === 'adjustment') {
                $toGodown = $fromGodown;
            }

            // Generate realistic quantities and costs
            $quantity = rand(5, 100);
            $unitCost = $item->unit_cost ?? rand(10, 500);
            $totalCost = $quantity * $unitCost;

            StockMovement::create([
                'stationary_item_id' => $item->id,
                'from_godown_id' => $fromGodown->id,
                'to_godown_id' => $toGodown->id,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'total_cost' => $totalCost,
                'movement_type' => $movementType,
                'status' => $status,
                'movement_date' => now()->subDays(rand(1, 30)),
                'moved_by' => $user->name,
                'reason' => $this->generateNotes($movementType, $item->name, $fromGodown->name, $toGodown->name),
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        $this->command->info('Stock Movements seeded successfully!');
    }

    private function generateNotes($movementType, $itemName, $fromGodown, $toGodown): string
    {
        $notes = [
            'transfer' => [
                "Transfer of {$itemName} from {$fromGodown} to {$toGodown}",
                "Moving {$itemName} between warehouses for better distribution",
                "Relocating {$itemName} stock to optimize storage",
                "Transfer initiated for inventory rebalancing",
                "Moving {$itemName} to meet demand in {$toGodown}"
            ],
            'adjustment' => [
                "Stock adjustment for {$itemName} in {$fromGodown}",
                "Inventory correction for {$itemName}",
                "Stock count adjustment for {$itemName}",
                "Correcting {$itemName} inventory levels",
                "Stock reconciliation for {$itemName}"
            ],
            'return' => [
                "Return of {$itemName} from {$toGodown} to {$fromGodown}",
                "Customer return of {$itemName}",
                "Defective item return for {$itemName}",
                "Return processing for {$itemName}",
                "Inventory return for {$itemName}"
            ]
        ];

        $typeNotes = $notes[$movementType] ?? $notes['transfer'];
        return $typeNotes[array_rand($typeNotes)];
    }
}
