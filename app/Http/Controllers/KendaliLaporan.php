<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Pelanggaran;
use App\Models\JenisPelanggaran;
use App\Models\KategoriPelanggaran;
use App\Models\Setting;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class KendaliLaporan extends Controller
{
    /**
     * Get formatted jabatan for display
     */
    private function getFormattedJabatan(): string
    {
        $jabatan = session('jabatan', '-');
        $kelas = session('kelas', '');

        if ($jabatan === 'Wali Kelas' && !empty($kelas)) {
            return 'Wali Kelas - ' . $kelas;
        }

        return $jabatan;
    }

    /**
     * Get school name from settings
     */
    private function getNamaSekolah(): string
    {
        return Setting::get('nama_sekolah', 'SIPS');
    }

    /**
     * Check if user is OSIS
     */
    private function isOsis(): bool
    {
        return strtolower(session('role', 'petugas')) === 'osis';
    }

    /**
     * Display Laporan per Siswa page
     */
    public function laporanPerSiswa(Request $request)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // OSIS tidak memiliki akses ke laporan
        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $namaPetugas = session('nama_petugas', 'Petugas');
        $jabatan = $this->getFormattedJabatan();
        $namaSekolah = $this->getNamaSekolah();

        // Get filter parameters
        $kelas = $request->input('kelas', '');
        $search = $request->input('search', '');

        // Get all unique classes for filter dropdown
        $kelasList = DB::table('siswas')
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        // Get siswa with violation data
        $siswaQuery = DB::table('siswas')
            ->select(
                'siswas.id',
                'siswas.nis',
                'siswas.name as nama_siswa',
                'siswas.kelas',
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin'),
                DB::raw('COUNT(DISTINCT pelanggarans.id) as total_pelanggaran')
            )
            ->leftJoin('pelanggarans', 'siswas.id', '=', 'pelanggarans.id_siswa')
            ->leftJoin('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->leftJoin('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->when($kelas, function ($query) use ($kelas) {
                return $query->where('siswas.kelas', $kelas);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('siswas.name', 'like', "%{$search}%")
                      ->orWhere('siswas.nis', 'like', "%{$search}%");
                });
            })
            ->groupBy('siswas.id', 'siswas.nis', 'siswas.name', 'siswas.kelas')
            ->orderBy('total_poin', 'desc')
            ->orderBy('nama_siswa', 'asc');

        $siswa = $siswaQuery->get();

        return view('laporan-per-siswa', compact(
            'namaPetugas',
            'jabatan',
            'namaSekolah',
            'siswa',
            'kelasList',
            'kelas',
            'search'
        ));
    }

    /**
     * Display Rekap per Kelas page
     */
    public function rekapPerKelas(Request $request)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // OSIS tidak memiliki akses ke laporan
        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $namaPetugas = session('nama_petugas', 'Petugas');
        $jabatan = $this->getFormattedJabatan();
        $namaSekolah = $this->getNamaSekolah();

        // Get filter parameters
        $kelas = $request->input('kelas', '');
        $tanggalMulai = $request->input('tanggal_mulai', '');
        $tanggalAkhir = $request->input('tanggal_akhir', '');

        // Get all unique classes
        $kelasList = DB::table('siswas')
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        // Get violation data per class
        $rekapQuery = DB::table('siswas')
            ->select(
                'siswas.kelas',
                DB::raw('COUNT(DISTINCT siswas.id) as total_siswa'),
                DB::raw('COUNT(DISTINCT pelanggarans.id) as total_pelanggaran'),
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Ringan" THEN pelanggarans.id END) as pelanggaran_ringan'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Sedang" THEN pelanggarans.id END) as pelanggaran_sedang'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Berat" THEN pelanggarans.id END) as pelanggaran_berat')
            )
            ->leftJoin('pelanggarans', function($join) use ($tanggalMulai, $tanggalAkhir) {
                $join->on('siswas.id', '=', 'pelanggarans.id_siswa');
                if (!empty($tanggalMulai) && !empty($tanggalAkhir)) {
                    $join->whereBetween('pelanggarans.created_at', [$tanggalMulai . ' 00:00:00', $tanggalAkhir . ' 23:59:59']);
                }
            })
            ->leftJoin('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->leftJoin('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->when($kelas, function ($query) use ($kelas) {
                return $query->where('siswas.kelas', $kelas);
            })
            ->groupBy('siswas.kelas')
            ->orderBy('siswas.kelas');

        $rekap = $rekapQuery->get();

        // Calculate totals
        $totals = [
            'total_siswa' => $rekap->sum('total_siswa'),
            'total_pelanggaran' => $rekap->sum('total_pelanggaran'),
            'total_poin' => $rekap->sum('total_poin'),
            'pelanggaran_ringan' => $rekap->sum('pelanggaran_ringan'),
            'pelanggaran_sedang' => $rekap->sum('pelanggaran_sedang'),
            'pelanggaran_berat' => $rekap->sum('pelanggaran_berat'),
        ];

        return view('laporan-rekap-kelas', compact(
            'namaPetugas',
            'jabatan',
            'namaSekolah',
            'rekap',
            'kelasList',
            'kelas',
            'tanggalMulai',
            'tanggalAkhir',
            'totals'
        ));
    }

    /**
     * Display Rekap per Periode page
     */
    public function rekapPerPeriode(Request $request)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // OSIS tidak memiliki akses ke laporan
        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $namaPetugas = session('nama_petugas', 'Petugas');
        $jabatan = $this->getFormattedJabatan();
        $namaSekolah = $this->getNamaSekolah();

        // Get filter parameters
        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-01'));
        $tanggalAkhir = $request->input('tanggal_akhir', date('Y-m-t'));
        $kelas = $request->input('kelas', '');

        // Get all unique classes
        $kelasList = DB::table('siswas')
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        // Get violation data per day
        $rekapQuery = DB::table('pelanggarans')
            ->select(
                DB::raw('DATE(pelanggarans.created_at) as tanggal'),
                DB::raw('COUNT(DISTINCT pelanggarans.id_siswa) as siswa_pelaku'),
                DB::raw('COUNT(DISTINCT pelanggarans.id) as total_pelanggaran'),
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Ringan" THEN pelanggarans.id END) as pelanggaran_ringan'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Sedang" THEN pelanggarans.id END) as pelanggaran_sedang'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Berat" THEN pelanggarans.id END) as pelanggaran_berat')
            )
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->join('siswas', 'pelanggarans.id_siswa', '=', 'siswas.id')
            ->whereBetween('pelanggarans.created_at', [$tanggalMulai . ' 00:00:00', $tanggalAkhir . ' 23:59:59'])
            ->when($kelas, function ($query) use ($kelas) {
                return $query->where('siswas.kelas', $kelas);
            })
            ->groupBy(DB::raw('DATE(pelanggarans.created_at)'))
            ->orderBy('tanggal', 'desc');

        $rekap = $rekapQuery->get();

        // Calculate totals
        $totals = [
            'total_hari' => $rekap->count(),
            'siswa_pelaku' => $rekap->sum('siswa_pelaku'),
            'total_pelanggaran' => $rekap->sum('total_pelanggaran'),
            'total_poin' => $rekap->sum('total_poin'),
            'pelanggaran_ringan' => $rekap->sum('pelanggaran_ringan'),
            'pelanggaran_sedang' => $rekap->sum('pelanggaran_sedang'),
            'pelanggaran_berat' => $rekap->sum('pelanggaran_berat'),
        ];

        return view('laporan-rekap-periode', compact(
            'namaPetugas',
            'jabatan',
            'namaSekolah',
            'rekap',
            'kelasList',
            'kelas',
            'tanggalMulai',
            'tanggalAkhir',
            'totals'
        ));
    }

    /**
     * Display Siswa Poin Tertinggi page
     */
    public function siswaPoinTertinggi(Request $request)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // OSIS tidak memiliki akses ke laporan
        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $namaPetugas = session('nama_petugas', 'Petugas');
        $jabatan = $this->getFormattedJabatan();
        $namaSekolah = $this->getNamaSekolah();

        // Get filter parameters
        $kelas = $request->input('kelas', '');
        $limit = $request->input('limit', 10);

        // Get all unique classes
        $kelasList = DB::table('siswas')
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        // Get siswa with violation data sorted by points descending
        $siswaQuery = DB::table('siswas')
            ->select(
                'siswas.id',
                'siswas.nis',
                'siswas.name as nama_siswa',
                'siswas.kelas',
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin'),
                DB::raw('COUNT(DISTINCT pelanggarans.id) as total_pelanggaran')
            )
            ->leftJoin('pelanggarans', 'siswas.id', '=', 'pelanggarans.id_siswa')
            ->leftJoin('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->leftJoin('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->when($kelas, function ($query) use ($kelas) {
                return $query->where('siswas.kelas', $kelas);
            })
            ->groupBy('siswas.id', 'siswas.nis', 'siswas.name', 'siswas.kelas')
            ->orderBy('total_poin', 'desc')
            ->orderBy('total_pelanggaran', 'desc')
            ->limit($limit);

        $siswa = $siswaQuery->get();

        // Add rank
        foreach ($siswa as $index => $s) {
            $s->rank = $index + 1;
        }

        return view('laporan-siswa-tertinggi', compact(
            'namaPetugas',
            'jabatan',
            'namaSekolah',
            'siswa',
            'kelasList',
            'kelas',
            'limit'
        ));
    }

    /**
     * Export PDF for Laporan per Siswa
     */
    public function exportPdfPerSiswa(Request $request)
    {
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $kelas = $request->input('kelas', '');
        $search = $request->input('search', '');

        $siswa = DB::table('siswas')
            ->select(
                'siswas.id',
                'siswas.nis',
                'siswas.name as nama_siswa',
                'siswas.kelas',
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin'),
                DB::raw('COUNT(DISTINCT pelanggarans.id) as total_pelanggaran')
            )
            ->leftJoin('pelanggarans', 'siswas.id', '=', 'pelanggarans.id_siswa')
            ->leftJoin('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->leftJoin('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->when($kelas, function ($query) use ($kelas) {
                return $query->where('siswas.kelas', $kelas);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('siswas.name', 'like', "%{$search}%")
                      ->orWhere('siswas.nis', 'like', "%{$search}%");
                });
            })
            ->groupBy('siswas.id', 'siswas.nis', 'siswas.name', 'siswas.kelas')
            ->orderBy('total_poin', 'desc')
            ->get();

        $namaSekolah = $this->getNamaSekolah();
        $tanggal = date('d F Y');

        $html = view('pdf.laporan-per-siswa', compact('siswa', 'namaSekolah', 'tanggal', 'kelas', 'search'))->render();

        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'landscape');
        $dompdf->render();

        return $dompdf->stream('laporan-per-siswa-' . date('Y-m-d') . '.pdf', ['Attachment' => true]);
    }

    /**
     * Export PDF for Rekap per Kelas
     */
    public function exportPdfPerKelas(Request $request)
    {
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $kelas = $request->input('kelas', '');
        $tanggalMulai = $request->input('tanggal_mulai', '');
        $tanggalAkhir = $request->input('tanggal_akhir', '');

        $rekap = DB::table('siswas')
            ->select(
                'siswas.kelas',
                DB::raw('COUNT(DISTINCT siswas.id) as total_siswa'),
                DB::raw('COUNT(DISTINCT pelanggarans.id) as total_pelanggaran'),
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Ringan" THEN pelanggarans.id END) as pelanggaran_ringan'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Sedang" THEN pelanggarans.id END) as pelanggaran_sedang'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Berat" THEN pelanggarans.id END) as pelanggaran_berat')
            )
            ->leftJoin('pelanggarans', function($join) use ($tanggalMulai, $tanggalAkhir) {
                $join->on('siswas.id', '=', 'pelanggarans.id_siswa');
                if (!empty($tanggalMulai) && !empty($tanggalAkhir)) {
                    $join->whereBetween('pelanggarans.created_at', [$tanggalMulai . ' 00:00:00', $tanggalAkhir . ' 23:59:59']);
                }
            })
            ->leftJoin('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->leftJoin('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->when($kelas, function ($query) use ($kelas) {
                return $query->where('siswas.kelas', $kelas);
            })
            ->groupBy('siswas.kelas')
            ->orderBy('siswas.kelas')
            ->get();

        $totals = [
            'total_siswa' => $rekap->sum('total_siswa'),
            'total_pelanggaran' => $rekap->sum('total_pelanggaran'),
            'total_poin' => $rekap->sum('total_poin'),
            'pelanggaran_ringan' => $rekap->sum('pelanggaran_ringan'),
            'pelanggaran_sedang' => $rekap->sum('pelanggaran_sedang'),
            'pelanggaran_berat' => $rekap->sum('pelanggaran_berat'),
        ];

        $namaSekolah = $this->getNamaSekolah();
        $tanggal = date('d F Y');

        $html = view('pdf.laporan-rekap-kelas', compact('rekap', 'totals', 'namaSekolah', 'tanggal', 'kelas', 'tanggalMulai', 'tanggalAkhir'))->render();

        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'landscape');
        $dompdf->render();

        return $dompdf->stream('laporan-rekap-kelas-' . date('Y-m-d') . '.pdf', ['Attachment' => true]);
    }

    /**
     * Export PDF for Rekap per Periode
     */
    public function exportPdfPerPeriode(Request $request)
    {
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-01'));
        $tanggalAkhir = $request->input('tanggal_akhir', date('Y-m-t'));
        $kelas = $request->input('kelas', '');

        $rekap = DB::table('pelanggarans')
            ->select(
                DB::raw('DATE(pelanggarans.created_at) as tanggal'),
                DB::raw('COUNT(DISTINCT pelanggarans.id_siswa) as siswa_pelaku'),
                DB::raw('COUNT(DISTINCT pelanggarans.id) as total_pelanggaran'),
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Ringan" THEN pelanggarans.id END) as pelanggaran_ringan'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Sedang" THEN pelanggarans.id END) as pelanggaran_sedang'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Berat" THEN pelanggarans.id END) as pelanggaran_berat')
            )
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->join('siswas', 'pelanggarans.id_siswa', '=', 'siswas.id')
            ->whereBetween('pelanggarans.created_at', [$tanggalMulai . ' 00:00:00', $tanggalAkhir . ' 23:59:59'])
            ->when($kelas, function ($query) use ($kelas) {
                return $query->where('siswas.kelas', $kelas);
            })
            ->groupBy(DB::raw('DATE(pelanggarans.created_at)'))
            ->orderBy('tanggal', 'desc')
            ->get();

        $totals = [
            'total_hari' => $rekap->count(),
            'siswa_pelaku' => $rekap->sum('siswa_pelaku'),
            'total_pelanggaran' => $rekap->sum('total_pelanggaran'),
            'total_poin' => $rekap->sum('total_poin'),
            'pelanggaran_ringan' => $rekap->sum('pelanggaran_ringan'),
            'pelanggaran_sedang' => $rekap->sum('pelanggaran_sedang'),
            'pelanggaran_berat' => $rekap->sum('pelanggaran_berat'),
        ];

        $namaSekolah = $this->getNamaSekolah();
        $tanggal = date('d F Y');

        $html = view('pdf.laporan-rekap-periode', compact('rekap', 'totals', 'namaSekolah', 'tanggal', 'tanggalMulai', 'tanggalAkhir', 'kelas'))->render();

        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'landscape');
        $dompdf->render();

        return $dompdf->stream('laporan-rekap-periode-' . date('Y-m-d') . '.pdf', ['Attachment' => true]);
    }

    /**
     * Export PDF for Siswa Poin Tertinggi
     */
    public function exportPdfSiswaTertinggi(Request $request)
    {
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $kelas = $request->input('kelas', '');
        $limit = $request->input('limit', 10);

        $siswa = DB::table('siswas')
            ->select(
                'siswas.id',
                'siswas.nis',
                'siswas.name as nama_siswa',
                'siswas.kelas',
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin'),
                DB::raw('COUNT(DISTINCT pelanggarans.id) as total_pelanggaran')
            )
            ->leftJoin('pelanggarans', 'siswas.id', '=', 'pelanggarans.id_siswa')
            ->leftJoin('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->leftJoin('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->when($kelas, function ($query) use ($kelas) {
                return $query->where('siswas.kelas', $kelas);
            })
            ->groupBy('siswas.id', 'siswas.nis', 'siswas.name', 'siswas.kelas')
            ->orderBy('total_poin', 'desc')
            ->orderBy('total_pelanggaran', 'desc')
            ->limit($limit)
            ->get();

        // Add rank
        foreach ($siswa as $index => $s) {
            $s->rank = $index + 1;
        }

        $namaSekolah = $this->getNamaSekolah();
        $tanggal = date('d F Y');

        $html = view('pdf.laporan-siswa-tertinggi', compact('siswa', 'namaSekolah', 'tanggal', 'kelas', 'limit'))->render();

        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('laporan-siswa-tertinggi-' . date('Y-m-d') . '.pdf', ['Attachment' => true]);
    }

    /**
     * Export Excel for Laporan per Siswa
     */
    public function exportExcelPerSiswa(Request $request)
    {
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $kelas = $request->input('kelas', '');
        $search = $request->input('search', '');

        return Excel::download(new LaporanExport('per-siswa', compact('kelas', 'search')), 'laporan-per-siswa-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export Excel for Rekap per Kelas
     */
    public function exportExcelPerKelas(Request $request)
    {
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $kelas = $request->input('kelas', '');

        return Excel::download(new LaporanExport('per-kelas', compact('kelas')), 'laporan-rekap-kelas-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export Excel for Rekap per Periode
     */
    public function exportExcelPerPeriode(Request $request)
    {
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-01'));
        $tanggalAkhir = $request->input('tanggal_akhir', date('Y-m-t'));
        $kelas = $request->input('kelas', '');

        return Excel::download(new LaporanExport('per-periode', compact('tanggalMulai', 'tanggalAkhir', 'kelas')), 'laporan-rekap-periode-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export Excel for Siswa Poin Tertinggi
     */
    public function exportExcelSiswaTertinggi(Request $request)
    {
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $kelas = $request->input('kelas', '');
        $limit = $request->input('limit', 10);

        return Excel::download(new LaporanExport('siswa-tertinggi', compact('kelas', 'limit')), 'laporan-siswa-tertinggi-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Display Detail per Siswa page
     */
    public function detailPerSiswa(Request $request, $id)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // OSIS tidak memiliki akses ke laporan
        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $namaPetugas = session('nama_petugas', 'Petugas');
        $jabatan = $this->getFormattedJabatan();
        $namaSekolah = $this->getNamaSekolah();

        // Get student data
        $siswa = DB::table('siswas')
            ->where('siswas.id', $id)
            ->first();

        if (!$siswa) {
            return redirect()->route('laporan.per-siswa')->with('error', 'Siswa tidak ditemukan');
        }

        // Get pelanggaran detail with jenis and kategori
        $pelanggaranDetail = DB::table('pelanggarans')
            ->select(
                'pelanggarans.id',
                'pelanggarans.deskripsi',
                'pelanggarans.lampiran',
                'pelanggarans.tipe_lampiran',
                'pelanggarans.created_at',
                'jenis_pelanggarans.nama as jenis_pelanggaran',
                'kategori_pelanggarans.nama as kategori',
                'kategori_pelanggarans.poin as poin',
                'petugas.name as nama_petugas'
            )
            ->leftJoin('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->leftJoin('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->leftJoin('petugas', 'pelanggarans.id_petugas', '=', 'petugas.id')
            ->where('pelanggarans.id_siswa', $id)
            ->orderBy('pelanggarans.created_at', 'desc')
            ->get();

        // Get pengampunan data for each pelanggaran
        $pengampunanMap = [];
        $pengampunanData = DB::table('pengampunan_pelanggarans')
            ->where('id_siswa', $id)
            ->get();

        foreach ($pengampunanData as $p) {
            $pengampunanMap[$p->id_pelanggaran] = $p;
        }

        // Calculate total points (original - reduced)
        $totalPoin = 0;
        foreach ($pelanggaranDetail as $p) {
            $poinDikurangi = isset($pengampunanMap[$p->id]) ? $pengampunanMap[$p->id]->poin_dikurangi : 0;
            $p->poin_dikurangi = $poinDikurangi;
            $p->poin_sisa = $p->poin - $poinDikurangi;
            $p->sudah_diampuni = isset($pengampunanMap[$p->id]) && $pengampunanMap[$p->id]->tipe === 'pengampunan';
            $totalPoin += $p->poin_sisa;
        }

        // Get stats by kategori
        $statsByKategori = DB::table('pelanggarans')
            ->select(
                'kategori_pelanggarans.nama as kategori',
                DB::raw('COUNT(DISTINCT pelanggarans.id) as jumlah'),
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin')
            )
            ->leftJoin('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->leftJoin('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('pelanggarans.id_siswa', $id)
            ->groupBy('kategori_pelanggarans.nama')
            ->get();

        return view('laporan-per-siswa-detail', compact(
            'namaPetugas',
            'jabatan',
            'namaSekolah',
            'siswa',
            'pelanggaranDetail',
            'totalPoin',
            'statsByKategori'
        ));
    }

    /**
     * Export PDF for Detail per Siswa
     */
    public function exportPdfDetailPerSiswa(Request $request, $id)
    {
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $namaSekolah = $this->getNamaSekolah();
        $tanggal = date('d F Y');
        $tanggalCetak = date('d-m-Y');

        // Get student data
        $siswa = DB::table('siswas')
            ->where('siswas.id', $id)
            ->first();

        if (!$siswa) {
            return redirect()->route('laporan.per-siswa')->with('error', 'Siswa tidak ditemukan');
        }

        // Get pelanggaran detail
        $pelanggaranDetail = DB::table('pelanggarans')
            ->select(
                'pelanggarans.id',
                'pelanggarans.deskripsi',
                'pelanggarans.lampiran',
                'pelanggarans.tipe_lampiran',
                'pelanggarans.created_at',
                'jenis_pelanggarans.nama as jenis_pelanggaran',
                'kategori_pelanggarans.nama as kategori',
                'kategori_pelanggarans.poin as poin',
                'petugas.name as nama_petugas'
            )
            ->leftJoin('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->leftJoin('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->leftJoin('petugas', 'pelanggarans.id_petugas', '=', 'petugas.id')
            ->where('pelanggarans.id_siswa', $id)
            ->orderBy('pelanggarans.created_at', 'desc')
            ->get();

        // Get pengampunan data
        $pengampunanMap = [];
        $pengampunanData = DB::table('pengampunan_pelanggarans')
            ->where('id_siswa', $id)
            ->get();

        foreach ($pengampunanData as $p) {
            $pengampunanMap[$p->id_pelanggaran] = $p;
        }

        // Calculate total points and get action history
        $totalPoin = 0;
        $allPengampunan = [];

        foreach ($pelanggaranDetail as $p) {
            $poinDikurangi = isset($pengampunanMap[$p->id]) ? $pengampunanMap[$p->id]->poin_dikurangi : 0;
            $p->poin_dikurangi = $poinDikurangi;
            $p->poin_sisa = $p->poin - $poinDikurangi;
            $p->sudah_diampuni = isset($pengampunanMap[$p->id]) && $pengampunanMap[$p->id]->tipe === 'pengampunan';
            $totalPoin += $p->poin_sisa;

            // Collect pengampunan history
            if (isset($pengampunanMap[$p->id])) {
                $allPengampunan[] = [
                    'id_pelanggaran' => $p->id,
                    'jenis_pelanggaran' => $p->jenis_pelanggaran,
                    'tipe' => $pengampunanMap[$p->id]->tipe,
                    'poin_asli' => $pengampunanMap[$p->id]->poin_asli,
                    'poin_dikurangi' => $pengampunanMap[$p->id]->poin_dikurangi,
                    'alasan' => $pengampunanMap[$p->id]->alasan,
                    'created_at' => $pengampunanMap[$p->id]->created_at,
                ];
            }
        }

        // Get stats by kategori
        $statsByKategori = DB::table('pelanggarans')
            ->select(
                'kategori_pelanggarans.nama as kategori',
                DB::raw('COUNT(DISTINCT pelanggarans.id) as jumlah'),
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin')
            )
            ->leftJoin('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->leftJoin('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('pelanggarans.id_siswa', $id)
            ->groupBy('kategori_pelanggarans.nama')
            ->get();

        // Get current petugas info for signature
        $idPetugas = session('id_petugas');
        $namaPetugas = session('nama_petugas', 'Petugas');
        $petugas = DB::table('petugas')
            ->where('id', $idPetugas)
            ->first();

        $ttdPetugas = $petugas ? $petugas->name : $namaPetugas;

        $html = view('pdf.laporan-per-siswa-detail', compact(
            'namaSekolah',
            'tanggal',
            'tanggalCetak',
            'siswa',
            'pelanggaranDetail',
            'totalPoin',
            'statsByKategori',
            'allPengampunan',
            'ttdPetugas'
        ))->render();

        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('laporan-detail-' . $siswa->nis . '-' . $tanggalCetak . '.pdf', ['Attachment' => true]);
    }
}

