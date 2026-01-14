<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectors = [
            'وسط جازان وفرسان',
            'أبوعريش',
            'العارضة',
            'صامطة',
            'المسارحة والحرث',
            'صبيا وضمد',
            'العيدابي وهروب والداير وفيفا',
            'بيش والريث والدرب',
        ];

        foreach ($sectors as $sector) {
            Sector::updateOrCreate(
                ['name' => $sector],
                [
                    'description' => null,
                    'status' => 'active',
                ]
            );
        }
    }
}
