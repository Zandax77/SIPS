<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriPelanggaran;

class KategoriPelanggaranSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * Skema Poin Pelanggaran untuk Siswa Usia Remaja hingga 20 Tahun
     * Kategori: Ringan, Sedang, Berat dengan rentang poin 5-100
     */
    public function run(): void
    {
        $kategori = [
            // Kategori Ringan
            [
                'nama' => 'Ringan',
                'poin' => 5,
                'deskripsi' => 'Pelanggaran kedisiplinan dan administratif ringan'
            ],
            [
                'nama' => 'Ringan',
                'poin' => 10,
                'deskripsi' => 'Pelanggaran kedisiplinan dan akademik ringan'
            ],
            [
                'nama' => 'Ringan',
                'poin' => 15,
                'deskripsi' => 'Pelanggaran kebersihan dan ketertiban ringan'
            ],
            // Kategori Sedang
            [
                'nama' => 'Sedang',
                'poin' => 20,
                'deskripsi' => 'Pelanggaran kedisiplinan dan kehadiran sedang'
            ],
            [
                'nama' => 'Sedang',
                'poin' => 30,
                'deskripsi' => 'Pelanggaran kesehatan dan penggunaan barang terlarang'
            ],
            [
                'nama' => 'Sedang',
                'poin' => 40,
                'deskripsi' => 'Pelanggaran kejujuran dan kekerasan ringan'
            ],
            // Kategori Berat
            [
                'nama' => 'Berat',
                'poin' => 50,
                'deskripsi' => 'Pelanggaran kekerasan dan kejujuran serius'
            ],
            [
                'nama' => 'Berat',
                'poin' => 75,
                'deskripsi' => 'Pelanggaran pelecehan dan perusakan properti'
            ],
            [
                'nama' => 'Berat',
                'poin' => 100,
                'deskripsi' => 'Pelanggaran kriminal dan sangat berat'
            ],
        ];

        foreach ($kategori as $k) {
            KategoriPelanggaran::create([
                'nama' => $k['nama'],
                'poin' => $k['poin']
            ]);
        }
    }
}

