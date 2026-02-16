<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Petugas;
use App\Models\Setting;

class KendaliAdmin extends Controller
{
    const DEFAULT_PASSWORD = '123456';

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
        $check = $this->checkAdmin();
        if ($check) return $check;

        $petugas = Petugas::orderBy('created_at', 'desc')->get();

        return view('kelola-petugas', compact('petugas'));
    }

    /**
     * Display settings page
     */
    public function settings()
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $namaSekolah = Setting::get('nama_sekolah', 'SMA Negeri');
        $alamatSekolah = Setting::get('alamat_sekolah', '');
        $teleponSekolah = Setting::get('telepon_sekolah', '');

        return view('pengaturan', compact('namaSekolah', 'alamatSekolah', 'teleponSekolah'));
    }

    /**
     * Update school settings
     */
    public function updateSettings(Request $request)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'alamat_sekolah' => 'nullable|string|max:500',
            'telepon_sekolah' => 'nullable|string|max:20',
        ], [
            'nama_sekolah.required' => 'Nama sekolah wajib diisi.',
        ]);

        // Update settings
        Setting::set('nama_sekolah', $request->nama_sekolah);
        Setting::set('alamat_sekolah', $request->alamat_sekolah ?? '');
        Setting::set('telepon_sekolah', $request->telepon_sekolah ?? '');

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Activate officer account
     */
    public function activate(Request $request, $id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $petugas = Petugas::findOrFail($id);

        // Cannot deactivate yourself
        if ($petugas->id == session('id_petugas')) {
            return redirect()->route('admin.petugas.index')->with('error', 'Anda tidak dapat mengaktifkan akun sendiri.');
        }

        $petugas->activate();

        return redirect()->route('admin.petugas.index')->with('success', 'Akun ' . $petugas->name . ' telah diaktifkan.');
    }

    /**
     * Block officer account
     */
    public function block(Request $request, $id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $petugas = Petugas::findOrFail($id);

        // Cannot block yourself
        if ($petugas->id == session('id_petugas')) {
            return redirect()->route('admin.petugas.index')->with('error', 'Anda tidak dapat memblokir akun sendiri.');
        }

        // Cannot block another admin
        if ($petugas->isAdmin()) {
            return redirect()->route('admin.petugas.index')->with('error', 'Anda tidak dapat memblokir akun admin lain.');
        }

        $petugas->block();

        return redirect()->route('admin.petugas.index')->with('success', 'Akun ' . $petugas->name . ' telah diblokir.');
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
     * Reset officer password to default "123456"
     */
    public function resetPassword(Request $request, $id)
    {
        $check = $this->checkAdmin();
        if ($check) return $check;

        $petugas = Petugas::findOrFail($id);

        // Cannot reset your own password through this method
        if ($petugas->id == session('id_petugas')) {
            return redirect()->route('admin.petugas.index')->with('error', 'Anda tidak dapat mereset password sendiri. Silakan gunakan fitur ubah password.');
        }

        // Reset password to default "123456"
        $petugas->resetPassword(self::DEFAULT_PASSWORD);

        return redirect()->route('admin.petugas.index')->with('success', 'Password ' . $petugas->name . ' telah direset ke "' . self::DEFAULT_PASSWORD . '". Harap informasikan kepada petugas tersebut untuk login dan segera mengganti password.');
    }
}

