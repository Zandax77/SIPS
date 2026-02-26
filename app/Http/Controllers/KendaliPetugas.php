<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Petugas;
use App\Models\Siswa;

class KendaliPetugas extends Controller
{
    /**
     * Display registration page
     */
    public function showRegisterForm()
    {
        // Get unique kelas from siswas table for Wali Kelas dropdown
        $kelasList = Siswa::distinct()->pluck('kelas')->sort()->values();

        return view('register', compact('kelasList'));
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:petugas',
            'password' => 'required|string|min:6|confirmed',
            'jabatan' => 'required|in:Kesiswaan,Wali Kelas,Guru BK,OSIS',
            'kelas' => 'nullable|required_if:jabatan,Wali Kelas|string|max:50',
        ], [
            'kelas.required_if' => 'Kolom kelas wajib diisi untuk jabatan Wali Kelas.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Create new petugas - status is inactive, needs admin activation
        $petugas = Petugas::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jabatan' => $request->jabatan,
            'kelas' => $request->jabatan === 'Wali Kelas' ? $request->kelas : null,
            'role' => 'petugas',
            'status' => 'inactive', // Requires admin activation
        ]);

        // Logout after registration - requires admin activation
        Auth::guard('petugas')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Registrasi berhasil! Akun Anda akan diaktifkan oleh administrator. Silakan tunggu atau hubungi admin untuk aktivasi.');
    }
}

