<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Tindakan - {{ $siswa->name }}</title>
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
        @if($sekolah)
        <div class="school-header" style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #7c3aed;">
            @if($sekolah->logo_base64)
            <div class="logo" style="flex-shrink: 0;">
                <img src="{{ $sekolah->logo_base64 }}" alt="Logo" style="max-height: 60px; max-width: 60px; object-fit: contain;">
            </div>
            @endif
            <div class="school-info" style="flex: 1;">
                <h1 style="font-size: 20px; color: #7c3aed; margin-bottom: 3px;">{{ $sekolah->nama_sekolah }}</h1>
                @if($sekolah->alamat_sekolah)
                <p style="font-size: 11px; color: #666;">{{ $sekolah->alamat_sekolah }}</p>
                @endif
            </div>
        </div>
        @endif

        <div class="header">
            <h1>📋 RIWAYAT TINDAKAN SISWA</h1>
            <p>Sistem Informasi Pelanggaran Siswa (SIPS)</p>
        </div>

        <div class="student-info">
            <h2>👤 Data Siswa</h2>
            <div class="info-row">
                <span class="info-label">Nama:</span>
                <span class="info-value"><strong>{{ $siswa->name }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">NIS:</span>
                <span class="info-value">{{ $siswa->nis }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kelas:</span>
                <span class="info-value">{{ $siswa->kelas }}</span>
            </div>
        </div>

        <div class="total-tindakan">
            <div class="label">TOTAL TINDAKAN</div>
            <div class="value">{{ count($tindakanSiswa) }}</div>
        </div>

        @if(count($tindakanSiswa) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 180px;">Jenis Tindakan</th>
                    <th style="width: 100px;">Hasil</th>
                    <th style="width: 150px;">Deskripsi</th>
                    <th style="width: 150px;">Catatan</th>
                    <th style="width: 100px;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tindakanSiswa as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->jenis_tindakan }}</td>
                    <td>
                        @if($data->hasil_tindakan == 'Berhasil')
                            <span class="hasil-berhasil">Berhasil</span>
                        @elseif($data->hasil_tindakan == 'Tidak Berhasil')
                            <span class="hasil-tidak-berhasil">Tidak Berhasil</span>
                        @elseif($data->hasil_tindakan == 'Sedang Berlangsung')
                            <span class="hasil-sedang">Sedang Berlangsung</span>
                        @else
                            <span class="hasil-evaluasi">Perlu Evaluasi</span>
                        @endif
                    </td>
                    <td>{{ $data->deskripsi_tindakan ?: '-' }}</td>
                    <td>{{ $data->catatan_hasil ?: '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->tanggal_tindakan)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">
            <p>Tidak ada tindakan tercatat untuk siswa ini.</p>
        </div>
        @endif

        <div class="footer">
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
            <p>SIPS - Sistem Informasi Pelanggaran Siswa</p>
        </div>
    </div>
</body>
</html>

