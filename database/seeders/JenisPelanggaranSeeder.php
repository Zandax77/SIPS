<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisPelanggaran;

class JenisPelanggaranSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisPelanggaran = [
            // Kategori Ringan (poin 5)
            [
                'id_kategori_pelanggaran' => 1,
                'nama' => 'Terlambat datang ke sekolah (1-15 menit)',
                'deskripsi' => 'Siswa datang terlambat ke sekolah melebihi batas toleransi'
            ],
            [
                'id_kategori_pelanggaran' => 1,
                'nama' => 'Tidak membawa raport',
                'deskripsi' => 'Siswa tidak membawa raport pada saat yang diminta'
            ],
            [
                'id_kategori_pelanggaran' => 1,
                'nama' => 'Seragam tidak rapi',
                'deskripsi' => 'Pakaian seragam tidak sesuai ketentuan (panjang, lipatan, dll)'
            ],
            [
                'id_kategori_pelanggaran' => 1,
                'nama' => 'Dinas luar tanpa izin',
                'deskripsi' => 'Keluar kelas tanpa izin guru yang mengajar'
            ],
            // Kategori Ringan (poin 10)
            [
                'id_kategori_pelanggaran' => 2,
                'nama' => 'Terlambat datang ke sekolah (16-30 menit)',
                'deskripsi' => 'Siswa datang terlambat ke sekolah 16-30 menit'
            ],
            [
                'id_kategori_pelanggaran' => 2,
                'nama' => 'Tidak mengerjakan PR',
                'deskripsi' => 'Siswa tidak mengerjakan tugas/PR yang diberikan'
            ],
            [
                'id_kategori_pelanggaran' => 2,
                'nama' => 'Membawa mainan yang tidak perlu',
                'deskripsi' => 'Membawa barang/mainan yang tidak diperlukan untuk pembelajaran'
            ],
            // Kategori Ringan (poin 15)
            [
                'id_kategori_pelanggaran' => 3,
                'nama' => 'Kelas tidak rapi',
                'deskripsi' => 'Meja/kursi tidak rapih setelah pembelajaran selesai'
            ],
            [
                'id_kategori_pelanggaran' => 3,
                'nama' => 'Membuang sampah sembarangan',
                'deskripsi' => 'Membuang sampah tidak pada tempatnya'
            ],
            // Kategori Sedang (poin 20)
            [
                'id_kategori_pelanggaran' => 4,
                'nama' => 'Membolos pelajaran',
                'deskripsi' => 'Tidak mengikuti pembelajaran tanpa alasan yang jelas'
            ],
            [
                'id_kategori_pelanggaran' => 4,
                'nama' => 'Terlambat datang ke sekolah (>30 menit)',
                'deskripsi' => 'Siswa datang terlambat ke sekolah lebih dari 30 menit'
            ],
            // Kategori Sedang (poin 30)
            [
                'id_kategori_pelanggaran' => 5,
                'nama' => 'Merokok di sekolah',
                'deskripsi' => 'Siswa merokok di lingkungan sekolah'
            ],
            [
                'id_kategori_pelanggaran' => 5,
                'nama' => 'HP/Smartphone saat pembelajaran',
                'deskripsi' => 'Menggunakan HP/smartphone saat jam pembelajaran'
            ],
            [
                'id_kategori_pelanggaran' => 5,
                'nama' => 'Membolos lebih dari 1 jam',
                'deskripsi' => 'Tidak mengikuti pembelajaran lebih dari 1 jam'
            ],
            // Kategori Sedang (poin 40)
            [
                'id_kategori_pelanggaran' => 6,
                'nama' => 'Membawa elektronik berlebihan',
                'deskripsi' => 'Membawa banyak elektronik tanpa izin yang jelas'
            ],
            [
                'id_kategori_pelanggaran' => 6,
                'nama' => 'Mencontek saat ujian',
                'deskripsi' => 'Melakukan kecurangan saat ujian/ulangan'
            ],
            // Kategori Berat (poin 50)
            [
                'id_kategori_pelanggaran' => 7,
                'nama' => 'Tawuran antar siswa',
                'deskripsi' => 'Melakukan tawuran/perkelahian dengan siswa lain'
            ],
            [
                'id_kategori_pelanggaran' => 7,
                'nama' => 'Menyontek saat ujian nasional',
                'deskripsi' => 'Melakukan kecurangan serius saat ujian nasional'
            ],
            // Kategori Berat (poin 75)
            [
                'id_kategori_pelanggaran' => 8,
                'nama' => 'Pelecehan verbal',
                'deskripsi' => 'Melakukan pelecehan verbal terhadap siswa/guru/staf'
            ],
            [
                'id_kategori_pelanggaran' => 8,
                'nama' => 'Vandalisme',
                'deskripsi' => 'Merusak fasilitas sekolah secara sengaja'
            ],
            // Kategori Berat (poin 100)
            [
                'id_kategori_pelanggaran' => 9,
                'nama' => 'Pelecehan fisik',
                'deskripsi' => 'Melakukan pelecehan atau tindakan tidak senonoh secara fisik'
            ],
            [
                'id_kategori_pelanggaran' => 9,
                'nama' => 'Narkoba/minuman keras',
                'deskripsi' => 'Membawa, menggunakan, atau mengedarkan obat/narkoba/minuman keras'
            ],
            [
                'id_kategori_pelanggaran' => 9,
                'nama' => 'Pencurian',
                'deskripsi' => 'Mencuri barang milik sekolah, guru, atau siswa lain'
            ],
        ];

        foreach ($jenisPelanggaran as $j) {
            JenisPelanggaran::create([
                'id_kategori_pelanggaran' => $j['id_kategori_pelanggaran'],
                'nama' => $j['nama'],
                'deskripsi' => $j['deskripsi']
            ]);
        }
    }
}

