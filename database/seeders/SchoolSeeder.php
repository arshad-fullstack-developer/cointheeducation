<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Government High School Lahore',
                'school_id' => 'GHS001',
                'address' => '123 Main Street, Lahore',
                'district' => 'Lahore',
                'tehsil' => 'Lahore City',
                'uc' => 'UC-01',
                'principal_name' => 'Dr. Ahmed Khan',
                'contact_number' => '+92-300-1234567',
                'email' => 'principal@ghslahore.edu.pk',
                'total_students' => 1200,
                'total_teachers' => 45,
                'school_type' => 'government',
                'status' => 'active',
                'registration_date' => '2020-01-15',
                'notes' => 'Premier government school in Lahore district',
            ],
            [
                'name' => 'Beaconhouse School System',
                'school_id' => 'BSS002',
                'address' => '456 Gulberg III, Lahore',
                'district' => 'Lahore',
                'tehsil' => 'Lahore City',
                'uc' => 'UC-02',
                'principal_name' => 'Ms. Sarah Ahmed',
                'contact_number' => '+92-300-2345678',
                'email' => 'principal@beaconhouse.edu.pk',
                'total_students' => 800,
                'total_teachers' => 35,
                'school_type' => 'private',
                'status' => 'active',
                'registration_date' => '2019-03-20',
                'notes' => 'Leading private school with modern facilities',
            ],
            [
                'name' => 'Madrassa Al-Huda',
                'school_id' => 'MAH003',
                'address' => '789 Model Town, Lahore',
                'district' => 'Lahore',
                'tehsil' => 'Lahore City',
                'uc' => 'UC-03',
                'principal_name' => 'Maulana Abdul Rahman',
                'contact_number' => '+92-300-3456789',
                'email' => 'principal@alhuda.edu.pk',
                'total_students' => 300,
                'total_teachers' => 15,
                'school_type' => 'madrassa',
                'status' => 'active',
                'registration_date' => '2021-06-10',
                'notes' => 'Islamic education center with modern curriculum',
            ],
            [
                'name' => 'Lahore Grammar School',
                'school_id' => 'LGS004',
                'address' => '321 Defence Housing Authority, Lahore',
                'district' => 'Lahore',
                'tehsil' => 'Lahore City',
                'uc' => 'UC-04',
                'principal_name' => 'Dr. Fatima Ali',
                'contact_number' => '+92-300-4567890',
                'email' => 'principal@lgs.edu.pk',
                'total_students' => 950,
                'total_teachers' => 40,
                'school_type' => 'private',
                'status' => 'active',
                'registration_date' => '2018-09-05',
                'notes' => 'Excellence in education with international standards',
            ],
            [
                'name' => 'Government Girls High School',
                'school_id' => 'GGHS005',
                'address' => '654 Faisal Town, Lahore',
                'district' => 'Lahore',
                'tehsil' => 'Lahore City',
                'uc' => 'UC-05',
                'principal_name' => 'Ms. Ayesha Khan',
                'contact_number' => '+92-300-5678901',
                'email' => 'principal@gghs.edu.pk',
                'total_students' => 750,
                'total_teachers' => 30,
                'school_type' => 'government',
                'status' => 'active',
                'registration_date' => '2020-11-12',
                'notes' => 'Dedicated to girls education and empowerment',
            ],
            [
                'name' => 'Madrassa Al-Noor',
                'school_id' => 'MAN006',
                'address' => '987 Johar Town, Lahore',
                'district' => 'Lahore',
                'tehsil' => 'Lahore City',
                'uc' => 'UC-06',
                'principal_name' => 'Maulana Muhammad Hassan',
                'contact_number' => '+92-300-6789012',
                'email' => 'principal@alnoor.edu.pk',
                'total_students' => 250,
                'total_teachers' => 12,
                'school_type' => 'madrassa',
                'status' => 'active',
                'registration_date' => '2022-02-18',
                'notes' => 'Traditional Islamic education with modern approach',
            ],
            [
                'name' => 'Aitchison College',
                'school_id' => 'AC007',
                'address' => '147 Mall Road, Lahore',
                'district' => 'Lahore',
                'tehsil' => 'Lahore City',
                'uc' => 'UC-07',
                'principal_name' => 'Mr. James Wilson',
                'contact_number' => '+92-300-7890123',
                'email' => 'principal@aitchison.edu.pk',
                'total_students' => 1100,
                'total_teachers' => 50,
                'school_type' => 'private',
                'status' => 'active',
                'registration_date' => '2017-07-25',
                'notes' => 'Historic institution with rich traditions',
            ],
            [
                'name' => 'Government Elementary School',
                'school_id' => 'GES008',
                'address' => '258 Shadman, Lahore',
                'district' => 'Lahore',
                'tehsil' => 'Lahore City',
                'uc' => 'UC-08',
                'principal_name' => 'Ms. Rukhsana Bibi',
                'contact_number' => '+92-300-8901234',
                'email' => 'principal@ges.edu.pk',
                'total_students' => 400,
                'total_teachers' => 18,
                'school_type' => 'government',
                'status' => 'active',
                'registration_date' => '2021-04-30',
                'notes' => 'Primary education focus with strong foundation',
            ],
            [
                'name' => 'The City School',
                'school_id' => 'TCS009',
                'address' => '369 Gulberg II, Lahore',
                'district' => 'Lahore',
                'tehsil' => 'Lahore City',
                'uc' => 'UC-09',
                'principal_name' => 'Ms. Nida Hassan',
                'contact_number' => '+92-300-9012345',
                'email' => 'principal@cityschool.edu.pk',
                'total_students' => 850,
                'total_teachers' => 38,
                'school_type' => 'private',
                'status' => 'active',
                'registration_date' => '2019-12-08',
                'notes' => 'Quality education with holistic development',
            ],
            [
                'name' => 'Madrassa Al-Falah',
                'school_id' => 'MAF010',
                'address' => '741 Garden Town, Lahore',
                'district' => 'Lahore',
                'tehsil' => 'Lahore City',
                'uc' => 'UC-10',
                'principal_name' => 'Maulana Ibrahim Ali',
                'contact_number' => '+92-300-0123456',
                'email' => 'principal@alfalah.edu.pk',
                'total_students' => 180,
                'total_teachers' => 10,
                'school_type' => 'madrassa',
                'status' => 'active',
                'registration_date' => '2022-08-14',
                'notes' => 'Religious education with contemporary subjects',
            ],
        ];

        foreach ($schools as $school) {
            School::create($school);
        }
    }
}













