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
     */
    public function run(): void
    {
        $kategori = [
            [
                'nama' => 'Ringan',
                'poin' => 5
            ],
            [
                'nama' => 'Ringan',
                'poin' => 10
            ],
            [
                'nama' => 'Ringan',
                'poin' => 15
            ],
            [
                'nama' => 'Sedang',
                'poin' => 20
            ],
            [
                'nama' => 'Sedang',
                'poin' => 30
            ],
            [
                'nama' => 'Sedang',
                'poin' => 40
            ],
            [
                'nama' => 'Berat',
                'poin' => 50
            ],
            [
                'nama' => 'Berat',
                'poin' => 75
            ],
            [
                'nama' => 'Berat',
                'poin' => 100
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

