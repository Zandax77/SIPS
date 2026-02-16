<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Siswa - {{ $siswa->name }} - SIPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        indigo: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        },
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
                        <a href="{{ route('siswa.poin') }}" class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="hidden sm:inline">Kembali</span>
                        </a>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-xl font-bold text-gray-800">SIPS</span>
                                <p class="text-xs text-gray-500">Detail Siswa</p>
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
                                <p class="text-sm font-semibold text-gray-800">{{ $namaPetugas }}</p>
                                <p class="text-xs text-gray-500">{{ $jabatan }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="ml-2">
                                @csrf
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
            <!-- Page Header -->
            <div class="mb-8 animate-fade-in">
                <h1 class="text-2xl font-bold text-gray-800">Detail Pelanggaran Siswa</h1>
                <p class="text-gray-500 mt-1">Riwayat pelanggaran dan total poin</p>
            </div>

            <!-- Student Info Card -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 mb-6 animate-fade-in delay-100">
                <div class="flex flex-col md:flex-row md:items-center gap-6">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 rounded-2xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $siswa->name }}</h2>
                        <div class="flex flex-wrap gap-4 mt-2">
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                <span class="font-medium">NIS: {{ $siswa->nis }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="font-medium">Kelas: {{ $siswa->kelas }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Poin -->
                    <div class="flex flex-col items-end">
                        <p class="text-sm text-gray-500">Total Poin</p>
                        <p class="text-4xl font-bold {{ $totalPoin > 10 ? 'text-rose-600' : ($totalPoin > 0 ? 'text-amber-600' : 'text-emerald-600') }}">
                            {{ $totalPoin }}
                        </p>
                        <p class="text-sm text-gray-400">poin</p>
                    </div>
                </div>
            </div>

            <!-- Stats by Category -->
            @if(count($statsByKategori) > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-fade-in delay-200">
                @foreach($statsByKategori as $stat)
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ $stat->kategori }}</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stat->jumlah }} pelanggaran</p>
                            <p class="text-sm text-gray-400">{{ $stat->total_poin }} poin</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl {{ $stat->kategori == 'Ringan' ? 'bg-emerald-100' : ($stat->kategori == 'Sedang' ? 'bg-amber-100' : 'bg-rose-100') }} flex items-center justify-center">
                            @if($stat->kategori == 'Ringan')
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            @elseif($stat->kategori == 'Sedang')
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            @else
                            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- AI Suggestions Panel -->
            @if(isset($aiSuggestions) && $totalPoin > 0)
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 shadow-lg shadow-indigo-200/50 border border-indigo-100 mb-8 animate-fade-in delay-200">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-bold text-gray-800">💡 Saran Tindakan AI</h3>
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if($aiSuggestions['priority_level'] <= 2) bg-emerald-100 text-emerald-700
                                @elseif($aiSuggestions['priority_level'] == 3) bg-amber-100 text-amber-700
                                @else bg-rose-100 text-rose-700 @endif">
                                Prioritas {{ $aiSuggestions['priority_level'] }}/5
                            </span>
                        </div>

                        <!-- Recommendation Card -->
                        <div class="bg-white rounded-xl p-4 mb-4 border-l-4
                            @if($aiSuggestions['recommendation']['color'] == 'emerald') border-emerald-500
                            @elseif($aiSuggestions['recommendation']['color'] == 'blue') border-blue-500
                            @elseif($aiSuggestions['recommendation']['color'] == 'amber') border-amber-500
                            @elseif($aiSuggestions['recommendation']['color'] == 'orange') border-orange-500
                            @else border-rose-500 @endif">
                            <h4 class="font-semibold text-gray-800">{{ $aiSuggestions['recommendation']['title'] }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $aiSuggestions['recommendation']['description'] }}</p>
                        </div>

                        <!-- Analysis Text -->
                        <div class="bg-white/60 rounded-lg p-3 mb-4">
                            <p class="text-sm text-gray-700">{!! nl2br(e($aiSuggestions['analysis'])) !!}</p>
                        </div>

                        <!-- ========== BARU: Violation Analysis Section ========== -->
                        @if(isset($aiSuggestions['violation_analysis']))
                        <div class="bg-white rounded-xl p-4 mb-4 border border-indigo-100">
                            <h4 class="font-semibold text-gray-800 mb-3">📊 Analisis Pelanggaran</h4>

                            <!-- Nama Pelanggaran Terbanyak -->
                            @if($aiSuggestions['violation_analysis']['nama_pelanggaran_terbanyak'])
                            <div class="mb-3">
                                <span class="text-xs font-medium text-gray-500">Pelanggaran Tertinggi:</span>
                                <p class="text-sm font-medium text-indigo-700">
                                    {{ $aiSuggestions['violation_analysis']['nama_pelanggaran_terbanyak'] }}
                                    ({{ $aiSuggestions['violation_analysis']['jumlah_terbanyak'] }}x)
                                </p>
                            </div>
                            @endif

                            <!-- Pelanggaran Berulang Warning -->
                            @if($aiSuggestions['violation_analysis']['pelanggaran_berulang'])
                            <div class="mb-3 p-3 bg-amber-50 rounded-lg border border-amber-200">
                                <div class="flex items-center gap-2 text-amber-700 mb-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <span class="font-medium text-sm">Pelanggaran Berulang Terdeteksi!</span>
                                </div>
                                <ul class="text-xs text-amber-700 space-y-1">
                                    @foreach($aiSuggestions['violation_analysis']['detail_pelanggaran_berulang'] as $nama => $data)
                                    <li>• {{ $nama }} - {{ $data['jumlah'] }}x</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <!-- Kata Kunci Deskripsi -->
                            @if(count($aiSuggestions['violation_analysis']['kata_kunci_deskripsi']) > 0)
                            <div>
                                <span class="text-xs font-medium text-gray-500">Kata Kunci Dominan:</span>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($aiSuggestions['violation_analysis']['kata_kunci_deskripsi'] as $keyword => $count)
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs">
                                        {{ $keyword }} ({{ $count }})
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Action Items -->
                        @if(count($aiSuggestions['action_items']) > 0)
                        <div class="space-y-2">
                            <p class="text-sm font-medium text-gray-700">📋 Tindakan yang Direkomendasikan:</p>
                            @foreach($aiSuggestions['action_items'] as $index => $action)
                            <div class="flex items-start gap-2 bg-white rounded-lg p-3 border border-gray-100">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                                    @if($action['priority'] == 'critical') bg-rose-600 text-white
                                    @elseif($action['priority'] == 'high') bg-amber-500 text-white
                                    @elseif($action['priority'] == 'medium') bg-blue-500 text-white
                                    @else bg-gray-400 text-white @endif">
                                    {{ $index + 1 }}
                                </span>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800">{{ $action['title'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $action['description'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Trend Indicator -->
                        @if($aiSuggestions['trend'] !== 'stabil' && $totalPoin > 0)
                        <div class="mt-4 flex items-center gap-2 text-sm">
                            <span class="font-medium text-gray-600">Tren:</span>
                            @if($aiSuggestions['trend'] === 'meningkat')
                            <span class="px-2 py-1 rounded bg-rose-100 text-rose-700 font-medium">↑ Meningkat - Perlu Intervensi!</span>
                            @elseif($aiSuggestions['trend'] === 'menurun')
                            <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 font-medium">↓ Menurun - Tetap Pantau</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Table Section -->
            <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden animate-fade-in delay-300">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Riwayat Pelanggaran</h2>
                            <p class="text-sm text-gray-500">Daftar pelanggaran yang telah dicatat</p>
                        </div>
                    </div>
                </div>

                @if(count($pelanggaranDetail) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lampiran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($pelanggaranDetail as $index => $data)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->jenis_pelanggaran }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $data->kategori == 'Ringan' ? 'bg-emerald-100 text-emerald-700' :
                                                   ($data->kategori == 'Sedang' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                                {{ $data->kategori }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                                {{ $data->poin }} Poin
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $data->deskripsi }}">
                                            {{ $data->deskripsi ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($data->lampiran)
                                                @if($data->tipe_lampiran === 'foto')
                                                    <button onclick="viewAttachment({{ $data->id }}, 'foto')"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        Lihat Foto
                                                    </button>
                                                @else
                                                    <a href="{{ route('siswa.pelanggaran.download', $data->id) }}"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-purple-100 text-purple-700 hover:bg-purple-200 transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                        </svg>
                                                        Unduh
                                                    </a>
                                                @endif
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $data->nama_petugas }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($data->sudah_diampuni)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Diampuni
                                                </span>
                                            @elseif($data->poin_dikurangi > 0)
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                                        -{{ $data->poin_dikurangi }} Poin
                                                    </span>
                                                </div>
                                            @endif
                                            <button onclick="showTindakanModal({{ $data->id }})"
                                                class="ml-2 text-indigo-600 hover:text-indigo-800 text-sm font-medium hover:underline">
                                                Tindakan
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-4 text-gray-500">Tidak ada pelanggaran tercatat</p>
                    </div>
                @endif
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white/60 backdrop-blur-sm border-t border-gray-100 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} SIPS. All rights reserved.
                </p>
            </div>
        </footer>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeDetailModal()"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="bg-white rounded-2xl shadow-2xl m-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-indigo-50 to-white rounded-t-2xl">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Pelanggaran</h3>
                    <button onclick="closeDetailModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6" id="detailModalContent">
                    <!-- Content will be loaded dynamically -->
                    <div class="flex justify-center py-8">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Lightbox -->
    <div id="lightboxModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/90" onclick="closeLightbox()"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 max-w-4xl max-h-[90vh] w-full">
            <div class="flex flex-col items-center">
                <button onclick="closeLightbox()" class="absolute top-4 right-4 p-2 text-white/70 hover:text-white hover:bg-white/10 rounded-lg transition-colors z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <img id="lightboxImage" src="" alt="Lampiran Foto" class="max-h-[80vh] w-auto rounded-lg shadow-2xl">
                <div class="mt-4 flex gap-3">
                    <a id="lightboxDownloadBtn" href="#" download class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Unduh
                    </a>
                    <button onclick="closeLightbox()" class="px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tindakan Modal (Pengampunan & Pengurangan Poin) -->
    <div id="tindakanModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeTindakanModal()"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="bg-white rounded-2xl shadow-2xl m-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-indigo-50 to-white rounded-t-2xl">
                    <h3 class="text-lg font-semibold text-gray-800">Tindakan Pelanggaran</h3>
                    <button onclick="closeTindakanModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6" id="tindakanModalContent">
                    <!-- Content will be loaded dynamically -->
                    <div class="flex justify-center py-8">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Store pelanggaran data globally for modal access
        const pelanggaranData = @json($pelanggaranDetail);

        // Show detail modal
        function showDetailModal(id) {
            const data = pelanggaranData.find(p => p.id === id);
            if (!data) return;

            const modalContent = document.getElementById('detailModalContent');

            const kategoriClass = data.kategori === 'Ringan' ? 'bg-emerald-100 text-emerald-700' :
                                  (data.kategori === 'Sedang' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700');

            let lampiranHtml = '-';
            if (data.lampiran) {
                if (data.tipe_lampiran === 'foto') {
                    lampiranHtml = `
                        <button onclick="viewAttachment(${data.id}, 'foto')" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Lihat Foto
                        </button>
                    `;
                } else {
                    lampiranHtml = `
                        <a href="/siswa/pelanggaran/download/${data.id}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-purple-100 text-purple-700 hover:bg-purple-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Unduh Dokumen
                        </a>
                    `;
                }
            }

            modalContent.innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Siswa</label>
                            <p class="text-gray-800 font-medium">{{ $siswa->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">NIS</label>
                            <p class="text-gray-800">{{ $siswa->nis }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Kelas</label>
                            <p class="text-gray-800">{{ $siswa->kelas }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Jenis Pelanggaran</label>
                            <p class="text-gray-800 font-medium">${data.jenis_pelanggaran}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Kategori</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${kategoriClass}">
                                ${data.kategori}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Poin</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                ${data.poin} Poin
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Deskripsi</label>
                        <p class="text-gray-800">${data.deskripsi || '-'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Lampiran</label>
                        <div class="mt-1">${lampiranHtml}</div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Petugas</label>
                            <p class="text-gray-800">${data.nama_petugas}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Waktu</label>
                            <p class="text-gray-800">${new Date(data.created_at).toLocaleString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</p>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('detailModal').classList.remove('hidden');
        }

        // Close detail modal
        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // View attachment (photo lightbox)
        function viewAttachment(id, type) {
            if (type === 'foto') {
                const img = document.getElementById('lightboxImage');
                const downloadBtn = document.getElementById('lightboxDownloadBtn');

                img.src = '/siswa/pelanggaran/lampiran/' + id;
                downloadBtn.href = '/siswa/pelanggaran/download/' + id;

                document.getElementById('lightboxModal').classList.remove('hidden');
            }
        }

        // Close lightbox
        function closeLightbox() {
            document.getElementById('lightboxModal').classList.add('hidden');
        }

        // Close modals on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDetailModal();
                closeLightbox();
                closeTindakanModal();
            }
        });

        // Show Tindakan Modal (for pengampunan/pengurangan)
        function showTindakanModal(id) {
            const modalContent = document.getElementById('tindakanModalContent');
            modalContent.innerHTML = `
                <div class="flex justify-center py-8">
                    <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            `;
            document.getElementById('tindakanModal').classList.remove('hidden');

            // Fetch pelanggaran detail
            fetch('/api/pelanggaran/' + id + '/detail')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        modalContent.innerHTML = `
                            <div class="text-center py-4">
                                <p class="text-red-600">${data.error}</p>
                            </div>
                        `;
                        return;
                    }

                    const p = data.pelanggaran;
                    const riwayat = data.riwayat_pengampunan;

                    let actionButtons = '';
                    let riwayatHtml = '';

                    if (p.sudah_diampuni) {
                        actionButtons = `
                            <div class="bg-gray-100 rounded-lg p-4 text-center">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-gray-600 font-medium">Pelanggaran ini sudah diampuni</p>
                            </div>
                        `;
                    } else {
                        actionButtons = `
                            <!-- Tab Buttons -->
                            <div class="flex border-b border-gray-200 mb-4">
                                <button type="button" onclick="switchTab('pengampunan')" id="tab-pengampunan" class="flex-1 py-2 px-4 text-center font-medium text-indigo-600 border-b-2 border-indigo-600">
                                    Pengampunan
                                </button>
                                <button type="button" onclick="switchTab('pengurangan')" id="tab-pengurangan" class="flex-1 py-2 px-4 text-center font-medium text-gray-500 hover:text-indigo-600">
                                    Pengurangan Poin
                                </button>
                            </div>

                            <!-- Pengampunan Form -->
                            <div id="form-pengampunan">
                                <form action="{{ route('pelanggaran.pengampunan') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_pelanggaran" value="${p.id}">

                                    <div class="bg-emerald-50 rounded-lg p-4 mb-4">
                                        <div class="flex items-center gap-2 text-emerald-700 mb-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="font-medium">Pengampunan Penuh</span>
                                        </div>
                                        <p class="text-sm text-emerald-600">Menghapus seluruh pelanggaran dan poin (${p.poin} poin)</p>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Pengampunan</label>
                                        <textarea name="alasan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan alasan pengampunan..." required></textarea>
                                    </div>

                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                        Simpan Pengampunan
                                    </button>
                                </form>
                            </div>

                            <!-- Pengurangan Poin Form -->
                            <div id="form-pengurangan" class="hidden">
                                <form action="{{ route('pelanggaran.pengurangan-poin') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_pelanggaran" value="${p.id}">

                                    <div class="bg-amber-50 rounded-lg p-4 mb-4">
                                        <div class="flex items-center gap-2 text-amber-700 mb-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 0 9 11-18 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="font-medium">Pengurangan Poin</span>
                                        </div>
                                        <p class="text-sm text-amber-600">Mengurangi sebagian poin pelanggaran (Sisa: <span id="poin-sisa">${p.poin_sisa}</span> poin)</p>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Poin yang Dikurangi</label>
                                        <input type="number" name="poin_dikurangi" id="poin_dikurangi" min="1" max="${p.poin_sisa}" value="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <p class="text-xs text-gray-500 mt-1">Maksimal ${p.poin_sisa} poin</p>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Pengurangan</label>
                                        <textarea name="alasan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan alasan pengurangan poin..." required></textarea>
                                    </div>

                                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                        Simpan Pengurangan Poin
                                    </button>
                                </form>
                            </div>
                        `;
                    }

                    // Build riwayat HTML
                    if (riwayat && riwayat.length > 0) {
                        riwayatHtml = `
                            <div class="mt-6 border-t border-gray-200 pt-4">
                                <h4 class="font-medium text-gray-800 mb-3">Riwayat Tindakan</h4>
                                <div class="space-y-2 max-h-40 overflow-y-auto">
                                    ${riwayat.map(r => `
                                        <div class="bg-gray-50 rounded-lg p-3 text-sm">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium ${r.tipe === 'pengampunan' ? 'text-emerald-600' : 'text-amber-600'}">
                                                    ${r.tipe === 'pengampunan' ? 'Pengampunan' : 'Pengurangan Poin'}
                                                </span>
                                                <span class="text-gray-500">${r.poin_dikurangi} poin</span>
                                            </div>
                                            <p class="text-gray-600 mt-1">${r.alasan}</p>
                                            <p class="text-xs text-gray-400 mt-1">${new Date(r.created_at).toLocaleString('id-ID')}</p>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    }

                    modalContent.innerHTML = `
                        <div class="space-y-4">
                            <!-- Pelanggaran Info -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div>
                                        <span class="text-gray-500">Pelanggaran:</span>
                                        <p class="font-medium text-gray-800">${p.jenis_pelanggaran}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Kategori:</span>
                                        <p class="font-medium text-gray-800">${p.kategori}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Poin Asli:</span>
                                        <p class="font-medium text-gray-800">${p.poin}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Poin Sisa:</span>
                                        <p class="font-medium ${p.poin_sisa > 0 ? 'text-amber-600' : 'text-emerald-600'}">${p.poin_sisa}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            ${actionButtons}

                            ${riwayatHtml}
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalContent.innerHTML = `
                        <div class="text-center py-4">
                            <p class="text-red-600">Terjadi kesalahan saat memuat data</p>
                        </div>
                    `;
                });
        }

        // Close Tindakan Modal
        function closeTindakanModal() {
            document.getElementById('tindakanModal').classList.add('hidden');
        }

        // Switch tabs for tindakan form
        function switchTab(tab) {
            const pengampunanTab = document.getElementById('tab-pengampunan');
            const penguranganTab = document.getElementById('tab-pengurangan');
            const pengampunanForm = document.getElementById('form-pengampunan');
            const penguranganForm = document.getElementById('form-pengurangan');

            if (tab === 'pengampunan') {
                pengampunanTab.classList.add('text-indigo-600', 'border-b-2', 'border-indigo-600');
                pengampunanTab.classList.remove('text-gray-500');
                penguranganTab.classList.remove('text-indigo-600', 'border-b-2', 'border-indigo-600');
                penguranganTab.classList.add('text-gray-500');
                pengampunanForm.classList.remove('hidden');
                penguranganForm.classList.add('hidden');
            } else {
                penguranganTab.classList.add('text-indigo-600', 'border-b-2', 'border-indigo-600');
                penguranganTab.classList.remove('text-gray-500');
                pengampunanTab.classList.remove('text-indigo-600', 'border-b-2', 'border-indigo-600');
                pengampunanTab.classList.add('text-gray-500');
                penguranganForm.classList.remove('hidden');
                pengampunanForm.classList.add('hidden');
            }
        }
    </script>
</body>
</html>

