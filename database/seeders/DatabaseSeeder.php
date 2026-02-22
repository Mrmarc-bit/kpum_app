<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@kpum.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Panitia
        User::create([
            'name' => 'Panitia KPUM',
            'email' => 'panitia@kpum.ac.id',
            'password' => Hash::make('password'),
            'role' => 'panitia',
            'email_verified_at' => now(),
        ]);

        // Mahasiswa
        User::create([
            'name' => 'Mahasiswa Test',
            'email' => 'mahasiswa@kpum.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
        ]);
        // Default Settings
        \App\Models\Setting::create([
            'key' => 'show_candidates',
            'value' => 'true',
        ]);
    }
}
