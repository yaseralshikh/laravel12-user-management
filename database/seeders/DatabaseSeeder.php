<?php

namespace Database\Seeders;

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
        $this->call(LaratrustSeeder::class);

        $users = [
            [
                'email' => 'superadmin@app.com',
                'name' => 'Super Admin',
                'password' => 'password',
                'role' => 'superadmin',
            ],
            [
                'email' => 'admin1@app.com',
                'name' => 'Admin',
                'password' => 'password',
                'role' => 'admin',
            ],
            [
                'email' => 'supervisor1@app.com',
                'name' => 'Supervisor1',
                'educational_sector' => 'وسط جازان وفرسان',
                'password' => 'password',
                'role' => 'supervisor',
            ],
            [
                'email' => 'principal1@app.com',
                'name' => 'Principal1',
                'educational_sector' => 'وسط جازان وفرسان',
                'password' => 'password',
                'role' => 'principal',
            ],
            [
                'email' => 'coordinator1@app.com',
                'name' => 'Coordinator1',
                'educational_sector' => 'وسط جازان وفرسان',
                'password' => 'password',
                'role' => 'coordinator',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'educational_sector' => $userData['educational_sector'] ?? null,
                    'password' => bcrypt($userData['password']),
                    'email_verified_at' => now(),
                ]
            );

            $user->addRole($userData['role']);
        }

        $this->call(SchoolSeeder::class);
    }
}
