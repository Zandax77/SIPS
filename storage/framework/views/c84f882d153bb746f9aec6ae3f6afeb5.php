<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap per Periode - SIPS</title>
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
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
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

    <div class="relative min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-sm border-b border-gray-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-4">
                        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="hidden sm:inline">Kembali</span>
                        </a>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-xl font-bold text-gray-800">SIPS</span>
                                <p class="text-xs text-gray-500">Rekap per Periode</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            <span class="text-xs font-medium text-emerald-700">DB Terhubung</span>
                        </div>

                        <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-semibold text-gray-800"><?php echo e($namaPetugas); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($jabatan); ?></p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <form action="<?php echo e(route('logout')); ?>" method="POST" class="ml-2">
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
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Page Header -->
            <div class="mb-8 animate-fade-in">
                <h1 class="text-2xl font-bold text-gray-800">Rekap per Periode</h1>
                <p class="text-gray-500 mt-1">Cetak dan export rekap pelanggaran berdasarkan periode</p>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 mb-6 animate-fade-in">
                <form action="<?php echo e(route('laporan.rekap-periode')); ?>" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="w-full sm:w-40">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="<?php echo e($tanggalMulai); ?>" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                    </div>

                    <div class="w-full sm:w-40">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" value="<?php echo e($tanggalAkhir); ?>" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                    </div>

                    <div class="w-full sm:w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Filter Kelas</label>
                        <select name="kelas" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none bg-white">
                            <option value="">Semua Kelas</option>
                            <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($k); ?>" <?php echo e($kelas == $k ? 'selected' : ''); ?>><?php echo e($k); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                            Filter
                        </button>
                    </div>
                </form>

                <!-- Export Buttons -->
                <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap gap-3">
                    <a href="<?php echo e(route('laporan.rekap-periode.pdf', ['tanggal_mulai' => $tanggalMulai, 'tanggal_akhir' => $tanggalAkhir, 'kelas' => $kelas])); ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-rose-100 text-rose-700 rounded-lg hover:bg-rose-200 transition-colors font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Export PDF
                    </a>
                    <a href="<?php echo e(route('laporan.rekap-periode.excel', ['tanggal_mulai' => $tanggalMulai, 'tanggal_akhir' => $tanggalAkhir, 'kelas' => $kelas])); ?>" class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 transition-colors font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </a>
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
                <div class="bg-white rounded-xl p-4 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <p class="text-xs font-medium text-gray-500">Total Hari</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($totals['total_hari']); ?></p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <p class="text-xs font-medium text-gray-500">Siswa Pelaku</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-1"><?php echo e($totals['siswa_pelaku']); ?></p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <p class="text-xs font-medium text-gray-500">Pelanggaran Ringan</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1"><?php echo e($totals['pelanggaran_ringan']); ?></p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <p class="text-xs font-medium text-gray-500">Pelanggaran Sedang</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1"><?php echo e($totals['pelanggaran_sedang']); ?></p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <p class="text-xs font-medium text-gray-500">Pelanggaran Berat</p>
                    <p class="text-2xl font-bold text-rose-600 mt-1"><?php echo e($totals['pelanggaran_berat']); ?></p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <p class="text-xs font-medium text-gray-500">Total Poin</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e($totals['total_poin']); ?></p>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden animate-fade-in">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Data Rekap Harian</h2>
                            <p class="text-sm text-gray-500">Periode: <?php echo e(\Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y')); ?> - <?php echo e(\Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y')); ?></p>
                        </div>
                    </div>
                </div>

                <?php if(count($rekap) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa Pelaku</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ringan</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sedang</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Berat</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Poin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php $__currentLoopData = $rekap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center"><?php echo e($index + 1); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo e(\Carbon\Carbon::parse($data->tanggal)->format('d/m/Y')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center"><?php echo e($data->siswa_pelaku); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-emerald-600 text-center font-medium"><?php echo e($data->pelanggaran_ringan); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-600 text-center font-medium"><?php echo e($data->pelanggaran_sedang); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-rose-600 text-center font-medium"><?php echo e($data->pelanggaran_berat); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-center font-bold"><?php echo e($data->total_pelanggaran); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-center font-bold"><?php echo e($data->total_poin); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot class="bg-gray-100">
                                <tr>
                                    <td colspan="2" class="px-6 py-3 text-right text-sm font-bold text-gray-800">TOTAL</td>
                                    <td class="px-6 py-3 text-center text-sm font-bold text-indigo-600"><?php echo e($totals['siswa_pelaku']); ?></td>
                                    <td class="px-6 py-3 text-center text-sm font-bold text-emerald-600"><?php echo e($totals['pelanggaran_ringan']); ?></td>
                                    <td class="px-6 py-3 text-center text-sm font-bold text-amber-600"><?php echo e($totals['pelanggaran_sedang']); ?></td>
                                    <td class="px-6 py-3 text-center text-sm font-bold text-rose-600"><?php echo e($totals['pelanggaran_berat']); ?></td>
                                    <td class="px-6 py-3 text-center text-sm font-bold text-gray-800"><?php echo e($totals['total_pelanggaran']); ?></td>
                                    <td class="px-6 py-3 text-center text-sm font-bold text-gray-800"><?php echo e($totals['total_poin']); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-4 text-gray-500">Tidak ada data ditemukan</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white/60 backdrop-blur-sm border-t border-gray-100 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; <?php echo e(date('Y')); ?> <?php echo e($namaSekolah); ?> - SIPS. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>

<?php /**PATH /Users/abscom23/Documents/SIPS/resources/views/laporan-rekap-periode.blade.php ENDPATH**/ ?>