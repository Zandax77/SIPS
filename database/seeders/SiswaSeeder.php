<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Siswa;

class SiswaSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = ['X IPA 1', 'X IPA 2', 'X IPA 3', 'X IPA 4', 'X IPS 1', 'X IPS 2', 'XI IPA 1', 'XI IPA 2', 'XI IPA 3', 'XI IPS 1', 'XI IPS 2', 'XII IPA 1', 'XII IPA 2', 'XII IPS 1', 'XII IPS 2'];

        $namaDepan = ['Ahmad', 'Budi', 'Cici', 'Dewi', 'Eka', 'Fajar', 'Gita', 'Hendra', 'Ira', 'Joko', 'Kira', 'Lulu', 'Maya', 'Nanda', 'Oki', 'Putri', 'Rudi', 'Sari', 'Tari', 'Ujang', 'Vina', 'Wawan', 'Xena', 'Yogi', 'Zara'];

        $namaBelakang = ['Saputra', 'Wijaya', 'Permana', 'Sari', 'Kusuma', 'Santoso', 'Prasetyo', 'Utama', 'Nugroho', 'Lestari', 'Susanto', 'Hartono', 'Yulianto', 'Setiawan', 'Rahmawati', 'Indah', 'Mulyono', 'Kurniawan', 'Safitri', 'Putra', 'Nur', 'Khadijah', 'Firmansah', 'Ariyanto', 'Salsabila'];

        for ($i = 1; $i <= 100; $i++) {
            $nis = '2024' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $nama = $namaDepan[array_rand($namaDepan)] . ' ' . $namaBelakang[array_rand($namaBelakang)];
            $kelasPilihan = $kelas[array_rand($kelas)];

            Siswa::create([
                'nis' => $nis,
                'name' => $nama,
                'kelas' => $kelasPilihan
            ]);
        }
    }
}

