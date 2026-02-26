<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pelanggaran Per Kelas - SIPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        indigo: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        emerald: {
                            500: '#10b981',
                            600: '#059669',
                        },
                        amber: {
                            500: '#f59e0b',
                            600: '#d97706',
                        },
                        rose: {
                            500: '#f43f5e',
                            600: '#e11d48',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.4s ease-out forwards;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen">
    <!-- Decorative Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-100 rounded-full opacity-40 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-100 rounded-full opacity-40 blur-3xl"></div>
    </div>

    <!-- Main Container -->
    <div class="relative min-h-screen flex flex-col lg:flex-row">
        <!-- Header (Mobile only) -->
        <header class="bg-white/80 backdrop-blur-sm border-b border-gray-100 sticky top-0 z-50 lg:hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-3">
                        <button onclick="window.location.href='<?php echo e(route('dashboard')); ?>'" class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-800">SIPS</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all duration-200" title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar Navigation (Desktop) -->
        <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-100 lg:h-screen lg:overflow-y-auto lg:block flex-shrink-0 hidden lg:block">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-800">SIPS</span>
                </div>
            </div>
            <div class="flex flex-col h-full">
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <?php if(($jabatanRaw ?? '') !== 'OSIS'): ?>
                    <a href="<?php echo e(route('siswa.poin')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="font-medium">Data Siswa & Poin</span>
                    </a>
                    <?php endif; ?>

                    <a href="<?php echo e(route('pelanggaran.catat')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-medium">Catat Pelanggaran</span>
                    </a>

                    <!-- Menu: Laporan Pelanggaran Per Kelas (Active) -->
                    <a href="<?php echo e(route('pelanggaran.laporan.kelas')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-medium">Laporan BK</span>
                    </a>

                    <?php if(in_array($jabatanRaw ?? '', ['Kesiswaan', 'Guru BK']) || ($role ?? '') === 'admin'): ?>
                    <a href="<?php echo e(route('jenis.pelanggaran')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span class="font-medium">Jenis Pelanggaran</span>
                    </a>
                    <?php endif; ?>

                    <?php if(($role ?? '') === 'admin' || ($jabatanRaw ?? '') === 'Kesiswaan'): ?>
                    <a href="<?php echo e(route('admin.petugas.index')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-indigo-600-50 hover:text transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="font-medium">Kelola Petugas</span>
                    </a>
                    <?php endif; ?>

                    <?php if(($role ?? '') === 'admin'): ?>
                    <a href="<?php echo e(route('admin.sekolah.index')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="font-medium">Kelola Sekolah</span>
                    </a>
                    <?php endif; ?>

                    <a href="<?php echo e(route('password.change.form')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        <span class="font-medium">Ubah Password</span>
                    </a>
                </nav>
                <div class="px-4 py-4 border-t border-gray-100">
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 mb-2">SIPS v1.0</p>
                        <p class="text-xs text-gray-400">Sistem Informasi<br>Pelanggaran Siswa</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Page Header -->
                <div class="mb-8 animate-fade-in">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Laporan Pelanggaran Per Kelas</h1>
                            <p class="text-gray-500 mt-1">Cetak data pelanggaran siswa berdasarkan kelas dan periode tertentu</p>
                        </div>
                        <?php if($selectedKelas): ?>
                        <div class="bg-white rounded-xl px-4 py-3 shadow-lg shadow-gray-200/50 border border-gray-100">
                            <div class="flex items-center gap-2 text-sm">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-600">Periode:</span>
                                <span class="font-semibold text-gray-800">
                                    <?php echo e(\Carbon\Carbon::parse($tanggalMulai)->format('d M Y')); ?> - <?php echo e(\Carbon\Carbon::parse($tanggalAkhir)->format('d M Y')); ?>

                                </span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 mb-6 animate-fade-in">
                    <form action="<?php echo e(route('pelanggaran.laporan.kelas')); ?>" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Kelas Select -->
                            <div>
                                <label for="kelas" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                                <select name="kelas" id="kelas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($kelas); ?>" <?php echo e($selectedKelas == $kelas ? 'selected' : ''); ?>><?php echo e($kelas); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Tanggal Mulai -->
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="<?php echo e($tanggalMulai); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Tanggal Akhir -->
                            <div>
                                <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="<?php echo e($tanggalAkhir); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3 pt-2">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-colors">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Tampilkan Data
                            </button>

                            <?php if($selectedKelas): ?>
                            <a href="<?php echo e(route('pelanggaran.laporan.kelas.cetak', ['kelas' => $selectedKelas, 'tanggal_mulai' => $tanggalMulai, 'tanggal_akhir' => $tanggalAkhir])); ?>" target="_blank" class="px-6 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-200 transition-colors">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Cetak
                            </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Results Section -->
                <?php if($selectedKelas): ?>
                <div class="space-y-6">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-white rounded-xl p-5 shadow-lg shadow-gray-200/50 border border-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Pelanggaran</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo e($totalPelanggaran); ?></p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl p-5 shadow-lg shadow-gray-200/50 border border-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Poin</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo e($totalPoin); ?></p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl p-5 shadow-lg shadow-gray-200/50 border border-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Siswa Melanggar</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo e(count($rekapPerSiswa)); ?></p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl p-5 shadow-lg shadow-gray-200/50 border border-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Kelas</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo e($selectedKelas); ?></p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rekap Per Siswa -->
                    <?php if(count($rekapPerSiswa) > 0): ?>
                    <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                            <h2 class="text-lg font-semibold text-gray-800">Rekap Per Siswa</h2>
                            <p class="text-sm text-gray-500">Ringkasan pelanggaran siswa di kelas <?php echo e($selectedKelas); ?></p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[600px]">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jml Pelanggaran</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Poin</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php $__currentLoopData = $rekapPerSiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($index + 1); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo e($siswa->nis); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?php echo e($siswa->nama_siswa); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                                    <?php echo e($siswa->jumlah_pelanggaran); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    <?php if($siswa->total_poin >= 50): ?> bg-rose-100 text-rose-700
                                                    <?php elseif($siswa->total_poin >= 25): ?> bg-amber-100 text-amber-700
                                                    <?php else: ?> bg-emerald-100 text-emerald-700
                                                    <?php endif; ?>">
                                                    <?php echo e($siswa->total_poin); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="<?php echo e(route('siswa.detail', $siswa->id)); ?>" class="text-indigo-600 hover:text-indigo-900 font-medium">Detail</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Detail Pelanggaran -->
                    <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-rose-50 to-white">
                            <h2 class="text-lg font-semibold text-gray-800">Detail Pelanggaran</h2>
                            <p class="text-sm text-gray-500">Data lengkap pelanggaran siswa di kelas <?php echo e($selectedKelas); ?></p>
                        </div>
                        <div class="overflow-x-auto max-h-96">
                            <table class="w-full min-w-[800px]">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggaran</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Poin</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php $__currentLoopData = $pelanggaranData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"><?php echo e($index + 1); ?></td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-800"><?php echo e($data->nama_siswa); ?></div>
                                                <div class="text-xs text-gray-500"><?php echo e($data->nis); ?></div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($data->jenis_pelanggaran); ?></td>
                                            <td class="px-4 py-3 text-center">
                                                <?php if($data->kategori == 'Ringan'): ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-700">Ringan</span>
                                                <?php elseif($data->kategori == 'Sedang'): ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700">Sedang</span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-700">Berat</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm font-medium text-gray-800"><?php echo e($data->poin); ?></td>
                                            <td class="px-4 py-3 text-sm text-gray-600 max-w-xs truncate"><?php echo e($data->deskripsi ?: '-'); ?></td>
                                            <td class="px-4 py-3 text-sm text-gray-600"><?php echo e($data->nama_petugas); ?></td>
                                            <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap"><?php echo e(\Carbon\Carbon::parse($data->created_at)->format('d/m/Y')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="bg-white rounded-2xl p-12 shadow-lg shadow-gray-200/50 border border-gray-100 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-4 text-gray-500 text-lg">Tidak ada pelanggaran untuk kelas <?php echo e($selectedKelas); ?> pada periode yang dipilih.</p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <!-- Empty State -->
                <div class="bg-white rounded-2xl p-12 shadow-lg shadow-gray-200/50 border border-gray-100 text-center animate-fade-in">
                    <svg class="mx-auto h-16 w-16 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="mt-4 text-gray-500 text-lg">Pilih kelas dan periode untuk melihat data pelanggaran</p>
                </div>
                <?php endif; ?>
            </main>

            <!-- Footer -->
            <footer class="bg-white/60 backdrop-blur-sm border-t border-gray-100 py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-500">
                        &copy; <?php echo e(date('Y')); ?> SIPS. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>

<?php /**PATH /Users/abscom23/Desktop/SIPS/resources/views/laporan-pelanggaran-kelas.blade.php ENDPATH**/ ?>