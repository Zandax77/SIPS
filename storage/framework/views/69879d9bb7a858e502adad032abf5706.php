<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa Poin Tertinggi - SIPS</title>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-xl font-bold text-gray-800">SIPS</span>
                                <p class="text-xs text-gray-500">Siswa Poin Tertinggi</p>
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
                <h1 class="text-2xl font-bold text-gray-800">Siswa Poin Tertinggi</h1>
                <p class="text-gray-500 mt-1">Daftar siswa dengan pelanggaran tertinggi</p>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 mb-6 animate-fade-in">
                <form action="<?php echo e(route('laporan.siswa-tertinggi')); ?>" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="w-full sm:w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Filter Kelas</label>
                        <select name="kelas" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none bg-white">
                            <option value="">Semua Kelas</option>
                            <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($k); ?>" <?php echo e($kelas == $k ? 'selected' : ''); ?>><?php echo e($k); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="w-full sm:w-40">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tampilkan</label>
                        <select name="limit" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none bg-white">
                            <option value="5" <?php echo e($limit == 5 ? 'selected' : ''); ?>>Top 5</option>
                            <option value="10" <?php echo e($limit == 10 ? 'selected' : ''); ?>>Top 10</option>
                            <option value="20" <?php echo e($limit == 20 ? 'selected' : ''); ?>>Top 20</option>
                            <option value="50" <?php echo e($limit == 50 ? 'selected' : ''); ?>>Top 50</option>
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
                    <a href="<?php echo e(route('laporan.siswa-tertinggi.pdf', ['kelas' => $kelas, 'limit' => $limit])); ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-rose-100 text-rose-700 rounded-lg hover:bg-rose-200 transition-colors font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Export PDF
                    </a>
                    <a href="<?php echo e(route('laporan.siswa-tertinggi.excel', ['kelas' => $kelas, 'limit' => $limit])); ?>" class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 transition-colors font-medium">
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

            <!-- Top 3 Cards -->
            <?php if(count($siswa) >= 3): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- 2nd Place -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 order-2 md:order-1 animate-fade-in">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-200 mb-4">
                            <span class="text-2xl font-bold text-gray-600">2</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-800"><?php echo e($siswa[1]->nama_siswa); ?></p>
                        <p class="text-sm text-gray-500"><?php echo e($siswa[1]->kelas); ?></p>
                        <div class="mt-4 inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 rounded-full">
                            <span class="text-xl font-bold"><?php echo e($siswa[1]->total_poin); ?></span>
                            <span class="ml-1 text-sm">poin</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-2"><?php echo e($siswa[1]->total_pelanggaran); ?> pelanggaran</p>
                    </div>
                </div>

                <!-- 1st Place -->
                <div class="bg-gradient-to-br from-yellow-50 to-amber-100 rounded-2xl p-6 shadow-xl shadow-yellow-200/50 border-2 border-yellow-300 order-1 md:order-2 animate-fade-in">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-yellow-400 mb-4 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <p class="text-xl font-bold text-gray-800"><?php echo e($siswa[0]->nama_siswa); ?></p>
                        <p class="text-sm text-gray-600"><?php echo e($siswa[0]->kelas); ?></p>
                        <div class="mt-4 inline-flex items-center px-6 py-3 bg-yellow-400 text-white rounded-full shadow-md">
                            <span class="text-2xl font-bold"><?php echo e($siswa[0]->total_poin); ?></span>
                            <span class="ml-1 text-sm">poin</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2"><?php echo e($siswa[0]->total_pelanggaran); ?> pelanggaran</p>
                    </div>
                </div>

                <!-- 3rd Place -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 order-3 animate-fade-in">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-600 mb-4">
                            <span class="text-2xl font-bold text-white">3</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-800"><?php echo e($siswa[2]->nama_siswa); ?></p>
                        <p class="text-sm text-gray-500"><?php echo e($siswa[2]->kelas); ?></p>
                        <div class="mt-4 inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 rounded-full">
                            <span class="text-xl font-bold"><?php echo e($siswa[2]->total_poin); ?></span>
                            <span class="ml-1 text-sm">poin</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-2"><?php echo e($siswa[2]->total_pelanggaran); ?> pelanggaran</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Data Table -->
            <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden animate-fade-in">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Daftar Lengkap</h2>
                            <p class="text-sm text-gray-500">Top <?php echo e($limit); ?> Siswa</p>
                        </div>
                    </div>
                </div>

                <?php if(count($siswa) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Poin</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jml Pelanggaran</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php $__currentLoopData = $siswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50 transition-colors duration-150 <?php echo e($index < 3 ? 'bg-yellow-50/50' : ''); ?>">
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <?php if($data->rank == 1): ?>
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-400 text-white font-bold">1</span>
                                            <?php elseif($data->rank == 2): ?>
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-gray-700 font-bold">2</span>
                                            <?php elseif($data->rank == 3): ?>
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-600 text-white font-bold">3</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-600 font-semibold"><?php echo e($data->rank); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"><?php echo e($data->nis); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium"><?php echo e($data->nama_siswa); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                                <?php echo e($data->kelas); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <?php if($data->total_poin > 15): ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-rose-100 text-rose-700">
                                                    <?php echo e($data->total_poin); ?>

                                                </span>
                                            <?php elseif($data->total_poin > 10): ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-amber-100 text-amber-700">
                                                    <?php echo e($data->total_poin); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-emerald-100 text-emerald-700">
                                                    <?php echo e($data->total_poin); ?>

                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center"><?php echo e($data->total_pelanggaran); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
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

<?php /**PATH /Users/abscom23/Documents/SIPS/resources/views/laporan-siswa-tertinggi.blade.php ENDPATH**/ ?>