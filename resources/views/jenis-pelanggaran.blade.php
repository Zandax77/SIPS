<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jenis Pelanggaran - SIPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        indigo: { 50: '#eef2ff', 100: '#e0e7ff', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca' },
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-xl font-bold text-gray-800">SIPS</span>
                                <p class="text-xs text-gray-500">Jenis Pelanggaran</p>
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
                            <p class="font-medium text-rose-800">Error!</p>
                            <p class="text-sm text-rose-600">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Page Header -->
            <div class="mb-8 animate-fade-in flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Daftar Jenis Pelanggaran</h1>
                    <p class="text-gray-500 mt-1">Jenis pelanggaran dan poin masing-masing</p>
                </div>
                @if(isset($isKesiswaan) && $isKesiswaan)
                    <button onclick="openModal('jenisModal')" class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium flex items-center gap-2 shadow-lg shadow-indigo-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Jenis Pelanggaran
                    </button>
                @endif
            </div>

            <!-- Search & Filter Section -->
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 mb-6 animate-fade-in delay-100">
                <form action="{{ route('jenis.pelanggaran') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Jenis Pelanggaran</label>
                        <div class="relative">
                            <input type="text"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Nama pelanggaran..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="w-full sm:w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="kategori" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none bg-white">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriList as $k)
                                <option value="{{ $k->nama }}" {{ $filterKategori == $k->nama ? 'selected' : '' }}>
                                    {{ $k->nama }}
                                </option>
                            @endforeach
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
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 animate-fade-in delay-200">
                <!-- Total -->
                <div class="bg-white rounded-xl p-4 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total</p>
                            <p class="text-xl font-bold text-gray-800">{{ $stats['total_jenis'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ringan -->
                <div class="bg-white rounded-xl p-4 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Ringan</p>
                            <p class="text-xl font-bold text-emerald-600">{{ $stats['ringan'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sedang -->
                <div class="bg-white rounded-xl p-4 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Sedang</p>
                            <p class="text-xl font-bold text-amber-600">{{ $stats['sedang'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Berat -->
                <div class="bg-white rounded-xl p-4 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-rose-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Berat</p>
                            <p class="text-xl font-bold text-rose-600">{{ $stats['berat'] }}</p>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Daftar Jenis Pelanggaran</h2>
                                <p class="text-sm text-gray-500">Jenis pelanggaran berdasarkan kategori</p>
                            </div>
                        </div>
                        @if(isset($isKesiswaan) && $isKesiswaan)
                            <div class="flex items-center gap-2">
                                <button onclick="openModal('kategoriModal')" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-medium flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Kelola Kategori
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                @if(count($jenisPelanggaran) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 sticky top-0 z-10">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pelanggaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jml Catat</th>
                                    @if(isset($isKesiswaan) && $isKesiswaan)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($jenisPelanggaran as $index => $data)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ ($jenisPelanggaran->currentPage() - 1) * $jenisPelanggaran->perPage() + $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-medium text-gray-800">{{ $data->nama }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $data->deskripsi }}">
                                            {{ $data->deskripsi ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $data->kategori == 'Ringan' ? 'bg-emerald-100 text-emerald-700' :
                                                   ($data->kategori == 'Sedang' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                                {{ $data->kategori }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-indigo-100 text-indigo-700">
                                                {{ $data->poin }} Poin
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $data->jumlah_pelanggaran }}x
                                        </td>
                                        @if(isset($isKesiswaan) && $isKesiswaan)
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <button onclick="editJenis({{ $data->id }}, '{{ addslashes($data->nama) }}', {{ $data->id_kategori_pelanggaran }}, '{{ addslashes($data->deskripsi) }}')"
                                                        class="p-2 text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-colors"
                                                        title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </button>
                                                    <form action="{{ route('jenis.pelanggaran.delete', $data->id) }}" method="POST" class="inline" onsubmit="return confirmDelete(event)">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors"
                                                            title="Hapus">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $jenisPelanggaran->appends(['search' => $search, 'kategori' => $filterKategori])->links() }}
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-4 text-gray-500">Tidak ada jenis pelanggaran ditemukan</p>
                        @if($search || $filterKategori)
                            <p class="mt-1 text-sm text-gray-400">Coba filter yang berbeda</p>
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

    <!-- Modal Tambah/Edit Jenis Pelanggaran -->
    <div id="jenisModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('jenisModal')"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 animate-fade-in">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800" id="jenisModalTitle">Tambah Jenis Pelanggaran</h3>
                    <button onclick="closeModal('jenisModal')" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="jenisForm" action="{{ route('jenis.pelanggaran.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="jenisMethod" value="POST">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggaran</label>
                        <input type="text" name="nama" id="jenisNama" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                            placeholder="Contoh: Terlambat">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="id_kategori_pelanggaran" id="jenisKategori" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none bg-white">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriList as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }} ({{ $k->poin }} poin)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (Opsional)</label>
                        <textarea name="deskripsi" id="jenisDeskripsi" rows="3"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none resize-none"
                            placeholder="Tambahkan detail pelanggaran..."></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('jenisModal')"
                            class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Kelola Kategori -->
    <div id="kategoriModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('kategoriModal')"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6 animate-fade-in max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Kelola Kategori Pelanggaran</h3>
                    <button onclick="closeModal('kategoriModal')" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Tambah Kategori -->
                <div class="mb-8 bg-gray-50 rounded-xl p-4">
                    <h4 class="font-medium text-gray-800 mb-4">Tambah Kategori Baru</h4>
                    <form action="{{ route('kategori.pelanggaran.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-2">
                                <input type="text" name="nama" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                                    placeholder="Nama kategori (Contoh: Sangat Berat)">
                            </div>
                            <div>
                                <input type="number" name="poin" required min="1"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none"
                                    placeholder="Poin">
                            </div>
                            <div class="sm:col-span-3">
                                <button type="submit"
                                    class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors font-medium">
                                    Tambah Kategori
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Daftar Kategori -->
                <div>
                    <h4 class="font-medium text-gray-800 mb-4">Daftar Kategori</h4>
                    <div class="space-y-3">
                        @foreach($kategoriList as $k)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">{{ $k->nama }}</p>
                                    <p class="text-sm text-gray-500">{{ $k->poin }} Poin</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button onclick="editKategori({{ $k->id }}, '{{ addslashes($k->nama) }}', {{ $k->poin }})"
                                        class="p-2 text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <form action="{{ route('kategori.pelanggaran.delete', $k->id) }}" method="POST" class="inline" onsubmit="return confirmDelete(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div id="editKategoriModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('editKategoriModal')"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 animate-fade-in">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Edit Kategori</h3>
                    <button onclick="closeModal('editKategoriModal')" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="editKategoriForm" action="" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                        <input type="text" name="nama" id="editKategoriNama" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Poin</label>
                        <input type="number" name="poin" id="editKategoriPoin" required min="1"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('editKategoriModal')"
                            class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = '';
        }

        function editJenis(id, nama, kategori, deskripsi) {
            document.getElementById('jenisModalTitle').textContent = 'Edit Jenis Pelanggaran';
            document.getElementById('jenisForm').action = '{{ route('jenis.pelanggaran.update', ':id') }}'.replace(':id', id);
            document.getElementById('jenisMethod').value = 'PUT';
            document.getElementById('jenisNama').value = nama;
            document.getElementById('jenisKategori').value = kategori;
            document.getElementById('jenisDeskripsi').value = deskripsi || '';
            openModal('jenisModal');
        }

        function editKategori(id, nama, poin) {
            document.getElementById('editKategoriForm').action = '{{ route('kategori.pelanggaran.update', ':id') }}'.replace(':id', id);
            document.getElementById('editKategoriNama').value = nama;
            document.getElementById('editKategoriPoin').value = poin;
            openModal('editKategoriModal');
        }

        function confirmDelete(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
            return false;
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal('jenisModal');
                closeModal('kategoriModal');
                closeModal('editKategoriModal');
            }
        });
    </script>
</body>
</html>

