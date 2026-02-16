<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekap Periode - {{ $namaSekolah }}</title>
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
            <h1 class="text-2xl font-bold text-gray-800">LAPORAN REKAP PELANGGARAN</h1>
            <h2 class="text-xl text-gray-600">{{ $namaSekolah }}</h2>
            <p class="text-gray-500 mt-2">Rekapitulasi per Periode</p>
            <p class="text-gray-400">Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}</p>
            <p class="text-gray-400">Tanggal Cetak: {{ $tanggal }}</p>
            @if($kelas)
                <p class="text-gray-500 mt-1">Kelas: {{ $kelas }}</p>
            @endif
        </div>

        <!-- Table -->
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">No</th>
                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Tanggal</th>
                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Siswa Pelaku</th>
                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Ringan</th>
                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Sedang</th>
                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Berat</th>
                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Total Pelanggaran</th>
                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Total Poin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekap as $index => $item)
                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                        <td class="border border-gray-300 px-4 py-2 text-sm text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm text-center">{{ $item->siswa_pelaku }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm text-center text-emerald-600">{{ $item->pelanggaran_ringan }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm text-center text-amber-600">{{ $item->pelanggaran_sedang }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm text-center text-rose-600">{{ $item->pelanggaran_berat }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm text-center font-semibold">{{ $item->total_pelanggaran }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm text-center font-bold">{{ $item->total_poin }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="border border-gray-300 px-4 py-8 text-center text-gray-500">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-gray-100 font-semibold">
                    <td colspan="2" class="border border-gray-300 px-4 py-2 text-right">TOTAL</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ $totals['siswa_pelaku'] }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center text-emerald-600">{{ $totals['pelanggaran_ringan'] }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center text-amber-600">{{ $totals['pelanggaran_sedang'] }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center text-rose-600">{{ $totals['pelanggaran_berat'] }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ $totals['total_pelanggaran'] }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center font-bold">{{ $totals['total_poin'] }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
            <p>Sistem Informasi Pelanggaran Siswa (SIPS)</p>
        </div>
    </div>
</body>
</html>

