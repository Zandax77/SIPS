<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Detail Pelanggaran Siswa - {{ $siswa->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
        @page { margin: 0; }

        /* Custom table styling */
        .custom-table {
            border-collapse: collapse;
            width: 100%;
        }
        .custom-table th,
        .custom-table td {
            border: 1px solid #374151;
            padding: 8px;
        }
        .custom-table th {
            background-color: #f3f4f6;
            color: #1f2937;
            font-weight: 600;
            text-align: center;
        }
        .custom-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .custom-table tr:hover {
            background-color: #f3f4f6;
        }

        /* Header styling */
        .header-border {
            border-bottom: 3px solid #000;
        }

        /* Signature boxes */
        .signature-box {
            border-top: 1px solid #9ca3af;
            padding-top: 10px;
        }
    </style>
</head>
<body class="bg-white" onload="window.print()">
    <!-- Print Button (Hidden when printing) -->
    <div class="no-print fixed top-4 right-4 z-50" style="display: none;">
        <a href="{{ route('laporan.per-siswa') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 shadow-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <div class="max-w-4xl mx-auto bg-white shadow-lg my-8 mx-4">
        <!-- Header / Kop Sekolah -->
        <div class="header-border px-8 py-6">
            <table class="w-full">
                <tr>
                    <td class="w-20 align-middle">
                        <div class="w-16 h-16 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </td>
                    <td class="text-center align-middle">
                        <h1 class="text-2xl font-bold text-gray-800 uppercase tracking-wide">{{ $namaSekolah }}</h1>
                        <p class="text-gray-600 mt-1">LAPORAN DETAIL PELANGGARAN SISWA</p>
                        <p class="text-gray-500 text-sm">Tanggal: {{ $tanggal }}</p>
                    </td>
                    <td class="w-20"></td>
                </tr>
            </table>
        </div>

        <!-- Info Siswa -->
        <div class="px-8 py-4 bg-gray-50" style="border-bottom: 2px solid #000;">
            <table class="w-full">
                <tr>
                    <td class="py-1 text-gray-700 w-32 font-semibold">Nama Siswa</td>
                    <td class="py-1 font-bold text-gray-900">: {{ $siswa->name }}</td>
                    <td class="py-1 text-gray-700 w-24 font-semibold">NIS</td>
                    <td class="py-1 font-bold text-gray-900">: {{ $siswa->nis }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-700 font-semibold">Kelas</td>
                    <td class="py-1 font-bold text-gray-900">: {{ $siswa->kelas }}</td>
                    <td class="py-1 text-gray-700 font-semibold">Total Poin</td>
                    <td class="py-1 font-bold text-xl
                        @if($totalPoin == 0) text-green-700
                        @elseif($totalPoin <= 10) text-yellow-700
                        @else text-red-700 @endif">
                        : {{ $totalPoin }} Poin
                    </td>
                </tr>
            </table>
        </div>

        <!-- Stats by Kategori -->
        @if(count($statsByKategori) > 0)
        <div class="px-8 py-4" style="border-bottom: 1px solid #e5e7eb;">
            <p class="text-sm font-semibold text-gray-700 mb-3">Ringkasan per Kategori:</p>
            <table class="w-full">
                <tr>
                    @foreach($statsByKategori as $stat)
                    <td class="px-3 py-2 rounded-lg text-center font-semibold
                        @if($stat->kategori == 'Ringan') bg-green-100 text-green-800
                        @elseif($stat->kategori == 'Sedang') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif"
                        style="@if($stat->kategori == 'Ringan') background-color: #dcfce7; color: #166534;
                        @elseif($stat->kategori == 'Sedang') background-color: #fef3c7; color: #92400e;
                        @else background-color: #fee2e2; color: #991b1b; @endif">
                        {{ $stat->kategori }}: {{ $stat->jumlah }} ({{ $stat->total_poin }} poin)
                    </td>
                    @endforeach
                </tr>
            </table>
        </div>
        @endif

        <!-- Tabel Riwayat Pelanggaran -->
        <div class="px-8 py-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4" style="border-left: 4px solid #4f46e5; padding-left: 12px;">
                RIWAYAT PELANGGARAN
            </h3>

            @if(count($pelanggaranDetail) > 0)
            <table class="custom-table">
                <thead>
                    <tr style="background-color: #e5e7eb;">
                        <th class="px-3 py-2 text-center" style="width: 40px;">No</th>
                        <th class="px-3 py-2 text-center" style="width: 80px;">Tanggal</th>
                        <th class="px-3 py-2 text-left">Jenis Pelanggaran</th>
                        <th class="px-3 py-2 text-center" style="width: 80px;">Kategori</th>
                        <th class="px-3 py-2 text-center" style="width: 60px;">Poin</th>
                        <th class="px-3 py-2 text-left">Deskripsi</th>
                        <th class="px-3 py-2 text-center" style="width: 100px;">Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pelanggaranDetail as $index => $data)
                    <tr>
                        <td class="px-3 py-2 text-center">{{ $index + 1 }}</td>
                        <td class="px-3 py-2 text-center">
                            {{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') }}
                        </td>
                        <td class="px-3 py-2 font-medium">{{ $data->jenis_pelanggaran }}</td>
                        <td class="px-3 py-2 text-center">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($data->kategori == 'Ringan') text-green-800
                                @elseif($data->kategori == 'Sedang') text-yellow-800
                                @else text-red-800 @endif"
                                style="@if($data->kategori == 'Ringan') background-color: #dcfce7;
                                @elseif($data->kategori == 'Sedang') background-color: #fef3c7;
                                @else background-color: #fee2e2; @endif">
                                {{ $data->kategori }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-center font-semibold">
                            @if($data->sudah_diampuni)
                                <span class="text-gray-400 line-through">{{ $data->poin }}</span>
                                <span class="text-green-600 text-xs block">Diampuni</span>
                            @elseif($data->poin_dikurangi > 0)
                                <span class="text-gray-400 line-through">{{ $data->poin }}</span>
                                <span class="text-yellow-600 text-xs block">-{{ $data->poin_dikurangi }}</span>
                            @else
                                <span class="@if($data->kategori == 'Ringan') text-green-700 @elseif($data->kategori == 'Sedang') text-yellow-700 @else text-red-700 @endif">
                                    {{ $data->poin }}
                                </span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-gray-600">
                            {{ $data->deskripsi ?: '-' }}
                        </td>
                        <td class="px-3 py-2 text-center text-gray-600">
                            {{ $data->nama_petugas }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #e5e7eb; font-weight: bold;">
                        <td colspan="4" class="px-3 py-2 text-right">TOTAL POIN:</td>
                        <td class="px-3 py-2 text-center text-lg
                            @if($totalPoin == 0) text-green-700
                            @elseif($totalPoin <= 10) text-yellow-700
                            @else text-red-700 @endif">
                            {{ $totalPoin }}
                        </td>
                        <td colspan="2" class="px-3 py-2"></td>
                    </tr>
                </tfoot>
            </table>
            @else
            <div class="text-center py-8 text-gray-500">
                <p>Tidak ada pelanggaran tercatat</p>
            </div>
            @endif
        </div>

        <!-- Catatan Tindakan / Riwayat Pengampunan -->
        @if(count($allPengampunan) > 0)
        <div class="px-8 py-6" style="background-color: #fffbeb; border-top: 2px solid #000;">
            <h3 class="text-lg font-bold text-gray-800 mb-4" style="border-left: 4px solid #f59e0b; padding-left: 12px;">
                CATATAN TINDAKAN / RIWAYAT PENGAMPUNAN
            </h3>
            <table class="custom-table">
                <thead>
                    <tr style="background-color: #fef3c7;">
                        <th class="px-3 py-2 text-center" style="width: 40px;">No</th>
                        <th class="px-3 py-2 text-left">Pelanggaran</th>
                        <th class="px-3 py-2 text-center">Tipe</th>
                        <th class="px-3 py-2 text-center">Poin</th>
                        <th class="px-3 py-2 text-left">Alasan</th>
                        <th class="px-3 py-2 text-center">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allPengampunan as $index => $action)
                    <tr>
                        <td class="px-3 py-2 text-center">{{ $index + 1 }}</td>
                        <td class="px-3 py-2">{{ $action['jenis_pelanggaran'] }}</td>
                        <td class="px-3 py-2 text-center">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($action['tipe'] == 'pengampunan') text-green-800
                                @else text-yellow-800 @endif"
                                style="@if($action['tipe'] == 'pengampunan') background-color: #dcfce7;
                                @else background-color: #fef3c7; @endif">
                                {{ $action['tipe'] == 'pengampunan' ? 'Pengampunan' : 'Pengurangan' }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-center font-semibold
                            @if($action['tipe'] == 'pengampunan') text-green-700
                            @else text-yellow-700 @endif">
                            {{ $action['tipe'] == 'pengampunan' ? $action['poin_asli'] : $action['poin_dikurangi'] }}
                        </td>
                        <td class="px-3 py-2 text-gray-600">{{ $action['alasan'] }}</td>
                        <td class="px-3 py-2 text-center text-gray-600">
                            {{ \Carbon\Carbon::parse($action['created_at'])->format('d/m/Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Tanda Tangan -->
        <div class="px-8 py-8" style="border-top: 2px solid #000;">
            <table class="w-full">
                <tr>
                    <td class="w-1/2 px-4 text-center align-bottom">
                        <p class="text-gray-600 mb-16">Wali Kelas</p>
                        <div class="signature-box">
                            <p class="font-semibold text-gray-800">{{ $siswa->kelas }}</p>
                        </div>
                    </td>
                    <td class="w-1/2 px-4 text-center align-bottom">
                        <p class="text-gray-600 mb-16">Guru BK / Konseler</p>
                        <div class="signature-box">
                            <p class="font-semibold text-gray-800">________________</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="px-8 py-4 bg-gray-100 text-center" style="border-top: 1px solid #d1d5db;">
            <p class="text-xs text-gray-500">
                Dicetak pada: {{ date('d F Y H:i:s') }} | Sistem Informasi Pelanggaran Siswa (SIPS)
            </p>
        </div>
    </div>

    <!-- JavaScript for print handling -->
    <script>
        window.addEventListener('afterprint', function() {
            // Optional: redirect back after printing
        });
    </script>
</body>
</html>

