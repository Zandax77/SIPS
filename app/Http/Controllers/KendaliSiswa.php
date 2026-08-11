<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Petugas;
use App\Models\JenisPelanggaran;
use App\Models\KategoriPelanggaran;
use App\Models\TindakanSiswa;
use App\Models\Sekolah;
use App\Services\AISuggestionService;
use Illuminate\Support\Facades\Storage;

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
     * Display data siswa dengan total poin pelanggaran (setelah dikurangi prestasi)
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

        // Search functionality
        $search = $request->input('search', '');
        $sortBy = $request->input('sort', 'poin_desc'); // Default sort by poin desc

        // Get all siswa with their violation points (minus prestasi reduction)
        $siswaQuery = DB::table('siswas')
            ->select(
                'siswas.id',
                'siswas.nis',
                'siswas.name as nama_siswa',
                'siswas.kelas',
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin_kotor'),
                DB::raw('COALESCE((SELECT SUM(pengurangan_poin) FROM prestasis WHERE prestasis.id_siswa = siswas.id), 0) as total_pengurangan_poin'),
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

        // Sorting - use effective poin (after reduction) for sorting
        switch ($sortBy) {
            case 'nama_asc':
                $siswaQuery->orderBy('nama_siswa', 'asc');
                break;
            case 'nama_desc':
                $siswaQuery->orderBy('nama_siswa', 'desc');
                break;
            case 'poin_asc':
                $siswaQuery->orderByRaw('(COALESCE(SUM(kategori_pelanggarans.poin), 0) - COALESCE((SELECT SUM(pengurangan_poin) FROM prestasis WHERE prestasis.id_siswa = siswas.id), 0)) ASC');
                break;
            case 'poin_desc':
                $siswaQuery->orderByRaw('(COALESCE(SUM(kategori_pelanggarans.poin), 0) - COALESCE((SELECT SUM(pengurangan_poin) FROM prestasis WHERE prestasis.id_siswa = siswas.id), 0)) DESC');
                break;
            case 'kelas':
                $siswaQuery->orderBy('kelas', 'asc')->orderBy('nama_siswa', 'asc');
                break;
            default:
                $siswaQuery->orderByRaw('(COALESCE(SUM(kategori_pelanggarans.poin), 0) - COALESCE((SELECT SUM(pengurangan_poin) FROM prestasis WHERE prestasis.id_siswa = siswas.id), 0)) DESC');
        }

        $siswa = $siswaQuery->paginate(10);

        // Calculate effective poin for each student
        $siswa->getCollection()->transform(function ($item) {
            $item->total_poin = max(0, $item->total_poin_kotor - $item->total_pengurangan_poin);
            return $item;
        });

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

        // Get pelanggaran detail with bukti_foto
        $pelanggaranDetail = DB::table('pelanggarans')
            ->select(
                'pelanggarans.id',
                'jenis_pelanggarans.nama as jenis_pelanggaran',
                'kategori_pelanggarans.nama as kategori',
                'kategori_pelanggarans.poin',
                'pelanggarans.deskripsi',
                'pelanggarans.bukti_foto',
                'petugas.name as nama_petugas',
                'pelanggarans.created_at'
            )
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->join('petugas', 'pelanggarans.id_petugas', '=', 'petugas.id')
            ->where('pelanggarans.id_siswa', $id)
            ->orderBy('pelanggarans.created_at', 'desc')
            ->get();

        // Calculate total poin
        $totalPoin = $pelanggaranDetail->sum('poin');

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

        // Get prestasi reduction
        $totalPenguranganPoin = \App\Models\Prestasi::getTotalPenguranganPoin($id);
        $poinEfektif = max(0, $totalPoin - $totalPenguranganPoin);

        // Get prestasi data
        $prestasiSiswa = \App\Models\Prestasi::getPrestasiForSiswa($id);

        // Get AI suggestions
        $aiSuggestionService = new AISuggestionService();
        $aiSuggestions = $aiSuggestionService->getSuggestions($id);
        $hasEvidencePhotos = $aiSuggestionService->hasEvidencePhotos($id);
        $violationsWithPhotos = $aiSuggestionService->getViolationsWithPhotos($id);

        // Get action history
        $tindakanSiswa = TindakanSiswa::where('id_siswa', $id)
            ->orderBy('tanggal_tindakan', 'desc')
            ->get();

        return view('siswa-poin-detail', compact(
            'namaPetugas',
            'jabatan',
            'siswa',
            'pelanggaranDetail',
            'totalPoin',
            'poinEfektif',
            'totalPenguranganPoin',
            'prestasiSiswa',
            'statsByKategori',
            'aiSuggestions',
            'hasEvidencePhotos',
            'violationsWithPhotos',
            'tindakanSiswa'
        ));
    }

    /**
     * API: Search siswa untuk autocomplete
     */
    public function searchApi(Request $request)
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

        // Get total poin (after prestasi reduction)
        $totalPoinKotor = DB::table('pelanggarans')
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('pelanggarans.id_siswa', $id)
            ->sum('kategori_pelanggarans.poin');

        $totalPenguranganPoin = \App\Models\Prestasi::getTotalPenguranganPoin($id);
        $siswa->total_poin = max(0, $totalPoinKotor - $totalPenguranganPoin);
        $siswa->total_pengurangan_poin = $totalPenguranganPoin;

        return response()->json($siswa);
    }

    /**
     * Store new action for a student
     */
    public function storeTindakan(Request $request, $id)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // Kepala Sekolah cannot record actions
        if (session('jabatan') === 'Kepala Sekolah') {
            return redirect()->back()->with('error', 'Kepala Sekolah tidak dapat mencatat tindakan.');
        }

        // Validate request
        $request->validate([
            'jenis_tindakan' => 'required|string|max:255',
            'deskripsi_tindakan' => 'nullable|string|max:1000',
            'hasil_tindakan' => 'required|in:Berhasil,Tidak Berhasil,Perlu Evaluasi,Sedang Berlangsung',
            'catatan_hasil' => 'nullable|string|max:1000',
            'tanggal_tindakan' => 'required|date',
            'bukti_foto' => 'nullable|file|mimes:jpeg,jpg,png,gif,pdf,doc,docx|max:2048',
        ], [
            'jenis_tindakan.required' => 'Jenis tindakan wajib dipilih.',
            'hasil_tindakan.required' => 'Hasil tindakan wajib dipilih.',
            'tanggal_tindakan.required' => 'Tanggal tindakan wajib diisi.',
            'bukti_foto.max' => 'Ukuran file maksimal 2MB.',
            'bukti_foto.mimes' => 'Format file harus JPEG, JPG, PNG, GIF, PDF, DOC, atau DOCX.',
        ]);

        // Check if siswa exists and is accessible (for Wali Kelas)
        $siswa = DB::table('siswas')->where('id', $id)->first();
        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan');
        }

        $kelasWali = $this->getWaliKelas();
        if ($kelasWali && $siswa->kelas !== $kelasWali) {
            return redirect()->back()->with('error', 'Anda hanya dapat mencatat tindakan untuk siswa di kelas ' . $kelasWali);
        }

        // Get petugas ID from session
        $id_petugas = session('id_petugas');

        // Handle file upload
        $buktiFotoPath = null;
        if ($request->hasFile('bukti_foto')) {
            $file = $request->file('bukti_foto');
            $filename = 'tindakan_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $buktiFotoPath = $file->storeAs('bukti_tindakan', $filename, 'public');
        }

        // Create new action
        $tindakan = new TindakanSiswa;
        $tindakan->id_siswa = $id;
        $tindakan->id_petugas = $id_petugas;
        $tindakan->jenis_tindakan = $request->input('jenis_tindakan');
        $tindakan->deskripsi_tindakan = $request->input('deskripsi_tindakan', '');
        $tindakan->hasil_tindakan = $request->input('hasil_tindakan');
        $tindakan->catatan_hasil = $request->input('catatan_hasil', '');
        $tindakan->bukti_foto = $buktiFotoPath;
        $tindakan->tanggal_tindakan = $request->input('tanggal_tindakan');
        $tindakan->save();

        return redirect()->route('siswa.detail', $id)->with('success', 'Tindakan berhasil dicatat!');
    }

    /**
     * Update existing action for a student
     */
    public function updateTindakan(Request $request, $id, $tindakanId)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // Kepala Sekolah cannot update actions
        if (session('jabatan') === 'Kepala Sekolah') {
            return redirect()->back()->with('error', 'Kepala Sekolah tidak dapat mengubah tindakan.');
        }

        // Validate request
        $request->validate([
            'jenis_tindakan' => 'required|string|max:255',
            'deskripsi_tindakan' => 'nullable|string|max:1000',
            'hasil_tindakan' => 'required|in:Berhasil,Tidak Berhasil,Perlu Evaluasi,Sedang Berlangsung',
            'catatan_hasil' => 'nullable|string|max:1000',
            'tanggal_tindakan' => 'required|date',
            'bukti_foto' => 'nullable|file|mimes:jpeg,jpg,png,gif,pdf,doc,docx|max:2048',
        ], [
            'bukti_foto.max' => 'Ukuran file maksimal 2MB.',
            'bukti_foto.mimes' => 'Format file harus JPEG, JPG, PNG, GIF, PDF, DOC, atau DOCX.',
        ]);

        // Get tindakan
        $tindakan = TindakanSiswa::where('id', $tindakanId)
            ->where('id_siswa', $id)
            ->first();

        if (!$tindakan) {
            return redirect()->back()->with('error', 'Tindakan tidak ditemukan');
        }

        // Handle file upload - delete old file if new one is uploaded
        $buktiFotoPath = $tindakan->bukti_foto;
        if ($request->hasFile('bukti_foto')) {
            // Delete old file if exists
            if ($tindakan->bukti_foto && Storage::disk('public')->exists($tindakan->bukti_foto)) {
                Storage::disk('public')->delete($tindakan->bukti_foto);
            }
            // Upload new file
            $file = $request->file('bukti_foto');
            $filename = 'tindakan_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $buktiFotoPath = $file->storeAs('bukti_tindakan', $filename, 'public');
        }

        // Update tindakan
        $tindakan->jenis_tindakan = $request->input('jenis_tindakan');
        $tindakan->deskripsi_tindakan = $request->input('deskripsi_tindakan', '');
        $tindakan->hasil_tindakan = $request->input('hasil_tindakan');
        $tindakan->catatan_hasil = $request->input('catatan_hasil', '');
        $tindakan->bukti_foto = $buktiFotoPath;
        $tindakan->tanggal_tindakan = $request->input('tanggal_tindakan');
        $tindakan->save();

        return redirect()->route('siswa.detail', $id)->with('success', 'Tindakan berhasil diperbarui!');
    }

    /**
     * Delete action for a student
     */
    public function deleteTindakan(Request $request, $id, $tindakanId)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // Kepala Sekolah cannot delete actions
        if (session('jabatan') === 'Kepala Sekolah') {
            return redirect()->back()->with('error', 'Kepala Sekolah tidak dapat menghapus tindakan.');
        }

        // Get tindakan
        $tindakan = TindakanSiswa::where('id', $tindakanId)
            ->where('id_siswa', $id)
            ->first();

        if (!$tindakan) {
            return redirect()->back()->with('error', 'Tindakan tidak ditemukan');
        }

        // Delete associated file if exists
        if ($tindakan->bukti_foto && Storage::disk('public')->exists($tindakan->bukti_foto)) {
            Storage::disk('public')->delete($tindakan->bukti_foto);
        }

        // Delete tindakan
        $tindakan->delete();

        return redirect()->route('siswa.detail', $id)->with('success', 'Tindakan berhasil dihapus!');
    }

    /**
     * Cetak Riwayat Pelanggaran
     */
    public function cetakPelanggaran($id)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        $kelasWali = $this->getWaliKelas();

        // Get siswa info
        $siswa = DB::table('siswas')->where('id', $id)->first();
        if (!$siswa) {
            return redirect()->route('siswa.poin')->with('error', 'Siswa tidak ditemukan');
        }

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
                'petugas.name as nama_petugas',
                'pelanggarans.created_at'
            )
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->join('petugas', 'pelanggarans.id_petugas', '=', 'petugas.id')
            ->where('pelanggarans.id_siswa', $id)
            ->orderBy('pelanggarans.created_at', 'desc')
            ->get();

        $totalPoin = $pelanggaranDetail->sum('poin');

        // Get school information
        $sekolah = Sekolah::getOrCreate();

        return view('pdf.pelanggaran', compact('siswa', 'pelanggaranDetail', 'totalPoin', 'sekolah'));
    }

    /**
     * Cetak Riwayat Tindakan
     */
    public function cetakTindakan($id)
    {
        // Cek session
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        $kelasWali = $this->getWaliKelas();

        // Get siswa info
        $siswa = DB::table('siswas')->where('id', $id)->first();
        if (!$siswa) {
            return redirect()->route('siswa.poin')->with('error', 'Siswa tidak ditemukan');
        }

        if ($kelasWali && $siswa->kelas !== $kelasWali) {
            return redirect()->route('siswa.poin')->with('error', 'Anda hanya dapat melihat data siswa dari kelas ' . $kelasWali);
        }

        // Get tindakan
        $tindakanSiswa = TindakanSiswa::where('id_siswa', $id)
            ->orderBy('tanggal_tindakan', 'desc')
            ->get();

        // Get school information
        $sekolah = Sekolah::getOrCreate();

        // Get petugas info for tanda tangan
        $namaPetugasCetak = session('nama_petugas', '-');
        $jabatanPetugasCetak = session('jabatan', '-');

        // Get Guru BK (first active BK teacher)
        $guruBK = Petugas::where('jabatan', 'Guru BK')
            ->where('status', 'active')
            ->first();

        return view('pdf.tindakan', compact(
            'siswa',
            'tindakanSiswa',
            'sekolah',
            'namaPetugasCetak',
            'jabatanPetugasCetak',
            'guruBK'
        ));
    }
}

