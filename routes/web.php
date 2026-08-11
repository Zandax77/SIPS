<?php

use App\Http\Controllers\KendaliUtama;
use App\Http\Controllers\KendaliPetugas;
use App\Http\Controllers\KendaliSiswa;
use App\Http\Controllers\KendaliPelanggaran;
use App\Http\Controllers\KendaliJenisPelanggaran;
use App\Http\Controllers\KendaliAdmin;
use App\Http\Controllers\KendaliPrestasi;
use Illuminate\Support\Facades\Route;

// Splash Screen Route (First page when app launches)
Route::get('/', function () {
    return view('splash');
})->name('splash');

// Landing Page Route
Route::get('/landing', [KendaliUtama::class, 'landing'])->name('landing');

// Landing page API (public)
Route::get('/api/landing/stats', [KendaliUtama::class, 'getLandingStats'])->name('api.landing.stats');

// Login Routes
Route::get('/login', [KendaliUtama::class, 'login'])->name('login');
Route::post('/login', [KendaliUtama::class, 'loginAction'])->name('login.action');
Route::post('/logout', [KendaliUtama::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [KendaliPetugas::class, 'showRegisterForm'])->name('petugas.register.show');
Route::post('/register', [KendaliPetugas::class, 'register'])->name('petugas.register');

// Protected Routes
Route::middleware(['auth:petugas'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [KendaliUtama::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/counts', [KendaliUtama::class, 'getViolationCountsApi'])->name('api.dashboard.counts');

    // Change Password
    Route::get('/password/change', [KendaliUtama::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/password/update', [KendaliUtama::class, 'updatePassword'])->name('password.update');

    // Admin: Kelola Petugas (Admin only)
    Route::get('/admin/petugas', [KendaliAdmin::class, 'index'])->name('admin.petugas.index');
    Route::post('/admin/petugas/{id}/activate', [KendaliAdmin::class, 'activate'])->name('admin.petugas.activate');
    Route::post('/admin/petugas/{id}/block', [KendaliAdmin::class, 'block'])->name('admin.petugas.block');
    Route::post('/admin/petugas/{id}/reset-password', [KendaliAdmin::class, 'resetPassword'])->name('admin.petugas.reset-password');
    Route::delete('/admin/petugas/{id}', [KendaliAdmin::class, 'delete'])->name('admin.petugas.delete');

    // Admin: Kelola Sekolah (Admin only)
    Route::get('/admin/sekolah', [KendaliAdmin::class, 'sekolahAccount'])->name('admin.sekolah.index');
    Route::post('/admin/sekolah', [KendaliAdmin::class, 'updateSekolah'])->name('admin.sekolah.update');
    Route::post('/admin/sekolah/delete-logo', [KendaliAdmin::class, 'deleteLogo'])->name('admin.sekolah.delete-logo');

    // Admin: Kelola Akun Kepala Sekolah
    Route::post('/admin/sekolah/kepsek/create', [KendaliAdmin::class, 'createAkunKepalaSekolah'])->name('admin.sekolah.kepsek.create');
    Route::post('/admin/sekolah/kepsek/reset-password', [KendaliAdmin::class, 'resetPasswordKepalaSekolah'])->name('admin.sekolah.kepsek.reset-password');
    Route::post('/admin/sekolah/kepsek/delete', [KendaliAdmin::class, 'deleteAkunKepalaSekolah'])->name('admin.sekolah.kepsek.delete');

    // Admin: Import Data Siswa
    Route::get('/admin/siswa/import', [KendaliAdmin::class, 'importSiswaForm'])->name('admin.siswa.import.form');
    Route::post('/admin/siswa/import', [KendaliAdmin::class, 'importSiswaAction'])->name('admin.siswa.import.action');
    Route::get('/admin/siswa/import/template', [KendaliAdmin::class, 'downloadTemplate'])->name('admin.siswa.import.template');

    // Menu: Data Siswa & Poin
    Route::get('/siswa/poin', [KendaliSiswa::class, 'index'])->name('siswa.poin');
    Route::get('/siswa/detail/{id}', [KendaliSiswa::class, 'detail'])->name('siswa.detail');
    Route::get('/api/siswa/search', [KendaliSiswa::class, 'searchApi'])->name('api.siswa.search');
    Route::get('/api/siswa/{id}', [KendaliSiswa::class, 'getById'])->name('api.siswa.get');

    // Menu: Catat Pelanggaran
    Route::get('/pelanggaran/catat', [KendaliPelanggaran::class, 'index'])->name('pelanggaran.catat');
    Route::post('/pelanggaran/catat', [KendaliPelanggaran::class, 'catatPelanggaran'])->name('pelanggaran.catat.action');
    Route::get('/api/pelanggaran/search-siswa', [KendaliPelanggaran::class, 'searchSiswa'])->name('api.pelanggaran.search-siswa');
    Route::get('/api/pelanggaran/siswa/{id}', [KendaliPelanggaran::class, 'getSiswaById'])->name('api.pelanggaran.siswa');

    // Laporan Pelanggaran Per Kelas (BK)
    Route::get('/pelanggaran/laporan/kelas', [KendaliPelanggaran::class, 'laporanKelas'])->name('pelanggaran.laporan.kelas');
    Route::get('/pelanggaran/laporan/kelas/cetak', [KendaliPelanggaran::class, 'cetakLaporanKelas'])->name('pelanggaran.laporan.kelas.cetak');

    // Data Siswa Melanggar (Berdasarkan Range Tanggal)
    Route::get('/pelanggaran/laporan/siswa', [KendaliPelanggaran::class, 'laporanSiswa'])->name('pelanggaran.laporan.siswa');

    // Menu: Jenis Pelanggaran
    Route::get('/jenis-pelanggaran', [KendaliJenisPelanggaran::class, 'index'])->name('jenis.pelanggaran');

    // CRUD: Jenis Pelanggaran (Kesiswaan and Admin only)
    Route::post('/jenis-pelanggaran/store', [KendaliJenisPelanggaran::class, 'storeJenis'])->name('jenis.pelanggaran.store');
    Route::put('/jenis-pelanggaran/{id}', [KendaliJenisPelanggaran::class, 'updateJenis'])->name('jenis.pelanggaran.update');
    Route::delete('/jenis-pelanggaran/{id}', [KendaliJenisPelanggaran::class, 'deleteJenis'])->name('jenis.pelanggaran.delete');

    // CRUD: Kategori Pelanggaran (Kesiswaan and Admin only)
    Route::post('/kategori-pelanggaran/store', [KendaliJenisPelanggaran::class, 'storeKategori'])->name('kategori.pelanggaran.store');
    Route::put('/kategori-pelanggaran/{id}', [KendaliJenisPelanggaran::class, 'updateKategori'])->name('kategori.pelanggaran.update');
    Route::delete('/kategori-pelanggaran/{id}', [KendaliJenisPelanggaran::class, 'deleteKategori'])->name('kategori.pelanggaran.delete');

    // Action Tracking (Tindakan Siswa)
    Route::post('/siswa/{id}/tindakan', [KendaliSiswa::class, 'storeTindakan'])->name('siswa.tindakan.store');
    Route::put('/siswa/{id}/tindakan/{tindakanId}', [KendaliSiswa::class, 'updateTindakan'])->name('siswa.tindakan.update');
    Route::delete('/siswa/{id}/tindakan/{tindakanId}', [KendaliSiswa::class, 'deleteTindakan'])->name('siswa.tindakan.delete');

    // Print & Export: Riwayat Pelanggaran
    Route::get('/siswa/{id}/pelanggaran/cetak', [KendaliSiswa::class, 'cetakPelanggaran'])->name('siswa.pelanggaran.cetak');

    // Print & Export: Riwayat Tindakan
    Route::get('/siswa/{id}/tindakan/cetak', [KendaliSiswa::class, 'cetakTindakan'])->name('siswa.tindakan.cetak');

    // Menu: Prestasi Siswa (Kesiswaan & Admin)
    Route::get('/prestasi', [KendaliPrestasi::class, 'index'])->name('prestasi.index');
    Route::get('/prestasi/tambah', [KendaliPrestasi::class, 'create'])->name('prestasi.create');
    Route::post('/prestasi/store', [KendaliPrestasi::class, 'store'])->name('prestasi.store');
    Route::get('/prestasi/{id}/edit', [KendaliPrestasi::class, 'edit'])->name('prestasi.edit');
    Route::put('/prestasi/{id}', [KendaliPrestasi::class, 'update'])->name('prestasi.update');
    Route::delete('/prestasi/{id}', [KendaliPrestasi::class, 'destroy'])->name('prestasi.destroy');

    // Prestasi: API
    Route::get('/api/prestasi/search-siswa', [KendaliPrestasi::class, 'searchSiswa'])->name('api.prestasi.search-siswa');
    Route::get('/api/prestasi/siswa/{id}', [KendaliPrestasi::class, 'getBySiswa'])->name('api.prestasi.siswa');
});
