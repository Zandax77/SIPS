<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pelanggaran Kelas {{ $selectedKelas }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 12px;
        }
        .header h1 {
            font-size: 18px;
            color: #4f46e5;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            color: #666;
        }
        .school-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #4f46e5;
        }
        .school-header .logo {
            flex-shrink: 0;
        }
        .school-header .logo img {
            max-height: 50px;
            max-width: 50px;
            object-fit: contain;
        }
        .school-header .school-info {
            flex: 1;
        }
        .school-header .school-info h1 {
            font-size: 16px;
            color: #4f46e5;
            margin-bottom: 3px;
        }
        .school-header .school-info p {
            font-size: 10px;
            color: #666;
        }
        .info-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
        }
        .info-box h2 {
            font-size: 14px;
            color: #4f46e5;
            margin-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 6px;
        }
        .info-row {
            display: flex;
            margin-bottom: 4px;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
        }
        .info-value {
            flex: 1;
        }
        .stats-grid {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            flex: 1;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
        }
        .stat-card .label {
            font-size: 10px;
            color: #666;
            margin-bottom: 4px;
        }
        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #4f46e5;
            color: white;
            font-weight: bold;
            font-size: 9px;
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
            padding: 1px 5px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: bold;
        }
        .kategori-sedang {
            background: #fef3c7;
            color: #92400e;
            padding: 1px 5px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: bold;
        }
        .kategori-berat {
            background: #fee2e2;
            color: #991b1b;
            padding: 1px 5px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: bold;
        }
        .poin-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 10px;
        }
        .poin-high {
            background: #fee2e2;
            color: #991b1b;
        }
        .poin-medium {
            background: #fef3c7;
            color: #92400e;
        }
        .poin-low {
            background: #d1fae5;
            color: #065f46;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #666;
        }
        .section-title {
            background: #f3f4f6;
            padding: 8px 12px;
            margin: 20px 0 10px 0;
            border-left: 4px solid #4f46e5;
        }
        .section-title h3 {
            font-size: 14px;
            color: #4f46e5;
        }
        @media print {
            body {
                font-size: 10px;
            }
            .container {
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- School Header -->
        @if($sekolah)
        <div class="school-header">
            @if($sekolah->logo_base64)
            <div class="logo">
                <img src="{{ $sekolah->logo_base64 }}" alt="Logo">
            </div>
            @endif
            <div class="school-info">
                <h1>{{ $sekolah->nama_sekolah }}</h1>
                @if($sekolah->alamat_sekolah)
                <p>{{ $sekolah->alamat_sekolah }}</p>
                @endif
            </div>
        </div>
        @endif

        <div class="header">
            <h1>📋 LAPORAN PELANGGARAN SISWA PER KELAS</h1>
            <p>Sistem Informasi Pelanggaran Siswa (SIPS)</p>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <h2>📅 Informasi Periode</h2>
            <div class="info-row">
                <span class="info-label">Kelas:</span>
                <span class="info-value"><strong>{{ $selectedKelas }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Periode:</span>
                <span class="info-value">
                    {{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Cetak:</span>
                <span class="info-value">{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</span>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="label">Total Pelanggaran</div>
                <div class="value">{{ $totalPelanggaran }}</div>
            </div>
            <div class="stat-card">
                <div class="label">Total Poin</div>
                <div class="value">{{ $totalPoin }}</div>
            </div>
            <div class="stat-card">
                <div class="label">Siswa Melanggar</div>
                <div class="value">{{ count($rekapPerSiswa) }}</div>
            </div>
        </div>

        <!-- Rekap Per Siswa -->
        @if(count($rekapPerSiswa) > 0)
        <div class="section-title">
            <h3>📊 Rekap Pelanggaran Per Siswa</h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 60px;">NIS</th>
                    <th style="width: 150px;">Nama</th>
                    <th style="width: 80px; text-align: center;">Jml</th>
                    <th style="width: 80px; text-align: center;">Total Poin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekapPerSiswa as $index => $siswa)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $siswa->nis }}</td>
                    <td>{{ $siswa->nama_siswa }}</td>
                    <td style="text-align: center;">
                        <span class="poin-badge" style="background: #e0e7ff; color: #4f46e5;">
                            {{ $siswa->jumlah_pelanggaran }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        @if($siswa->total_poin >= 50)
                            <span class="poin-badge poin-high">{{ $siswa->total_poin }}</span>
                        @elseif($siswa->total_poin >= 25)
                            <span class="poin-badge poin-medium">{{ $siswa->total_poin }}</span>
                        @else
                            <span class="poin-badge poin-low">{{ $siswa->total_poin }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Detail Pelanggaran -->
        <div class="section-title">
            <h3>📝 Detail Pelanggaran</h3>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 25px;">No</th>
                    <th style="width: 100px;">Siswa</th>
                    <th style="width: 120px;">Pelanggaran</th>
                    <th style="width: 60px; text-align: center;">Kategori</th>
                    <th style="width: 40px; text-align: center;">Poin</th>
                    <th style="width: 150px;">Deskripsi</th>
                    <th style="width: 80px;">Petugas</th>
                    <th style="width: 70px;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pelanggaranData as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div style="font-weight: bold;">{{ $data->nama_siswa }}</div>
                        <div style="font-size: 9px; color: #666;">{{ $data->nis }}</div>
                    </td>
                    <td>{{ $data->jenis_pelanggaran }}</td>
                    <td style="text-align: center;">
                        @if($data->kategori == 'Ringan')
                            <span class="kategori-ringan">Ringan</span>
                        @elseif($data->kategori == 'Sedang')
                            <span class="kategori-sedang">Sedang</span>
                        @else
                            <span class="kategori-berat">Berat</span>
                        @endif
                    </td>
                    <td style="text-align: center; font-weight: bold;">{{ $data->poin }}</td>
                    <td>{{ $data->deskripsi ?: '-' }}</td>
                    <td>{{ $data->nama_petugas }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">
            <p>Tidak ada pelanggaran tercatat untuk kelas {{ $selectedKelas }} pada periode ini.</p>
        </div>
        @endif

        <div class="footer">
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
            <p>SIPS - Sistem Informasi Pelanggaran Siswa</p>
        </div>
    </div>
</body>
</html>

