<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Per Siswa - {{ $namaSekolah }}</title>
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
            <h1 class="text-2xl font-bold text-gray-800">LAPORAN PELANGGARAN SISWA</h1>
            <h2 class="text-xl text-gray-600">{{ $namaSekolah }}</h2>
            <p class="text-gray-500 mt-2">Laporan per Siswa</p>
            <p class="text-gray-400">Tanggal: {{ $tanggal }}</p>
            @if($kelas || $search)
                <p class="text-gray-500 mt-1">
                    @if($kelas)Kelas: {{ $kelas }}@endif
                    @if($kelas && $search) | @endif
                    @if($search)Cari: {{ $search }}@endif
                </p>
            @endif
        </div>

        <!-- Table -->
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">No</th>
                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">NIS</th>
                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Nama Siswa</th>
                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Kelas</th>
                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Total Poin</th>
                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Jml Pelanggaran</th>
                    <th class="border border-gray-300 px-4 py-2 text-center text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswa as $index => $item)
                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                        <td class="border border-gray-300 px-4 py-2 text-sm">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">{{ $item->nis }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">{{ $item->nama_siswa }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm">{{ $item->kelas }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm text-center font-semibold
                            @if($item->total_poin == 0) text-emerald-600
                            @elseif($item->total_poin <= 10) text-amber-600
                            @else text-rose-600 @endif">
                            {{ $item->total_poin }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-sm text-center">{{ $item->total_pelanggaran }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-sm text-center">
                            <a href="{{ route('laporan.per-siswa.detail', $item->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="border border-gray-300 px-4 py-8 text-center text-gray-500">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-gray-100 font-semibold">
                    <td colspan="4" class="border border-gray-300 px-4 py-2 text-right">TOTAL</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ $siswa->sum('total_poin') }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ $siswa->sum('total_pelanggaran') }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">-</td>
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

