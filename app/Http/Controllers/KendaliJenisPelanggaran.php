<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\JenisPelanggaran;
use App\Models\KategoriPelanggaran;

class KendaliJenisPelanggaran extends Controller
{
    /**
     * Check if user has Kesiswaan role
     */
    private function isKesiswaan(): bool
    {
        return session('jabatan') === 'Kesiswaan';
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
     * Display daftar jenis pelanggaran dengan poin
     */
    public function index(Request $request)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // Only Kesiswaan and Guru BK can access this page
        $jabatan = session('jabatan', '');
        if (!in_array($jabatan, ['Kesiswaan', 'Guru BK'])) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $namaPetugas = session('nama_petugas', 'Petugas');
        $jabatan = $this->getFormattedJabatan();
        $isKesiswaan = $this->isKesiswaan();

        // Search functionality
        $search = $request->input('search', '');
        $filterKategori = $request->input('kategori', '');

        // Get all jenis pelanggaran dengan kategori dan poin
        $query = DB::table('jenis_pelanggarans')
            ->select(
                'jenis_pelanggarans.id',
                'jenis_pelanggarans.nama',
                'jenis_pelanggarans.deskripsi',
                'kategori_pelanggarans.nama as kategori',
                'kategori_pelanggarans.poin',
                'jenis_pelanggarans.id_kategori_pelanggaran',
                DB::raw('(SELECT COUNT(*) FROM pelanggarans WHERE id_jenis_pelanggaran = jenis_pelanggarans.id) as jumlah_pelanggaran')
            )
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->when($search, function ($query) use ($search) {
                return $query->where('jenis_pelanggarans.nama', 'like', "%{$search}%");
            })
            ->when($filterKategori, function ($query) use ($filterKategori) {
                return $query->where('kategori_pelanggarans.nama', $filterKategori);
            })
            ->orderBy('kategori_pelanggarans.nama')
            ->orderBy('jenis_pelanggarans.nama');

        $jenisPelanggaran = $query->paginate(10);

        // Get kategori list for filter and dropdowns
        $kategoriList = DB::table('kategori_pelanggarans')
            ->select('id', 'nama', 'poin')
            ->orderBy('nama')
            ->get();

        // Get statistics
        $stats = [
            'total_jenis' => DB::table('jenis_pelanggarans')->count(),
            'ringan' => DB::table('jenis_pelanggarans')
                ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
                ->where('kategori_pelanggarans.nama', 'Ringan')
                ->count(),
            'sedang' => DB::table('jenis_pelanggarans')
                ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
                ->where('kategori_pelanggarans.nama', 'Sedang')
                ->count(),
            'berat' => DB::table('jenis_pelanggarans')
                ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
                ->where('kategori_pelanggarans.nama', 'Berat')
                ->count(),
        ];

        return view('jenis-pelanggaran', compact(
            'namaPetugas',
            'jabatan',
            'jenisPelanggaran',
            'kategoriList',
            'search',
            'filterKategori',
            'stats',
            'isKesiswaan'
        ));
    }

    /**
     * Store new jenis pelanggaran
     */
    public function storeJenis(Request $request)
    {
        // Only Kesiswaan can add
        if (!$this->isKesiswaan()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menambah data.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'id_kategori_pelanggaran' => 'required|exists:kategori_pelanggarans,id',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        DB::table('jenis_pelanggarans')->insert([
            'nama' => $request->nama,
            'id_kategori_pelanggaran' => $request->id_kategori_pelanggaran,
            'deskripsi' => $request->deskripsi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('jenis.pelanggaran')
            ->with('success', 'Jenis pelanggaran "' . $request->nama . '" berhasil ditambahkan.');
    }

    /**
     * Update jenis pelanggaran
     */
    public function updateJenis(Request $request, $id)
    {
        // Only Kesiswaan can update
        if (!$this->isKesiswaan()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah data.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'id_kategori_pelanggaran' => 'required|exists:kategori_pelanggarans,id',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        DB::table('jenis_pelanggarans')
            ->where('id', $id)
            ->update([
                'nama' => $request->nama,
                'id_kategori_pelanggaran' => $request->id_kategori_pelanggaran,
                'deskripsi' => $request->deskripsi,
                'updated_at' => now(),
            ]);

        return redirect()->route('jenis.pelanggaran')
            ->with('success', 'Jenis pelanggaran berhasil diperbarui.');
    }

    /**
     * Delete jenis pelanggaran
     */
    public function deleteJenis($id)
    {
        // Only Kesiswaan can delete
        if (!$this->isKesiswaan()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus data.');
        }

        // Check if jenis pelanggaran is used
        $usageCount = DB::table('pelanggarans')->where('id_jenis_pelanggaran', $id)->count();

        if ($usageCount > 0) {
            return redirect()->route('jenis.pelanggaran')
                ->with('error', 'Jenis pelanggaran tidak dapat dihapus karena sudah digunakan dalam ' . $usageCount . ' catatan pelanggaran.');
        }

        $jenis = DB::table('jenis_pelanggarans')->where('id', $id)->first();

        DB::table('jenis_pelanggarans')->where('id', $id)->delete();

        return redirect()->route('jenis.pelanggaran')
            ->with('success', 'Jenis pelanggaran "' . $jenis->nama . '" berhasil dihapus.');
    }

    /**
     * Store new kategori pelanggaran
     */
    public function storeKategori(Request $request)
    {
        // Only Kesiswaan can add
        if (!$this->isKesiswaan()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menambah data.');
        }

        $request->validate([
            'nama' => 'required|string|max:50|unique:kategori_pelanggarans',
            'poin' => 'required|integer|min:1',
        ]);

        DB::table('kategori_pelanggarans')->insert([
            'nama' => $request->nama,
            'poin' => $request->poin,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('jenis.pelanggaran')
            ->with('success', 'Kategori pelanggaran "' . $request->nama . '" (' . $request->poin . ' poin) berhasil ditambahkan.');
    }

    /**
     * Update kategori pelanggaran
     */
    public function updateKategori(Request $request, $id)
    {
        // Only Kesiswaan can update
        if (!$this->isKesiswaan()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah data.');
        }

        $request->validate([
            'nama' => 'required|string|max:50|unique:kategori_pelanggarans,nama,' . $id,
            'poin' => 'required|integer|min:1',
        ]);

        DB::table('kategori_pelanggarans')
            ->where('id', $id)
            ->update([
                'nama' => $request->nama,
                'poin' => $request->poin,
                'updated_at' => now(),
            ]);

        return redirect()->route('jenis.pelanggaran')
            ->with('success', 'Kategori pelanggaran berhasil diperbarui.');
    }

    /**
     * Delete kategori pelanggaran
     */
    public function deleteKategori($id)
    {
        // Only Kesiswaan can delete
        if (!$this->isKesiswaan()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus data.');
        }

        // Check if kategori is used by any jenis pelanggaran
        $usageCount = DB::table('jenis_pelanggarans')->where('id_kategori_pelanggaran', $id)->count();

        if ($usageCount > 0) {
            return redirect()->route('jenis.pelanggaran')
                ->with('error', 'Kategori tidak dapat dihapus karena sudah digunakan oleh ' . $usageCount . ' jenis pelanggaran.');
        }

        $kategori = DB::table('kategori_pelanggarans')->where('id', $id)->first();

        DB::table('kategori_pelanggarans')->where('id', $id)->delete();

        return redirect()->route('jenis.pelanggaran')
            ->with('success', 'Kategori pelanggaran "' . $kategori->nama . '" berhasil dihapus.');
    }
}

