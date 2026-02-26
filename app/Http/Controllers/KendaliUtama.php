<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Petugas;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\Sekolah;

class KendaliUtama extends Controller
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
     * Display login page
     */
    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->has('id_petugas')) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    /**
     * Display landing page
     */
    public function landing()
    {
        $sekolah = \App\Models\Sekolah::getOrCreate();
        return view('landing', compact('sekolah'));
    }

    /**
     * Handle login action
     */
    public function loginAction(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $remember = $request->has('remember');

        // Attempt login dengan credentials dan remember me
        if (Auth::guard('petugas')->attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();

            // Get user data dan store in session
            $petugas = Petugas::where('email', $credentials['email'])->first();

            // Check if account is blocked
            if ($petugas->status === 'blocked') {
                Auth::guard('petugas')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Akun Anda telah diblokir. Silakan hubungi administrator.',
                ])->withInput($request->only('email', 'remember'));
            }

            // Check if account is inactive (waiting for activation)
            if ($petugas->status === 'inactive') {
                Auth::guard('petugas')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Akun Anda belum diaktifkan. Silakan hubungi administrator untuk aktivasi.',
                ])->withInput($request->only('email', 'remember'));
            }

            session([
                'id_petugas' => $petugas->id,
                'nama_petugas' => $petugas->name,
                'jabatan' => $petugas->jabatan,
                'kelas' => $petugas->kelas,
                'role' => $petugas->role,
            ]);

            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang, ' . $petugas->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->withInput($request->only('email', 'remember'));
    }

    /**
     * Handle logout action
     */
    public function logout(Request $request)
    {
        Auth::guard('petugas')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Display change password form
     */
    public function showChangePasswordForm()
    {
        // Cek session petugas
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        return view('ubah-password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        // Cek session petugas
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6',
            'new_password_confirmation' => 'required|string|min:6|same:new_password',
        ], [
            'new_password_confirmation.same' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
        ]);

        // Get current logged in petugas
        $petugas = Auth::guard('petugas')->user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $petugas->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak benar.'])->withInput();
        }

        // Check if new password is same as current password
        if (Hash::check($request->new_password, $petugas->password)) {
            return back()->withErrors(['new_password' => 'Password baru tidak boleh sama dengan password saat ini.'])->withInput();
        }

        // Update password
        $petugas->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('password.change.form')->with('success', 'Password berhasil diubah. Silakan login ulang dengan password baru.');
    }

    /**
     * Helper function to calculate violation counts
     */
    private function getViolationCounts()
    {
        $kelasWali = $this->getWaliKelas();
        $today = date('Y-m-d');

        // Get ALL category IDs for each severity level
        $kategoriRinganIds = DB::table('kategori_pelanggarans')
            ->where('nama', 'Ringan')
            ->pluck('id')
            ->toArray();

        $kategoriSedangIds = DB::table('kategori_pelanggarans')
            ->where('nama', 'Sedang')
            ->pluck('id')
            ->toArray();

        $kategoriBeratIds = DB::table('kategori_pelanggarans')
            ->where('nama', 'Berat')
            ->pluck('id')
            ->toArray();

        // Get all jenis_pelanggaran IDs for each category
        $jenisRinganIds = DB::table('jenis_pelanggarans')
            ->whereIn('id_kategori_pelanggaran', $kategoriRinganIds)
            ->pluck('id')
            ->toArray();

        $jenisSedangIds = DB::table('jenis_pelanggarans')
            ->whereIn('id_kategori_pelanggaran', $kategoriSedangIds)
            ->pluck('id')
            ->toArray();

        $jenisBeratIds = DB::table('jenis_pelanggarans')
            ->whereIn('id_kategori_pelanggaran', $kategoriBeratIds)
            ->pluck('id')
            ->toArray();

        $countRingan = 0;
        $countSedang = 0;
        $countBerat = 0;

        // Helper function to build siswa subquery for Wali Kelas filtering
        $siswaSubquery = function ($query) use ($kelasWali) {
            $query->select('id')->from('siswas');
            if ($kelasWali) {
                $query->where('kelas', $kelasWali);
            }
        };

        // Count ringan violations
        if (!empty($jenisRinganIds)) {
            $countRingan = DB::table('pelanggarans')
                ->whereDate('created_at', $today)
                ->whereIn('id_jenis_pelanggaran', $jenisRinganIds)
                ->when($kelasWali, function ($query) use ($siswaSubquery) {
                    return $query->whereIn('id_siswa', $siswaSubquery);
                })
                ->count();
        }

        // Count sedang violations
        if (!empty($jenisSedangIds)) {
            $countSedang = DB::table('pelanggarans')
                ->whereDate('created_at', $today)
                ->whereIn('id_jenis_pelanggaran', $jenisSedangIds)
                ->when($kelasWali, function ($query) use ($siswaSubquery) {
                    return $query->whereIn('id_siswa', $siswaSubquery);
                })
                ->count();
        }

        // Count berat violations
        if (!empty($jenisBeratIds)) {
            $countBerat = DB::table('pelanggarans')
                ->whereDate('created_at', $today)
                ->whereIn('id_jenis_pelanggaran', $jenisBeratIds)
                ->when($kelasWali, function ($query) use ($siswaSubquery) {
                    return $query->whereIn('id_siswa', $siswaSubquery);
                })
                ->count();
        }

        return [
            'ringan' => $countRingan,
            'sedang' => $countSedang,
            'berat' => $countBerat,
        ];
    }

    /**
     * API endpoint for violation counts (for auto-refresh)
     */
    public function getViolationCountsApi()
    {
        if (!session()->has('id_petugas')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $counts = $this->getViolationCounts();
            return response()->json([
                'success' => true,
                'data' => $counts,
                'timestamp' => now()->toIso8601String()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get landing page statistics (public endpoint)
     */
    public function getLandingStats()
    {
        try {
            // Get total counts
            $totalSiswa = DB::table('siswas')->count();
            $totalPelanggaran = DB::table('pelanggarans')->count();
            $totalPetugas = DB::table('petugas')->count();

            // Get today's violation counts by category
            $today = date('Y-m-d');

            // Get ALL category IDs for each severity level
            $kategoriRinganIds = DB::table('kategori_pelanggarans')
                ->where('nama', 'Ringan')
                ->pluck('id')
                ->toArray();

            $kategoriSedangIds = DB::table('kategori_pelanggarans')
                ->where('nama', 'Sedang')
                ->pluck('id')
                ->toArray();

            $kategoriBeratIds = DB::table('kategori_pelanggarans')
                ->where('nama', 'Berat')
                ->pluck('id')
                ->toArray();

            // Get all jenis_pelanggaran IDs for each category
            $jenisRinganIds = DB::table('jenis_pelanggarans')
                ->whereIn('id_kategori_pelanggaran', $kategoriRinganIds)
                ->pluck('id')
                ->toArray();

            $jenisSedangIds = DB::table('jenis_pelanggarans')
                ->whereIn('id_kategori_pelanggaran', $kategoriSedangIds)
                ->pluck('id')
                ->toArray();

            $jenisBeratIds = DB::table('jenis_pelanggarans')
                ->whereIn('id_kategori_pelanggaran', $kategoriBeratIds)
                ->pluck('id')
                ->toArray();

            // Today's counts
            $todayRingan = !empty($jenisRinganIds)
                ? DB::table('pelanggarans')->whereDate('created_at', $today)->whereIn('id_jenis_pelanggaran', $jenisRinganIds)->count()
                : 0;

            $todaySedang = !empty($jenisSedangIds)
                ? DB::table('pelanggarans')->whereDate('created_at', $today)->whereIn('id_jenis_pelanggaran', $jenisSedangIds)->count()
                : 0;

            $todayBerat = !empty($jenisBeratIds)
                ? DB::table('pelanggarans')->whereDate('created_at', $today)->whereIn('id_jenis_pelanggaran', $jenisBeratIds)->count()
                : 0;

            // Get 7-day chart data
            $chartData = [];
            $dates = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-{$i} days"));
                $dates[] = date('d/m', strtotime($date));

                $chartData['ringan'][] = !empty($jenisRinganIds)
                    ? DB::table('pelanggarans')->whereDate('created_at', $date)->whereIn('id_jenis_pelanggaran', $jenisRinganIds)->count()
                    : 0;

                $chartData['sedang'][] = !empty($jenisSedangIds)
                    ? DB::table('pelanggarans')->whereDate('created_at', $date)->whereIn('id_jenis_pelanggaran', $jenisSedangIds)->count()
                    : 0;

                $chartData['berat'][] = !empty($jenisBeratIds)
                    ? DB::table('pelanggarans')->whereDate('created_at', $date)->whereIn('id_jenis_pelanggaran', $jenisBeratIds)->count()
                    : 0;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_siswa' => $totalSiswa,
                    'total_pelanggaran' => $totalPelanggaran,
                    'total_petugas' => $totalPetugas,
                    'today' => [
                        'ringan' => $todayRingan,
                        'sedang' => $todaySedang,
                        'berat' => $todayBerat,
                        'total' => $todayRingan + $todaySedang + $todayBerat
                    ],
                    'chart' => [
                        'dates' => $dates,
                        'ringan' => $chartData['ringan'],
                        'sedang' => $chartData['sedang'],
                        'berat' => $chartData['berat']
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display dashboard
     */
    public function index()
    {
        // Cek session petugas
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // 1. Cek koneksi database
        $dbConnected = false;
        try {
            DB::connection()->getPdo();
            $dbConnected = true;
        } catch (\Exception $e) {
            $dbConnected = false;
        }

        // 2. Get data user aktif dari session
        $namaPetugas = session('nama_petugas', 'Petugas');
        $jabatanRaw = session('jabatan', '-');
        $role = session('role', 'petugas');
        $jabatan = $this->getFormattedJabatan();
        $kelasWali = $this->getWaliKelas();

        // 3. Get violation counts
        $counts = $this->getViolationCounts();
        $countRingan = $counts['ringan'];
        $countSedang = $counts['sedang'];
        $countBerat = $counts['berat'];

        // 4. Data grafik pelanggaran 7 hari terakhir
        $chartData = [];
        $dates = [];
        $today = date('Y-m-d');

        // Get ALL category IDs for each severity level
        $kategoriRinganIds = DB::table('kategori_pelanggarans')
            ->where('nama', 'Ringan')
            ->pluck('id')
            ->toArray();

        $kategoriSedangIds = DB::table('kategori_pelanggarans')
            ->where('nama', 'Sedang')
            ->pluck('id')
            ->toArray();

        $kategoriBeratIds = DB::table('kategori_pelanggarans')
            ->where('nama', 'Berat')
            ->pluck('id')
            ->toArray();

        // Get all jenis_pelanggaran IDs for each category
        $jenisRinganIds = DB::table('jenis_pelanggarans')
            ->whereIn('id_kategori_pelanggaran', $kategoriRinganIds)
            ->pluck('id')
            ->toArray();

        $jenisSedangIds = DB::table('jenis_pelanggarans')
            ->whereIn('id_kategori_pelanggaran', $kategoriSedangIds)
            ->pluck('id')
            ->toArray();

        $jenisBeratIds = DB::table('jenis_pelanggarans')
            ->whereIn('id_kategori_pelanggaran', $kategoriBeratIds)
            ->pluck('id')
            ->toArray();

        // Helper function to build siswa subquery for Wali Kelas filtering
        $siswaSubquery = function ($query) use ($kelasWali) {
            $query->select('id')->from('siswas');
            if ($kelasWali) {
                $query->where('kelas', $kelasWali);
            }
        };

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $dates[] = date('d/m', strtotime($date));

            // Count ringan violations
            $chartData['ringan'][] = !empty($jenisRinganIds)
                ? DB::table('pelanggarans')
                    ->whereDate('created_at', $date)
                    ->whereIn('id_jenis_pelanggaran', $jenisRinganIds)
                    ->when($kelasWali, function ($query) use ($siswaSubquery) {
                        return $query->whereIn('id_siswa', $siswaSubquery);
                    })
                    ->count()
                : 0;

            // Count sedang violations
            $chartData['sedang'][] = !empty($jenisSedangIds)
                ? DB::table('pelanggarans')
                    ->whereDate('created_at', $date)
                    ->whereIn('id_jenis_pelanggaran', $jenisSedangIds)
                    ->when($kelasWali, function ($query) use ($siswaSubquery) {
                        return $query->whereIn('id_siswa', $siswaSubquery);
                    })
                    ->count()
                : 0;

            // Count berat violations
            $chartData['berat'][] = !empty($jenisBeratIds)
                ? DB::table('pelanggarans')
                    ->whereDate('created_at', $date)
                    ->whereIn('id_jenis_pelanggaran', $jenisBeratIds)
                    ->when($kelasWali, function ($query) use ($siswaSubquery) {
                        return $query->whereIn('id_siswa', $siswaSubquery);
                    })
                    ->count()
                : 0;
        }

        // 5. Get siswa dengan pelanggaran berat hari ini
        $siswaPelanggaranBerat = [];
        if (!empty($jenisBeratIds)) {
            $siswaPelanggaranBeratQuery = DB::table('pelanggarans')
                ->select('siswas.nis', 'siswas.name as nama_siswa', 'siswas.kelas', 'jenis_pelanggarans.nama as pelanggaran', 'pelanggarans.created_at', 'pelanggarans.deskripsi')
                ->join('siswas', 'pelanggarans.id_siswa', '=', 'siswas.id')
                ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
                ->whereDate('pelanggarans.created_at', $today)
                ->whereIn('jenis_pelanggarans.id_kategori_pelanggaran', $kategoriBeratIds)
                ->when($kelasWali, function ($query) use ($kelasWali) {
                    return $query->where('siswas.kelas', $kelasWali);
                })
                ->orderBy('pelanggarans.created_at', 'desc');

            $siswaPelanggaranBerat = $siswaPelanggaranBeratQuery->get();
        }

        // 6. Get school information
        $sekolah = Sekolah::getOrCreate();

        return view('dashboard', compact(
            'dbConnected',
            'namaPetugas',
            'jabatan',
            'jabatanRaw',
            'role',
            'countRingan',
            'countSedang',
            'countBerat',
            'chartData',
            'dates',
            'siswaPelanggaranBerat',
            'sekolah'
        ));
    }
}

