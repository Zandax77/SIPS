<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * SIPS Chatbot Service
 * Memberikan respons otomatis untuk pertanyaan pengunjung landing page
 * tentang data pelanggaran dan peraturan SIPS
 */
class SIPSChatbotService
{
    /**
     * Data kategori pelanggaran untuk referensi
     */
    private $kategoriPelanggaran = [
        'ringan' => [
            'nama' => 'Pelanggaran Ringan',
            'poin' => '5-15 poin',
            'contoh' => ['Terlambat datang ke sekolah', 'Tidak membawa raport', 'Seragam tidak rapi', 'Dinas keluar kelas tanpa izin']
        ],
        'sedang' => [
            'nama' => 'Pelanggaran Sedang',
            'poin' => '20-40 poin',
            'contoh' => ['Membolos pelajaran', 'Merokok di lingkungan sekolah', 'Menggunakan HP saat pembelajaran', 'Mencontek saat ujian']
        ],
        'berat' => [
            'nama' => 'Pelanggaran Berat',
            'poin' => '50-100 poin',
            'contoh' => ['Tawuran antar siswa', 'Mencuri', 'Pelecehan', 'Menyimpan/menggunakan narkoba']
        ]
    ];

    /**
     * Level tindakan berdasarkan total poin
     */
    private $levelTindakan = [
        [
            'poin' => '0-25',
            'nama' => 'Peringatan',
            'tindakan' => ['Peringatan lisan', 'Catatan di buku pelanggaran', 'Komunikasi dengan orang tua']
        ],
        [
            'poin' => '26-50',
            'nama' => 'Pemanggilan Orang Tua',
            'tindakan' => ['Pemanggilan orang tua ke sekolah', 'Surat peringatan keras', 'Program bimbingan khusus']
        ],
        [
            'poin' => '51-75',
            'nama' => 'Penanganan Khusus',
            'tindakan' => ['Rapat Komite Sekolah', 'Skorsing 1-3 hari', 'Rujukan ke psikolog']
        ],
        [
            'poin' => '76-100',
            'nama' => 'Tindakan Berat',
            'tindakan' => ['Surat peringatan terakhir', 'Skorsing jangka panjang', 'Koordinasi dengan kepolisian']
        ],
        [
            'poin' => '>100',
            'nama' => 'Pemecatan',
            'tindakan' => ['Surat keputusan pemecatan', 'Koordinasi dengan Dinas Pendidikan']
        ]
    ];

    /**
     * Proses pesan dari pengguna dan berikan respons
     *
     * @param string $message Pesan dari pengguna
     * @param array $landingData Data dari landing page
     * @return array Respons dari chatbot
     */
    public function getResponse($message, $landingData = [])
    {
        // Get school name from settings if available
        $namaSekolah = 'SMA Negeri';
        if (!empty($landingData['nama_sekolah'])) {
            $namaSekolah = $landingData['nama_sekolah'];
        }

        $message = strtolower(trim($message));

        // Deteksi kategori pertanyaan
        if ($this->contains($message, ['halo', 'hai', 'hi', 'hello', 'assalamualaikum', 'selamat'])) {
            return $this->greeting($namaSekolah);
        }

        if ($this->contains($message, ['terima kasih', 'thanks', 'thank you'])) {
            return $this->thankYou();
        }

        if ($this->contains($message, ['apa itu', 'apa si', 'apa itu sips', 'definisi', 'pengertian'])) {
            return $this->aboutSIPS();
        }

        if ($this->contains($message, ['kategori', 'jenis pelanggaran', 'macam pelanggaran', 'klasifikasi'])) {
            return $this->kategoriPelanggaran();
        }

        if ($this->contains($message, ['ringan'])) {
            return $this->pelanggaranRingan();
        }

        if ($this->contains($message, ['sedang'])) {
            return $this->pelanggaranSedang();
        }

        if ($this->contains($message, ['berat'])) {
            return $this->pelanggaranBerat();
        }

        if ($this->contains($message, ['poin', 'point', 'skor'])) {
            return $this->sistemPoin($message);
        }

        if ($this->contains($message, ['tindakan', 'konsekuensi', 'akibat', 'apa yang terjadi', 'sanksi'])) {
            return $this->tindakanBerdasarkanPoin($message);
        }

        if ($this->contains($message, ['ptos', 'pemecatan', 'dikeluarkan', 'drop out'])) {
            return $this->aboutPTOS();
        }

        if ($this->contains($message, ['data', 'statistik', 'jumlah', 'total', 'grafik', 'tren'])) {
            return $this->statistik($landingData);
        }

        if ($this->contains($message, ['login', 'masuk', 'akses', 'pakai', 'gunakan'])) {
            return $this->caraLogin();
        }

        if ($this->contains($message, ['sekolah', 'siswa', 'pelaku', 'melanggar'])) {
            return $this->informasiUmum();
        }

        if ($this->contains($message, ['bantuan', 'help', 'tolong', 'cara', 'bagaimana'])) {
            return $this->bantuan();
        }

        if ($this->contains($message, ['pengampunan', 'pengurangan', 'kurangi poin', 'hapus poin'])) {
            return $this->pengampunanPoin();
        }

        // Default respons jika tidak memahami pertanyaan
        return $this->defaultResponse();
    }

    /**
     * Sapaan selamat datang
     */
    private function greeting($namaSekolah = 'SMA Negeri')
    {
        return [
            'message' => "Assalamualaikum Wr. Wb.\n\nSelamat datang di SIPS (Sistem Informasi Pelanggaran Siswa)! 👋\n\nSaya adalah asisten virtual yang siap membantu Anda memahami sistem pelanggaran siswa di sekolah ini.\n\nAnda dapat bertanya kepada saya tentang:\n📋 Kategori pelanggaran (Ringan, Sedang, Berat)\n📊 Sistem poin dan konsekuensinya\n📈 Data statistik pelanggaran\n❓ Pertanyaan lainnya tentang SIPS\n\nSilakan ajukan pertanyaan Anda!",
            'quick_replies' => [
                'Apa itu SIPS?',
                'Kategori Pelanggaran',
                'Sistem Poin',
                'Data Statistik'
            ]
        ];
    }

    /**
     * Respons berterima kasih
     */
    private function thankYou()
    {
        return [
            'message' => "Sama-sama! 😊\n\nSenang bisa membantu Anda. Jika ada pertanyaan lain tentang SIPS, jangan ragu untuk bertanya ya.\n\nTetap semangat menciptakan lingkungan sekolah yang disiplin dan kondusif!",
            'quick_replies' => [
                'Kategori Pelanggaran',
                'Cara Login',
                'Hubungi Kami'
            ]
        ];
    }

    /**
     * Tentang SIPS
     */
    private function aboutSIPS()
    {
        return [
            'message' => "📋 **Tentang SIPS**\n\nSIPS (Sistem Informasi Pelanggaran Siswa) adalah sistem informasi yang digunakan untuk:\n\n1. **Pencatatan Pelanggaran**\n   Mencatat setiap pelanggaran yang dilakukan siswa secara terstruktur dan terorganisir.\n\n2. **Monitoring**\n   Memantau pelanggaran siswa secara real-time berdasarkan kategori, kelas, dan waktu.\n\n3. **Analisis**\n   Menganalisis pola pelanggaran untuk mendukung pengambilan keputusan yang tepat.\n\n4. **Tindakan Pembinaan**\n   Memberikan rekomendasi tindakan berdasarkan tingkat pelanggaran siswa.\n\nTujuan utama SIPS adalah mendukung terciptanya disiplin dan ketertiban di lingkungan sekolah melalui sistem yang transparan dan akuntabel.",
            'quick_replies' => [
                'Kategori Pelanggaran',
                'Sistem Poin',
                'Cara Login'
            ]
        ];
    }

    /**
     * Kategori Pelanggaran
     */
    private function kategoriPelanggaran()
    {
        return [
            'message' => "📊 **Kategori Pelanggaran di SIPS**\n\nPelanggaran siswa dikelompokkan menjadi 3 kategori berdasarkan tingkat keparahan:\n\n🔵 **Pelanggaran Ringan (5-15 Poin)**\n- Terlambat datang ke sekolah\n- Tidak membawa raport/buku nilai\n- Seragam tidak rapi atau tidak lengkap\n- Dinas keluar kelas tanpa izin\n- Berbicara tidak sopan (ringan)\n\n🟠 **Pelanggaran Sedang (20-40 Poin)**\n- Membolos pelajaran\n- Merokok di lingkungan sekolah\n- Menggunakan HP saat pembelajaran\n- Mencontek saat ujian/ulangan\n- Memalsukan tanda tangan orang tua\n\n🔴 **Pelanggaran Berat (50-100 Poin)**\n- Tawuran antar siswa\n- Mencontek saat ujian nasional\n- Mencuri\n- Pelecehan verbal/fisik\n- Menyimpan/menggunakan narkoba\n\nMau tahu lebih detail tentang kategori tertentu?",
            'quick_replies' => [
                'Pelanggaran Ringan',
                'Pelanggaran Sedang',
                'Pelanggaran Berat',
                'Sistem Poin'
            ]
        ];
    }

    /**
     * Detail Pelanggaran Ringan
     */
    private function pelanggaranRingan()
    {
        return [
            'message' => "🔵 **Pelanggaran Ringan (5-15 Poin)**\n\nPelanggaran ringan merupakan pelanggaran yang bersifat ringan dan biasanya terkait dengan kedisiplinan sehari-hari. Berikut detailnya:\n\n**Poin 5:**\n• Terlambat datang ke sekolah (1-15 menit)\n• Tidak membawa raport/buku nilai\n• Seragam tidak rapi atau tidak lengkap\n• Dinas keluar kelas tanpa izin\n• Berbicara tidak sopan (ringan)\n\n**Poin 10:**\n• Terlambat 16-30 menit\n• Tidak mengerjakan PR/tugas\n• Membawa barang tidak diperlukan\n\n**Poin 15:**\n• Kelas tidak rapi setelah pelajaran\n• Membuang sampah sembarangan\n\n**Implikasi:**\nPelanggaran ringan yang terakumulasi akan berpengaruh pada status siswa. Jika total poin mencapai 26, maka akan dipanggilkan orang tua.",
            'quick_replies' => [
                'Kategori Sedang',
                'Kategori Berat',
                'Sistem Poin'
            ]
        ];
    }

    /**
     * Detail Pelanggaran Sedang
     */
    private function pelanggaranSedang()
    {
        return [
            'message' => "🟠 **Pelanggaran Sedang (20-40 Poin)**\n\nPelanggaran sedang merupakan pelanggaran yang lebih serius dan memerlukan perhatian khusus. Berikut detailnya:\n\n**Poin 20:**\n• Membolos pelajaran (1 jam)\n• Terlambat datang >30 menit\n• Tidak mengikuti kegiatan sekolah\n\n**Poin 30:**\n• Merokok di lingkungan sekolah\n• Menggunakan HP saat pembelajaran\n• Membolos lebih dari 1 jam\n\n**Poin 40:**\n• Membawa elektronik berlebihan\n• Mencontek saat ujian/ulangan\n• Memalsukan tanda tangan orang tua\n• Berkelahi/perkelahian ringan\n\n**Implikasi:**\nPelanggaran sedang menunjukkan tingkat kedisiplinan yang rendah. Siswa dengan pelanggaran sedang akan dipanggilkan orang tua dan wajib membuat perjanjian tertulis dengan sekolah.",
            'quick_replies' => [
                'Kategori Ringan',
                'Kategori Berat',
                'Tindakan Berdasarkan Poin'
            ]
        ];
    }

    /**
     * Detail Pelanggaran Berat
     */
    private function pelanggaranBerat()
    {
        return [
            'message' => "🔴 **Pelanggaran Berat (50-100 Poin)**\n\nPelanggaran berat adalah pelanggaran yang sangat serius dan dapat mengancam keamanan sekolah. Berikut detailnya:\n\n**Poin 50:**\n• Tawuran antar siswa\n• Mencontek saat ujian nasional\n• Memasuki tempat terlarang\n\n**Poin 75:**\n• Pelecehan verbal\n• Vandalisme/merusak fasilitas\n• Mencuri\n\n**Poin 100:**\n• Pelecehan fisik\n• Menyimpan/menggunakan narkoba\n• Menyimpan/menggunakan alkohol\n• Membawa senjata\n\n**⚠️ Peringatan:**\nPelanggaran berat memerlukan penanganan segera. Siswa yang melakukan pelanggaran berat akan:\n1. Diproses melalui Rapat Komite Sekolah\n2. Diberikan skorsing\n3. Dik координации dengan pihak kepolisian (jika diperlukan)\n4. Diproses untuk pemecatan jika tidak menunjukkan perbaikan",
            'quick_replies' => [
                'Proses PTOS',
                'Tindakan Berdasarkan Poin',
                'Pengampunan Poin'
            ]
        ];
    }

    /**
     * Sistem Poin
     */
    private function sistemPoin($message = '')
    {
        return [
            'message' => "📊 **Sistem Poin Pelanggaran**\n\nSIPS menggunakan sistem poin untuk mengukur tingkat pelanggaran siswa. Berikut penjelasan lengkapnya:\n\n**Cara Kerja:**\nSetiap pelanggaran memiliki nilai poin tertentu berdasarkan kategorinya:\n• Ringan: 5-15 poin\n• Sedang: 20-40 poin\n• Berat: 50-100 poin\n\n**Total Poin Akumulatif:**\nPoin bersifat akumulatif. Semakin banyak pelanggaran, semakin tinggi total poin.\n\n**Batas Ambang Poin:**\n🟢 0-25: Aman - Pemantauan normal\n🟡 26-50: Waspada - Perlu perhatian khusus\n🟠 51-75: Bahaya - Intervention segera\n🔴 76-100: Kritis - Tindakan drastis\n⚫ >100: Pemecatan\n\n**Contoh:**\nJika siswa terlambat 3x (5 poin each) dan membolos 1x (20 poin), maka total poin = 15 + 20 = 35 poin (status Waspada)",
            'quick_replies' => [
                'Tindakan Berdasarkan Poin',
                'Pengampunan Poin',
                'Kategori Pelanggaran'
            ]
        ];
    }

    /**
     * Tindakan Berdasarkan Poin
     */
    private function tindakanBerdasarkanPoin($message = '')
    {
        $response = "⚡ **Tindakan Berdasarkan Total Poin**\n\nBerikut adalah tindakan yang akan diambil berdasarkan akumulasi poin siswa:\n\n";

        foreach ($this->levelTindakan as $level) {
            $response .= "**{$level['poin']} Poin - {$level['nama']}**\n";
            foreach ($level['tindakan'] as $tindakan) {
                $response .= "• {$tindakan}\n";
            }
            $response .= "\n";
        }

        $response .= "**Catatan Penting:**\nTindakan dapat disesuaikan berdasarkan konteks pelanggaran, riwayat siswa, dan pertimbangan pihak sekolah. Orang tua/wali akan selalu diinformasikan tentang setiap pelanggaran dan tindakan yang diambil.";

        return [
            'message' => $response,
            'quick_replies' => [
                'Pengampunan Poin',
                'Proses PTOS',
                'Hubungi Kami'
            ]
        ];
    }

    /**
     * Tentang PTOS
     */
    private function aboutPTOS()
    {
        return [
            'message' => "⚠️ **Tentang PTOS (Petition to Out School)**\n\nPTOS adalah proses pemecatan siswa dari sekolah akibat pelanggaran yang sangat berat atau akumulasi pelanggaran berat yang tidak menunjukkan perbaikan.\n\n**Kapan PTOS Dilakukan?**\n1. Total poin melebihi 100\n2. Melakukan pelanggaran sangat berat (narkoba, senjata, kekerasan berat)\n3. Tidak menunjukkan perbaikan setelah berbagai intervention\n\n**Proses PTOS:**\n1. 📋 Surat keputusan pemecatan dari Kepala Sekolah\n2. 📞 Koordinasi dengan Dinas Pendidikan\n3. 👥 Bantuan transfer ke sekolah lain jika memungkinkan\n4. 📝 Dokumentasi lengkap untuk keperluan legal\n\n**Pencegahan:**\nSiswa dapat mengurangi poin melalui program pengampunan seperti:\n• Menjadi asisten guru (5 poin/jam)\n• Membersihkan fasilitas sekolah (3 poin/jam)\n• Program bimbingan BK (5 poin/sesi)\n• Prestasi akademik/non-akademik (10-20 poin)",
            'quick_replies' => [
                'Pengampunan Poin',
                'Tindakan Berdasarkan Poin',
                'Kategori Pelanggaran'
            ]
        ];
    }

    /**
     * Statistik dari Landing Page
     */
    private function statistik($landingData = [])
    {
        $totalPelanggaran = 0;
        $totalSiswa = 0;
        $totalKelas = 0;

        if (!empty($landingData['chartData'])) {
            $chartData = $landingData['chartData'];
            $totalPelanggaran = array_sum($chartData['ringan'] ?? [0]) +
                                array_sum($chartData['sedang'] ?? [0]) +
                                array_sum($chartData['berat'] ?? [0]);
        }

        if (!empty($landingData['kelasStats'])) {
            $totalSiswa = $landingData['kelasStats']->sum('jumlah_siswa_pelaku');
            $totalKelas = $landingData['kelasStats']->count();
        }

        $message = "📈 **Data Statistik Pelanggaran**\n\n";

        if ($totalPelanggaran > 0 || $totalSiswa > 0) {
            $message .= "Berdasarkan data dalam 7 hari terakhir:\n\n";
            $message .= "📊 **Total Pelanggaran:** {$totalPelanggaran} pelanggaran\n";
            $message .= "👥 **Siswa Pelaku:** {$totalSiswa} siswa\n";
            $message .= "🏫 **Kelas Terlibat:** {$totalKelas} kelas\n\n";

            if (!empty($landingData['chartData'])) {
                $chartData = $landingData['chartData'];
                $ringan = array_sum($chartData['ringan'] ?? [0]);
                $sedang = array_sum($chartData['sedang'] ?? [0]);
                $berat = array_sum($chartData['berat'] ?? [0]);

                $message .= "**Rincian per Kategori:**\n";
                $message .= "🔵 Ringan: {$ringan}\n";
                $message .= "🟠 Sedang: {$sedang}\n";
                $message .= "🔴 Berat: {$berat}\n";
            }

            $message .= "\n_Grafik tren pelanggaran dapat dilihat pada halaman utama di atas._";
        } else {
            $message .= "Berdasarkan data dalam 7 hari terakhir, tidak ada pelanggaran yang tercatat. Ini menunjukkan kondisi yang baik untuk disiplin sekolah! 🎉\n\nTetap semangat untuk menjaga ketertiban bersama!";
        }

        return [
            'message' => $message,
            'quick_replies' => [
                'Kategori Pelanggaran',
                'Sistem Poin',
                'Tentang SIPS'
            ]
        ];
    }

    /**
     * Cara Login
     */
    private function caraLogin()
    {
        return [
            'message' => "🔐 **Cara Login ke SIPS**\n\nUntuk mengakses sistem SIPS, ikuti langkah-langkah berikut:\n\n1. **Klik Tombol Login**\n   Klik tombol \"Login\" yang berada di pojok kanan atas halaman.\n\n2. **Masukkan Kredensial**\n   Masukkan email dan password yang telah didaftarkan oleh administrator.\n\n3. **Pilih Peran**\n   Anda akan diarahkan ke halaman sesuai peran:\n   • **Admin**: Mengelola semua data\n   • **Kesiswaan**: Mengelola pelanggaran dan siswa\n   • **Wali Kelas**: Melihat data kelas masing-masing\n   • **OSIS**: Mencatat pelanggaran\n\n**Catatan:**\n• Akun hanya dapat dibuat oleh administrator sekolah\n• Jika lupa password,hubungi administrator untuk reset\n\n_Untuk melihat data pelanggaran publik, Anda dapat melihatnya di halaman ini tanpa login._",
            'quick_replies' => [
                'Tentang SIPS',
                'Kategori Pelanggaran',
                'Hubungi Kami'
            ]
        ];
    }

    /**
     * Informasi Umum
     */
    private function informasiUmum()
    {
        return [
            'message' => "ℹ️ **Informasi Umum SIPS**\n\n**Apa itu Pelanggaran?**\nPelanggaran adalah tindakan yang melanggar tata tertip sekolah, meliputi kedisiplinan, kejujuran, keamanan, dan kesopanan.\n\n**Siapa yang Dapat Melanggar?**\nSetiap siswa berpotensi melakukan pelanggaran. SIPS mencatat semua pelanggaran yang dilakukan siswa tanpa terkecuali.\n\n**Tujuan Pencatatan:**\n1. Mendorong siswa untuk mematuhi aturan sekolah\n2. Memberikan gambaran客观 tentang perilaku siswa\n3. Memfasilitasi proses bimbingan dan pembinaan\n4. Melindungi siswa lain dari dampak negative\n\n**Prinsip Dasar:**\n• Setiap siswa berhak mendapatkan kesempatan menjelaskan\n• Pelanggaran harus dibuktikan dengan jelas\n• Orang tua/wali selalu diinformasikan\n• Tindakan bersifat edukatif, bukan hanya punishment",
            'quick_replies' => [
                'Kategori Pelanggaran',
                'Sistem Poin',
                'Tindakan Berdasarkan Poin'
            ]
        ];
    }

    /**
     * Bantuan
     */
    private function bantuan()
    {
        return [
            'message' => "❓ **Bantuan**\n\nSaya siap membantu Anda memahami SIPS! Berikut beberapa topik yang dapat Anda tanyakan:\n\n**📋 Kategori & Poin:**\n• Apa saja kategori pelanggaran?\n• Berapa poin untuk terlambat?\n• Apa bedanya pelanggaran ringan dan berat?\n\n**⚡ Tindakan:**\n• Apa yang terjadi jika poin mencapai 50?\n• Kapan siswa dipanggilkan orang tua?\n• Apa itu PTOS?\n\n**📊 Data:**\n• Berapa total pelanggaran hari ini?\n• Bagaimana tren pelanggaran terbaru?\n\n**🔐 Akses:**\n• Bagaimana cara login?\n• Siapa yang dapat mengakses sistem?\n\n**Lainnya:**\n• Apa itu SIPS?\n• Bagaimana sistem pengampunan poin?\n\nSilakan pilih topik atau ketik pertanyaan Anda sendiri!",
            'quick_replies' => [
                'Apa itu SIPS?',
                'Kategori Pelanggaran',
                'Sistem Poin',
                'Data Statistik'
            ]
        ];
    }

    /**
     * Pengampunan Poin
     */
    private function pengampunanPoin()
    {
        return [
            'message' => "♻️ **Program Pengampunan Poin**\n\nSIPS memberikan kesempatan bagi siswa untuk mengurangi poin pelanggaran melalui kegiatan produktif. Berikut program yang tersedia:\n\n| No | Kegiatan | Pengurangan Poin | Keterangan |\n|----|----------|-------------------|-------------|\n| 1 | Menjadi asisten guru | 5 poin/jam | Max 20 poin/bulan |\n| 2 | Membersihkan fasilitas sekolah | 3 poin/jam | Max 15 poin/bulan |\n| 3 | Program bimbingan BK | 5 poin/sesi | Max 20 poin/bulan |\n| 4 | Mengembalikan barang yang diambil | 10 poin | Untuk kasus pencurian |\n| 5 | Membantu operasional sekolah | 5 poin/hari | Max 25 poin/bulan |\n| 6 | Prestasi akademik/non akademik | 10-20 poin | Sesuai keputusan |\n\n**Tujuan:**\nProgram ini dirancang untuk memberikan motivasi kepada siswa agar memperbaiki perilaku mereka dan menunjukkan kemajuan positif.\n\n**Syarat:**\n• Activities harus disetujui oleh pihak sekolah\n• Bukti kegiatan harus didokumentasikan\n• Pengurangan poin dilakukan setelah verifikasi",
            'quick_replies' => [
                'Sistem Poin',
                'Tindakan Berdasarkan Poin',
                'Kategori Pelanggaran'
            ]
        ];
    }

    /**
     * Respons default untuk pertanyaan yang tidak dikenali
     */
    private function defaultResponse()
    {
        return [
            'message' => "🤔 **Maaf, saya tidak memahami pertanyaan Anda.**\n\nSaya adalah asisten virtual SIPS yang khusus membantu pertanyaan tentang:\n• Kategori dan jenis pelanggaran\n• Sistem poin dan konsekuensi\n• Data statistik pelanggaran\n• Cara penggunaan sistem\n\nSilakan pilih dari topik berikut atau ajukan pertanyaan dengan kata kunci yang lebih jelas:\n\n**Pertanyaan Popular:**\n1. Apa itu SIPS?\n2. Kategori Pelanggaran\n3. Sistem Poin\n4. Tindakan Berdasarkan Poin\n5. Data Statistik",
            'quick_replies' => [
                'Apa itu SIPS?',
                'Kategori Pelanggaran',
                'Sistem Poin',
                'Data Statistik',
                'Bantuan'
            ]
        ];
    }

    /**
     * Helper untuk mendeteksi kata kunci dalam pesan
     */
    private function contains($message, $keywords)
    {
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }
}

