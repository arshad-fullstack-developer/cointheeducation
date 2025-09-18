<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\School;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();
        
        if ($schools->isEmpty()) {
            $this->command->warn('No schools found. Please run SchoolSeeder first.');
            return;
        }

        $genders = ['male', 'female', 'other'];
        $classes = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
        $districts = ['Lahore', 'Karachi', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan', 'Peshawar', 'Quetta'];
        $tehsils = ['Central', 'North', 'South', 'East', 'West', 'Rural', 'Urban'];
        $ucs = ['UC-1', 'UC-2', 'UC-3', 'UC-4', 'UC-5', 'UC-6', 'UC-7', 'UC-8'];
        $statuses = ['active', 'inactive', 'dropped_out', 'graduated'];
        $enrollmentTypes = ['school_based', 'individual'];
        $guardianProfessions = ['Farmer', 'Teacher', 'Doctor', 'Engineer', 'Business', 'Laborer', 'Government Employee', 'Private Employee'];

        $maleNames = [
            'Ahmad Ali', 'Muhammad Hassan', 'Usman Khan', 'Ali Raza', 'Hassan Ahmed',
            'Zain Malik', 'Omar Farooq', 'Bilal Khan', 'Hamza Ali', 'Yusuf Khan',
            'Ibrahim Ali', 'Abdullah Khan', 'Muhammad Umar', 'Ahmed Hassan', 'Saad Ali'
        ];

        $femaleNames = [
            'Fatima Ali', 'Aisha Khan', 'Zara Ahmed', 'Hania Malik', 'Mariam Khan',
            'Sara Ali', 'Ayesha Hassan', 'Noor Khan', 'Layla Ahmed', 'Zainab Ali',
            'Maryam Khan', 'Hafsa Ali', 'Amina Khan', 'Khadija Ahmed', 'Sumaya Ali'
        ];

        $fatherNames = [
            'Muhammad Ali', 'Ahmed Khan', 'Hassan Ali', 'Usman Khan', 'Abdullah Ali',
            'Omar Khan', 'Bilal Ali', 'Hamza Khan', 'Yusuf Ali', 'Ibrahim Khan'
        ];

        $motherNames = [
            'Fatima Khan', 'Aisha Ali', 'Zara Khan', 'Hania Ali', 'Mariam Khan',
            'Sara Ali', 'Ayesha Khan', 'Noor Ali', 'Layla Khan', 'Zainab Ali'
        ];

        for ($i = 1; $i <= 30; $i++) {
            $gender = $genders[array_rand($genders)];
            $name = $gender === 'female' ? $femaleNames[array_rand($femaleNames)] : $maleNames[array_rand($maleNames)];
            
            // Generate a unique CTE ID
            $cteId = 'CTE' . str_pad($i, 6, '0', STR_PAD_LEFT);
            
            // Generate a realistic date of birth (between 5 and 18 years ago)
            $age = rand(5, 18);
            $dateOfBirth = now()->subYears($age)->subDays(rand(0, 365));
            
            // Generate enrollment date (within last 2 years)
            $enrollmentDate = now()->subDays(rand(0, 730));
            
            $status = $statuses[array_rand($statuses)];
            $dropoutDate = null;
            $dropoutReason = null;
            
            if ($status === 'dropped_out') {
                $dropoutDate = $enrollmentDate->addDays(rand(30, 365));
                $dropoutReasons = [
                    'Financial difficulties',
                    'Family relocation',
                    'Health issues',
                    'Employment',
                    'Marriage',
                    'Lack of interest',
                    'Transportation issues'
                ];
                $dropoutReason = $dropoutReasons[array_rand($dropoutReasons)];
            }

            Student::create([
                'cte_id' => $cteId,
                'school_id' => $schools->random()->id,
                'name' => $name,
                'date_of_birth' => $dateOfBirth,
                'gender' => $gender,
                'class' => $classes[array_rand($classes)],
                'father_name' => $fatherNames[array_rand($fatherNames)],
                'mother_name' => $motherNames[array_rand($motherNames)],
                'guardian_contact' => '03' . rand(10000000, 99999999),
                'guardian_profession' => $guardianProfessions[array_rand($guardianProfessions)],
                'address' => 'House #' . rand(1, 999) . ', Street #' . rand(1, 50) . ', ' . $districts[array_rand($districts)],
                'district' => $districts[array_rand($districts)],
                'tehsil' => $tehsils[array_rand($tehsils)],
                'uc' => $ucs[array_rand($ucs)],
                'enrollment_type' => $enrollmentTypes[array_rand($enrollmentTypes)],
                'enrollment_date' => $enrollmentDate,
                'status' => $status,
                'dropout_reason' => $dropoutReason,
                'dropout_date' => $dropoutDate,
                'notes' => rand(0, 1) ? 'Student shows good academic performance.' : null,
            ]);
        }

        $this->command->info('Students seeded successfully!');
    }
}







