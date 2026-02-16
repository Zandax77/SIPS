<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\JenisPelanggaran;
use App\Models\KategoriPelanggaran;
use App\Models\PengampunanPelanggaran;
use App\Services\AISuggestionService;

class KendaliSiswa extends Controller
{
    /**
     * Get Wali Kelas's assigned class from session
     */
    private function getWaliKelas(): ?string
    {
        $jabatan = session('jabatan', '');
        $kelas = session('kelas', '');

        if ($jabatan === 'Wali Kelas' && !empty($kelas)) {
            return $kelas;
        }

        return null;
    }

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
     * Check if user is OSIS
     */
    private function isOsis(): bool
    {
        return strtolower(session('role', 'petugas')) === 'osis';
    }

    /**
     * Display data siswa dengan total poin pelanggaran
     */
    public function index(Request $request)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // OSIS tidak memiliki akses ke data siswa & poin
        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $namaPetugas = session('nama_petugas', 'Petugas');
        $jabatan = $this->getFormattedJabatan();
        $kelasWali = $this->getWaliKelas();

        // Search functionality
        $search = $request->input('search', '');
        $sortBy = $request->input('sort', 'poin_desc'); // Default sort by poin desc

        // Get all siswa with their violation points
        $siswaQuery = DB::table('siswas')
            ->select(
                'siswas.id',
                'siswas.nis',
                'siswas.name as nama_siswa',
                'siswas.kelas',
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin'),
                DB::raw('COUNT(pelanggarans.id) as total_pelanggaran')
            )
            ->leftJoin('pelanggarans', 'siswas.id', '=', 'pelanggarans.id_siswa')
            ->leftJoin('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->leftJoin('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('siswas.name', 'like', "%{$search}%")
                      ->orWhere('siswas.nis', 'like', "%{$search}%")
                      ->orWhere('siswas.kelas', 'like', "%{$search}%");
                });
            })
            // Filter by Guru Wali's class if applicable
            ->when($kelasWali, function ($query) use ($kelasWali) {
                return $query->where('siswas.kelas', $kelasWali);
            })
            ->groupBy('siswas.id', 'siswas.nis', 'siswas.name', 'siswas.kelas');

        // Sorting
        switch ($sortBy) {
            case 'nama_asc':
                $siswaQuery->orderBy('nama_siswa', 'asc');
                break;
            case 'nama_desc':
                $siswaQuery->orderBy('nama_siswa', 'desc');
                break;
            case 'poin_asc':
                $siswaQuery->orderBy('total_poin', 'asc');
                break;
            case 'poin_desc':
                $siswaQuery->orderBy('total_poin', 'desc');
                break;
            case 'kelas':
                $siswaQuery->orderBy('kelas', 'asc')->orderBy('nama_siswa', 'asc');
                break;
            default:
                $siswaQuery->orderBy('total_poin', 'desc');
        }

        $siswa = $siswaQuery->paginate(10);

        return view('siswa-poin', compact(
            'namaPetugas',
            'jabatan',
            'siswa',
            'search',
            'sortBy'
        ));
    }

    /**
     * Display detail pelanggaran siswa
     */
    public function detail($id)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // OSIS tidak memiliki akses ke detail siswa
        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $namaPetugas = session('nama_petugas', 'Petugas');
        $jabatan = $this->getFormattedJabatan();
        $kelasWali = $this->getWaliKelas();

        // Get siswa info
        $siswa = DB::table('siswas')->where('id', $id)->first();

        if (!$siswa) {
            return redirect()->route('siswa.poin')->with('error', 'Siswa tidak ditemukan');
        }

        // Check if Guru Wali can access this student
        if ($kelasWali && $siswa->kelas !== $kelasWali) {
            return redirect()->route('siswa.poin')->with('error', 'Anda hanya dapat melihat data siswa dari kelas ' . $kelasWali);
        }

        // Get pelanggaran detail
        $pelanggaranDetail = DB::table('pelanggarans')
            ->select(
                'pelanggarans.id',
                'jenis_pelanggarans.nama as jenis_pelanggaran',
                'kategori_pelanggarans.nama as kategori',
                'kategori_pelanggarans.poin',
                'pelanggarans.deskripsi',
                'pelanggarans.lampiran',
                'pelanggarans.tipe_lampiran',
                'petugas.name as nama_petugas',
                'pelanggarans.created_at'
            )
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->join('petugas', 'pelanggarans.id_petugas', '=', 'petugas.id')
            ->where('pelanggarans.id_siswa', $id)
            ->orderBy('pelanggarans.created_at', 'desc')
            ->get();

        // Calculate total poin (considering pengampunan/pengurangan)
        $totalPoin = $pelanggaranDetail->sum('poin');

        // Get pengampunan records for this student
        $pengampunanRecords = DB::table('pengampunan_pelanggarans')
            ->where('id_siswa', $id)
            ->get();

        // Calculate total poin dikurangi
        $totalPoinDikurangi = $pengampunanRecords->sum('poin_dikurangi');

        // Adjust totalPoin by subtracting dikurangi points (only for non-forgiven violations)
        // For forgiven violations, the entire point is already counted as 0 effectively
        $forgivenPelanggaranIds = $pengampunanRecords
            ->where('tipe', 'pengampunan')
            ->pluck('id_pelanggaran')
            ->toArray();

        // Get points that should be excluded (fully forgiven violations)
        $forgivenPoints = DB::table('pelanggarans')
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->whereIn('pelanggarans.id', $forgivenPelanggaranIds)
            ->sum('kategori_pelanggarans.poin');

        // Final total poin = original points - forgiven points - reduced points
        $totalPoin = $totalPoin - $forgivenPoints - $totalPoinDikurangi;
        if ($totalPoin < 0) $totalPoin = 0;

        // Add pengampunan info to pelanggaran records for display
        foreach ($pelanggaranDetail as $pelanggaran) {
            $pelPoinDikurangi = $pengampunanRecords
                ->where('id_pelanggaran', $pelanggaran->id)
                ->sum('poin_dikurangi');

            $isForgiven = in_array($pelanggaran->id, $forgivenPelanggaranIds);

            $pelanggaran->poin_dikurangi = $pelPoinDikurangi;
            $pelanggaran->poin_sisa = $isForgiven ? 0 : ($pelanggaran->poin - $pelPoinDikurangi);
            $pelanggaran->sudah_diampuni = $isForgiven;
        }

        // Group by kategori untuk statistik
        $statsByKategori = DB::table('pelanggarans')
            ->select(
                'kategori_pelanggarans.nama as kategori',
                DB::raw('COUNT(pelanggarans.id) as jumlah'),
                DB::raw('SUM(kategori_pelanggarans.poin) as total_poin')
            )
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('pelanggarans.id_siswa', $id)
            ->groupBy('kategori_pelanggarans.nama')
            ->get();

        // AI Analysis - Generate suggestions based on violation history
        $aiSuggestionService = new AISuggestionService();
        $aiSuggestions = $aiSuggestionService->analyzeViolationHistory($id);

        return view('siswa-poin-detail', compact(
            'namaPetugas',
            'jabatan',
            'siswa',
            'pelanggaranDetail',
            'totalPoin',
            'statsByKategori',
            'aiSuggestions'
        ));
    }

    /**
     * API: Search siswa untuk autocomplete
     */
    public function searchApi(Request $request)
    {
        // OSIS tidak memiliki akses ke API ini
        if ($this->isOsis()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $search = $request->input('q', '');
        $kelasWali = $this->getWaliKelas();

        $siswa = DB::table('siswas')
            ->select('id', 'nis', 'name as nama_siswa', 'kelas')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            })
            // Filter by Guru Wali's class if applicable
            ->when($kelasWali, function ($query) use ($kelasWali) {
                return $query->where('kelas', $kelasWali);
            })
            ->limit(10)
            ->get();

        return response()->json($siswa);
    }

    /**
     * API: Get siswa by ID untuk QR scan
     */
    public function getById($id)
    {
        // OSIS tidak memiliki akses ke API ini
        if ($this->isOsis()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $kelasWali = $this->getWaliKelas();

        $siswa = DB::table('siswas')
            ->select('id', 'nis', 'name as nama_siswa', 'kelas')
            ->where('id', $id)
            ->when($kelasWali, function ($query) use ($kelasWali) {
                return $query->where('kelas', $kelasWali);
            })
            ->first();

        if (!$siswa) {
            return response()->json(['error' => 'Siswa tidak ditemukan'], 404);
        }

        // Get total poin
        $totalPoin = DB::table('pelanggarans')
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('pelanggarans.id_siswa', $id)
            ->sum('kategori_pelanggarans.poin');

        $siswa->total_poin = $totalPoin;

        return response()->json($siswa);
    }

    /**
     * View lampiran (attachment) file - photo or document
     */
    public function viewLampiran($id)
    {
        // OSIS tidak memiliki akses ke lampiran
        if ($this->isOsis()) {
            abort(403, 'Unauthorized');
        }

        // Get pelanggaran data
        $pelanggaran = DB::table('pelanggarans')
            ->where('id', $id)
            ->first();

        if (!$pelanggaran || !$pelanggaran->lampiran) {
            abort(404, 'Lampiran tidak ditemukan');
        }

        // Get siswa info for display
        $siswa = DB::table('siswas')->where('id', $pelanggaran->id_siswa)->first();

        // Build full path
        $filePath = storage_path('app/private/' . $pelanggaran->lampiran);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        // Get file extension
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeType = '';

        switch (strtolower($extension)) {
            case 'jpg':
            case 'jpeg':
                $mimeType = 'image/jpeg';
                break;
            case 'pdf':
                $mimeType = 'application/pdf';
                break;
            case 'doc':
                $mimeType = 'application/msword';
                break;
            case 'docx':
                $mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                break;
            default:
                $mimeType = 'application/octet-stream';
        }

        // Return file response with proper headers
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="lampiran_' . $id . '.' . $extension . '"',
        ]);
    }

    /**
     * Download lampiran (attachment) file
     */
    public function downloadLampiran($id)
    {
        // OSIS tidak memiliki akses download lampiran
        if ($this->isOsis()) {
            abort(403, 'Unauthorized');
        }

        // Get pelanggaran data
        $pelanggaran = DB::table('pelanggarans')
            ->where('id', $id)
            ->first();

        if (!$pelanggaran || !$pelanggaran->lampiran) {
            abort(404, 'Lampiran tidak ditemukan');
        }

        // Get siswa info for filename
        $siswa = DB::table('siswas')->where('id', $pelanggaran->id_siswa)->first();
        $jenis = DB::table('jenis_pelanggarans')->where('id', $pelanggaran->id_jenis_pelanggaran)->first();

        // Build full path
        $filePath = storage_path('app/private/' . $pelanggaran->lampiran);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        // Get file extension
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        // Create descriptive filename
        $filename = 'lampiran_' . ($siswa->name ?? 'siswa') . '_' . ($jenis->nama ?? 'pelanggaran') . '_' . date('Y-m-d', strtotime($pelanggaran->created_at)) . '.' . $extension;

        return response()->download($filePath, $filename);
    }
}

