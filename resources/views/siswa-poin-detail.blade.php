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
                        rose: { 500: '#f43f5e', 600: '#e11d48' },
                        orange: { 500: '#f97316', 600: '#ea580c' }
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
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-emerald-800">Berhasil!</p>
                            <p class="text-sm text-emerald-600">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-rose-50 border border-rose-200 rounded-xl p-4 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-rose-800">Gagal!</p>
                            <p class="text-sm text-rose-600">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Page Header -->
            <div class="mb-8 animate-fade-in">
                <h1 class="text-2xl font-bold text-gray-800">Detail Pelanggaran Siswa</h1>
                <p class="text-gray-500 mt-1">Riwayat pelanggaran dan total poin</p>
            </div>

            <!-- AI Suggestions Section -->
            @if(isset($aiSuggestions) && $totalPoin > 0)
            <div class="bg-gradient-to-r from-indigo-50 to-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-indigo-100 mb-6 animate-fade-in">
                <div class="flex items-start gap-4">
                    <!-- AI Icon -->
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-lg font-semibold text-gray-800">Saran Tindakan AI</h2>
                            <!-- Priority Badge -->
                            @if($aiSuggestions['priority'] === 'critical')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                PRIORITAS KRITIS
                            </span>
                            @elseif($aiSuggestions['priority'] === 'serious')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700 border border-orange-200">
                                PRIORITAS TINGGI
                            </span>
                            @elseif($aiSuggestions['priority'] === 'medium')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                PRIORITAS SEDANG
                            </span>
                            @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                PEMANTAUAN
                            </span>
                            @endif
                        </div>
                        
                        <!-- Level Info -->
                        <div class="mb-4 p-3 rounded-lg bg-white/60 border border-gray-100">
                            <div class="flex items-center gap-2">
                                @if($aiSuggestions['level']['color'] === 'rose')
                                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span class="font-medium text-rose-700">{{ $aiSuggestions['level']['name'] }}</span>
                                @elseif($aiSuggestions['level']['color'] === 'orange')
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span class="font-medium text-orange-700">{{ $aiSuggestions['level']['name'] }}</span>
                                @elseif($aiSuggestions['level']['color'] === 'amber')
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span class="font-medium text-amber-700">{{ $aiSuggestions['level']['name'] }}</span>
                                @else
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium text-blue-700">{{ $aiSuggestions['level']['name'] }}</span>
                                @endif
                                <span class="text-gray-500 text-sm">- {{ $aiSuggestions['level']['description'] }}</span>
                            </div>
                        </div>

                        <!-- Suggestions List -->
                        <div class="space-y-3">
                            @foreach($aiSuggestions['suggestions'] as $suggestion)
                            <div class="p-4 rounded-xl border transition-all duration-200 hover:shadow-md
                                @if($suggestion['type'] === 'critical' || $suggestion['type'] === 'serious')
                                    bg-rose-50 border-rose-200
                                @elseif($suggestion['type'] === 'high')
                                    bg-orange-50 border-orange-200
                                @elseif($suggestion['type'] === 'medium')
                                    bg-amber-50 border-amber-200
                                @elseif($suggestion['type'] === 'light')
                                    bg-blue-50 border-blue-200
                                @elseif($suggestion['type'] === 'positive')
                                    bg-emerald-50 border-emerald-200
                                @else
                                    bg-gray-50 border-gray-200
                                @endif
                            ">
                                <div class="flex items-start gap-3">
                                    <!-- Icon based on type -->
                                    @if($suggestion['type'] === 'critical' || $suggestion['type'] === 'serious')
                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    @elseif($suggestion['type'] === 'high')
                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    @elseif($suggestion['type'] === 'positive')
                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    @else
                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    @endif

                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-800">{{ $suggestion['title'] }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $suggestion['description'] }}</p>
                                        <div class="mt-2 p-2 rounded-lg bg-white/70">
                                            <p class="text-sm font-medium text-indigo-700">
                                                <span class="text-indigo-500">→</span> {{ $suggestion['action'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

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

            <!-- Evidence Photos Button (for Berat violations) -->
            @if($hasEvidencePhotos && count($violationsWithPhotos) > 0)
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 mb-6 animate-fade-in">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Bukti Foto/Dokumen</h3>
                            <p class="text-sm text-gray-500">{{ count($violationsWithPhotos) }} pelanggaran dengan bukti</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('photoModal').classList.remove('hidden')" 
                        class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Lihat Bukti
                    </button>
                </div>
            </div>
            @endif

            <!-- Action Tracking Section -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 mb-6 animate-fade-in">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Riwayat Tindakan</h3>
                                <p class="text-sm text-gray-500">Catatan tindakan yang telah diambil</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="document.getElementById('tindakanModal').classList.remove('hidden')" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Catat Tindakan
                            </button>
                            <a href="{{ route('siswa.tindakan.cetak', $siswa->id) }}" target="_blank" class="px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Cetak
                            </a>
                        </div>
                    </div>

                @if(isset($tindakanSiswa) && count($tindakanSiswa) > 0)
                    <div class="space-y-4">
                        @foreach($tindakanSiswa as $index => $tindakan)
                        <div class="p-4 rounded-xl border 
                            @if($tindakan->hasil_tindakan === 'Berhasil')
                                bg-emerald-50 border-emerald-200
                            @elseif($tindakan->hasil_tindakan === 'Tidak Berhasil')
                                bg-rose-50 border-rose-200
                            @elseif($tindakan->hasil_tindakan === 'Sedang Berlangsung')
                                bg-amber-50 border-amber-200
                            @else
                                bg-gray-50 border-gray-200
                            @endif
                        ">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-semibold text-gray-800">{{ $tindakan->jenis_tindakan }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                            @if($tindakan->hasil_tindakan === 'Berhasil')
                                                bg-emerald-100 text-emerald-700
                                            @elseif($tindakan->hasil_tindakan === 'Tidak Berhasil')
                                                bg-rose-100 text-rose-700
                                            @elseif($tindakan->hasil_tindakan === 'Sedang Berlangsung')
                                                bg-amber-100 text-amber-700
                                            @else
                                                bg-gray-100 text-gray-700
                                            @endif
                                        ">
                                            {{ $tindakan->hasil_tindakan }}
                                        </span>
                                    </div>
                                    @if($tindakan->deskripsi_tindakan)
                                    <p class="text-sm text-gray-600 mb-2">{{ $tindakan->deskripsi_tindakan }}</p>
                                    @endif
                                    @if($tindakan->catatan_hasil)
                                    <p class="text-sm text-gray-500 italic">Catatan: {{ $tindakan->catatan_hasil }}</p>
                                    @endif
                                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                        <span>Tanggal: {{ \Carbon\Carbon::parse($tindakan->tanggal_tindakan)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button onclick="editTindakan({{ $tindakan->id }}, '{{ $tindakan->jenis_tindakan }}', '{{ $tindakan->deskripsi_tindakan }}', '{{ $tindakan->hasil_tindakan }}', '{{ $tindakan->catatan_hasil }}', '{{ $tindakan->tanggal_tindakan }}')" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <form action="{{ route('siswa.tindakan.delete', ['id' => $siswa->id, 'tindakanId' => $tindakan->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tindakan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-xl">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="mt-4 text-gray-500">Belum ada tindakan yang dicatat</p>
                    </div>
                @endif
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden animate-fade-in delay-300">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                            <div class="flex items-center justify-between">
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
                                <div class="flex items-center gap-2">
                                <a href="{{ route('siswa.pelanggaran.cetak', $siswa->id) }}" target="_blank" class="px-3 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors font-medium flex items-center gap-2 text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                        Cetak
                                    </a>
                                </div>
                            </div>
                </div>

                @if(count($pelanggaranDetail) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 sticky top-0 z-10">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
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
                                            @if($data->bukti_foto)
                                            <button onclick="showEvidence('{{ $data->id }}')" class="p-2 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors" title="Lihat Bukti">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>
                                            @else
                                            <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $data->nama_petugas }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y H:i') }}
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

    <!-- Photo Evidence Modal -->
    @if($hasEvidencePhotos && count($violationsWithPhotos) > 0)
    <div id="photoModal" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="document.getElementById('photoModal').classList.add('hidden')"></div>
        
        <!-- Modal Content -->
        <div class="absolute inset-4 md:inset-10 bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-rose-50 to-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Bukti Foto/Dokumen Pelanggaran</h3>
                        <p class="text-sm text-gray-500">Pelanggaran Berat/Sedang</p>
                    </div>
                </div>
                <button onclick="document.getElementById('photoModal').classList.add('hidden')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($violationsWithPhotos as $violation)
                    <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
                        <!-- Photo/Document -->
                        <div class="aspect-video bg-gray-200 relative">
                            @if($violation->bukti_foto)
                                @php
                                    $extension = pathinfo($violation->bukti_foto, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp
                                
                                @if($isImage)
                                    <img src="{{ asset('storage/' . $violation->bukti_foto) }}" 
                                         alt="Bukti Pelanggaran" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-red-50">
                                        <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="mt-2 text-sm text-red-600 font-medium uppercase">{{ $extension }}</span>
                                    </div>
                                @endif
                                
                                <!-- Category Badge -->
                                <span class="absolute top-2 right-2 px-2 py-1 rounded-lg text-xs font-bold
                                    {{ $violation->kategori == 'Berat' ? 'bg-rose-500 text-white' : 'bg-amber-500 text-white' }}">
                                    {{ $violation->kategori }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Info -->
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-800">{{ $violation->jenis_pelanggaran }}</h4>
                            <div class="mt-2 flex items-center justify-between text-sm">
                                <span class="px-2 py-1 rounded-lg bg-indigo-100 text-indigo-700 font-medium">
                                    {{ $violation->poin }} Poin
                                </span>
                                <span class="text-gray-500">
                                    {{ \Carbon\Carbon::parse($violation->created_at)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Single Evidence Modal -->
    <div id="singlePhotoModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeSingleModal()"></div>
        <div class="absolute inset-4 md:inset-20 bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Bukti Pelanggaran</h3>
                <button onclick="closeSingleModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-auto p-6" id="singlePhotoContent">
                <!-- Content loaded via JS -->
            </div>
        </div>
    </div>

    <script>
        // Store violations data for single modal
        const violationsData = @json($pelanggaranDetail->keyBy('id'));

        function showEvidence(id) {
            const violation = violationsData[id];
            if (!violation || !violation.bukti_foto) return;

            const modal = document.getElementById('singlePhotoModal');
            const content = document.getElementById('singlePhotoContent');
            
            const extension = violation.bukti_foto.split('.').pop().toLowerCase();
            const isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(extension);

            let html = '';
            if (isImage) {
                html = `
                    <div class="flex flex-col items-center">
                        <img src="/storage/${violation.bukti_foto}" alt="Bukti Pelanggaran" class="max-w-full max-h-[70vh] rounded-lg shadow-lg">
                    </div>
                `;
            } else {
                html = `
                    <div class="flex flex-col items-center justify-center h-full">
                        <svg class="w-24 h-24 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-4 text-lg font-medium text-gray-700 uppercase">${extension} Document</p>
                        <a href="/storage/${violation.bukti_foto}" target="_blank" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Buka File</a>
                    </div>
                `;
            }

            html += `
                <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Pelanggaran</p>
                            <p class="font-medium text-gray-800">${violation.jenis_pelanggaran}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Kategori</p>
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium ${violation.kategori === 'Berat' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700'}">
                                ${violation.kategori}
                            </span>
                        </div>
                        <div>
                            <p class="text-gray-500">Poin</p>
                            <p class="font-medium text-indigo-700">${violation.poin} Poin</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Waktu</p>
                            <p class="font-medium text-gray-800">${new Date(violation.created_at).toLocaleDateString('id-ID')}</p>
                        </div>
                    </div>
                    ${violation.deskripsi ? `
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-gray-500 text-sm">Deskripsi</p>
                        <p class="text-gray-800">${violation.deskripsi}</p>
                    </div>
                    ` : ''}
                </div>
            `;

            content.innerHTML = html;
            modal.classList.remove('hidden');
        }

        function closeSingleModal() {
            document.getElementById('singlePhotoModal').classList.add('hidden');
        }

        // Action Modal Functions
        function editTindakan(id, jenis, deskripsi, hasil, catatan, tanggal) {
            document.getElementById('tindakan-modal-title').textContent = 'Edit Tindakan';
            document.getElementById('tindakan-form').action = `/siswa/{{ $siswa->id }}/tindakan/${id}`;
            document.getElementById('tindakan-method').value = 'PUT';
            document.getElementById('jenis_tindakan').value = jenis;
            document.getElementById('deskripsi_tindakan').value = deskripsi || '';
            document.getElementById('hasil_tindakan').value = hasil;
            document.getElementById('catatan_hasil').value = catatan || '';
            document.getElementById('tanggal_tindakan').value = tanggal;
            document.getElementById('tindakanModal').classList.remove('hidden');
        }

        function closeTindakanModal() {
            document.getElementById('tindakanModal').classList.add('hidden');
            // Reset form
            document.getElementById('tindakan-modal-title').textContent = 'Catat Tindakan';
            document.getElementById('tindakan-form').action = "{{ route('siswa.tindakan.store', ['id' => $siswa->id]) }}";
            document.getElementById('tindakan-method').value = 'POST';
            document.getElementById('jenis_tindakan').value = '';
            document.getElementById('deskripsi_tindakan').value = '';
            document.getElementById('hasil_tindakan').value = 'Sedang Berlangsung';
            document.getElementById('catatan_hasil').value = '';
            document.getElementById('tanggal_tindakan').value = new Date().toISOString().split('T')[0];
        }
    </script>

    <!-- Tindakan Modal -->
    <div id="tindakanModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeTindakanModal()"></div>
        <div class="absolute inset-4 md:inset-10 bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 id="tindakan-modal-title" class="text-lg font-semibold text-gray-800">Catat Tindakan</h3>
                        <p class="text-sm text-gray-500">Form pencatatan tindakan siswa</p>
                    </div>
                </div>
                <button onclick="closeTindakanModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="flex-1 overflow-y-auto p-6">
                <form id="tindakan-form" action="{{ route('siswa.tindakan.store', ['id' => $siswa->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" id="tindakan-method" name="_method" value="POST">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Tindakan <span class="text-rose-500">*</span></label>
                            <select id="jenis_tindakan" name="jenis_tindakan" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none bg-white">
                                <option value="">-- Pilih Jenis Tindakan --</option>
                                <option value="Panggilan Siswa">Panggilan Siswa</option>
                                <option value="Panggilan Orang Tua">Panggilan Orang Tua</option>
                                <option value="Surat Peringatan">Surat Peringatan</option>
                                <option value="Surat Peringatan Keras">Surat Peringatan Keras</option>
                                <option value="Pertemuan dengan BK">Pertemuan dengan BK</option>
                                <option value="Pertemuan dengan Wali Kelas">Pertemuan dengan Wali Kelas</option>
                                <option value="Pertemuan dengan Wakasek">Pertemuan dengan Wakasek</option>
                                <option value="Pertemuan dengan Kepala Sekolah">Pertemuan dengan Kepala Sekolah</option>
                                <option value="Perjanjian Perubahan Perilaku (PBK)">Perjanjian Perubahan Perilaku (PBK)</option>
                                <option value="Skorsing">Skorsing</option>
                                <option value="MoU dengan Orang Tua">MoU dengan Orang Tua</option>
                                <option value="Upacara Pembentukan Karakter">Upacara Pembentukan Karakter</option>
                                <option value="Bimbingan Konseling">Bimbingan Konseling</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Tindakan</label>
                            <textarea id="deskripsi_tindakan" name="deskripsi_tindakan" rows="3" placeholder="Jelaskan detail tindakan yang diambil..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hasil Tindakan <span class="text-rose-500">*</span></label>
                            <select id="hasil_tindakan" name="hasil_tindakan" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none bg-white">
                                <option value="Sedang Berlangsung">Sedang Berlangsung</option>
                                <option value="Berhasil">Berhasil</option>
                                <option value="Tidak Berhasil">Tidak Berhasil</option>
                                <option value="Perlu Evaluasi">Perlu Evaluasi</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Hasil</label>
                            <textarea id="catatan_hasil" name="catatan_hasil" rows="2" placeholder="Catatan hasil tindakan..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Tindakan <span class="text-rose-500">*</span></label>
                            <input type="date" id="tanggal_tindakan" name="tanggal_tindakan" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none">
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeTindakanModal()" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors font-medium">
                            Simpan Tindakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

