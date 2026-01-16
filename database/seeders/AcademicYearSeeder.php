<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicYears = [
            [
                'name' => '2024-2025',
                'starts_on' => Carbon::create('2024', '09', '01'),
                'ends_on' => Carbon::create('2025', '06', '30'),
                'status' => 'inactive',
            ],
            [
                'name' => '2025-2026',
                'starts_on' => Carbon::create('2025', '09', '01'),
                'ends_on' => Carbon::create('2026', '06', '30'),
                'status' => 'active',
            ],
        ];

        foreach ($academicYears as $year) {
            AcademicYear::firstOrCreate(
                ['name' => $year['name']],
                [
                    'starts_on' => $year['starts_on'],
                    'ends_on' => $year['ends_on'],
                    'status' => $year['status'],
                ]
            );
        }
    }
}
