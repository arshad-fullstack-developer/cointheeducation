<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\StationaryItem;
use App\Models\Godown;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $stationaryItems = StationaryItem::all();
        $godowns = Godown::all();
        
        $salespersons = ['Rahul Sharma', 'Priya Patel', 'Amit Kumar', 'Neha Singh', 'Rajesh Verma'];
        $paymentTypes = ['cash', 'credit'];
        $statuses = ['pending', 'completed', 'cancelled'];

        for ($i = 1; $i <= 25; $i++) {
            $customer = $customers->random();
            $item = $stationaryItems->random();
            $godown = $godowns->random();
            $quantity = rand(1, 10);
            $unitPrice = $item->selling_price ?? rand(50, 500);
            $totalAmount = $quantity * $unitPrice;
            $profitAmount = ($unitPrice - ($item->cost_price ?? $unitPrice * 0.7)) * $quantity;
            $donationAmount = rand(0, 1) ? rand(10, 100) : 0;

            Sale::create([
                'invoice_number' => 'INV' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'stationary_item_id' => $item->id,
                'godown_id' => $godown->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'profit_amount' => $profitAmount,
                'donation_amount' => $donationAmount,
                'salesperson' => $salespersons[array_rand($salespersons)],
                'payment_type' => $paymentTypes[array_rand($paymentTypes)],
                'sale_date' => now()->subDays(rand(0, 30)),
                'project_details' => rand(0, 1) ? 'School project for ' . $customer->name : null,
                'public_message' => rand(0, 1) ? 'Thank you for your purchase!' : null,
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }
}
