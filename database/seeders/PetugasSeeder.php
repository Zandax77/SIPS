<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Petugas;

class PetugasSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin (Main Admin)
        Petugas::create([
            'name' => 'Super Admin',
            'email' => 'admin@sips.test',
            'password' => Hash::make('admin123'),
            'jabatan' => 'Super Admin',
            'kelas' => null,
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Create Kesiswaan user
        Petugas::create([
            'name' => 'Petugas Kesiswaan',
            'email' => 'kesiswaan@sips.test',
            'password' => Hash::make('password123'),
            'jabatan' => 'Kesiswaan',
            'kelas' => null,
            'role' => 'petugas',
            'status' => 'active',
        ]);

        // Create Wali Kelas for X IPA 1
        Petugas::create([
            'name' => 'Wali Kelas X IPA 1',
            'email' => 'wali.xipa1@sips.test',
            'password' => Hash::make('password123'),
            'jabatan' => 'Wali Kelas',
            'kelas' => 'X IPA 1',
            'role' => 'petugas',
            'status' => 'active',
        ]);

        // Create Wali Kelas for X IPA 2
        Petugas::create([
            'name' => 'Wali Kelas X IPA 2',
            'email' => 'wali.xipa2@sips.test',
            'password' => Hash::make('password123'),
            'jabatan' => 'Wali Kelas',
            'kelas' => 'X IPA 2',
            'role' => 'petugas',
            'status' => 'active',
        ]);

        // Create Wali Kelas for X IPS 1
        Petugas::create([
            'name' => 'Wali Kelas X IPS 1',
            'email' => 'wali.xips1@sips.test',
            'password' => Hash::make('password123'),
            'jabatan' => 'Wali Kelas',
            'kelas' => 'X IPS 1',
            'role' => 'petugas',
            'status' => 'active',
        ]);

        // Create Guru BK
        Petugas::create([
            'name' => 'Guru BK',
            'email' => 'bk@sips.test',
            'password' => Hash::make('password123'),
            'jabatan' => 'Guru BK',
            'kelas' => null,
            'role' => 'petugas',
            'status' => 'active',
        ]);
    }
}

