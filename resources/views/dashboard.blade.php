<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $namaSekolah }} SIPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
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
                        <!-- Logo -->
                        <div class="flex items-center gap-3">
                            <!-- Mobile Menu Button -->
                            <button id="mobile-menu-btn" class="lg:hidden p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>

                            <!-- Logo -->
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-xl font-bold text-gray-800">{{ $namaSekolah }}</span>
                                <p class="text-xs text-gray-500">SIPS</p>
                            </div>
                        </div>

                    <!-- User Info & Status -->
                    <div class="flex items-center gap-4">
                        <!-- Database Status -->
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full {{ $dbConnected ? 'bg-emerald-50 border border-emerald-200' : 'bg-rose-50 border border-rose-200' }}">
                            <div class="w-2 h-2 rounded-full {{ $dbConnected ? 'bg-emerald-500' : 'bg-rose-500' }} animate-pulse"></div>
                            <span class="text-xs font-medium {{ $dbConnected ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $dbConnected ? 'DB Terhubung' : 'DB Terputus' }}
                            </span>
                        </div>

                        <!-- User Profile -->
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

        <!-- Sidebar Navigation (Desktop & Mobile) -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-100 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 lg:h-screen lg:overflow-y-auto lg:block flex-shrink-0">
            <!-- Desktop Header (inside sidebar) -->
            <div class="hidden lg:block px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-gray-800">{{ $namaSekolah }}</span>
                        <p class="text-xs text-gray-500">SIPS</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col h-full">
                <!-- Sidebar Header (Mobile only) -->
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
                    <!-- Dashboard (Active) -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <!-- Change Password -->
                    <a href="{{ route('password.change') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        <span class="font-medium">Ubah Password</span>
                    </a>

                    <!-- Menu: Data Siswa & Poin (Hidden for OSIS) -->
                    @if(strtolower($role ?? 'petugas') !== 'osis')
                    <a href="{{ route('siswa.poin') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="font-medium">Data Siswa & Poin</span>
                    </a>
                    @endif

                    <!-- Menu: Catat Pelanggaran -->
                    <a href="{{ route('pelanggaran.catat') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-medium">Catat Pelanggaran</span>
                    </a>

                    <!-- Menu: Jenis Pelanggaran (Only for Kesiswaan, Guru BK, and Admin) -->
                    @if(in_array($jabatanRaw ?? '', ['Kesiswaan', 'Guru BK']) || ($role ?? '') === 'admin')
                    <a href="{{ route('jenis.pelanggaran') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span class="font-medium">Jenis Pelanggaran</span>
                    </a>
                    @endif

                    <!-- Menu: Cetak Laporan (Admin, Kesiswaan, Guru BK, Wali Kelas - NOT for OSIS) -->
                    @if(strtolower($role ?? 'petugas') !== 'osis')
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                <span class="font-medium">Cetak Laporan</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition class="ml-4 mt-1 space-y-1 bg-gray-50 rounded-lg overflow-hidden" style="display: none;">
                            <a href="{{ route('laporan.per-siswa') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Laporan per Siswa
                            </a>
                            <a href="{{ route('laporan.rekap-kelas') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Rekap per Kelas
                            </a>
                            <a href="{{ route('laporan.rekap-periode') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Rekap per Periode
                            </a>
                            <a href="{{ route('laporan.siswa-tertinggi') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                Siswa Poin Tertinggi
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Menu: Kelola Petugas (Admin only) -->
                    @if(($role ?? '') === 'admin')
                    <a href="{{ route('admin.petugas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="font-medium">Kelola Petugas</span>
                    </a>

                    <!-- Menu: Pengaturan (Admin only) -->
                    <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium">Pengaturan</span>
                    </a>
                    @endif
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
            <!-- Content -->
            <main class="flex-1 max-w-7xl w-full mx-auto px-4 py-8">
                <!-- Welcome Section -->
                <div class="mb-8 animate-fade-in">
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                    <p class="text-gray-500 mt-1">Ringkasan pelanggaran siswa hari ini</p>
                </div>

                <!-- Notification Section (Admin only - Password Reset Requests) -->
                @if(($role ?? '') === 'admin' && isset($passwordResetRequests) && count($passwordResetRequests) > 0)
                <div class="mb-8 bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl border border-amber-200 overflow-hidden animate-fade-in">
                    <div class="px-6 py-4 bg-amber-100 border-b border-amber-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <h2 class="text-lg font-semibold text-amber-800">Permintaan Reset Password</h2>
                        </div>
                        <span class="bg-amber-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ count($passwordResetRequests) }}</span>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($passwordResetRequests as $petugas)
                            <div class="flex items-center justify-between bg-white rounded-xl p-4 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $petugas->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $petugas->email }} - {{ $petugas->jabatan }}</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $petugas->password_reset_expires ? \Carbon\Carbon::parse($petugas->password_reset_expires)->format('d/m/Y H:i') : '' }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('admin.petugas.index') }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        Reset Password
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6 mb-8">
                    <!-- Ringan Card -->
                    <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg shadow-gray-200/50 border border-gray-100 animate-fade-in delay-100 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500">Pelanggaran Ringan</p>
                                <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mt-1 sm:mt-2" id="countRingan">{{ $countRingan }}</p>
                                <p class="text-xs text-gray-400 mt-1">Hari ini</p>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-emerald-100 flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        @if($countRingan == 0)
                        <p class="text-xs text-emerald-600 mt-2">Tidak ada pelanggaran ringan hari ini</p>
                        @endif
                    </div>

                    <!-- Sedang Card -->
                    <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg shadow-gray-200/50 border border-gray-100 animate-fade-in delay-200 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500">Pelanggaran Sedang</p>
                                <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mt-1 sm:mt-2" id="countSedang">{{ $countSedang }}</p>
                                <p class="text-xs text-gray-400 mt-1">Hari ini</p>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-amber-100 flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </div>
                        @if($countSedang == 0)
                        <p class="text-xs text-amber-600 mt-2">Tidak ada pelanggaran sedang hari ini</p>
                        @endif
                    </div>

                    <!-- Berat Card -->
                    <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg shadow-gray-200/50 border border-gray-100 animate-fade-in delay-300 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500">Pelanggaran Berat</p>
                                <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mt-1 sm:mt-2" id="countBerat">{{ $countBerat }}</p>
                                <p class="text-xs text-gray-400 mt-1">Hari ini</p>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-rose-100 flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        @if($countBerat == 0)
                        <p class="text-xs text-rose-600 mt-2">Tidak ada pelanggaran berat hari ini</p>
                        @endif
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 mb-8 animate-fade-in delay-400">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Tren Pelanggaran</h2>
                            <p class="text-sm text-gray-500">7 hari terakhir berdasarkan kategori</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                <span class="text-gray-600">Ringan</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                <span class="text-gray-600">Sedang</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                                <span class="text-gray-600">Berat</span>
                            </div>
                        </div>
                    </div>
                    @php
                        $totalChartData = array_sum($chartData['ringan'] ?? [0]) + array_sum($chartData['sedang'] ?? [0]) + array_sum($chartData['berat'] ?? [0]);
                    @endphp
                    @if($totalChartData > 0)
                    <div class="h-48 md:h-64">
                        <canvas id="violationChart"></canvas>
                    </div>
                    @else
                    <div class="h-48 md:h-64 flex items-center justify-center bg-gray-50 rounded-xl">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <p class="mt-4 text-gray-500">Belum ada data pelanggaran dalam 7 hari terakhir</p>
                            <p class="text-sm text-gray-400">Data akan muncul setelah ada pencatatan pelanggaran</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Table Section -->
                <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden animate-fade-in delay-400">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Siswa Pelanggaran Berat</h2>
                                <p class="text-sm text-gray-500">Data pelanggaran berat hari ini</p>
                            </div>
                        </div>
                    </div>

                    @if(count($siswaPelanggaranBerat) > 0)
                        <div class="overflow-x-auto -mx-6">
                            <table class="w-full min-w-[600px]">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggaran</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($siswaPelanggaranBerat as $index => $data)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $data->nis }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $data->nama_siswa }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $data->kelas }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                                    {{ $data->pelanggaran }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $data->deskripsi }}">
                                                {{ $data->deskripsi }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($data->created_at)->format('H:i') }}
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
                            <p class="mt-4 text-gray-500">Tidak ada pelanggaran berat hari ini</p>
                        </div>
                    @endif
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white/60 backdrop-blur-sm border-t border-gray-100 py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-500">
                        &copy; {{ date('Y') }} {{ $namaSekolah }} - SIPS. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Chart Configuration
        const ctx = document.getElementById('violationChart') ? document.getElementById('violationChart').getContext('2d') : null;

        const dates = @json($dates);
        const ringanData = {!! json_encode($chartData['ringan'] ?? [0,0,0,0,0,0,0]) !!};
        const sedangData = {!! json_encode($chartData['sedang'] ?? [0,0,0,0,0,0,0]) !!};
        const beratData = {!! json_encode($chartData['berat'] ?? [0,0,0,0,0,0,0]) !!};

        // Only render chart if canvas exists and there's data
        if (ctx && (ringanData.some(x => x > 0) || sedangData.some(x => x > 0) || beratData.some(x => x > 0))) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [
                        {
                            label: 'Ringan',
                            data: ringanData,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Sedang',
                            data: sedangData,
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#f59e0b',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Berat',
                            data: beratData,
                            borderColor: '#f43f5e',
                            backgroundColor: 'rgba(244, 63, 94, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#f43f5e',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#1f2937',
                            bodyColor: '#6b7280',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw + ' pelanggaran';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(156, 163, 175, 0.1)'
                            },
                            ticks: {
                                stepSize: 1,
                                color: '#9ca3af'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
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

            // Auto-refresh violation counts every 10 seconds
            function updateViolationCounts() {
                fetch('/api/dashboard/counts')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data) {
                            const countRinganEl = document.getElementById('countRingan');
                            const countSedangEl = document.getElementById('countSedang');
                            const countBeratEl = document.getElementById('countBerat');

                            // Animate counter update
                            animateCounter(countRinganEl, parseInt(countRinganEl.textContent), data.data.ringan);
                            animateCounter(countSedangEl, parseInt(countSedangEl.textContent), data.data.sedang);
                            animateCounter(countBeratEl, parseInt(countBeratEl.textContent), data.data.berat);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching violation counts:', error);
                    });
            }

            // Animate counter transition
            function animateCounter(element, oldValue, newValue) {
                if (oldValue !== newValue) {
                    element.style.transform = 'scale(1.1)';
                    element.style.transition = 'transform 0.2s ease';
                    element.textContent = newValue;
                    setTimeout(() => {
                        element.style.transform = 'scale(1)';
                    }, 200);
                }
            }

            // Start polling after page load
            setTimeout(() => {
                updateViolationCounts();
                setInterval(updateViolationCounts, 10000); // Update every 10 seconds
            }, 2000); // First update after 2 seconds
        });
    </script>
</body>
</html>

