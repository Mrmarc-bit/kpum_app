<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Mahasiswa;

class StressTestSeeder extends Seeder
{
    public function run()
    {
        $count = 100;
        $file = fopen(base_path('test_users.csv'), 'w');
        fputcsv($file, ['nim', 'password', 'access_code']);
        
        // Ensure table is clean for test users to avoid duplicates
        // Mahasiswa::where('nim', 'like', 'TEST%')->delete();

        for ($i = 1; $i <= $count; $i++) {
            $nim = 'TEST' . str_pad($i, 5, '0', STR_PAD_LEFT);
            $passwordRaw = '01012000'; // DDMMYYYY
            $passwordHash = Hash::make($passwordRaw);
            $accessCode = 'AC' . str_pad($i, 5, '0', STR_PAD_LEFT);
            
            Mahasiswa::updateOrCreate(
                ['nim' => $nim],
                [
                    'name' => 'Load Test User ' . $i,
                    'email' => 'loadtest' . $i . '@kpum.test',
                    'password' => $passwordHash,
                    'dob' => '2000-01-01',
                    'access_code' => $accessCode,
                    'prodi' => 'Informatika (INF)', // Must match enum
                    'fakultas' => 'Fakultas Matematika dan Komputer (FMIKOM)',
                ]
            );

            fputcsv($file, [$nim, $passwordRaw, $accessCode]);
        }

        fclose($file);
        $this->command->info("Created $count test users. Credentials in test_users.csv");
    }
}
