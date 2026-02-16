<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Siswa Poin Tertinggi - <?php echo e($namaSekolah); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="bg-white">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">LAPORAN SISWA POIN TERTINGGI</h1>
            <h2 class="text-xl text-gray-600"><?php echo e($namaSekolah); ?></h2>
            <p class="text-gray-500 mt-2">Daftar Siswa dengan Poin Pelanggaran Tertinggi</p>
            <p class="text-gray-400">Tanggal: <?php echo e($tanggal); ?></p>
            <?php if($kelas || $limit): ?>
                <p class="text-gray-500 mt-1">
                    <?php if($kelas): ?>Kelas: <?php echo e($kelas); ?><?php endif; ?>
                    <?php if($kelas && $limit): ?> | <?php endif; ?>
                    <?php if($limit): ?>Top <?php echo e($limit); ?> Siswa<?php endif; ?>
                </p>
            @endif
        </div>

        <!-- Table -->
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Rank</th>
                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">NIS</th>
                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Nama Siswa</th>
                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Kelas</th>
                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Total Poin</th>
                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Jml Pelanggaran</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $siswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="<?php echo e($index % 2 == 0 ? 'bg-white' : 'bg-gray-50'); ?>">
                        <td class="border border-gray-300 px-4 py-2 text-sm text-center">
                            <?php if($item->rank == 1): ?>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-400 text-white font-bold">1</span>
                            <?php elseif($item->rank == 2): ?>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-gray-700 font-bold">2</span>
                            <?php elseif($item->rank == 3): ?>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-600 text-white font-bold">3</span>
                            <?php else: ?>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-600 font-semibold"><?php echo e($item->rank); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-sm"><?php echo e($item->nis); ?></td>
                        <td class="border border-gray-300 px-4 py-2 text-sm font-semibold"><?php echo e($item->nama_siswa); ?></td>
                        <td class="border border-gray-300 px-4 py-2 text-sm"><?php echo e($item->kelas); ?></td>
                        <td class="border border-gray-300 px-4 py-2 text-sm text-center font-bold text-lg
                            <?php if($item->total_poin > 15): ?> text-rose-600
                            <?php elseif($item->total_poin > 10): ?> text-amber-600
                            <?php else: ?> text-emerald-600 <?php endif; ?>">
                            <?php echo e($item->total_poin); ?>

                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-sm text-center"><?php echo e($item->total_pelanggaran); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-gray-500">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>Dicetak pada: <?php echo e(date('d F Y H:i:s')); ?></p>
            <p>Sistem Informasi Pelanggaran Siswa (SIPS)</p>
        </div>
    </div>
</body>
</html>

<?php /**PATH /Users/abscom23/Documents/SIPS/resources/views/pdf/laporan-siswa-tertinggi.blade.php ENDPATH**/ ?>