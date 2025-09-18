<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StationaryAward;
use App\Models\Student;
use App\Models\School;
use App\Models\StationaryItem;
use App\Models\Godown;

class StationaryAwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $schools = School::all();
        $stationaryItems = StationaryItem::all();
        $godowns = Godown::all();
        
        $awardTypes = ['mobile_stationary', 'school_based', 'volunteer'];
        $statuses = ['awarded', 'returned', 'cancelled'];
        $awardedBy = ['Teacher A', 'Teacher B', 'Volunteer C', 'Principal D', 'Coordinator E'];

        for ($i = 0; $i < 25; $i++) {
            $student = $students->random();
            $stationaryItem = $stationaryItems->random();
            $quantity = rand(1, 5);
            $unitCost = $stationaryItem->unit_cost;
            $totalCost = $quantity * $unitCost;
            $status = $statuses[array_rand($statuses)];
            
            $awardDate = fake()->dateTimeBetween('-6 months', 'now');
            $returnDate = $status === 'returned' ? fake()->dateTimeBetween($awardDate, 'now') : null;

            StationaryAward::create([
                'student_id' => $student->id,
                'school_id' => $student->school_id,
                'stationary_item_id' => $stationaryItem->id,
                'godown_id' => $godowns->random()->id,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'total_cost' => $totalCost,
                'award_type' => $awardTypes[array_rand($awardTypes)],
                'awarded_by' => $awardedBy[array_rand($awardedBy)],
                'award_date' => $awardDate,
                'notes' => fake()->optional(0.7)->sentence(),
                'status' => $status,
                'return_date' => $returnDate,
                'return_notes' => $status === 'returned' ? fake()->optional(0.8)->sentence() : null,
            ]);
        }
    }
}




