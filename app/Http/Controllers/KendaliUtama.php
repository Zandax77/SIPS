<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Petugas;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\Setting;

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
        // Jika sudah login, redirect ke dashboard atau halaman sesuai role
        if (session()->has('id_petugas')) {
            // OSIS users should go to Catat Pelanggaran
            if (strtolower(session('role', 'petugas')) === 'osis') {
                return redirect()->route('pelanggaran.catat');
            }
            return redirect()->route('dashboard');
        }
        return view('login');
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

            // Redirect based on role
            // OSIS users go directly to Catat Pelanggaran (they don't have access to Dashboard)
            if (strtolower($petugas->role) === 'osis') {
                return redirect()->route('pelanggaran.catat')->with('success', 'Selamat datang, ' . $petugas->name . '! Anda sedang di halaman Catat Pelanggaran.');
            }

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

        return redirect()->route('welcome')->with('success', 'Anda telah berhasil logout.');
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
     * Display dashboard
     */
    public function index()
    {
        // Cek session petugas
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        // Check if user is OSIS - OSIS has limited access
        $role = strtolower(session('role', 'petugas'));
        $jabatan = session('jabatan', '-');

        // OSIS users should only have access to Catat Pelanggaran
        // If OSIS tries to access Dashboard directly, redirect to Catat Pelanggaran
        if ($role === 'osis') {
            return redirect()->route('pelanggaran.catat')->with('info', 'Anda sedang berada di halaman Catat Pelanggaran.');
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

        // 6. Get password reset requests from petugas table (simpler approach)
        $passwordResetRequests = [];
        if ($role === 'admin') {
            // Get all petugas who have requested password reset (have a token but haven't been reset recently)
            $passwordResetRequests = Petugas::whereNotNull('password_reset_token')
                ->where('id', '!=', session('id_petugas')) // Exclude self
                ->orderBy('password_reset_expires', 'desc')
                ->limit(10)
                ->get();
        }

        // 7. Get school name from settings
        $namaSekolah = Setting::get('nama_sekolah', 'SIPS');

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
            'passwordResetRequests',
            'namaSekolah'
        ));
    }

    /**
     * Display forgot password page
     */
    public function forgotPassword()
    {
        // If already logged in, redirect to dashboard
        if (session()->has('id_petugas')) {
            return redirect()->route('dashboard');
        }
        return view('forgot-password');
    }

    /**
     * Handle forgot password request
     */
    public function requestReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        // Find petugas by email
        $petugas = Petugas::where('email', $request->email)->first();

        if (!$petugas) {
            // Don't reveal if email exists or not for security
            return back()->with('info', 'Jika email tersebut terdaftar, permintaan reset password akan dikirim ke Super Admin.');
        }

        // Check if account is blocked
        if ($petugas->status === 'blocked') {
            return back()->withErrors(['email' => 'Akun Anda telah diblokir. Silakan hubungi administrator.'])->withInput();
        }

        // Generate reset token
        $petugas->generatePasswordResetToken();

        // Get all super admins (role = admin)
        $admins = Petugas::where('role', 'admin')->get();

        // Send notification to all admins
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\ResetPasswordRequestNotification($petugas));
        }

        return back()->with('info', 'Permintaan reset password telah dikirim ke Super Admin. Silakan tunggu atau hubungi admin untuk mereset password Anda.');
    }

    /**
     * Display change password page (for petugas who just got their password reset)
     */
    public function changePasswordForm()
    {
        // Must be logged in
        if (!session()->has('id_petugas')) {
            return redirect()->route('login');
        }

        return view('change-password');
    }

    /**
     * Display landing page (public - before login)
     */
    public function welcome()
    {
        // 1. Get violation data for chart (last 7 days by category)
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

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $dates[] = date('d/m', strtotime($date));

            // Count ringan violations
            $chartData['ringan'][] = !empty($jenisRinganIds)
                ? DB::table('pelanggarans')
                    ->whereDate('created_at', $date)
                    ->whereIn('id_jenis_pelanggaran', $jenisRinganIds)
                    ->count()
                : 0;

            // Count sedang violations
            $chartData['sedang'][] = !empty($jenisSedangIds)
                ? DB::table('pelanggarans')
                    ->whereDate('created_at', $date)
                    ->whereIn('id_jenis_pelanggaran', $jenisSedangIds)
                    ->count()
                : 0;

            // Count berat violations
            $chartData['berat'][] = !empty($jenisBeratIds)
                ? DB::table('pelanggarans')
                    ->whereDate('created_at', $date)
                    ->whereIn('id_jenis_pelanggaran', $jenisBeratIds)
                    ->count()
                : 0;
        }

        // 2. Get class data with unique student violators count (last 7 days)
        $weekAgo = date('Y-m-d', strtotime('-7 days'));

        // Get unique student violators per class in the last 7 days
        $kelasData = DB::table('pelanggarans')
            ->select('siswas.kelas', DB::raw('COUNT(DISTINCT pelanggarans.id_siswa) as jumlah_siswa_pelaku'))
            ->join('siswas', 'pelanggarans.id_siswa', '=', 'siswas.id')
            ->where('pelanggarans.created_at', '>=', $weekAgo)
            ->groupBy('siswas.kelas')
            ->orderBy('siswas.kelas')
            ->get();

        // Also get total violations per class
        $kelasPelanggaran = DB::table('pelanggarans')
            ->select('siswas.kelas', DB::raw('COUNT(pelanggarans.id) as jumlah_pelanggaran'))
            ->join('siswas', 'pelanggarans.id_siswa', '=', 'siswas.id')
            ->where('pelanggarans.created_at', '>=', $weekAgo)
            ->groupBy('siswas.kelas')
            ->orderBy('siswas.kelas')
            ->get()
            ->keyBy('kelas');

        // Merge data
        $kelasStats = $kelasData->map(function ($item) use ($kelasPelanggaran) {
            $item->jumlah_pelanggaran = $kelasPelanggaran->get($item->kelas)->jumlah_pelanggaran ?? 0;
            return $item;
        });

        // 3. Get school name from settings
        $namaSekolah = Setting::get('nama_sekolah', 'SIPS');

        return view('welcome', compact('chartData', 'dates', 'kelasStats', 'namaSekolah'));
    }

    /**
     * Handle change password request
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $petugas = Petugas::find(session('id_petugas'));

        if (!$petugas) {
            return back()->with('error', 'Data petugas tidak ditemukan.');
        }

        // Verify current password
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $petugas->password)) {
            return back()->with('error', 'Password saat ini salah.');
        }

        // Update password
        $petugas->resetPassword($request->new_password);

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }
}

