<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Tindakan - <?php echo e($siswa->name); ?></title>
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
            border-bottom: 2px solid #7c3aed;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 24px;
            color: #7c3aed;
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
            color: #7c3aed;
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
        .total-tindakan {
            background: #f5f3ff;
            border: 1px solid #c4b5fd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin-bottom: 25px;
        }
        .total-tindakan .label {
            font-size: 14px;
            color: #6d28d9;
            font-weight: bold;
        }
        .total-tindakan .value {
            font-size: 36px;
            font-weight: bold;
            color: #7c3aed;
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
            background: #7c3aed;
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
        .hasil-berhasil {
            background: #d1fae5;
            color: #065f46;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .hasil-tidak-berhasil {
            background: #fee2e2;
            color: #991b1b;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .hasil-sedang {
            background: #fef3c7;
            color: #92400e;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .hasil-evaluasi {
            background: #e0e7ff;
            color: #3730a3;
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
        <div class="school-header" style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #7c3aed;">
            <?php if($sekolah->logo_base64): ?>
            <div class="logo" style="flex-shrink: 0;">
                <img src="<?php echo e($sekolah->logo_base64); ?>" alt="Logo" style="max-height: 60px; max-width: 60px; object-fit: contain;">
            </div>
            <?php endif; ?>
            <div class="school-info" style="flex: 1;">
                <h1 style="font-size: 20px; color: #7c3aed; margin-bottom: 3px;"><?php echo e($sekolah->nama_sekolah); ?></h1>
                <?php if($sekolah->alamat_sekolah): ?>
                <p style="font-size: 11px; color: #666;"><?php echo e($sekolah->alamat_sekolah); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="header">
            <h1>📋 RIWAYAT TINDAKAN SISWA</h1>
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

        <div class="total-tindakan">
            <div class="label">TOTAL TINDAKAN</div>
            <div class="value"><?php echo e(count($tindakanSiswa)); ?></div>
        </div>

        <?php if(count($tindakanSiswa) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 160px;">Jenis Tindakan</th>
                    <th style="width: 80px;">Hasil</th>
                    <th style="width: 120px;">Deskripsi</th>
                    <th style="width: 120px;">Catatan</th>
                    <th style="width: 80px;">Tanggal</th>
                    <th style="width: 80px;">Bukti</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $tindakanSiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($data->jenis_tindakan); ?></td>
                    <td>
                        <?php if($data->hasil_tindakan == 'Berhasil'): ?>
                            <span class="hasil-berhasil">Berhasil</span>
                        <?php elseif($data->hasil_tindakan == 'Tidak Berhasil'): ?>
                            <span class="hasil-tidak-berhasil">Tidak Berhasil</span>
                        <?php elseif($data->hasil_tindakan == 'Sedang Berlangsung'): ?>
                            <span class="hasil-sedang">Sedang Berlangsung</span>
                        <?php else: ?>
                            <span class="hasil-evaluasi">Perlu Evaluasi</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($data->deskripsi_tindakan ?: '-'); ?></td>
                    <td><?php echo e($data->catatan_hasil ?: '-'); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($data->tanggal_tindakan)->format('d/m/Y')); ?></td>
                    <td style="text-align: center;">
                        <?php if($data->bukti_foto): ?>
                            <?php
                                $extension = pathinfo($data->bukti_foto, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                            ?>
                            <?php if($isImage): ?>
                                <img src="<?php echo e(public_path('storage/' . $data->bukti_foto)); ?>" alt="Bukti" style="width: 80px; height: 80px; border-radius: 6px; object-fit: contain; border: 1px solid #e5e7eb; background: #f9fafb;">
                            <?php else: ?>
                                <span style="font-size: 10px; color: #7c3aed; font-weight: bold;"><?php echo e(strtoupper($extension)); ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="color: #ccc;">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <?php if($tindakanSiswa->whereNotNull('bukti_foto')->count() > 0): ?>
        <div style="margin-top: 25px;">
            <h3 style="font-size: 14px; color: #7c3aed; margin-bottom: 15px; padding-bottom: 5px; border-bottom: 1px solid #e5e7eb;">LAMPIRAN BUKTI FOTO/DOKUMEN</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <?php $__currentLoopData = $tindakanSiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($data->bukti_foto): ?>
                            <?php
                                $extension = pathinfo($data->bukti_foto, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                            ?>
                            <td style="border: 1px solid #e5e7eb; padding: 15px; text-align: center; width: 50%; background: #f9fafb; page-break-inside: avoid;" valign="top">
                                <?php if($isImage): ?>
                                    <img src="<?php echo e(public_path('storage/' . $data->bukti_foto)); ?>" alt="Bukti" style="max-width: 100%; width: auto; height: auto; max-height: 300px; object-fit: contain; border-radius: 6px; margin-bottom: 8px;">
                                <?php else: ?>
                                    <div style="width: 100%; min-height: 100px; display: flex; align-items: center; justify-content: center; background: #f3f4f6; border-radius: 6px; margin-bottom: 8px; padding: 20px;">
                                        <span style="font-size: 24px; font-weight: bold; color: #7c3aed;"><?php echo e(strtoupper($extension)); ?></span>
                                    </div>
                                <?php endif; ?>
                                <div style="font-size: 11px; color: #666; margin-top: 5px; padding-top: 8px; border-top: 1px dashed #e5e7eb;">
                                    <strong><?php echo e($data->jenis_tindakan); ?></strong><br>
                                    <?php echo e(\Carbon\Carbon::parse($data->tanggal_tindakan)->format('d/m/Y')); ?>

                                </div>
                            </td>
                            <?php if($loop->iteration % 2 == 0 && !$loop->last): ?>
                </tr><tr>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(count($tindakanSiswa->whereNotNull('bukti_foto')) % 2 != 0): ?>
                        <td style="border: none; width: 50%;">&nbsp;</td>
                    <?php endif; ?>
                </tr>
            </table>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <div class="no-data">
            <p>Tidak ada tindakan tercatat untuk siswa ini.</p>
        </div>
        <?php endif; ?>

        <!-- Signature Section -->
        <div style="margin-top: 40px; page-break-inside: avoid;">
            <h3 style="font-size: 14px; color: #7c3aed; margin-bottom: 20px; padding-bottom: 5px; border-bottom: 1px solid #e5e7eb; text-align: center;">LEMBAR PENGESAHAN</h3>
            <p style="font-size: 11px; color: #666; text-align: center; margin-bottom: 30px;">
                Yang bertanda tangan di bawah ini menyatakan bahwa tindakan terhadap siswa tersebut di atas telah dilaksanakan sesuai dengan ketentuan yang berlaku.
            </p>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <!-- Kolom 1: Orang Tua/Wali -->
                    <td style="width: 33.33%; text-align: center; padding: 10px; vertical-align: top;">
                        <div style="padding: 10px;">
                            <p style="font-weight: bold; font-size: 13px; color: #333; margin-bottom: 5px;">Mengetahui,</p>
                            <p style="font-size: 12px; color: #7c3aed; font-weight: bold; margin-bottom: 60px;">Orang Tua/Wali Murid</p>
                            <div style="margin-top: 10px; border-top: 1px solid #333; padding-top: 5px; width: 80%; margin-left: auto; margin-right: auto;">
                                <p style="font-size: 11px; font-weight: bold; color: #333;">( _____________________ )</p>
                                <p style="font-size: 10px; color: #666; margin-top: 2px;">Nama & Tanda Tangan</p>
                            </div>
                            <p style="font-size: 10px; color: #999; margin-top: 5px;">Tanggal: ______________</p>
                        </div>
                    </td>

                    <!-- Kolom 2: Guru BK -->
                    <td style="width: 33.33%; text-align: center; padding: 10px; vertical-align: top;">
                        <div style="padding: 10px; border-left: 1px dashed #ddd; border-right: 1px dashed #ddd; min-height: 180px;">
                            <p style="font-weight: bold; font-size: 13px; color: #333; margin-bottom: 5px;">Mengetahui,</p>
                            <?php if(isset($guruBK) && $guruBK): ?>
                            <p style="font-size: 12px; color: #7c3aed; font-weight: bold; margin-bottom: 60px;">Guru BK</p>
                            <div style="margin-top: 10px; border-top: 1px solid #333; padding-top: 5px; width: 80%; margin-left: auto; margin-right: auto;">
                                <p style="font-size: 11px; font-weight: bold; color: #333;"><?php echo e($guruBK->name); ?></p>
                                <p style="font-size: 10px; color: #666; margin-top: 2px;">NIP. ______________</p>
                            </div>
                            <?php else: ?>
                            <p style="font-size: 12px; color: #7c3aed; font-weight: bold; margin-bottom: 60px;">Guru BK</p>
                            <div style="margin-top: 10px; border-top: 1px solid #333; padding-top: 5px; width: 80%; margin-left: auto; margin-right: auto;">
                                <p style="font-size: 11px; font-weight: bold; color: #333;">( _____________________ )</p>
                                <p style="font-size: 10px; color: #666; margin-top: 2px;">Nama & Tanda Tangan</p>
                            </div>
                            <?php endif; ?>
                            <p style="font-size: 10px; color: #999; margin-top: 5px;">Tanggal: ______________</p>
                        </div>
                    </td>

                    <!-- Kolom 3: Kepala Sekolah -->
                    <td style="width: 33.33%; text-align: center; padding: 10px; vertical-align: top;">
                        <div style="padding: 10px;">
                            <p style="font-weight: bold; font-size: 13px; color: #333; margin-bottom: 5px;">Mengesahkan,</p>
                            <?php if($sekolah && $sekolah->nama_kepala_sekolah): ?>
                            <p style="font-size: 12px; color: #7c3aed; font-weight: bold; margin-bottom: 60px;">Kepala Sekolah</p>
                            <div style="margin-top: 10px; border-top: 1px solid #333; padding-top: 5px; width: 80%; margin-left: auto; margin-right: auto;">
                                <p style="font-size: 11px; font-weight: bold; color: #333;"><?php echo e($sekolah->nama_kepala_sekolah); ?></p>
                                <p style="font-size: 10px; color: #666; margin-top: 2px;">NIP. <?php echo e($sekolah->nip_kepala_sekolah ?: '______________'); ?></p>
                            </div>
                            <?php else: ?>
                            <p style="font-size: 12px; color: #7c3aed; font-weight: bold; margin-bottom: 60px;">Kepala Sekolah</p>
                            <div style="margin-top: 10px; border-top: 1px solid #333; padding-top: 5px; width: 80%; margin-left: auto; margin-right: auto;">
                                <p style="font-size: 11px; font-weight: bold; color: #333;">( _____________________ )</p>
                                <p style="font-size: 10px; color: #666; margin-top: 2px;">Nama & Tanda Tangan</p>
                            </div>
                            <?php endif; ?>
                            <p style="font-size: 10px; color: #999; margin-top: 5px;">Tanggal: ______________</p>
                        </div>
                    </td>
                </tr>
            </table>
            
            <!-- Additional Info: Petugas Pencetak -->
            <?php if(isset($namaPetugasCetak)): ?>
            <div style="margin-top: 15px; font-size: 10px; color: #999; text-align: center; font-style: italic;">
                <p>Dicetak oleh: <?php echo e($namaPetugasCetak); ?> (<?php echo e($jabatanPetugasCetak); ?>)</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p>Dicetak pada: <?php echo e(\Carbon\Carbon::now()->format('d/m/Y H:i:s')); ?></p>
            <p>SIPS - Sistem Informasi Pelanggaran Siswa</p>
        </div>
    </div>
</body>
</html>

<?php /**PATH /Users/abscom23/Documents/SIPS/resources/views/pdf/tindakan.blade.php ENDPATH**/ ?>