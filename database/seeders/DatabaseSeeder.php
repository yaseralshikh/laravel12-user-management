<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(SectorSeeder::class);
        $this->call(LaratrustSeeder::class);  

        $users = [
            [
                'nastional_id' => '1234567890',
                'email' => 'superadmin@app.com',
                'name' => 'Super Admin',
                'password' => 'password',
                'role' => 'superadmin',
            ],
            [
                'nastional_id' => '0987654321',
                'email' => 'admin1@app.com',
                'name' => 'Admin',
                'password' => 'password',
                'role' => 'admin',
            ],
            [
                'nastional_id' => '1122334455',
                'email' => 'supervisor1@app.com',
                'name' => 'Supervisor1',
                'sector_id' => 1,
                'password' => 'password',

                'role' => 'supervisor',
            ],
            [
                'nastional_id' => '6677889900',
                'email' => 'principal1@app.com',
                'name' => 'Principal1',
                'sector_id' => 1,
                'password' => 'password',
                'role' => 'principal',
            ],
            [
                'nastional_id' => '9988776655',
                'email' => 'principal2@app.com',
                'name' => 'Principal2',
                'sector_id' => 1,
                'password' => 'password',
                'role' => 'principal',
            ],
            [
                'nastional_id' => '5544332211',
                'email' => 'coordinator1@app.com',
                'name' => 'Coordinator1',
                'sector_id' => 1,
                'password' => 'password',
                'role' => 'coordinator',
            ],
            [
                'nastional_id' => '2233445566',
                'email' => 'coordinator2@app.com',
                'name' => 'Coordinator2',
                'sector_id' => 1,
                'password' => 'password',
                'role' => 'coordinator',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'nastional_id' => $userData['nastional_id'],
                    'name' => $userData['name'],
                    'sector_id' => $userData['sector_id'] ?? null,
                    'password' => bcrypt($userData['password']),
                    'email_verified_at' => now(),
                ]
            );

            $user->addRole($userData['role']);
        }

        $this->call(SchoolSeeder::class);
        $this->call(AcademicYearSeeder::class);
    }
}
