<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Admin
        $admin = User::create([
            'name' => 'Admin Sambal',
            'email' => 'admin@sambal.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Buat cart untuk admin
        $admin->cart()->create();



        $this->command->info('admin seeded successfully!');
    }
}