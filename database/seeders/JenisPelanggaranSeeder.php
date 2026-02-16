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
     *
     * Skema Poin Pelanggaran untuk Siswa Usia Remaja hingga 20 Tahun
     * - Kategori Ringan (Poin 5, 10, 15)
     * - Kategori Sedang (Poin 20, 30, 40)
     * - Kategori Berat (Poin 50, 75, 100)
     */
    public function run(): void
    {
        $jenisPelanggaran = [
            // ============================================
            // KATEGORI RINGAN - POIN 5
            // ============================================
            [
                'id_kategori_pelanggaran' => 1,
                'nama' => 'Terlambat datang ke sekolah (1-15 menit)',
                'deskripsi' => 'Siswa datang terlambat ke sekolah melebihi batas toleransi 15 menit'
            ],
            [
                'id_kategori_pelanggaran' => 1,
                'nama' => 'Tidak membawa raport',
                'deskripsi' => 'Siswa tidak membawa raport/buku nilai pada saat yang diminta'
            ],
            [
                'id_kategori_pelanggaran' => 1,
                'nama' => 'Seragam tidak rapi',
                'deskripsi' => 'Pakaian seragam tidak sesuai ketentuan (panjang, lipatan, dll)'
            ],
            [
                'id_kategori_pelanggaran' => 1,
                'nama' => 'Dinas keluar kelas tanpa izin',
                'deskripsi' => 'Keluar kelas tanpa izin guru yang mengajar'
            ],
            [
                'id_kategori_pelanggaran' => 1,
                'nama' => 'Berbicara tidak sopan (ringan)',
                'deskripsi' => 'Menggunakan bahasa yang tidak sopan dalam percakapan sehari-hari'
            ],
            // ============================================
            // KATEGORI RINGAN - POIN 10
            // ============================================
            [
                'id_kategori_pelanggaran' => 2,
                'nama' => 'Terlambat datang ke sekolah (16-30 menit)',
                'deskripsi' => 'Siswa datang terlambat ke sekolah 16-30 menit'
            ],
            [
                'id_kategori_pelanggaran' => 2,
                'nama' => 'Tidak mengerjakan PR',
                'deskripsi' => 'Siswa tidak mengerjakan tugas/PR yang diberikan guru'
            ],
            [
                'id_kategori_pelanggaran' => 2,
                'nama' => 'Membawa barang tidak diperlukan',
                'deskripsi' => 'Membawa barang/mainan yang tidak diperlukan untuk pembelajaran'
            ],
            [
                'id_kategori_pelanggaran' => 2,
                'nama' => 'Tidak membawa alat tulis',
                'deskripsi' => 'Siswa tidak membawa alat tulis penting untuk pembelajaran'
            ],
            // ============================================
            // KATEGORI RINGAN - POIN 15
            // ============================================
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
            [
                'id_kategori_pelanggaran' => 3,
                'nama' => 'Merusak tanaman sekolah',
                'deskripsi' => 'Memotong/membuang tanaman sekolah tanpa izin'
            ],
            // ============================================
            // KATEGORI SEDANG - POIN 20
            // ============================================
            [
                'id_kategori_pelanggaran' => 4,
                'nama' => 'Membolos pelajaran',
                'deskripsi' => 'Tidak mengikuti pembelajaran tanpa alasan yang jelas (satu jam)'
            ],
            [
                'id_kategori_pelanggaran' => 4,
                'nama' => 'Terlambat datang ke sekolah (>30 menit)',
                'deskripsi' => 'Siswa datang terlambat ke sekolah lebih dari 30 menit'
            ],
            [
                'id_kategori_pelanggaran' => 4,
                'nama' => 'Tidak mengikuti kegiatan sekolah',
                'deskripsi' => 'Tidak mengikuti kegiatan sekolah wajib tanpa alasan'
            ],
            // ============================================
            // KATEGORI SEDANG - POIN 30
            // ============================================
            [
                'id_kategori_pelanggaran' => 5,
                'nama' => 'Merokok di sekolah',
                'deskripsi' => 'Siswa merokok di lingkungan sekolah'
            ],
            [
                'id_kategori_pelanggaran' => 5,
                'nama' => 'HP/Smartphone saat pembelajaran',
                'deskripsi' => 'Menggunakan HP/smartphone saat jam pembelajaran tanpa izin'
            ],
            [
                'id_kategori_pelanggaran' => 5,
                'nama' => 'Membolos lebih dari 1 jam',
                'deskripsi' => 'Tidak mengikuti pembelajaran lebih dari 1 jam'
            ],
            // ============================================
            // KATEGORI SEDANG - POIN 40
            // ============================================
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
            [
                'id_kategori_pelanggaran' => 6,
                'nama' => 'Memalsukan tanda tangan',
                'deskripsi' => 'Memalsukan tanda tangan orang tua atau guru'
            ],
            [
                'id_kategori_pelanggaran' => 6,
                'nama' => 'Berkelahi ringan',
                'deskripsi' => 'Melakukan perkelahian ringan dengan siswa lain'
            ],
            // ============================================
            // KATEGORI BERAT - POIN 50
            // ============================================
            [
                'id_kategori_pelanggaran' => 7,
                'nama' => 'Tawuran antar siswa',
                'deskripsi' => 'Melakukan tawuran/perkelahian dengan siswa lain'
            ],
            [
                'id_kategori_pelanggaran' => 7,
                'nama' => 'Mencontek saat ujian nasional',
                'deskripsi' => 'Melakukan kecurangan serius saat ujian nasional'
            ],
            [
                'id_kategori_pelanggaran' => 7,
                'nama' => 'Masuk tempat terlarang',
                'deskripsi' => 'Masuk ke tempat yang dilarang sekolah'
            ],
            // ============================================
            // KATEGORI BERAT - POIN 75
            // ============================================
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
            [
                'id_kategori_pelanggaran' => 8,
                'nama' => 'Pencurian',
                'deskripsi' => 'Mencuri barang milik sekolah, guru, atau siswa lain'
            ],
            // ============================================
            // KATEGORI BERAT - POIN 100
            // ============================================
            [
                'id_kategori_pelanggaran' => 9,
                'nama' => 'Pelecehan fisik',
                'deskripsi' => 'Melakukan pelecehan atau tindakan tidak senonoh secara fisik'
            ],
            [
                'id_kategori_pelanggaran' => 9,
                'nama' => 'Narkoba',
                'deskripsi' => 'Membawa, menggunakan, atau mengedarkan obat/narkoba'
            ],
            [
                'id_kategori_pelanggaran' => 9,
                'nama' => 'Minuman keras',
                'deskripsi' => 'Membawa, menggunakan, atau mengedarkan minuman keras'
            ],
            [
                'id_kategori_pelanggaran' => 9,
                'nama' => 'Membawa senjata',
                'deskripsi' => 'Membawa senjata tajam atau senjata api di lingkungan sekolah'
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

