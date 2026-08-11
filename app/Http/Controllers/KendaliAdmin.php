<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Petugas;
use App\Models\Sekolah;
use App\Models\Siswa;

class KendaliAdmin extends Controller
{
    /**
     * Check if user is admin or Kesiswaan
     */
    private function checkAccess()
    {
        $role = session('role');
        $jabatan = session('jabatan');
        
        // Admin can do everything
        if (session('role') === 'admin') {
            return null;
        }
        
        // Kesiswaan can only activate/block OSIS accounts
        if ($jabatan === 'Kesiswaan') {
            return null;
        }
        
        return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya admin dan Kesiswaan yang dapat mengakses fitur ini.');
    }

    /**
     * Check if user is admin
     */
    private function checkAdmin()
    {
        if (!session()->has('id_petugas') || session('role') !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses fitur ini.');
        }
        return null;
    }

    /**
     * Display list of all officers
     */
    public function index()
    {
        $check = $this->checkAccess();
        if ($check) return $check;

        $petugas = Petugas::orderBy('created_at', 'desc')->get();

        return view('kelola-petugas', compact('petugas'));
    }

    /**
     * Activate officer account
     */
    public function activate(Request $request, $id)
    {
        $check = $this->checkAccess();
        if ($check) return $check;

        $petugas = Petugas::findOrFail($id);

        // Check if current user is Kesiswaan - can only activate OSIS
        $jabatan = session('jabatan');
        if ($jabatan === 'Kesiswaan' && $petugas->jabatan !== 'OSIS') {
            return redirect()->route('admin.petugas.index')->with('error', 'Kesiswaan hanya dapat mengaktifkan akun OSIS.');
        }

        // Cannot deactivate yourself
        if ($petugas->id == session('id_petugas')) {
            return redirect()->route('admin.petugas.index')->with('error', 'Anda tidak dapat mengaktifkan akun sendiri.');
        }

        // Admin can activate anyone, Kesiswaan can only activate OSIS
        if (session('role') === 'admin' || ($jabatan === 'Kesiswaan' && $petugas->jabatan === 'OSIS')) {
            $petugas->activate();
            return redirect()->route('admin.petugas.index')->with('success', 'Akun ' . $petugas->name . ' telah diaktifkan.');
        }

        return redirect()->route('admin.petugas.index')->with('error', 'Anda tidak memiliki izin untuk mengaktifkan akun ini.');
    }

    /**
     * Block officer account
     */
    public function block(Request $request, $id)
    {
        $check = $this->checkAccess();
        if ($check) return $check;

        $petugas = Petugas::findOrFail($id);

        // Check if current user is Kesiswaan - can only block OSIS
        $jabatan = session('jabatan');
        if ($jabatan === 'Kesiswaan' && $petugas->jabatan !== 'OSIS') {
            return redirect()->route('admin.petugas.index')->with('error', 'Kesiswaan hanya dapat memblokir akun OSIS.');
        }

        // Cannot block yourself
        if ($petugas->id == session('id_petugas')) {
            return redirect()->route('admin.petugas.index')->with('error', 'Anda tidak dapat memblokir akun sendiri.');
        }

        // Cannot block another admin
        if ($petugas->isAdmin()) {
            return redirect()->route('admin.petugas.index')->with('error', 'Anda tidak dapat memblokir akun admin lain.');
        }

        // Kesiswaan can only block OSIS accounts
        if ($jabatan === 'Kesiswaan' && $petugas->jabatan !== 'OSIS') {
            return redirect()->route('admin.petugas.index')->with('error', 'Kesiswaan hanya dapat memblokir akun OSIS.');
        }

        $petugas->block();

        return redirect()->route('admin.petugas.index')->with('success', 'Akun ' . $petugas->name . ' telah diblokir.');
    }

    /**
     * Reset officer password to default
     */
    public function resetPassword(Request $request, $id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $petugas = Petugas::findOrFail($id);

        // Cannot reset your own password from here
        if ($petugas->id == session('id_petugas')) {
            return redirect()->route('admin.petugas.index')->with('error', 'Anda tidak dapat mereset password sendiri.');
        }

        // Reset password to default: 12345678
        $petugas->update([
            'password' => Hash::make('12345678'),
        ]);

        return redirect()->route('admin.petugas.index')->with('success', 'Password ' . $petugas->name . ' telah direset menjadi 12345678.');
    }

    /**
     * Delete officer account
     */
    public function delete(Request $request, $id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $petugas = Petugas::findOrFail($id);

        // Cannot delete yourself
        if ($petugas->id == session('id_petugas')) {
            return redirect()->route('admin.petugas.index')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // Cannot delete another admin
        if ($petugas->isAdmin()) {
            return redirect()->route('admin.petugas.index')->with('error', 'Anda tidak dapat menghapus akun admin lain.');
        }

        $name = $petugas->name;
        $petugas->delete();

        return redirect()->route('admin.petugas.index')->with('success', 'Akun ' . $name . ' telah dihapus.');
    }

    /**
     * Display school settings form
     */
    public function sekolah()
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $sekolah = Sekolah::getOrCreate();

        return view('kelola-sekolah', compact('sekolah'));
    }

    /**
     * Update school information
     */
    public function updateSekolah(Request $request)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'alamat_sekolah' => 'nullable|string',
            'logo_sekolah' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nama_kepala_sekolah' => 'nullable|string|max:255',
            'nip_kepala_sekolah' => 'nullable|string|max:30',
        ]);

        $sekolah = Sekolah::getOrCreate();

        // Update basic info
        $sekolah->nama_sekolah = $request->nama_sekolah;
        $sekolah->alamat_sekolah = $request->alamat_sekolah;
        $sekolah->nama_kepala_sekolah = $request->nama_kepala_sekolah;
        $sekolah->nip_kepala_sekolah = $request->nip_kepala_sekolah;

        // Handle logo upload
        if ($request->hasFile('logo_sekolah')) {
            $sekolah->uploadLogo($request->file('logo_sekolah'));
        }

        $sekolah->save();

        return redirect()->route('admin.sekolah.index')->with('success', 'Informasi sekolah berhasil diperbarui.');
    }

    /**
     * Delete school logo
     */
    public function deleteLogo()
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $sekolah = Sekolah::getOrCreate();
        
        if ($sekolah->deleteLogo()) {
            return response()->json(['success' => true, 'message' => 'Logo berhasil dihapus']);
        }
        
        return response()->json(['success' => false, 'message' => 'Gagal menghapus logo'], 400);
    }

    /**
     * Get existing Kepala Sekolah account or return null
     */
    private function getAkunKepalaSekolah(): ?Petugas
    {
        return Petugas::where('jabatan', Petugas::JABATAN_KEPALA_SEKOLAH)->first();
    }

    /**
     * Helper to check if current user is view-only (Kepala Sekolah)
     */
    private function checkViewOnly()
    {
        $jabatan = session('jabatan', '');
        if ($jabatan === Petugas::JABATAN_KEPALA_SEKOLAH) {
            return true;
        }
        return false;
    }

    /**
     * Display Kepala Sekolah account management
     */
    public function sekolahAccount()
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $akun = $this->getAkunKepalaSekolah();
        $sekolah = Sekolah::getOrCreate();

        return view('kelola-sekolah', compact('sekolah', 'akun'));
    }

    /**
     * Create Kepala Sekolah account
     */
    public function createAkunKepalaSekolah(Request $request)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        // Check if already exists
        if ($this->getAkunKepalaSekolah()) {
            return redirect()->route('admin.sekolah.index')->with('error', 'Akun Kepala Sekolah sudah ada.');
        }

        $sekolah = Sekolah::getOrCreate();
        
        // Generate email from school name
        $namaSekolah = $sekolah->nama_sekolah ?: 'sekolah';
        $slug = Str::slug($namaSekolah);
        $email = 'kepsek.' . $slug . '@sips.sch.id';

        // Use nama_kepala_sekolah from sekolah data, or a default name
        $namaKepsek = $sekolah->nama_kepala_sekolah ?: 'Kepala Sekolah ' . $sekolah->nama_sekolah;

        $petugas = Petugas::create([
            'name' => $namaKepsek,
            'email' => $email,
            'password' => Hash::make('12345678'),
            'jabatan' => Petugas::JABATAN_KEPALA_SEKOLAH,
            'kelas' => null,
            'role' => 'petugas',
            'status' => 'active',
        ]);

        return redirect()->route('admin.sekolah.index')->with('success', 'Akun Kepala Sekolah berhasil dibuat. Email: ' . $petugas->email . ', Password: 12345678');
    }

    /**
     * Reset Kepala Sekolah account password to default
     */
    public function resetPasswordKepalaSekolah(Request $request)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $akun = $this->getAkunKepalaSekolah();
        if (!$akun) {
            return redirect()->route('admin.sekolah.index')->with('error', 'Akun Kepala Sekolah belum dibuat.');
        }

        $akun->update([
            'password' => Hash::make('12345678'),
        ]);

        // Also update the name if nama_kepala_sekolah has changed
        $sekolah = Sekolah::getOrCreate();
        if ($sekolah->nama_kepala_sekolah && $akun->name !== $sekolah->nama_kepala_sekolah) {
            $akun->update(['name' => $sekolah->nama_kepala_sekolah]);
        }

        return redirect()->route('admin.sekolah.index')->with('success', 'Password akun Kepala Sekolah telah direset menjadi 12345678.');
    }

    /**
     * Delete Kepala Sekolah account
     */
    public function deleteAkunKepalaSekolah(Request $request)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $akun = $this->getAkunKepalaSekolah();
        if (!$akun) {
            return redirect()->route('admin.sekolah.index')->with('error', 'Akun Kepala Sekolah belum dibuat.');
        }

        $akun->delete();

        return redirect()->route('admin.sekolah.index')->with('success', 'Akun Kepala Sekolah telah dihapus.');
    }

    /**
     * Display import siswa form
     */
    public function importSiswaForm()
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        return view('import-siswa');
    }

    /**
     * Download CSV template for import
     */
    public function downloadTemplate()
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="template_import_siswa.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['NIS', 'Nama', 'Kelas'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, $columns);
            
            // Example row
            fputcsv($file, ['1234567890', 'Contoh Siswa', 'X-1']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Process CSV import for students
     */
    public function importSiswaAction(Request $request)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'file.required' => 'Pilih file CSV untuk diimpor.',
            'file.mimes' => 'File harus berformat CSV.',
            'file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        // Open and read CSV
        $handle = fopen($path, 'r');
        if (!$handle) {
            return redirect()->route('admin.siswa.import.form')->with('error', 'Gagal membaca file.');
        }

        // Detect BOM and skip
        $bom = fread($handle, 3);
        if ($bom !== chr(0xEF).chr(0xBB).chr(0xBF)) {
            // Not BOM, reset to beginning
            rewind($handle);
        }

        $baris = 0;
        $sukses = 0;
        $gagal = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            $baris++;
            
            // Skip header row
            if ($baris === 1) {
                continue;
            }

            // Clean up row data
            $row = array_map('trim', $row);

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Validate columns: NIS, Nama, Kelas
            if (count($row) < 3) {
                $gagal++;
                $errors[] = "Baris $baris: Data tidak lengkap (minimal NIS, Nama, Kelas).";
                continue;
            }

            $nis = $row[0];
            $name = $row[1];
            $kelas = $row[2];

            // Validate NIS
            if (empty($nis)) {
                $gagal++;
                $errors[] = "Baris $baris: NIS tidak boleh kosong.";
                continue;
            }

            // Validate Nama
            if (empty($name)) {
                $gagal++;
                $errors[] = "Baris $baris: Nama tidak boleh kosong.";
                continue;
            }

            // Validate Kelas
            if (empty($kelas)) {
                $gagal++;
                $errors[] = "Baris $baris: Kelas tidak boleh kosong.";
                continue;
            }

            // Check duplicate NIS
            $existing = Siswa::where('nis', $nis)->first();
            if ($existing) {
                // Update existing
                $existing->update([
                    'name' => $name,
                    'kelas' => $kelas,
                ]);
                $sukses++;
                continue;
            }

            // Create new student
            try {
                Siswa::create([
                    'nis' => $nis,
                    'name' => $name,
                    'kelas' => $kelas,
                ]);
                $sukses++;
            } catch (\Exception $e) {
                $gagal++;
                $errors[] = "Baris $baris (NIS: $nis): " . $e->getMessage();
            }
        }

        fclose($handle);

        // Build result message
        $message = "Import selesai. $sukses data berhasil diimpor.";
        if ($gagal > 0) {
            $message .= " $gagal data gagal.";
        }

        if ($gagal > 0 && count($errors) > 0) {
            $message .= ' Detail: ' . implode(' | ', array_slice($errors, 0, 10));
            if (count($errors) > 10) {
                $message .= ' (dan ' . (count($errors) - 10) . ' error lainnya)';
            }
        }

        if ($gagal === 0) {
            return redirect()->route('admin.siswa.import.form')->with('success', $message);
        } elseif ($sukses > 0) {
            return redirect()->route('admin.siswa.import.form')->with('success', $message)->with('warning_errors', $errors);
        } else {
            return redirect()->route('admin.siswa.import.form')->with('error', $message);
        }
    }
}

