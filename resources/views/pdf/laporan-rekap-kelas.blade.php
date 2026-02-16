<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekap Kelas - {{ $namaSekolah }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
        @page { margin: 0; }

        .custom-table {
            border-collapse: collapse;
            width: 100%;
        }
        .custom-table th,
        .custom-table td {
            border: 1px solid #374151;
            padding: 6px 8px;
            font-size: 11px;
        }
        .custom-table th {
            background-color: #e5e7eb;
            color: #1f2937;
            font-weight: 600;
            text-align: center;
        }
        .custom-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .header-border {
            border-bottom: 3px solid #000;
        }

        .signature-box {
            border-top: 1px solid #9ca3af;
            padding-top: 10px;
        }
    </style>
</head>
<body class="bg-white" onload="window.print()">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header / Kop Sekolah -->
        <div class="header-border px-4 py-4 mb-4">
            <table class="w-full">
                <tr>
                    <td class="w-16 align-middle">
                        <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </td>
                    <td class="text-center align-middle">
                        <h1 class="text-xl font-bold text-gray-800 uppercase tracking-wide">{{ $namaSekolah }}</h1>
                        <p class="text-gray-600 font-semibold">LAPORAN REKAPITULASI PELANGGARAN SISWA</p>
                        <p class="text-gray-500 text-sm">Periode:
                            @if(!empty($tanggalMulai) && !empty($tanggalAkhir))
                                {{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}
                            @else
                                Semua Periode
                            @endif
                        </p>
                        <p class="text-gray-500 text-sm">Tanggal Cetak: {{ $tanggal }}</p>
                    </td>
                    <td class="w-16"></td>
                </tr>
            </table>
        </div>

        <!-- Summary Stats -->
        <div class="mb-4">
            <table class="w-full">
                <tr>
                    <td class="px-2 py-1 text-center">
                        <span class="inline-block px-3 py-1 rounded bg-green-100 text-green-800 text-xs font-semibold">
                            Ringan: {{ $totals['pelanggaran_ringan'] }}
                        </span>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <span class="inline-block px-3 py-1 rounded bg-yellow-100 text-yellow-800 text-xs font-semibold">
                            Sedang: {{ $totals['pelanggaran_sedang'] }}
                        </span>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <span class="inline-block px-3 py-1 rounded bg-red-100 text-red-800 text-xs font-semibold">
                            Berat: {{ $totals['pelanggaran_berat'] }}
                        </span>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <span class="inline-block px-3 py-1 rounded bg-gray-100 text-gray-800 text-xs font-semibold">
                            Total: {{ $totals['total_pelanggaran'] }}
                        </span>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <span class="inline-block px-3 py-1 rounded bg-indigo-100 text-indigo-800 text-xs font-bold">
                            Total Poin: {{ $totals['total_poin'] }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Data Table per Kelas with Student Details -->
        @forelse($rekap as $kelasData)
        <div class="mb-6">
            <h3 class="text-sm font-bold text-gray-800 mb-2" style="border-left: 4px solid #4f46e5; padding-left: 8px;">
                KELAS {{ $kelasData->kelas }}
                <span class="text-gray-500 font-normal">({{ $kelasData->total_siswa }} siswa, {{ $kelasData->total_pelanggaran }} pelanggaran)</span>
            </h3>

            <?php
            // Get detailed violations for this class
            $query = DB::table('pelanggarans')
                ->select(
                    'siswas.name as nama_siswa',
                    'siswas.nis',
                    'jenis_pelanggarans.nama as jenis_pelanggaran',
                    'kategori_pelanggarans.nama as kategori',
                    'kategori_pelanggarans.poin as poin',
                    'pelanggarans.created_at',
                    'pelanggarans.deskripsi'
                )
                ->join('siswas', 'pelanggarans.id_siswa', '=', 'siswas.id')
                ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
                ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
                ->where('siswas.kelas', $kelasData->kelas);

            if(!empty($tanggalMulai) && !empty($tanggalAkhir)) {
                $query->whereBetween('pelanggarans.created_at', [$tanggalMulai . ' 00:00:00', $tanggalAkhir . ' 23:59:59']);
            }

            $detailPelanggaran = $query->orderBy('pelanggarans.created_at', 'desc')->get();
            ?>

            @if(count($detailPelanggaran) > 0)
            <table class="custom-table">
                <thead>
                    <tr style="background-color: #e5e7eb;">
                        <th style="width: 30px;">No</th>
                        <th style="width: 80px;">NIS</th>
                        <th style="width: 120px;">Nama Siswa</th>
                        <th style="width: 150px;">Pelanggaran</th>
                        <th style="width: 60px;">Kategori</th>
                        <th style="width: 40px;">Poin</th>
                        <th style="width: 70px;">Tanggal</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detailPelanggaran as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $detail->nis }}</td>
                        <td class="font-medium">{{ $detail->nama_siswa }}</td>
                        <td>{{ $detail->jenis_pelanggaran }}</td>
                        <td class="text-center">
                            <span class="px-1 py-0.5 rounded text-xs font-semibold
                                @if($detail->kategori == 'Ringan') text-green-800
                                @elseif($detail->kategori == 'Sedang') text-yellow-800
                                @else text-red-800 @endif"
                                style="@if($detail->kategori == 'Ringan') background-color: #dcfce7;
                                @elseif($detail->kategori == 'Sedang') background-color: #fef3c7;
                                @else background-color: #fee2e2; @endif">
                                {{ $detail->kategori }}
                            </span>
                        </td>
                        <td class="text-center font-semibold
                            @if($detail->kategori == 'Ringan') text-green-700
                            @elseif($detail->kategori == 'Sedang') text-yellow-700
                            @else text-red-700 @endif">
                            {{ $detail->poin }}
                        </td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') }}</td>
                        <td class="text-gray-600">{{ $detail->deskripsi ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #e5e7eb; font-weight: bold;">
                        <td colspan="5" class="text-right">TOTAL POIN KELAS:</td>
                        <td class="text-center">{{ $kelasData->total_poin }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
            @else
            <p class="text-gray-500 text-sm italic">Tidak ada pelanggaran</p>
            @endif
        </div>
        @empty
        <div class="text-center py-8 text-gray-500">
            <p>Tidak ada data</p>
        </div>
        @endforelse

        <!-- Tanda Tangan -->
        <div class="mt-8 px-4">
            <table class="w-full">
                <tr>
                    <td class="w-1/2 px-4 text-center align-bottom">
                        <p class="text-gray-600 mb-16">Wali Kelas</p>
                        <div class="signature-box">
                            <p class="font-semibold text-gray-800">________________</p>
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
        <div class="mt-4 pt-4 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">
                Dicetak pada: {{ date('d F Y H:i:s') }} | Sistem Informasi Pelanggaran Siswa (SIPS)
            </p>
        </div>
    </div>
</body>
</html>

