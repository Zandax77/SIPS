<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Pelanggaran;

class PelanggaranSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all siswa IDs
        $siswaIds = DB::table('siswas')->pluck('id')->toArray();

        // Get all jenis_pelanggaran IDs grouped by category
        $jenisRinganIds = DB::table('jenis_pelanggarans')
            ->whereIn('id_kategori_pelanggaran', [1, 2, 3]) // Ringan categories
            ->pluck('id')
            ->toArray();

        $jenisSedangIds = DB::table('jenis_pelanggarans')
            ->whereIn('id_kategori_pelanggaran', [4, 5, 6]) // Sedang categories
            ->pluck('id')
            ->toArray();

        $jenisBeratIds = DB::table('jenis_pelanggarans')
            ->whereIn('id_kategori_pelanggaran', [7, 8, 9]) // Berat categories
            ->pluck('id')
            ->toArray();

        // Get all petugas IDs
        $petugasIds = DB::table('petugas')->pluck('id')->toArray();

        // Create sample violations for the past 7 days
        $today = now();

        // Create violations for today and past 6 days
        for ($dayOffset = 0; $dayOffset <= 6; $dayOffset++) {
            $date = $today->copy()->subDays($dayOffset);

            // Random number of violations per day (1-5 ringan, 0-3 sedang, 0-2 berat)
            $ringanCount = rand(1, 5);
            $sedangCount = rand(0, 3);
            $beratCount = rand(0, 2);

            // Create ringan violations
            for ($i = 0; $i < $ringanCount; $i++) {
                if (!empty($siswaIds) && !empty($jenisRinganIds) && !empty($petugasIds)) {
                    Pelanggaran::create([
                        'id_siswa' => $siswaIds[array_rand($siswaIds)],
                        'id_jenis_pelanggaran' => $jenisRinganIds[array_rand($jenisRinganIds)],
                        'id_petugas' => $petugasIds[array_rand($petugasIds)],
                        'deskripsi' => 'Pelanggaran ringan yang tercatat secara otomatis',
                        'created_at' => $date->copy()->setTime(rand(7, 15), rand(0, 59)),
                    ]);
                }
            }

            // Create sedang violations
            for ($i = 0; $i < $sedangCount; $i++) {
                if (!empty($siswaIds) && !empty($jenisSedangIds) && !empty($petugasIds)) {
                    Pelanggaran::create([
                        'id_siswa' => $siswaIds[array_rand($siswaIds)],
                        'id_jenis_pelanggaran' => $jenisSedangIds[array_rand($jenisSedangIds)],
                        'id_petugas' => $petugasIds[array_rand($petugasIds)],
                        'deskripsi' => 'Pelanggaran sedang yang tercatat secara otomatis',
                        'created_at' => $date->copy()->setTime(rand(7, 15), rand(0, 59)),
                    ]);
                }
            }

            // Create berat violations
            for ($i = 0; $i < $beratCount; $i++) {
                if (!empty($siswaIds) && !empty($jenisBeratIds) && !empty($petugasIds)) {
                    Pelanggaran::create([
                        'id_siswa' => $siswaIds[array_rand($siswaIds)],
                        'id_jenis_pelanggaran' => $jenisBeratIds[array_rand($jenisBeratIds)],
                        'id_petugas' => $petugasIds[array_rand($petugasIds)],
                        'deskripsi' => 'Pelanggaran berat yang tercatat secara otomatis',
                        'created_at' => $date->copy()->setTime(rand(7, 15), rand(0, 59)),
                    ]);
                }
            }
        }
    }
}

