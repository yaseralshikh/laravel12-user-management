<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * name
     * ministry_code
     * gender
     * stage
     * is_complex
     * school_type
     * building_type
     * status
     * educational_sector
     * coordinator_id
     * principal_id
     **/


    public function run(): void
    {
        $schools = [
            [
                'name' => 'الفيصلية',
                'ministry_code' => 'SCH001',
                'gender' => 'بنين',
                'stage' => 'ابتدائي',
                'is_complex' => false,
                'school_type' => 'حكومي',
                'building_type' => 'حكومي',
                'status' => 'نشط',
                'sector_id' => 1,
                'coordinator_id' => 6,
                'principal_id' => 4,
            ],
                        [
                'name' => 'علي بن أبي طالب',
                'ministry_code' => 'SCH002',
                'gender' => 'بنين',
                'stage' => 'ابتدائي',
                'is_complex' => true,
                'school_type' => 'حكومي',
                'building_type' => 'حكومي',
                'status' => 'نشط',
                'sector_id' => 1,
                'coordinator_id' => 7,
                'principal_id' => 5,
            ],
                        [
                'name' => 'علي بن أبي طالب',
                'ministry_code' => 'SCH003',
                'gender' => 'بنين',
                'stage' => 'متوسط',
                'is_complex' => true,
                'school_type' => 'حكومي',
                'building_type' => 'حكومي',
                'status' => 'نشط',
                'sector_id' => 1,
                'coordinator_id' => 7,
                'principal_id' => 5,
            ],
                        [
                'name' => 'علي بن أبي طالب',
                'ministry_code' => 'SCH004',
                'gender' => 'بنين',
                'stage' => 'ثانوي',
                'is_complex' => true,
                'school_type' => 'حكومي',
                'building_type' => 'حكومي',
                'status' => 'نشط',
                'sector_id' => 1,
                'coordinator_id' => 7,
                'principal_id' => 5,
            ],
        ];
        
        foreach ($schools as $schoolData) {
            School::firstOrCreate(
                [
                    'name' => $schoolData['name'],
                    'ministry_code' => $schoolData['ministry_code'],
                    'gender' => $schoolData['gender'],
                    'stage' => $schoolData['stage'],
                    'is_complex' => $schoolData['is_complex'],
                    'school_type' => $schoolData['school_type'],
                    'building_type' => $schoolData['building_type'],
                    'status' => $schoolData['status'],
                    'sector_id' => $schoolData['sector_id'],
                    'coordinator_id' => $schoolData['coordinator_id'],
                    'principal_id' => $schoolData['principal_id'],
                ],
            );
        }
    }
}
