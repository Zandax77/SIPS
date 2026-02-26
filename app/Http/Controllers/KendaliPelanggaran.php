<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\JenisPelanggaran;
use App\Models\KategoriPelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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
     * Check if violation requires photo/document upload (Berat or Sedang)
     */
    private function requiresBuktiFoto($idJenisPelanggaran): bool
    {
        $jenis = DB::table('jenis_pelanggarans')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('jenis_pelanggarans.id', $idJenisPelanggaran)
            ->first();

        if ($jenis) {
            return in_array($jenis->nama, ['Berat', 'Sedang']);
        }

        return false;
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
        // Check violation category first for validation
        $jenisPelanggaran = DB::table('jenis_pelanggarans')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('jenis_pelanggarans.id', $request->id_jenis_pelanggaran)
            ->first();

        $kategoriName = $jenisPelanggaran->nama ?? '';
        $requiresBukti = in_array($kategoriName, ['Berat', 'Sedang']);

        // Validasi
        $rules = [
            'id_siswa' => 'required|exists:siswas,id',
            'id_jenis_pelanggaran' => 'required|exists:jenis_pelanggarans,id',
            'deskripsi' => 'nullable|string|max:500',
        ];

        // Add bukti_foto validation for Berat or Sedang
        if ($requiresBukti) {
            $rules['bukti_foto'] = 'required|file|mimes:jpeg,jpg,png,gif,pdf,doc,docx|max:1024'; // 1MB = 1024KB
        }

        $messages = [];
        if ($requiresBukti) {
            $messages['bukti_foto.required'] = 'Foto/Dokumen bukti pelanggaran wajib diupload untuk kategori ' . $kategoriName . '.';
            $messages['bukti_foto.max'] = 'Ukuran file maksimal 1MB.';
            $messages['bukti_foto.mimes'] = 'Format file harus JPEG, JPG, PNG, GIF, PDF, DOC, atau DOCX.';
        }

        $request->validate($rules, $messages);

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

        // Handle file upload
        $buktiFotoPath = null;
        if ($request->hasFile('bukti_foto') && $requiresBukti) {
            $file = $request->file('bukti_foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $buktiFotoPath = $file->storeAs('bukti_pelanggaran', $filename, 'public');
        }

        // Simpan pelanggaran
        $pelanggaran = new Pelanggaran;
        $pelanggaran->id_siswa = $request->input('id_siswa');
        $pelanggaran->id_jenis_pelanggaran = $request->input('id_jenis_pelanggaran');
        $pelanggaran->deskripsi = $request->input('deskripsi', '');
        $pelanggaran->id_petugas = $id_petugas;
        $pelanggaran->bukti_foto = $buktiFotoPath;
        $pelanggaran->save();

        // Get siswa name untuk notifikasi
        $siswa = DB::table('siswas')->where('id', $request->id_siswa)->first();

        $message = "Pelanggaran {$jenisPelanggaran->nama} ({$jenisPelanggaran->poin} poin) untuk {$siswa->name} berhasil dicatat.";

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
     * Get list of unique classes from siswa table
     */
    private function getKelasList(): array
    {
        $kelasWali = $this->getWaliKelas();
        
        $kelasQuery = DB::table('siswas')
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas');

        if ($kelasWali) {
            $kelasQuery->where('kelas', $kelasWali);
        }

        $kelas = $kelasQuery->pluck('kelas')->toArray();

        return $kelas;
    }

    /**
     * Display laporan pelanggaran per kelas form
     */
    public function laporanKelas(Request $request)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        $namaPetugas = session('nama_petugas', 'Petugas');
        $jabatan = $this->getFormattedJabatan();
        
        // Get kelas list
        $kelasList = $this->getKelasList();

        // Get selected filters
        $selectedKelas = $request->input('kelas', '');
        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-01'));
        $tanggalAkhir = $request->input('tanggal_akhir', date('Y-m-t'));

        // If kelas selected, get violation data
        $pelanggaranData = [];
        $rekapPerSiswa = [];
        $totalPoin = 0;
        $totalPelanggaran = 0;

        if ($selectedKelas) {
            // Get all violations for the selected class and date range
            $pelanggaranData = DB::table('pelanggarans')
                ->select(
                    'pelanggarans.id',
                    'siswas.nis',
                    'siswas.name as nama_siswa',
                    'siswas.kelas',
                    'jenis_pelanggarans.nama as jenis_pelanggaran',
                    'kategori_pelanggarans.nama as kategori',
                    'kategori_pelanggarans.poin',
                    'pelanggarans.deskripsi',
                    'petugas.name as nama_petugas',
                    'pelanggarans.created_at'
                )
                ->join('siswas', 'pelanggarans.id_siswa', '=', 'siswas.id')
                ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
                ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
                ->join('petugas', 'pelanggarans.id_petugas', '=', 'petugas.id')
                ->where('siswas.kelas', $selectedKelas)
                ->whereBetween('pelanggarans.created_at', [$tanggalMulai . ' 00:00:00', $tanggalAkhir . ' 23:59:59'])
                ->orderBy('pelanggarans.created_at', 'desc')
                ->get();

            // Calculate totals
            $totalPoin = $pelanggaranData->sum('poin');
            $totalPelanggaran = $pelanggaranData->count();

            // Group by student for summary
            $rekapPerSiswa = DB::table('pelanggarans')
                ->select(
                    'siswas.id',
                    'siswas.nis',
                    'siswas.name as nama_siswa',
                    'siswas.kelas',
                    DB::raw('COUNT(pelanggarans.id) as jumlah_pelanggaran'),
                    DB::raw('SUM(kategori_pelanggarans.poin) as total_poin')
                )
                ->join('siswas', 'pelanggarans.id_siswa', '=', 'siswas.id')
                ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
                ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
                ->where('siswas.kelas', $selectedKelas)
                ->whereBetween('pelanggarans.created_at', [$tanggalMulai . ' 00:00:00', $tanggalAkhir . ' 23:59:59'])
                ->groupBy('siswas.id', 'siswas.nis', 'siswas.name', 'siswas.kelas')
                ->orderByDesc('total_poin')
                ->get();

            // Stats by category
            $statsByKategori = DB::table('pelanggarans')
                ->select(
                    'kategori_pelanggarans.nama as kategori',
                    DB::raw('COUNT(pelanggarans.id) as jumlah'),
                    DB::raw('SUM(kategori_pelanggarans.poin) as total_poin')
                )
                ->join('siswas', 'pelanggarans.id_siswa', '=', 'siswas.id')
                ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
                ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
                ->where('siswas.kelas', $selectedKelas)
                ->whereBetween('pelanggarans.created_at', [$tanggalMulai . ' 00:00:00', $tanggalAkhir . ' 23:59:59'])
                ->groupBy('kategori_pelanggarans.nama')
                ->get();
        }

        return view('laporan-pelanggaran-kelas', compact(
            'namaPetugas',
            'jabatan',
            'kelasList',
            'selectedKelas',
            'tanggalMulai',
            'tanggalAkhir',
            'pelanggaranData',
            'rekapPerSiswa',
            'totalPoin',
            'totalPelanggaran'
        ));
    }

    /**
     * Cetak laporan pelanggaran per kelas
     */
    public function cetakLaporanKelas(Request $request)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        $selectedKelas = $request->input('kelas', '');
        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-01'));
        $tanggalAkhir = $request->input('tanggal_akhir', date('Y-m-t'));

        if (!$selectedKelas) {
            return redirect()->route('pelanggaran.laporan.kelas')
                ->with('error', 'Silakan pilih kelas terlebih dahulu.');
        }

        // Get school information
        $sekolah = \App\Models\Sekolah::getOrCreate();

        // Get pelanggaran data
        $pelanggaranData = DB::table('pelanggarans')
            ->select(
                'pelanggarans.id',
                'siswas.nis',
                'siswas.name as nama_siswa',
                'siswas.kelas',
                'jenis_pelanggarans.nama as jenis_pelanggaran',
                'kategori_pelanggarans.nama as kategori',
                'kategori_pelanggarans.poin',
                'pelanggarans.deskripsi',
                'petugas.name as nama_petugas',
                'pelanggarans.created_at'
            )
            ->join('siswas', 'pelanggarans.id_siswa', '=', 'siswas.id')
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->join('petugas', 'pelanggarans.id_petugas', '=', 'petugas.id')
            ->where('siswas.kelas', $selectedKelas)
            ->whereBetween('pelanggarans.created_at', [$tanggalMulai . ' 00:00:00', $tanggalAkhir . ' 23:59:59'])
            ->orderBy('siswas.name')
            ->orderBy('pelanggarans.created_at', 'desc')
            ->get();

        $totalPoin = $pelanggaranData->sum('poin');
        $totalPelanggaran = $pelanggaranData->count();

        // Rekap per siswa
        $rekapPerSiswa = DB::table('pelanggarans')
            ->select(
                'siswas.id',
                'siswas.nis',
                'siswas.name as nama_siswa',
                'siswas.kelas',
                DB::raw('COUNT(pelanggarans.id) as jumlah_pelanggaran'),
                DB::raw('SUM(kategori_pelanggarans.poin) as total_poin')
            )
            ->join('siswas', 'pelanggarans.id_siswa', '=', 'siswas.id')
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('siswas.kelas', $selectedKelas)
            ->whereBetween('pelanggarans.created_at', [$tanggalMulai . ' 00:00:00', $tanggalAkhir . ' 23:59:59'])
            ->groupBy('siswas.id', 'siswas.nis', 'siswas.name', 'siswas.kelas')
            ->orderByDesc('total_poin')
            ->get();

        return view('pdf.pelanggaran-kelas', compact(
            'sekolah',
            'selectedKelas',
            'tanggalMulai',
            'tanggalAkhir',
            'pelanggaranData',
            'rekapPerSiswa',
            'totalPoin',
            'totalPelanggaran'
        ));
    }
}

