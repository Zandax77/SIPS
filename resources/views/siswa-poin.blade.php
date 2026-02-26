<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa & Poin - SIPS</title>
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
                        emerald: {
                            500: '#10b981',
                            600: '#059669',
                        },
                        amber: {
                            500: '#f59e0b',
                            600: '#d97706',
                        },
                        rose: {
                            500: '#f43f5e',
                            600: '#e11d48',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.4s ease-out forwards;
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen">
    <!-- Decorative Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-100 rounded-full opacity-40 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-100 rounded-full opacity-40 blur-3xl"></div>
    </div>

    <!-- PWA Install Button - Hidden by default, shown when PWA is installable -->
    <button id="install-app-btn" 
            class="hidden fixed bottom-6 right-6 z-50 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-full shadow-lg shadow-indigo-300 hover:shadow-xl transition-all duration-300 flex items-center gap-2 font-medium animate-bounce">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
        </svg>
        <span>Install App</span>
    </button>

    <!-- Main Container -->
    <div class="relative min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-sm border-b border-gray-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo & Back -->
                    <div class="flex items-center gap-4">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="hidden sm:inline">Kembali</span>
                        </a>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-xl font-bold text-gray-800">SIPS</span>
                                <p class="text-xs text-gray-500">Data Siswa & Poin</p>
                            </div>
                        </div>
                    </div>

                    <!-- User Info -->
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
                                <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all duration-200" title="Logout">
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
                <h1 class="text-2xl font-bold text-gray-800">Data Siswa & Poin Pelanggaran</h1>
                <p class="text-gray-500 mt-1">Lihat total poin pelanggaran setiap siswa</p>
            </div>

            <!-- Search & Filter Section -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 mb-6 animate-fade-in delay-100">
                <form action="{{ route('siswa.poin') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Siswa</label>
                        <div class="relative">
                            <input type="text"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Nama, NIS, atau kelas..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="w-full sm:w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                        <select name="sort" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none bg-white">
                            <option value="poin_desc" {{ $sortBy == 'poin_desc' ? 'selected' : '' }}>Poin Tertinggi</option>
                            <option value="poin_asc" {{ $sortBy == 'poin_asc' ? 'selected' : '' }}>Poin Terendah</option>
                            <option value="nama_asc" {{ $sortBy == 'nama_asc' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="nama_desc" {{ $sortBy == 'nama_desc' ? 'selected' : '' }}>Nama Z-A</option>
                            <option value="kelas" {{ $sortBy == 'kelas' ? 'selected' : '' }}>Per Kelas</option>
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <div class="flex items-end">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-fade-in delay-200">
                <!-- Total Siswa -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Siswa</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $siswa->total() }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Poin 0 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tidak Ada Poin</p>
                            <p class="text-3xl font-bold text-emerald-600 mt-1">
                                {{ $siswa->where('total_poin', 0)->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Poin Rendah (1-10) -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Poin Rendah (1-10)</p>
                            <p class="text-3xl font-bold text-amber-600 mt-1">
                                {{ $siswa->filter(function($s) { return $s->total_poin > 0 && $s->total_poin <= 10; })->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Poin Tinggi (>10) -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Poin Tinggi (>10)</p>
                            <p class="text-3xl font-bold text-rose-600 mt-1">
                                {{ $siswa->filter(function($s) { return $s->total_poin > 10; })->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden animate-fade-in delay-300">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Daftar Siswa</h2>
                                <p class="text-sm text-gray-500">Total poin pelanggaran siswa</p>
                            </div>
                        </div>
                        <a href="{{ route('siswa.poin') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                            Lihat Semua
                        </a>
                    </div>
                </div>

                @if(count($siswa) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 sticky top-0 z-10">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Poin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jml Pelanggaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($siswa as $index => $data)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ ($siswa->currentPage() - 1) * $siswa->perPage() + $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->nis }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">{{ $data->nama_siswa }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                                {{ $data->kelas }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($data->total_poin == 0)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700">
                                                    {{ $data->total_poin }} Poin
                                                </span>
                                            @elseif($data->total_poin <= 10)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-amber-100 text-amber-700">
                                                    {{ $data->total_poin }} Poin
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-rose-100 text-rose-700">
                                                    {{ $data->total_poin }} Poin
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $data->total_pelanggaran }}x
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('siswa.detail', $data->id) }}"
                                               class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $siswa->appends(['search' => $search, 'sort' => $sortBy])->links() }}
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-4 text-gray-500">Tidak ada data siswa ditemukan</p>
                        @if($search)
                            <p class="mt-1 text-sm text-gray-400">Coba kata kunci pencarian yang lain</p>
                        @endif
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

    <script>
        // PWA Install Handler
        let deferredPrompt;
        const installBtn = document.getElementById('install-app-btn');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            installBtn.classList.remove('hidden');
            installBtn.classList.add('flex');
        });

        installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            deferredPrompt = null;
            installBtn.classList.add('hidden');
            installBtn.classList.remove('flex');
        });

        window.addEventListener('appinstalled', () => {
            installBtn.classList.add('hidden');
            installBtn.classList.remove('flex');
            deferredPrompt = null;
        });
    </script>
</body>
</html>

