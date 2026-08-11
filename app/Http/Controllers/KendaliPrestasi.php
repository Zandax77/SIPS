<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\Siswa;
use App\Models\Pelanggaran;
use App\Models\KategoriPelanggaran;
use App\Models\JenisPelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KendaliPrestasi extends Controller
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
     * Display a listing of all achievements
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

        // Filters
        $search = $request->input('search', '');
        $bidang = $request->input('bidang', '');
        $tingkat = $request->input('tingkat', '');
        $sortBy = $request->input('sort', 'tanggal_desc');

        // Query prestasi with joins
        $prestasiQuery = DB::table('prestasis')
            ->select(
                'prestasis.*',
                'siswas.nis',
                'siswas.name as nama_siswa',
                'siswas.kelas',
                'petugas.name as nama_petugas'
            )
            ->join('siswas', 'prestasis.id_siswa', '=', 'siswas.id')
            ->join('petugas', 'prestasis.id_petugas', '=', 'petugas.id')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('siswas.name', 'like', "%{$search}%")
                      ->orWhere('siswas.nis', 'like', "%{$search}%")
                      ->orWhere('prestasis.nama_prestasi', 'like', "%{$search}%");
                });
            })
            ->when($bidang, function ($query) use ($bidang) {
                return $query->where('prestasis.bidang', $bidang);
            })
            ->when($tingkat, function ($query) use ($tingkat) {
                return $query->where('prestasis.tingkat', $tingkat);
            })
            // Filter by Guru Wali's class if applicable
            ->when($kelasWali, function ($query) use ($kelasWali) {
                return $query->where('siswas.kelas', $kelasWali);
            });

        // Sorting
        switch ($sortBy) {
            case 'tanggal_asc':
                $prestasiQuery->orderBy('prestasis.tanggal_prestasi', 'asc');
                break;
            case 'nama_asc':
                $prestasiQuery->orderBy('siswas.name', 'asc');
                break;
            case 'nama_desc':
                $prestasiQuery->orderBy('siswas.name', 'desc');
                break;
            case 'poin_desc':
                $prestasiQuery->orderBy('prestasis.pengurangan_poin', 'desc');
                break;
            case 'poin_asc':
                $prestasiQuery->orderBy('prestasis.pengurangan_poin', 'asc');
                break;
            case 'tingkat':
                $prestasiQuery->orderByRaw("FIELD(tingkat, 'Sekolah', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional')");
                break;
            default: // tanggal_desc
                $prestasiQuery->orderBy('prestasis.tanggal_prestasi', 'desc');
        }

        $prestasi = $prestasiQuery->paginate(10);

        // Get stats
        $totalPrestasi = $prestasi->total();
        $totalPenguranganPoin = DB::table('prestasis')
            ->when($kelasWali, function ($query) use ($kelasWali) {
                return $query->whereIn('id_siswa', function ($q) use ($kelasWali) {
                    $q->select('id')->from('siswas')->where('kelas', $kelasWali);
                });
            })
            ->sum('pengurangan_poin');

        // Count by bidang
        $countAkademik = DB::table('prestasis')
            ->where('bidang', 'Akademik')
            ->when($kelasWali, function ($query) use ($kelasWali) {
                return $query->whereIn('id_siswa', function ($q) use ($kelasWali) {
                    $q->select('id')->from('siswas')->where('kelas', $kelasWali);
                });
            })
            ->count();

        $countNonAkademik = DB::table('prestasis')
            ->where('bidang', 'Non Akademik')
            ->when($kelasWali, function ($query) use ($kelasWali) {
                return $query->whereIn('id_siswa', function ($q) use ($kelasWali) {
                    $q->select('id')->from('siswas')->where('kelas', $kelasWali);
                });
            })
            ->count();

        // Count by tingkat
        $countByTingkat = DB::table('prestasis')
            ->select('tingkat', DB::raw('COUNT(*) as total'))
            ->when($kelasWali, function ($query) use ($kelasWali) {
                return $query->whereIn('id_siswa', function ($q) use ($kelasWali) {
                    $q->select('id')->from('siswas')->where('kelas', $kelasWali);
                });
            })
            ->groupBy('tingkat')
            ->pluck('total', 'tingkat')
            ->toArray();

        return view('prestasi-index', compact(
            'namaPetugas',
            'jabatan',
            'prestasi',
            'search',
            'bidang',
            'tingkat',
            'sortBy',
            'totalPrestasi',
            'totalPenguranganPoin',
            'countAkademik',
            'countNonAkademik',
            'countByTingkat'
        ));
    }

    /**
     * Show form to create a new achievement
     */
    public function create(Request $request)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        $namaPetugas = session('nama_petugas', 'Petugas');
        $jabatan = $this->getFormattedJabatan();
        $kelasWali = $this->getWaliKelas();

        // If siswa_id is provided, get student info
        $selectedSiswa = null;
        if ($request->has('siswa_id')) {
            $siswaQuery = DB::table('siswas')
                ->where('id', $request->siswa_id);

            if ($kelasWali) {
                $siswaQuery->where('kelas', $kelasWali);
            }

            $selectedSiswa = $siswaQuery->first();
        }

        // Get search query
        $searchSiswa = $request->input('search_siswa', '');
        $siswaResult = [];

        if ($searchSiswa) {
            $siswaQuery = DB::table('siswas')
                ->select('id', 'nis', 'name as nama_siswa', 'kelas')
                ->where(function ($q) use ($searchSiswa) {
                    $q->where('name', 'like', "%{$searchSiswa}%")
                      ->orWhere('nis', 'like', "%{$searchSiswa}%");
                });

            if ($kelasWali) {
                $siswaQuery->where('kelas', $kelasWali);
            }

            $siswaResult = $siswaQuery->limit(10)->get();
        }

        return view('prestasi-form', compact(
            'namaPetugas',
            'jabatan',
            'selectedSiswa',
            'searchSiswa',
            'siswaResult'
        ));
    }

    /**
     * Store a newly created achievement
     */
    public function store(Request $request)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // Kepala Sekolah cannot create achievements
        if (session('jabatan') === 'Kepala Sekolah') {
            return redirect()->back()->with('error', 'Kepala Sekolah tidak dapat menambah prestasi.');
        }

        $kelasWali = $this->getWaliKelas();

        // Validate request
        $request->validate([
            'id_siswa' => 'required|exists:siswas,id',
            'nama_prestasi' => 'required|string|max:255',
            'bidang' => 'required|in:Akademik,Non Akademik',
            'tingkat' => 'required|in:Sekolah,Kecamatan,Kabupaten,Provinsi,Nasional',
            'peringkat' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string|max:1000',
            'pengurangan_poin' => 'required|integer|min:0|max:100',
            'tanggal_prestasi' => 'required|date',
            'bukti_foto' => 'nullable|file|mimes:jpeg,jpg,png,gif,pdf|max:2048',
        ], [
            'id_siswa.required' => 'Siswa wajib dipilih.',
            'nama_prestasi.required' => 'Nama prestasi wajib diisi.',
            'bidang.required' => 'Bidang prestasi wajib dipilih.',
            'tingkat.required' => 'Tingkat prestasi wajib dipilih.',
            'pengurangan_poin.required' => 'Jumlah pengurangan poin wajib diisi.',
            'pengurangan_poin.max' => 'Pengurangan poin maksimal 100.',
            'tanggal_prestasi.required' => 'Tanggal prestasi wajib diisi.',
            'bukti_foto.max' => 'Ukuran file maksimal 2MB.',
            'bukti_foto.mimes' => 'Format file harus JPEG, JPG, PNG, GIF, atau PDF.',
        ]);

        // Check if siswa exists and is accessible
        $siswa = DB::table('siswas')->where('id', $request->id_siswa)->first();
        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan');
        }

        if ($kelasWali && $siswa->kelas !== $kelasWali) {
            return redirect()->back()->with('error', 'Anda hanya dapat mencatat prestasi untuk siswa di kelas ' . $kelasWali);
        }

        // Get petugas ID from session
        $id_petugas = session('id_petugas');

        // Handle file upload
        $buktiFotoPath = null;
        if ($request->hasFile('bukti_foto')) {
            $file = $request->file('bukti_foto');
            $filename = 'prestasi_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $buktiFotoPath = $file->storeAs('bukti_prestasi', $filename, 'public');
        }

        // Create new achievement
        $prestasi = new Prestasi;
        $prestasi->id_siswa = $request->id_siswa;
        $prestasi->id_petugas = $id_petugas;
        $prestasi->nama_prestasi = $request->nama_prestasi;
        $prestasi->bidang = $request->bidang;
        $prestasi->tingkat = $request->tingkat;
        $prestasi->peringkat = $request->peringkat;
        $prestasi->deskripsi = $request->deskripsi;
        $prestasi->pengurangan_poin = $request->pengurangan_poin;
        $prestasi->bukti_foto = $buktiFotoPath;
        $prestasi->tanggal_prestasi = $request->tanggal_prestasi;
        $prestasi->save();

        $message = "Prestasi {$request->nama_prestasi} ({$request->bidang} - {$request->tingkat}) untuk {$siswa->name} berhasil dicatat!";
        if ($request->pengurangan_poin > 0) {
            $message .= " Poin pelanggaran berkurang {$request->pengurangan_poin} poin.";
        }

        return redirect()->route('prestasi.index')->with('success', $message);
    }

    /**
     * Show form to edit achievement
     */
    public function edit($id)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        $namaPetugas = session('nama_petugas', 'Petugas');
        $jabatan = $this->getFormattedJabatan();

        $prestasi = Prestasi::findOrFail($id);

        $selectedSiswa = DB::table('siswas')
            ->where('id', $prestasi->id_siswa)
            ->first();

        return view('prestasi-form', compact(
            'namaPetugas',
            'jabatan',
            'prestasi',
            'selectedSiswa'
        ));
    }

    /**
     * Update the specified achievement
     */
    public function update(Request $request, $id)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // Kepala Sekolah cannot update achievements
        if (session('jabatan') === 'Kepala Sekolah') {
            return redirect()->back()->with('error', 'Kepala Sekolah tidak dapat mengubah prestasi.');
        }

        // Validate request
        $request->validate([
            'nama_prestasi' => 'required|string|max:255',
            'bidang' => 'required|in:Akademik,Non Akademik',
            'tingkat' => 'required|in:Sekolah,Kecamatan,Kabupaten,Provinsi,Nasional',
            'peringkat' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string|max:1000',
            'pengurangan_poin' => 'required|integer|min:0|max:100',
            'tanggal_prestasi' => 'required|date',
            'bukti_foto' => 'nullable|file|mimes:jpeg,jpg,png,gif,pdf|max:2048',
        ], [
            'bukti_foto.max' => 'Ukuran file maksimal 2MB.',
            'bukti_foto.mimes' => 'Format file harus JPEG, JPG, PNG, GIF, atau PDF.',
        ]);

        $prestasi = Prestasi::findOrFail($id);

        // Handle file upload - delete old file if new one is uploaded
        $buktiFotoPath = $prestasi->bukti_foto;
        if ($request->hasFile('bukti_foto')) {
            // Delete old file if exists
            if ($prestasi->bukti_foto && Storage::disk('public')->exists($prestasi->bukti_foto)) {
                Storage::disk('public')->delete($prestasi->bukti_foto);
            }
            // Upload new file
            $file = $request->file('bukti_foto');
            $filename = 'prestasi_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $buktiFotoPath = $file->storeAs('bukti_prestasi', $filename, 'public');
        }

        // Update achievement
        $prestasi->nama_prestasi = $request->nama_prestasi;
        $prestasi->bidang = $request->bidang;
        $prestasi->tingkat = $request->tingkat;
        $prestasi->peringkat = $request->peringkat;
        $prestasi->deskripsi = $request->deskripsi;
        $prestasi->pengurangan_poin = $request->pengurangan_poin;
        $prestasi->bukti_foto = $buktiFotoPath;
        $prestasi->tanggal_prestasi = $request->tanggal_prestasi;
        $prestasi->save();

        return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil diperbarui!');
    }

    /**
     * Remove the specified achievement
     */
    public function destroy($id)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        $prestasi = Prestasi::findOrFail($id);

        // Delete associated file if exists
        if ($prestasi->bukti_foto && Storage::disk('public')->exists($prestasi->bukti_foto)) {
            Storage::disk('public')->delete($prestasi->bukti_foto);
        }

        $siswaName = $prestasi->siswa->name ?? 'Siswa';
        $prestasi->delete();

        return redirect()->route('prestasi.index')->with('success', "Prestasi untuk {$siswaName} berhasil dihapus!");
    }

    /**
     * API: Search siswa for autocomplete
     */
    public function searchSiswa(Request $request)
    {
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
            ->when($kelasWali, function ($query) use ($kelasWali) {
                return $query->where('kelas', $kelasWali);
            })
            ->limit(10)
            ->get();

        return response()->json($siswa);
    }

    /**
     * API: Get achievements for a specific student
     */
    public function getBySiswa($siswaId)
    {
        $prestasi = Prestasi::getPrestasiForSiswa($siswaId);
        $totalPengurangan = Prestasi::getTotalPenguranganPoin($siswaId);

        return response()->json([
            'prestasi' => $prestasi,
            'total_pengurangan_poin' => $totalPengurangan,
        ]);
    }
}

