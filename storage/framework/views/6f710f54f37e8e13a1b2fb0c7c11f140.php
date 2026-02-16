<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catat Pelanggaran - SIPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        indigo: { 50: '#eef2ff', 100: '#e0e7ff', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca' },
                        emerald: { 500: '#10b981', 600: '#059669' },
                        amber: { 500: '#f59e0b', 600: '#d97706' },
                        rose: { 500: '#f43f5e', 600: '#e11d48' }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fade-in 0.4s ease-out forwards; }
        .delay-100 { animation-delay: 0.1s; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen">
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
                        <?php if(strtolower(session('role', 'petugas')) !== 'osis'): ?>
                        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="hidden sm:inline">Kembali</span>
                        </a>
                        <?php endif; ?>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-xl font-bold text-gray-800">SIPS</span>
                                <p class="text-xs text-gray-500">Catat Pelanggaran</p>
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
                                <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all duration-200">
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
            <!-- Success Message -->
            <?php if(session('success')): ?>
                <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-emerald-800">Berhasil!</p>
                            <p class="text-sm text-emerald-600"><?php echo e(session('success')); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="mb-8 animate-fade-in">
                <h1 class="text-2xl font-bold text-gray-800">Catat Pelanggaran</h1>
                <p class="text-gray-500 mt-1">Pencarian siswa dan pencatatan pelanggaran</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Student Search Section -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 animate-fade-in delay-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Pencarian Siswa</h2>
                            <p class="text-sm text-gray-500">Cari berdasarkan nama atau scan QR</p>
                        </div>
                    </div>

                    <!-- Tab Buttons -->
                    <div class="flex gap-2 mb-4">
                        <button onclick="switchTab('search')" id="tab-search" class="flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-indigo-600 text-white">
                            Cari Nama
                        </button>
                        <button onclick="switchTab('qr')" id="tab-qr" class="flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-600 hover:bg-gray-200">
                            Scan QR
                        </button>
                    </div>

                    <!-- Search Tab -->
                    <div id="search-tab">
                        <form action="<?php echo e(route('pelanggaran.catat')); ?>" method="GET" class="mb-4">
                            <div class="relative">
                                <input type="text"
                                    name="search_siswa"
                                    id="search-input"
                                    value="<?php echo e($searchSiswa); ?>"
                                    placeholder="Ketik nama atau NIS siswa..."
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <button type="submit" class="w-full mt-3 px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                                Cari Siswa
                            </button>
                        </form>

                        <!-- Search Results -->
                        <?php if(count($siswaResult) > 0): ?>
                            <div class="space-y-2">
                                <?php $__currentLoopData = $siswaResult; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('pelanggaran.catat', ['id_siswa' => $s->id])); ?>"
                                       class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800"><?php echo e($s->nama_siswa); ?></p>
                                            <p class="text-sm text-gray-500"><?php echo e($s->nis); ?> - <?php echo e($s->kelas); ?></p>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php elseif($searchSiswa && count($siswaResult) == 0): ?>
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="mt-4 text-gray-500">Siswa tidak ditemukan</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- QR Tab -->
                    <div id="qr-tab" class="hidden">
                        <div class="bg-gray-50 rounded-xl p-4 text-center">
                            <div id="reader" class="w-full max-w-xs mx-auto rounded-lg overflow-hidden"></div>
                            <p class="text-sm text-gray-500 mt-3">Arahkan kamera ke QR code siswa</p>
                            <button onclick="startScanner()" id="start-scanner" class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                                Mulai Scan
                            </button>
                            <button onclick="stopScanner()" id="stop-scanner" class="mt-3 ml-2 px-4 py-2 bg-rose-600 text-white rounded-xl hover:bg-rose-700 transition-colors font-medium hidden">
                                Hentikan
                            </button>
                        </div>
                        <div id="qr-result" class="mt-4 hidden">
                            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                                <p class="font-medium text-emerald-800">Siswa Ditemukan!</p>
                                <p id="qr-siswa-info" class="text-sm text-emerald-600 mt-1"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Violation Form Section -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 animate-fade-in delay-200">
                    <?php if($selectedSiswa): ?>
                        <!-- Selected Student Info -->
                        <div class="flex items-center gap-4 p-4 bg-indigo-50 rounded-xl mb-6 border border-indigo-100">
                            <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-800 text-lg"><?php echo e($selectedSiswa->name); ?></p>
                                <p class="text-sm text-gray-600"><?php echo e($selectedSiswa->nis); ?> - <?php echo e($selectedSiswa->kelas); ?></p>
                                <p class="text-sm mt-1">
                                    <span class="font-medium">Total Poin:</span>
                                    <span class="<?php echo e($selectedSiswa->total_poin > 10 ? 'text-rose-600' : ($selectedSiswa->total_poin > 0 ? 'text-amber-600' : 'text-emerald-600')); ?>">
                                        <?php echo e($selectedSiswa->total_poin); ?> Poin
                                    </span>
                                </p>
                            </div>
                            <a href="<?php echo e(route('pelanggaran.catat')); ?>" class="p-2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </div>

                        <!-- Violation Form -->
                        <form action="<?php echo e(route('pelanggaran.catat.action')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id_siswa" value="<?php echo e($selectedSiswa->id); ?>">

                            <?php if(count($jenisPelanggaran) > 0): ?>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pelanggaran</label>
                                <?php if(count($jenisPelanggaran) > 0): ?>
                                <select name="id_jenis_pelanggaran" id="jenis-pelanggaran" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none bg-white">
                                    <option value="">-- Pilih Jenis Pelanggaran --</option>
                                    <?php $__currentLoopData = $kategoriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori => $pelanggarans): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <optgroup label="<?php echo e($kategori); ?>" data-kategori="<?php echo e($kategori); ?>">
                                            <?php $__currentLoopData = $pelanggarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($p->id); ?>" data-kategori="<?php echo e($kategori); ?>"><?php echo e($p->nama); ?> (<?php echo e($p->poin); ?> poin)</option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </optgroup>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php else: ?>
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-amber-800">Belum Ada Jenis Pelanggaran</p>
                                            <p class="text-xs text-amber-600 mt-1">Silakan hubungi administrator untuk menambahkan jenis pelanggaran terlebih dahulu.</p>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php $__errorArgs = ['id_jenis_pelanggaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-sm text-rose-600 mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (Opsional)</label>
                                <textarea name="deskripsi" rows="3" placeholder="Tambahkan detail pelanggaran..."
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none resize-none"></textarea>
                            </div>

                            <!-- Lampiran Section - Only for Sedang/Berat -->
                            <div id="lampiran-section" class="mb-6 hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Lampiran <span class="text-rose-500">*</span>
                                    <span class="text-xs text-gray-500 font-normal">(Wajib untuk pelanggaran Sedang/Berat)</span>
                                </label>

                                <!-- Camera Capture Button -->
                                <button type="button" onclick="openCameraModal()" id="camera-btn" class="w-full mb-3 px-4 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors font-medium flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Ambil Foto dengan Kamera
                                </button>

                                <div class="relative">
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:border-indigo-400 transition-colors">
                                        <p class="text-center text-sm text-gray-500 mb-3">atau upload dari perangkat</p>
                                        <input type="file"
                                            name="lampiran"
                                            id="lampiran"
                                            accept=".jpg,.jpeg,.doc,.docx,.pdf"
                                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        <p class="text-xs text-gray-400 mt-2">Format: JPG (foto), DOC, DOCX, PDF (dokumen) | Maks: 1 MB</p>
                                    </div>
                                </div>
                                <!-- Preview Image -->
                                <div id="image-preview" class="mt-3 hidden">
                                    <p class="text-xs text-gray-500 mb-1">Preview:</p>
                                    <img id="preview-img" src="" alt="Preview" class="max-w-xs h-auto rounded-lg border border-gray-200">
                                </div>
                                <!-- Camera Capture Preview -->
                                <div id="camera-preview" class="mt-3 hidden">
                                    <p class="text-xs text-gray-500 mb-1">Foto dari Kamera:</p>
                                    <div class="relative inline-block">
                                        <img id="captured-photo" src="" alt="Captured" class="max-w-xs h-auto rounded-lg border border-gray-200">
                                        <button type="button" onclick="retakePhoto()" class="absolute -top-2 -right-2 bg-rose-600 text-white rounded-full p-1.5 hover:bg-rose-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <p id="photo-size" class="text-xs text-gray-500 mt-1"></p>
                                </div>
                                <?php $__errorArgs = ['lampiran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-sm text-rose-600 mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <button type="submit" class="w-full px-6 py-3 bg-rose-600 text-white rounded-xl hover:bg-rose-700 transition-colors font-medium flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Catat Pelanggaran
                            </button>
                            <?php else: ?>
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-base font-medium text-amber-800">Belum Ada Jenis Pelanggaran</p>
                                        <p class="text-sm text-amber-600 mt-1">Silakan hubungi administrator untuk menambahkan jenis pelanggaran terlebih dahulu sebelum dapat mencatat pelanggaran.</p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </form>
                    <?php else: ?>
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-800">Pilih Siswa</h3>
                            <p class="text-gray-500 mt-1">Cari siswa terlebih dahulu untuk mencatat pelanggaran</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white/60 backdrop-blur-sm border-t border-gray-100 py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; <?php echo e(date('Y')); ?> SIPS. All rights reserved.
                </p>
            </div>
        </footer>
    </div>

    <script>
        let html5QrCode;
        let isScanning = false;

        function switchTab(tab) {
            if (tab === 'search') {
                document.getElementById('search-tab').classList.remove('hidden');
                document.getElementById('qr-tab').classList.add('hidden');
                document.getElementById('tab-search').classList.add('bg-indigo-600', 'text-white');
                document.getElementById('tab-search').classList.remove('bg-gray-100', 'text-gray-600');
                document.getElementById('tab-qr').classList.add('bg-gray-100', 'text-gray-600');
                document.getElementById('tab-qr').classList.remove('bg-indigo-600', 'text-white');
                stopScanner();
            } else {
                document.getElementById('search-tab').classList.add('hidden');
                document.getElementById('qr-tab').classList.remove('hidden');
                document.getElementById('tab-qr').classList.add('bg-indigo-600', 'text-white');
                document.getElementById('tab-qr').classList.remove('bg-gray-100', 'text-gray-600');
                document.getElementById('tab-search').classList.add('bg-gray-100', 'text-gray-600');
                document.getElementById('tab-search').classList.remove('bg-indigo-600', 'text-white');
            }
        }

        function startScanner() {
            if (isScanning) return;

            html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
                .then(() => {
                    isScanning = true;
                    document.getElementById('start-scanner').classList.add('hidden');
                    document.getElementById('stop-scanner').classList.remove('hidden');
                })
                .catch(err => {
                    console.error("Gagal memulai scanner:", err);
                    alert("Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.");
                });
        }

        function stopScanner() {
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    isScanning = false;
                    document.getElementById('start-scanner').classList.remove('hidden');
                    document.getElementById('stop-scanner').classList.add('hidden');
                }).catch(err => console.error("Gagal menghentikan scanner:", err));
            }
        }

        function onScanSuccess(decodedText) {
            // Assuming QR code contains student ID
            const studentId = decodedText;

            // Fetch student data
            fetch(`/api/pelanggaran/siswa/${studentId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Siswa tidak ditemukan!');
                        return;
                    }

                    document.getElementById('qr-result').classList.remove('hidden');
                    document.getElementById('qr-siswa-info').innerHTML = `
                        <strong>${data.nama_siswa}</strong><br>
                        ${data.nis} - ${data.kelas}<br>
                        Total Poin: ${data.total_poin}
                    `;

                    // Redirect to violation form with selected student
                    window.location.href = `/pelanggaran/catat?id_siswa=${data.id}`;
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Terjadi kesalahan saat memproses QR code');
                });
        }

        // Handle lampiran (attachment) field visibility based on violation category
        document.addEventListener('DOMContentLoaded', function() {
            const jenisSelect = document.getElementById('jenis-pelanggaran');
            const lampiranSection = document.getElementById('lampiran-section');
            const lampiranInput = document.getElementById('lampiran');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            if (jenisSelect && lampiranSection) {
                jenisSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const kategori = selectedOption.getAttribute('data-kategori');

                    // Show lampiran section only for Sedang and Berat violations
                    if (kategori === 'Sedang' || kategori === 'Berat') {
                        lampiranSection.classList.remove('hidden');
                    } else {
                        lampiranSection.classList.add('hidden');
                        // Clear the input when hiding
                        lampiranInput.value = '';
                        imagePreview.classList.add('hidden');
                        // Also clear camera preview
                        const cameraPreview = document.getElementById('camera-preview');
                        const capturedPhoto = document.getElementById('captured-photo');
                        if (cameraPreview) cameraPreview.classList.add('hidden');
                        if (capturedPhoto) capturedPhoto.src = '';
                        // Remove hidden file input for camera
                        removeCameraInput();
                    }
                });
            }

            // Handle image preview for JPG files
            if (lampiranInput) {
                lampiranInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];

                    // Check file size (max 1MB = 1048576 bytes)
                    const maxSize = 1048576; // 1MB in bytes

                    if (file) {
                        // Show warning if file size exceeds 1MB
                        if (file.size > maxSize) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'File Terlalu Besar!',
                                text: 'Ukuran file tidak boleh lebih dari 1 MB. Silakan pilih file yang lebih kecil.',
                                confirmButtonColor: '#f59e0b',
                                confirmButtonText: 'OK'
                            });
                            // Clear the input
                            this.value = '';
                            imagePreview.classList.add('hidden');
                            return;
                        }

                        const extension = file.name.split('.').pop().toLowerCase();

                        // Show image preview for jpg/jpeg files
                        if (extension === 'jpg' || extension === 'jpeg') {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewImg.src = e.target.result;
                                imagePreview.classList.remove('hidden');
                            };
                            reader.readAsDataURL(file);
                        } else {
                            // Hide preview for documents
                            imagePreview.classList.add('hidden');
                        }

                        // Hide camera preview when file is selected
                        const cameraPreview = document.getElementById('camera-preview');
                        if (cameraPreview) cameraPreview.classList.add('hidden');
                    } else {
                        imagePreview.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    <script>
        // Camera functionality variables
        let cameraStream = null;
        let capturedImageBlob = null;

        // Open camera modal
        function openCameraModal() {
            const modal = document.getElementById('camera-modal');
            modal.classList.remove('hidden');
            startCamera();
        }

        // Close camera modal
        function closeCameraModal() {
            stopCamera();
            const modal = document.getElementById('camera-modal');
            modal.classList.add('hidden');
        }

        // Start camera
        async function startCamera() {
            const video = document.getElementById('camera-video');
            const loading = document.getElementById('camera-loading');
            const error = document.getElementById('camera-error');
            const captureBtn = document.getElementById('capture-btn');

            loading.classList.remove('hidden');
            error.classList.add('hidden');
            captureBtn.disabled = true;

            try {
                // Request camera access
                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment',
                        width: { ideal: 1920 },
                        height: { ideal: 1080 }
                    }
                });

                video.srcObject = cameraStream;
                loading.classList.add('hidden');
                captureBtn.disabled = false;

            } catch (err) {
                console.error('Camera error:', err);
                loading.classList.add('hidden');
                error.classList.remove('hidden');
            }
        }

        // Stop camera
        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
        }

        // Capture photo from camera
        function capturePhoto() {
            const video = document.getElementById('camera-video');
            const canvas = document.getElementById('capture-canvas');
            const ctx = canvas.getContext('2d');

            // Set canvas dimensions to match video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Draw video frame to canvas
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Compress and get image as blob
            compressImage(canvas, (blob) => {
                capturedImageBlob = blob;

                // Create preview
                const capturedPhoto = document.getElementById('captured-photo');
                const cameraPreview = document.getElementById('camera-preview');
                const photoSize = document.getElementById('photo-size');

                const url = URL.createObjectURL(blob);
                capturedPhoto.src = url;
                cameraPreview.classList.remove('hidden');

                // Show file size
                const sizeKB = Math.round(blob.size / 1024);
                photoSize.textContent = `Ukuran: ${sizeKB} KB`;

                // Create hidden file input for form submission
                createCameraInput(blob);

                // Close camera modal
                closeCameraModal();

                // Hide file input preview
                const imagePreview = document.getElementById('image-preview');
                if (imagePreview) imagePreview.classList.add('hidden');

                // Clear file input
                const lampiranInput = document.getElementById('lampiran');
                if (lampiranInput) lampiranInput.value = '';
            });
        }

        // Compress image to under 1MB
        function compressImage(canvas, callback) {
            const maxSize = 1048576; // 1MB in bytes
            const maxDimension = 1920;

            let quality = 0.8;
            let width = canvas.width;
            let height = canvas.height;

            // Calculate new dimensions if needed
            if (width > maxDimension || height > maxDimension) {
                if (width > height) {
                    height = Math.round((height / width) * maxDimension);
                    width = maxDimension;
                } else {
                    width = Math.round((width / height) * maxDimension);
                    height = maxDimension;
                }
            }

            // Create temporary canvas for resizing
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = width;
            tempCanvas.height = height;
            const tempCtx = tempCanvas.getContext('2d');
            tempCtx.drawImage(canvas, 0, 0, width, height);

            // Try to compress
            const tryCompress = (q) => {
                tempCanvas.toBlob((blob) => {
                    if (blob && blob.size > maxSize && q > 0.1) {
                        // Try with lower quality
                        tryCompress(q - 0.1);
                    } else {
                        callback(blob);
                    }
                }, 'image/jpeg', q);
            };

            tryCompress(quality);
        }

        // Create hidden file input for camera capture
        function createCameraInput(blob) {
            // Remove existing camera input if any
            removeCameraInput();

            // Create new file input
            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'lampiran';
            input.id = 'lampiran-camera';
            input.accept = 'image/jpeg';
            input.classList.add('hidden');

            // Create DataTransfer to set file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(new File([blob], 'camera_capture.jpg', { type: 'image/jpeg' }));
            input.files = dataTransfer.files;

            // Add to form
            const form = document.querySelector('form[enctype="multipart/form-data"]');
            if (form) {
                form.appendChild(input);
            }
        }

        // Remove camera input
        function removeCameraInput() {
            const existingInput = document.getElementById('lampiran-camera');
            if (existingInput) {
                existingInput.remove();
            }
        }

        // Retake photo
        function retakePhoto() {
            capturedImageBlob = null;
            const cameraPreview = document.getElementById('camera-preview');
            const capturedPhoto = document.getElementById('captured-photo');
            const photoSize = document.getElementById('photo-size');

            capturedPhoto.src = '';
            if (photoSize) photoSize.textContent = '';
            cameraPreview.classList.add('hidden');

            // Remove camera input
            removeCameraInput();
        }
    </script>

    <!-- Camera Modal for capturing photo -->
    <div id="camera-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeCameraModal()"></div>
        <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Ambil Foto</h3>
                    <button type="button" onclick="closeCameraModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <div id="video-container" class="relative bg-gray-900 rounded-xl overflow-hidden aspect-video">
                        <video id="camera-video" autoplay playsinline class="w-full h-full object-cover"></video>
                        <div id="camera-loading" class="absolute inset-0 flex items-center justify-center bg-gray-900">
                            <div class="text-white text-center">
                                <svg class="animate-spin w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-sm">Mengakses kamera...</p>
                            </div>
                        </div>
                        <div id="camera-error" class="hidden absolute inset-0 flex items-center justify-center bg-gray-900">
                            <div class="text-white text-center p-4">
                                <svg class="w-12 h-12 mx-auto mb-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <p class="text-sm">Tidak dapat mengakses kamera</p>
                                <p class="text-xs text-gray-400 mt-1">Pastikan izin kamera diberikan</p>
                            </div>
                        </div>
                    </div>
                    <canvas id="capture-canvas" class="hidden"></canvas>
                    <div class="flex gap-3 mt-4">
                        <button type="button" onclick="capturePhoto()" id="capture-btn" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Ambil Foto
                        </button>
                        <button type="button" onclick="closeCameraModal()" class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php /**PATH /Users/abscom23/Documents/SIPS/resources/views/catat-pelanggaran.blade.php ENDPATH**/ ?>