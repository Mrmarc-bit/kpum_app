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
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@kpum.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Panitia
        User::factory()->create([
            'name' => 'Panitia KPUM',
            'email' => 'panitia@kpum.ac.id',
            'password' => Hash::make('password'),
            'role' => 'panitia',
        ]);

        // Mahasiswa
        User::factory()->create([
            'name' => 'Mahasiswa Test',
            'email' => 'mahasiswa@kpum.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);
        // Default Settings
        \App\Models\Setting::create([
            'key' => 'show_candidates',
            'value' => 'true',
        ]);
    }
}
