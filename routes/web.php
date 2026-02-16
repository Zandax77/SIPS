<?php

use App\Http\Controllers\KendaliUtama;
use App\Http\Controllers\KendaliPetugas;
use App\Http\Controllers\KendaliSiswa;
use App\Http\Controllers\KendaliPelanggaran;
use App\Http\Controllers\KendaliJenisPelanggaran;
use App\Http\Controllers\KendaliAdmin;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\KendaliLaporan;

// Route::get('/', function () {
//     return view('welcome');
// });

// Login Routes
Route::get('', [KendaliUtama::class, 'welcome'])->name('welcome');
Route::get('/login', [KendaliUtama::class, 'login'])->name('login');
Route::post('/login', [KendaliUtama::class, 'loginAction'])->name('login.action');
Route::post('/logout', [KendaliUtama::class, 'logout'])->name('logout');

// Chatbot Routes (Public - Landing Page)
Route::post('/api/chatbot', [ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::get('/api/chatbot/greet', [ChatbotController::class, 'greet'])->name('chatbot.greet');
Route::post('/api/chatbot/quick-reply', [ChatbotController::class, 'quickReply'])->name('chatbot.quickReply');

// Forgot Password Routes
Route::get('/forgot-password', [KendaliUtama::class, 'forgotPassword'])->name('password.request');
Route::post('/forgot-password', [KendaliUtama::class, 'requestReset'])->name('password.email');

// Change Password Routes (for petugas after reset)
Route::get('/change-password', [KendaliUtama::class, 'changePasswordForm'])->name('password.change')->middleware('auth:petugas');
Route::post('/change-password', [KendaliUtama::class, 'changePassword'])->name('password.update')->middleware('auth:petugas');

// Registration Routes
Route::get('/register', [KendaliPetugas::class, 'showRegisterForm'])->name('petugas.register.show');
Route::post('/register', [KendaliPetugas::class, 'register'])->name('petugas.register');

// Protected Routes
Route::middleware(['auth:petugas'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [KendaliUtama::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/counts', [KendaliUtama::class, 'getViolationCountsApi'])->name('api.dashboard.counts');

    // Admin: Kelola Petugas (Admin only)
    Route::get('/admin/petugas', [KendaliAdmin::class, 'index'])->name('admin.petugas.index');
    Route::post('/admin/petugas/{id}/activate', [KendaliAdmin::class, 'activate'])->name('admin.petugas.activate');
    Route::post('/admin/petugas/{id}/block', [KendaliAdmin::class, 'block'])->name('admin.petugas.block');
    Route::post('/admin/petugas/{id}/reset-password', [KendaliAdmin::class, 'resetPassword'])->name('admin.petugas.reset-password');
    Route::delete('/admin/petugas/{id}', [KendaliAdmin::class, 'delete'])->name('admin.petugas.delete');

    // Admin: Pengaturan (Admin only)
    Route::get('/admin/settings', [KendaliAdmin::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings', [KendaliAdmin::class, 'updateSettings'])->name('admin.settings.update');

    // Menu: Data Siswa & Poin
    Route::get('/siswa/poin', [KendaliSiswa::class, 'index'])->name('siswa.poin');
    Route::get('/siswa/detail/{id}', [KendaliSiswa::class, 'detail'])->name('siswa.detail');
    Route::get('/api/siswa/search', [KendaliSiswa::class, 'searchApi'])->name('api.siswa.search');
    Route::get('/api/siswa/{id}', [KendaliSiswa::class, 'getById'])->name('api.siswa.get');

    // Lampiran (Attachment) Routes
    Route::get('/siswa/pelanggaran/lampiran/{id}', [KendaliSiswa::class, 'viewLampiran'])->name('siswa.pelanggaran.lampiran');
    Route::get('/siswa/pelanggaran/download/{id}', [KendaliSiswa::class, 'downloadLampiran'])->name('siswa.pelanggaran.download');

    // Menu: Catat Pelanggaran
    Route::get('/pelanggaran/catat', [KendaliPelanggaran::class, 'index'])->name('pelanggaran.catat');
    Route::post('/pelanggaran/catat', [KendaliPelanggaran::class, 'catatPelanggaran'])->name('pelanggaran.catat.action');
    Route::get('/api/pelanggaran/search-siswa', [KendaliPelanggaran::class, 'searchSiswa'])->name('api.pelanggaran.search-siswa');
    Route::get('/api/pelanggaran/siswa/{id}', [KendaliPelanggaran::class, 'getSiswaById'])->name('api.pelanggaran.siswa');

    // Menu: Pengampunan & Pengurangan Poin Pelanggaran
    Route::get('/api/pelanggaran/{id}/detail', [KendaliPelanggaran::class, 'getDetailPelanggaran'])->name('api.pelanggaran.detail');
    Route::post('/pelanggaran/pengampunan', [KendaliPelanggaran::class, 'simpanPengampunan'])->name('pelanggaran.pengampunan');
    Route::post('/pelanggaran/pengurangan-poin', [KendaliPelanggaran::class, 'simpanPenguranganPoin'])->name('pelanggaran.pengurangan-poin');
    Route::get('/api/pelanggaran/riwayat-pengampunan/{id_siswa}', [KendaliPelanggaran::class, 'getRiwayatPengampunan'])->name('api.pelanggaran.riwayat-pengampunan');

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

    // Menu: Cetak Laporan (Admin, Kesiswaan, Guru BK, Wali Kelas)
    Route::get('/laporan/per-siswa', [KendaliLaporan::class, 'laporanPerSiswa'])->name('laporan.per-siswa');
    Route::get('/laporan/rekap-kelas', [KendaliLaporan::class, 'rekapPerKelas'])->name('laporan.rekap-kelas');
    Route::get('/laporan/rekap-periode', [KendaliLaporan::class, 'rekapPerPeriode'])->name('laporan.rekap-periode');
    Route::get('/laporan/siswa-tertinggi', [KendaliLaporan::class, 'siswaPoinTertinggi'])->name('laporan.siswa-tertinggi');

    // Export PDF Routes
    Route::get('/laporan/per-siswa/pdf', [KendaliLaporan::class, 'exportPdfPerSiswa'])->name('laporan.per-siswa.pdf');
    Route::get('/laporan/rekap-kelas/pdf', [KendaliLaporan::class, 'exportPdfPerKelas'])->name('laporan.rekap-kelas.pdf');
    Route::get('/laporan/rekap-periode/pdf', [KendaliLaporan::class, 'exportPdfPerPeriode'])->name('laporan.rekap-periode.pdf');
    Route::get('/laporan/siswa-tertinggi/pdf', [KendaliLaporan::class, 'exportPdfSiswaTertinggi'])->name('laporan.siswa-tertinggi.pdf');

    // Export Excel Routes
    Route::get('/laporan/per-siswa/excel', [KendaliLaporan::class, 'exportExcelPerSiswa'])->name('laporan.per-siswa.excel');
    Route::get('/laporan/rekap-kelas/excel', [KendaliLaporan::class, 'exportExcelPerKelas'])->name('laporan.rekap-kelas.excel');
    Route::get('/laporan/rekap-periode/excel', [KendaliLaporan::class, 'exportExcelPerPeriode'])->name('laporan.rekap-periode.excel');
    Route::get('/laporan/siswa-tertinggi/excel', [KendaliLaporan::class, 'exportExcelSiswaTertinggi'])->name('laporan.siswa-tertinggi.excel');

    // Laporan Detail Per Siswa (Baru)
    Route::get('/laporan/per-siswa/detail/{id}', [KendaliLaporan::class, 'detailPerSiswa'])->name('laporan.per-siswa.detail');
    Route::get('/laporan/per-siswa/detail/{id}/pdf', [KendaliLaporan::class, 'exportPdfDetailPerSiswa'])->name('laporan.per-siswa.detail.pdf');
});
