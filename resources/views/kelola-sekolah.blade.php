<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Sekolah - SIPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        emerald: {
                            500: '#10b981',
                            600: '#059669',
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
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen">
    <!-- Decorative Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-100 rounded-full opacity-40 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-100 rounded-full opacity-40 blur-3xl"></div>
    </div>

    <!-- Main Container -->
    <div class="relative min-h-screen flex flex-col lg:flex-row">
        <!-- Header (Mobile only) -->
        <header class="bg-white/80 backdrop-blur-sm border-b border-gray-100 sticky top-0 z-50 lg:hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-3">
                        <button id="mobile-menu-btn" class="lg:hidden p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-800">SIPS</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            <span class="text-xs font-medium text-emerald-700">DB Terhubung</span>
                        </div>
                        <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-semibold text-gray-800">{{ session('name') }}</p>
                                <p class="text-xs text-gray-500">{{ session('jabatan') }}</p>
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

        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-100 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 lg:h-screen lg:overflow-y-auto lg:block flex-shrink-0">
            <div class="hidden lg:block px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-800">SIPS</span>
                </div>
            </div>
            <div class="flex flex-col h-full">
                <div class="px-6 py-4 border-b border-gray-100 lg:hidden">
                    <h2 class="text-lg font-semibold text-gray-800">Menu Navigasi</h2>
                    <button id="close-sidebar" class="mt-2 p-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    @if(($jabatanRaw ?? '') !== 'OSIS')
                    <a href="{{ route('siswa.poin') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="font-medium">Data Siswa & Poin</span>
                    </a>
                    @endif

                    <a href="{{ route('pelanggaran.catat') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-medium">Catat Pelanggaran</span>
                    </a>

                    @if(in_array($jabatanRaw ?? '', ['Kesiswaan', 'Guru BK']) || ($role ?? '') === 'admin')
                    <a href="{{ route('jenis.pelanggaran') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span class="font-medium">Jenis Pelanggaran</span>
                    </a>
                    @endif

                    @if(($role ?? '') === 'admin' || ($jabatanRaw ?? '') === 'Kesiswaan')
                    <a href="{{ route('admin.petugas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="font-medium">Kelola Petugas</span>
                    </a>
                    @endif

                    <!-- Active: Kelola Sekolah -->
                    <a href="{{ route('admin.sekolah.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="font-medium">Kelola Sekolah</span>
                    </a>

                    <a href="{{ route('password.change.form') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        <span class="font-medium">Ubah Password</span>
                    </a>
                </nav>

                <!-- Sidebar Footer -->
                <div class="px-4 py-4 border-t border-gray-100">
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 mb-2">SIPS v1.0</p>
                        <p class="text-xs text-gray-400">Sistem Informasi<br>Pelanggaran Siswa</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-30 lg:hidden hidden transition-opacity duration-300"></div>

        <!-- Main Content Wrapper -->
        <div class="flex-1 lg:ml-0">
            <main class="flex-1 max-w-7xl w-full mx-auto px-4 py-8">
                <!-- Page Header -->
                <div class="mb-8 animate-fade-in">
                    <h1 class="text-2xl font-bold text-gray-800">Kelola Sekolah</h1>
                    <p class="text-gray-500 mt-1">Kelola informasi sekolah, alamat, dan logo</p>
                </div>

                <!-- Success/Error Messages -->
                @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3 animate-fade-in">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-center gap-3 animate-fade-in">
                    <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <p class="text-rose-700 font-medium">{{ session('error') }}</p>
                </div>
                @endif

                <!-- School Settings Form -->
                <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden animate-fade-in">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Informasi Sekolah</h2>
                                <p class="text-sm text-gray-500">Data ini akan ditampilkan di laporan dan dokumen</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.sekolah.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column: Text Inputs -->
                            <div class="space-y-6">
                                <!-- School Name -->
                                <div>
                                    <label for="nama_sekolah" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Sekolah <span class="text-rose-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="nama_sekolah" 
                                        id="nama_sekolah" 
                                        value="{{ old('nama_sekolah', $sekolah->nama_sekolah) }}"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all @error('nama_sekolah') border-rose-500 @enderror"
                                        placeholder="Contoh: SMKN 1 Jakarta"
                                        required
                                    >
                                    @error('nama_sekolah')
                                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- School Address -->
                                <div>
                                    <label for="alamat_sekolah" class="block text-sm font-medium text-gray-700 mb-2">
                                        Alamat Sekolah
                                    </label>
                                    <textarea 
                                        name="alamat_sekolah" 
                                        id="alamat_sekolah" 
                                        rows="4"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all @error('alamat_sekolah') border-rose-500 @enderror"
                                        placeholder="Contoh: Jl. Merdeka No. 1, Jakarta Pusat"
                                    >{{ old('alamat_sekolah', $sekolah->alamat_sekolah) }}</textarea>
                                    @error('alamat_sekolah')
                                        <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column: Logo Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Logo Sekolah
                                </label>
                                
                                <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-indigo-300 transition-colors">
                                    <!-- Current Logo Preview -->
                                    @if($sekolah->logo_url)
                                        <div class="mb-4">
                                            <img 
                                                src="{{ $sekolah->logo_url }}" 
                                                alt="Logo {{ $sekolah->nama_sekolah }}" 
                                                class="max-h-32 mx-auto rounded-lg shadow-sm"
                                            >
                                            <p class="mt-2 text-sm text-gray-500">Logo saat ini</p>
                                        </div>
                                    @else
                                        <div class="mb-4">
                                            <div class="w-24 h-24 mx-auto rounded-full bg-gray-100 flex items-center justify-center">
                                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <p class="mt-2 text-sm text-gray-500">Belum ada logo</p>
                                        </div>
                                    @endif
                                    
                                    <!-- File Input -->
                                    <input 
                                        type="file" 
                                        name="logo_sekolah" 
                                        id="logo_sekolah"
                                        accept="image/*"
                                        class="hidden"
                                        onchange="previewImage(this)"
                                    >
                                    <label 
                                        for="logo_sekolah" 
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg cursor-pointer hover:bg-indigo-100 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Pilih Logo</span>
                                    </label>
                                    <p class="mt-2 text-xs text-gray-400">Format: JPG, PNG, GIF, SVG (Max 2MB)</p>
                                    
                                    <!-- New Logo Preview (Hidden by default) -->
                                    <div id="new-logo-preview" class="hidden mt-4">
                                        <img id="preview-img" src="" alt="Preview" class="max-h-32 mx-auto rounded-lg shadow-sm">
                                        <p class="mt-2 text-sm text-emerald-600">Logo baru akan digunakan setelah disimpan</p>
                                    </div>

                                    <!-- Delete Logo Button -->
                                    @if($sekolah->logo_url)
                                        <button 
                                            type="button"
                                            onclick="deleteLogo()"
                                            class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span class="text-sm font-medium">Hapus Logo</span>
                                        </button>
                                    @endif
                                </div>
                                @error('logo_sekolah')
                                    <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 flex justify-end gap-3">
                            <button 
                                type="submit"
                                class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200 hover:shadow-indigo-300 flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Preview Section -->
                <div class="mt-8 bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden animate-fade-in">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Pratinjau</h2>
                                <p class="text-sm text-gray-500">Tampilan informasi sekolah</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-start gap-6">
                            <!-- Logo Preview -->
                            <div class="flex-shrink-0">
                                @if($sekolah->logo_url)
                                    <img 
                                        src="{{ $sekolah->logo_url }}" 
                                        alt="Logo" 
                                        class="w-20 h-20 rounded-lg object-contain border border-gray-200"
                                    >
                                @else
                                    <div class="w-20 h-20 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- School Info -->
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-800">
                                    {{ $sekolah->nama_sekolah ?: 'Nama Sekolah' }}
                                </h3>
                                @if($sekolah->alamat_sekolah)
                                    <p class="mt-1 text-gray-600 flex items-start gap-2">
                                        <svg class="w-4 h-4 mt-0.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $sekolah->alamat_sekolah }}
                                    </p>
                                @else
                                    <p class="mt-1 text-gray-400 italic">Alamat belum diisi</p>
                                @endif
                            </div>
                        </div>
                    </div>
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
    </div>

    <script>
        // Preview selected image
        function previewImage(input) {
            const preview = document.getElementById('new-logo-preview');
            const previewImg = document.getElementById('preview-img');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Delete logo function
        function deleteLogo() {
            if (confirm('Apakah Anda yakin ingin menghapus logo sekolah?')) {
                fetch('{{ route("admin.sekolah.delete-logo") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Gagal menghapus logo');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
            }
        }

        // Mobile Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const closeSidebarBtn = document.getElementById('close-sidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', openSidebar);
            }

            if (closeSidebarBtn) {
                closeSidebarBtn.addEventListener('click', closeSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }
        });
    </script>
</body>
</html>

