<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\JenisPelanggaran;
use App\Models\KategoriPelanggaran;
use App\Models\PengampunanPelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class KendaliPelanggaran extends Controller
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
     * Display form pencatatan pelanggaran
     */
    public function index(Request $request)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        $namaPetugas = session('nama_petugas', 'Petugas');
        $jabatan = $this->getFormattedJabatan();
        $kelasWali = $this->getWaliKelas();
        $role = session('role', 'petugas');

        // Get semua jenis pelanggaran dengan kategori dan poin
        $jenisPelanggaran = DB::table('jenis_pelanggarans')
            ->select(
                'jenis_pelanggarans.id',
                'jenis_pelanggarans.nama',
                'jenis_pelanggarans.deskripsi',
                'kategori_pelanggarans.nama as kategori',
                'kategori_pelanggarans.poin'
            )
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->orderBy('kategori_pelanggarans.nama')
            ->orderBy('jenis_pelanggarans.nama')
            ->get();

        // Group by kategori untuk dropdown
        $kategoriList = $jenisPelanggaran->groupBy('kategori');

        // Pencarian siswa
        $searchSiswa = $request->input('search_siswa', '');
        $siswaResult = [];

        if ($searchSiswa) {
            $siswaQuery = DB::table('siswas')
                ->select('id', 'nis', 'name as nama_siswa', 'kelas')
                ->where(function ($q) use ($searchSiswa) {
                    $q->where('name', 'like', "%{$searchSiswa}%")
                      ->orWhere('nis', 'like', "%{$searchSiswa}%");
                });

            // Filter by Guru Wali's class if applicable
            if ($kelasWali) {
                $siswaQuery->where('kelas', $kelasWali);
            }

            $siswaResult = $siswaQuery->limit(10)->get();
        }

        // Get selected siswa if any
        $selectedSiswa = null;
        if ($request->has('id_siswa')) {
            $siswaQuery = DB::table('siswas')
                ->where('id', $request->id_siswa);

            // Filter by Guru Wali's class if applicable
            if ($kelasWali) {
                $siswaQuery->where('kelas', $kelasWali);
            }

            $selectedSiswa = $siswaQuery->first();

            if ($selectedSiswa) {
                // Get total poin siswa
                $totalPoin = DB::table('pelanggarans')
                    ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
                    ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
                    ->where('pelanggarans.id_siswa', $selectedSiswa->id)
                    ->sum('kategori_pelanggarans.poin');
                $selectedSiswa->total_poin = $totalPoin;
            } else {
                // Reset search if student not found (not in Guru Wali's class)
                return redirect()->route('pelanggaran.catat')
                    ->with('error', 'Siswa tidak ditemukan di kelas Anda.');
            }
        }

        return view('catat-pelanggaran', compact(
            'namaPetugas',
            'jabatan',
            'role',
            'jenisPelanggaran',
            'kategoriList',
            'searchSiswa',
            'siswaResult',
            'selectedSiswa'
        ));
    }

    /**
     * Store pelanggaran baru
     */
    public function catatPelanggaran(Request $request)
    {
        // Validasi
        $request->validate([
            'id_siswa' => 'required|exists:siswas,id',
            'id_jenis_pelanggaran' => 'required|exists:jenis_pelanggarans,id',
            'deskripsi' => 'nullable|string|max:500',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,doc,docx,pdf|max:1024', // max 1MB = 1024KB
        ]);

        // Check if id_petugas exists in session, if not get from auth
        $id_petugas = session()->get('id_petugas');
        if (!$id_petugas && auth()->guard('petugas')->check()) {
            $id_petugas = auth()->guard('petugas')->user()->id;
        }

        if (!$id_petugas) {
            return redirect()->back()->with('error', 'Sesi petugas tidak valid. Silakan login ulang.');
        }

        // Check if Guru Wali can record violation for this student
        $kelasWali = $this->getWaliKelas();
        if ($kelasWali) {
            $siswa = DB::table('siswas')->where('id', $request->id_siswa)->first();
            if (!$siswa || $siswa->kelas !== $kelasWali) {
                return redirect()->back()->with('error', 'Anda hanya dapat mencatat pelanggaran untuk siswa di kelas ' . $kelasWali);
            }
        }

        // Get violation type to check category (Sedang/Berat requires attachment)
        $jenisPelanggaran = DB::table('jenis_pelanggarans')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('jenis_pelanggarans.id', $request->id_jenis_pelanggaran)
            ->first();

        $kategori = $jenisPelanggaran->nama ?? '';

        // Check if attachment is required for Sedang/Berat violations
        if (in_array($kategori, ['Sedang', 'Berat'])) {
            if (!$request->hasFile('lampiran')) {
                return redirect()->back()->with('error', 'Lampiran wajib diunggah untuk pelanggaran ' . $kategori)->withInput();
            }
        }

        // Initialize variables for attachment
        $lampiranPath = null;
        $tipeLampiran = null;

        // Handle file upload if present
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $extension = $file->getClientOriginalExtension();

            // Determine file type
            if (in_array(strtolower($extension), ['jpg', 'jpeg'])) {
                $tipeLampiran = 'foto';
            } else {
                $tipeLampiran = 'dokumen';
            }

            // Create directory if not exists
            $storagePath = storage_path('app/private/lampiran_pelanggaran');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $file->storeAs('lampiran_pelanggaran', $filename);

            $lampiranPath = 'lampiran_pelanggaran/' . $filename;
        }

        // Simpan pelanggaran
        $pelanggaran = new Pelanggaran;
        $pelanggaran->id_siswa = $request->input('id_siswa');
        $pelanggaran->id_jenis_pelanggaran = $request->input('id_jenis_pelanggaran');
        $pelanggaran->deskripsi = $request->input('deskripsi', '');
        $pelanggaran->id_petugas = $id_petugas;
        $pelanggaran->lampiran = $lampiranPath;
        $pelanggaran->tipe_lampiran = $tipeLampiran;
        $pelanggaran->save();

        // Get siswa name untuk notifikasi
        $siswa = DB::table('siswas')->where('id', $request->id_siswa)->first();

        // Get pelanggaran info
        $jenis = DB::table('jenis_pelanggarans')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('jenis_pelanggarans.id', $request->id_jenis_pelanggaran)
            ->first();

        $message = "Pelanggaran {$jenis->nama} ({$jenis->poin} poin) untuk {$siswa->name} berhasil dicatat.";

        // OSIS users should stay on Catat Pelanggaran page
        if (session('role') === 'osis') {
            return redirect()->route('pelanggaran.catat')->with('success', $message);
        }

        return redirect()->route('dashboard')->with('success', $message);
    }

    /**
     * API: Search siswa untuk autocomplete
     */
    public function searchSiswa(Request $request)
    {
        $search = $request->input('q', '');
        $kelasWali = $this->getWaliKelas();

        $siswaQuery = DB::table('siswas')
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
            ->limit(10);

        $siswa = $siswaQuery->get();

        return response()->json($siswa);
    }

    /**
     * API: Get siswa by ID untuk QR scan
     */
    public function getSiswaById($id)
    {
        $kelasWali = $this->getWaliKelas();

        $siswaQuery = DB::table('siswas')
            ->select('id', 'nis', 'name as nama_siswa', 'kelas')
            ->where('id', $id)
            // Filter by Guru Wali's class if applicable
            ->when($kelasWali, function ($query) use ($kelasWali) {
                return $query->where('kelas', $kelasWali);
            });

        $siswa = $siswaQuery->first();

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
     * Get violation detail for pengampunan/pengurangan action
     */
    public function getDetailPelanggaran($id)
    {
        // OSIS tidak memiliki akses ke API ini
        if ($this->isOsis()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get pelanggaran dengan detail
        $pelanggaran = DB::table('pelanggarans')
            ->select(
                'pelanggarans.id',
                'pelanggarans.id_siswa',
                'jenis_pelanggarans.nama as jenis_pelanggaran',
                'kategori_pelanggarans.nama as kategori',
                'kategori_pelanggarans.poin',
                'pelanggarans.deskripsi',
                'pelanggarans.created_at'
            )
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('pelanggarans.id', $id)
            ->first();

        if (!$pelanggaran) {
            return response()->json(['error' => 'Pelanggaran tidak ditemukan'], 404);
        }

        // Get existing pengampunan if any
        $pengampunan = DB::table('pengampunan_pelanggarans')
            ->where('id_pelanggaran', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate remaining points
        $poinDikurangi = $pengampunan->sum('poin_dikurangi');
        $pelanggaran->poin_dikurangi = $poinDikurangi;
        $pelanggaran->poin_sisa = $pelanggaran->poin - $poinDikurangi;
        $pelanggaran->sudah_diampuni = $pengampunan->where('tipe', 'pengampunan')->count() > 0;

        // Get siswa info
        $siswa = DB::table('siswas')->where('id', $pelanggaran->id_siswa)->first();
        $pelanggaran->nama_siswa = $siswa->name ?? '';
        $pelanggaran->nis_siswa = $siswa->nis ?? '';
        $pelanggaran->kelas_siswa = $siswa->kelas ?? '';

        return response()->json([
            'pelanggaran' => $pelanggaran,
            'riwayat_pengampunan' => $pengampunan
        ]);
    }

    /**
     * Simpan pengampunan pelanggaran (hapus pelanggaran)
     */
    public function simpanPengampunan(Request $request)
    {
        // OSIS tidak memiliki akses fitur pengampunan
        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke fitur ini.');
        }

        $request->validate([
            'id_pelanggaran' => 'required|exists:pelanggarans,id',
            'alasan' => 'required|string|max:500',
        ]);

        // Check session
        $id_petugas = session()->get('id_petugas');
        if (!$id_petugas) {
            return redirect()->back()->with('error', 'Sesi petugas tidak valid. Silakan login ulang.');
        }

        // Get pelanggaran info
        $pelanggaran = DB::table('pelanggarans')
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('pelanggarans.id', $request->id_pelanggaran)
            ->first();

        if (!$pelanggaran) {
            return redirect()->back()->with('error', 'Pelanggaran tidak ditemukan.');
        }

        // Check if already forgiven
        $existingPengampunan = DB::table('pengampunan_pelanggarans')
            ->where('id_pelanggaran', $request->id_pelanggaran)
            ->where('tipe', 'pengampunan')
            ->first();

        if ($existingPengampunan) {
            return redirect()->back()->with('error', 'Pelanggaran ini sudah diampuni sebelumnya.');
        }

        // Get total poin yang sudah dikurangi
        $poinSudahDikurangi = DB::table('pengampunan_pelanggarans')
            ->where('id_pelanggaran', $request->id_pelanggaran)
            ->sum('poin_dikurangi');

        // Simpan pengampunan
        $pengampunan = new PengampunanPelanggaran;
        $pengampunan->id_pelanggaran = $request->id_pelanggaran;
        $pengampunan->id_siswa = $pelanggaran->id_siswa;
        $pengampunan->id_petugas = $id_petugas;
        $pengampunan->tipe = 'pengampunan';
        $pengampunan->poin_asli = $pelanggaran->poin;
        $pengampunan->poin_dikurangi = $pelanggaran->poin - $poinSudahDikurangi; // Ampuni sisa poin
        $pengampunan->alasan = $request->alasan;
        $pengampunan->save();

        // Get siswa name
        $siswa = DB::table('siswas')->where('id', $pelanggaran->id_siswa)->first();

        $message = "Pelanggaran {$pelanggaran->nama} untuk {$siswa->name} berhasil diampuni.";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Simpan pengurangan poin pelanggaran
     */
    public function simpanPenguranganPoin(Request $request)
    {
        // OSIS tidak memiliki akses fitur pengurangan poin
        if ($this->isOsis()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke fitur ini.');
        }

        $request->validate([
            'id_pelanggaran' => 'required|exists:pelanggarans,id',
            'poin_dikurangi' => 'required|integer|min:1',
            'alasan' => 'required|string|max:500',
        ]);

        // Check session
        $id_petugas = session()->get('id_petugas');
        if (!$id_petugas) {
            return redirect()->back()->with('error', 'Sesi petugas tidak valid. Silakan login ulang.');
        }

        // Get pelanggaran info
        $pelanggaran = DB::table('pelanggarans')
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('pelanggarans.id', $request->id_pelanggaran)
            ->first();

        if (!$pelanggaran) {
            return redirect()->back()->with('error', 'Pelanggaran tidak ditemukan.');
        }

        // Check if already fully forgiven
        $existingPengampunan = DB::table('pengampunan_pelanggarans')
            ->where('id_pelanggaran', $request->id_pelanggaran)
            ->where('tipe', 'pengampunan')
            ->first();

        if ($existingPengampunan) {
            return redirect()->back()->with('error', 'Pelanggaran ini sudah diampuni sepenuhnya.');
        }

        // Get total poin yang sudah dikurangi
        $poinSudahDikurangi = DB::table('pengampunan_pelanggarans')
            ->where('id_pelanggaran', $request->id_pelanggaran)
            ->sum('poin_dikurangi');

        $poinSisa = $pelanggaran->poin - $poinSudahDikurangi;

        // Validasi tidak melebihi poin pelanggaran
        if ($request->poin_dikurangi > $poinSisa) {
            return redirect()->back()->with('error', 'Poin yang dikurangi tidak boleh melebihi poin pelanggaran tersisa (' . $poinSisa . ' poin).');
        }

        // Simpan pengurangan poin
        $pengampunan = new PengampunanPelanggaran;
        $pengampunan->id_pelanggaran = $request->id_pelanggaran;
        $pengampunan->id_siswa = $pelanggaran->id_siswa;
        $pengampunan->id_petugas = $id_petugas;
        $pengampunan->tipe = 'pengurangan_poin';
        $pengampunan->poin_asli = $pelanggaran->poin;
        $pengampunan->poin_dikurangi = $request->poin_dikurangi;
        $pengampunan->alasan = $request->alasan;
        $pengampunan->save();

        // Get siswa name
        $siswa = DB::table('siswas')->where('id', $pelanggaran->id_siswa)->first();

        $message = "Poin pelanggaran {$pelanggaran->nama} untuk {$siswa->name} berhasil dikurangi {$request->poin_dikurangi} poin.";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Get riwayat pengampunan untuk siswa
     */
    public function getRiwayatPengampunan($id_siswa)
    {
        // OSIS tidak memiliki akses ke API ini
        if ($this->isOsis()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $riwayat = DB::table('pengampunan_pelanggarans')
            ->select(
                'pengampunan_pelanggarans.*',
                'petugas.name as nama_petugas',
                'jenis_pelanggarans.nama as nama_pelanggaran'
            )
            ->join('petugas', 'pengampunan_pelanggarans.id_petugas', '=', 'petugas.id')
            ->join('pelanggarans', 'pengampunan_pelanggarans.id_pelanggaran', '=', 'pelanggarans.id')
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->where('pengampunan_pelanggarans.id_siswa', $id_siswa)
            ->orderBy('pengampunan_pelanggarans.created_at', 'desc')
            ->get();

        return response()->json($riwayat);
    }
}

