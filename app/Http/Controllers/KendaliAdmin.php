<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Petugas;
use App\Models\Sekolah;

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
        ]);

        $sekolah = Sekolah::getOrCreate();

        // Update basic info
        $sekolah->nama_sekolah = $request->nama_sekolah;
        $sekolah->alamat_sekolah = $request->alamat_sekolah;

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
}

