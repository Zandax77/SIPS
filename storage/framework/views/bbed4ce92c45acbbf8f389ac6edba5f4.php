<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pelanggaran - <?php echo e($siswa->name); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 24px;
            color: #4f46e5;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            color: #666;
        }
        .student-info {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }
        .student-info h2 {
            font-size: 16px;
            color: #4f46e5;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 100px;
        }
        .info-value {
            flex: 1;
        }
        .total-poin {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin-bottom: 25px;
        }
        .total-poin .label {
            font-size: 14px;
            color: #991b1b;
            font-weight: bold;
        }
        .total-poin .value {
            font-size: 36px;
            font-weight: bold;
            color: #dc2626;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #4f46e5;
            color: white;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        tr:hover {
            background: #f3f4f6;
        }
        .kategori-ringan {
            background: #d1fae5;
            color: #065f46;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .kategori-sedang {
            background: #fef3c7;
            color: #92400e;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .kategori-berat {
            background: #fee2e2;
            color: #991b1b;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #666;
        }
        @media print {
            body {
                font-size: 11px;
            }
            .container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- School Header -->
        <?php if($sekolah): ?>
        <div class="school-header" style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #4f46e5;">
            <?php if($sekolah->logo_base64): ?>
            <div class="logo" style="flex-shrink: 0;">
                <img src="<?php echo e($sekolah->logo_base64); ?>" alt="Logo" style="max-height: 60px; max-width: 60px; object-fit: contain;">
            </div>
            <?php endif; ?>
            <div class="school-info" style="flex: 1;">
                <h1 style="font-size: 20px; color: #4f46e5; margin-bottom: 3px;"><?php echo e($sekolah->nama_sekolah); ?></h1>
                <?php if($sekolah->alamat_sekolah): ?>
                <p style="font-size: 11px; color: #666;"><?php echo e($sekolah->alamat_sekolah); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="header">
            <h1>📋 RIWAYAT PELANGGARAN SISWA</h1>
            <p>Sistem Informasi Pelanggaran Siswa (SIPS)</p>
        </div>

        <div class="student-info">
            <h2>👤 Data Siswa</h2>
            <div class="info-row">
                <span class="info-label">Nama:</span>
                <span class="info-value"><strong><?php echo e($siswa->name); ?></strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">NIS:</span>
                <span class="info-value"><?php echo e($siswa->nis); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Kelas:</span>
                <span class="info-value"><?php echo e($siswa->kelas); ?></span>
            </div>
        </div>

        <div class="total-poin">
            <div class="label">TOTAL POIN PELANGGARAN</div>
            <div class="value"><?php echo e($totalPoin); ?></div>
        </div>

        <?php if(count($pelanggaranDetail) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 150px;">Pelanggaran</th>
                    <th style="width: 80px;">Kategori</th>
                    <th style="width: 50px;">Poin</th>
                    <th style="width: 150px;">Deskripsi</th>
                    <th style="width: 100px;">Petugas</th>
                    <th style="width: 100px;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $pelanggaranDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($data->jenis_pelanggaran); ?></td>
                    <td>
                        <?php if($data->kategori == 'Ringan'): ?>
                            <span class="kategori-ringan">Ringan</span>
                        <?php elseif($data->kategori == 'Sedang'): ?>
                            <span class="kategori-sedang">Sedang</span>
                        <?php else: ?>
                            <span class="kategori-berat">Berat</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center; font-weight: bold;"><?php echo e($data->poin); ?></td>
                    <td><?php echo e($data->deskripsi ?: '-'); ?></td>
                    <td><?php echo e($data->nama_petugas); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($data->created_at)->format('d/m/Y')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="no-data">
            <p>Tidak ada pelanggaran tercatat untuk siswa ini.</p>
        </div>
        <?php endif; ?>

        <div class="footer">
            <p>Dicetak pada: <?php echo e(\Carbon\Carbon::now()->format('d/m/Y H:i:s')); ?></p>
            <p>SIPS - Sistem Informasi Pelanggaran Siswa</p>
        </div>
    </div>
</body>
</html>

<?php /**PATH /Users/abscom23/Desktop/SIPS/resources/views/pdf/pelanggaran.blade.php ENDPATH**/ ?>