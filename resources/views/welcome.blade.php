<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $namaSekolah }} - SIPS</title>

    <!-- PWA Meta Tags -->
    <meta name="description" content="Sistem Informasi Pelanggaran Siswa - Monitoring dan pencatatan pelanggaran siswa">
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SIPS">
    <meta name="application-name" content="SIPS">
    <meta name="msapplication-TileColor" content="#4f46e5">
    <meta name="msapplication-tap-highlight" content="no">

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/langgar.png">

    <!-- PWA Icons for iOS -->
    <link rel="icon" type="image/png" sizes="72x72" href="/icons/langgar.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/icons/langgar.png">
    <link rel="icon" type="image/png" sizes="128x128" href="/icons/langgar.png">
    <link rel="icon" type="image/png" sizes="144x144" href="/icons/langgar.png">
    <link rel="icon" type="image/png" sizes="152x152" href="/icons/langgar.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/langgar.png">
    <link rel="icon" type="image/png" sizes="384x384" href="/icons/langgar.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/icons/langgar.png">

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
            animation: fade-in 0.5s ease-out forwards;
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen">
    <!-- Decorative Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-100 rounded-full opacity-40 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-100 rounded-full opacity-40 blur-3xl"></div>
    </div>

    <!-- Header -->
    <header class="relative z-10 bg-white/80 backdrop-blur-sm border-b border-gray-100 sticky top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
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

                <!-- Login Button -->
                <a href="{{ route('login') }}" class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Login
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="text-center mb-12 animate-fade-in">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-4">
                Sistem Informasi <span class="text-indigo-600">Pelanggaran Siswa</span>
            </h1>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto">
                Monitoring dan pencatatan pelanggaran siswa secara efektif dan efisien.
                Dukung terciptanya disiplin dan ketertiban di lingkungan sekolah.
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
            @php
                $totalPelanggaran = array_sum($chartData['ringan'] ?? [0]) + array_sum($chartData['sedang'] ?? [0]) + array_sum($chartData['berat'] ?? [0]);
                $totalSiswaMelanggar = $kelasStats->sum('jumlah_siswa_pelaku');
                $totalKelasTerlibat = $kelasStats->count();
            @endphp

            @if($totalPelanggaran > 0)
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 animate-fade-in delay-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Pelanggaran</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalPelanggaran }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 animate-fade-in delay-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Pelanggaran</p>
                        <p class="text-2xl font-bold text-gray-400">0</p>
                    </div>
                </div>
            </div>
            @endif

            @if($totalSiswaMelanggar > 0)
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 animate-fade-in delay-200">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Siswa Melanggar</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalSiswaMelanggar }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 animate-fade-in delay-200">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Siswa Melanggar</p>
                        <p class="text-2xl font-bold text-gray-400">0</p>
                    </div>
                </div>
            </div>
            @endif

            @if($totalKelasTerlibat > 0)
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 animate-fade-in delay-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kelas Terlibat</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalKelasTerlibat }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 animate-fade-in delay-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kelas Terlibat</p>
                        <p class="text-2xl font-bold text-gray-400">0</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-2xl p-6 shadow-lg shadow-gray-200/50 border border-gray-100 mb-12 animate-fade-in delay-400">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Tren Pelanggaran</h2>
                    <p class="text-sm text-gray-500">Grafik pelanggaran siswa dalam 7 hari terakhir</p>
                </div>
                <div class="flex flex-wrap items-center gap-4 text-sm">
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
            @if($totalPelanggaran > 0)
            <div class="h-64 md:h-80">
                <canvas id="violationChart"></canvas>
            </div>
            @else
            <div class="h-64 md:h-80 flex items-center justify-center bg-gray-50 rounded-xl">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="mt-4 text-gray-500">Belum ada data pelanggaran dalam 7 hari terakhir</p>
                    <p class="text-sm text-gray-400">Silakan login untuk mulai mencatat pelanggaran siswa</p>
                    <a href="{{ route('login') }}" class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">Login Sekarang</a>
                </div>
            </div>
            @endif
        </div>

        <!-- Class Table Section -->
        <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden animate-fade-in delay-500">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-white border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800">Data Pelanggaran per Kelas</h2>
                <p class="text-sm text-gray-500">Statistik pelanggaran siswa dalam 7 hari terakhir</p>
            </div>

            @if($kelasStats->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Siswa Pelaku</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pelanggaran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($kelasStats as $index => $kelas)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $kelas->kelas }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-700">
                                            {{ $kelas->jumlah_siswa_pelaku }} siswa
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $kelas->jumlah_pelanggaran }} pelanggaran</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800" colspan="2">Total</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">{{ $kelasStats->sum('jumlah_siswa_pelaku') }} siswa</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">{{ $kelasStats->sum('jumlah_pelanggaran') }} pelanggaran</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="mt-4 text-gray-500">Tidak ada data pelanggaran dalam 7 hari terakhir</p>
                </div>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 bg-white/60 backdrop-blur-sm border-t border-gray-100 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ $namaSekolah }} - SIPS
            </p>
        </div>
    </footer>

    <!-- Chatbot Floating Button -->
    <button id="chatbot-toggle" class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-indigo-600 hover:bg-indigo-700 rounded-full shadow-lg shadow-indigo-300 flex items-center justify-center transition-all duration-300 hover:scale-110">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
        </svg>
        <span id="chat-notification" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center hidden">1</span>
    </button>

    <!-- Chatbot Window -->
    <div id="chatbot-window" class="fixed bottom-24 right-6 z-50 w-96 max-w-[calc(100vw-3rem)] h-[500px] bg-white rounded-2xl shadow-2xl shadow-indigo-200 hidden flex flex-col overflow-hidden border border-gray-100">
        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-5 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold text-sm">Asisten SIPS</h3>
                    <p class="text-indigo-200 text-xs">Selalu siap membantu</p>
                </div>
            </div>
            <button id="chatbot-close" class="text-white/80 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Chat Messages -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
            <!-- Messages will be inserted here -->
        </div>

        <!-- Quick Replies -->
        <div id="quick-replies" class="px-4 py-2 border-t border-gray-100 bg-white flex gap-2 overflow-x-auto flex-nowrap">
            <!-- Quick reply buttons will be inserted here -->
        </div>

        <!-- Chat Input -->
        <div class="p-4 bg-white border-t border-gray-100">
            <form id="chat-form" class="flex gap-2">
                <input type="text" id="chat-input" placeholder="Ketik pertanyaan Anda..." class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm" autocomplete="off">
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- PWA Install Banner -->
    <div id="pwa-install-banner" class="fixed bottom-24 left-4 right-4 md:left-auto md:right-6 md:w-96 bg-white rounded-2xl shadow-2xl shadow-indigo-200 border border-gray-100 p-4 hidden flex-col gap-3 z-40">
        <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-800">Instal Aplikasi SIPS</h3>
                <p class="text-sm text-gray-500 mt-1">Tambahkan ke homescreen untuk akses cepat seperti aplikasi native</p>
            </div>
            <button onclick="closeInstallBanner()" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="flex gap-2 mt-2">
            <button onclick="closeInstallBanner()" class="flex-1 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-xl text-sm font-medium transition-colors">
                Nanti saja
            </button>
            <button onclick="installPWA()" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition-colors">
                Install Sekarang
            </button>
        </div>
    </div>

    <script>
        // PWA Install Prompt
        let deferredPrompt;
        const installBanner = document.getElementById('pwa-install-banner');

        // Hide default iOS install banner by preventing default behavior
        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later
            deferredPrompt = e;
            // Show custom install banner
            if (installBanner) {
                installBanner.classList.remove('hidden');
                installBanner.classList.add('flex');
            }
        });

        // Handle iOS PWA installation
        function showiOSInstallPrompt() {
            if (installBanner) {
                installBanner.classList.remove('hidden');
                installBanner.classList.add('flex');
            }
        }

        // Close install banner
        function closeInstallBanner() {
            if (installBanner) {
                installBanner.classList.add('hidden');
                installBanner.classList.remove('flex');
            }
            // Store that user dismissed the banner in localStorage
            localStorage.setItem('pwaInstallDismissed', 'true');
        }

        // Install button click handler
        async function installPWA() {
            if (deferredPrompt) {
                // Show the install prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                const { outcome } = await deferredPrompt.userChoice;
                // Clear the deferredPrompt
                deferredPrompt = null;
                // Hide the banner
                if (installBanner) {
                    installBanner.classList.add('hidden');
                    installBanner.classList.remove('flex');
                }
            } else {
                // For iOS, show instructions
                alert('Untuk menginstal SIPS di iPhone/iPad:\n\n1. Ketuk tombol Share (bagikan) di bawah\n2. Scroll ke bawah dan ketuk "Add to Home Screen"\n3. Ketuk "Add" untuk menambahkan ke homescreen');
            }
        }

        // Check if app is already installed
        if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
            // App is installed
            console.log('App is running in standalone mode');
        }

        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then((registration) => {
                        console.log('Service Worker registered with scope:', registration.scope);
                    })
                    .catch((error) => {
                        console.log('Service Worker registration failed:', error);
                    });
            });
        }

        // Chatbot Functionality
        const chatbotToggle = document.getElementById('chatbot-toggle');
        const chatbotWindow = document.getElementById('chatbot-window');
        const chatbotClose = document.getElementById('chatbot-close');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const chatMessages = document.getElementById('chat-messages');
        const quickRepliesContainer = document.getElementById('quick-replies');

        let isChatbotOpen = false;

        // Toggle chatbot window
        chatbotToggle.addEventListener('click', () => {
            isChatbotOpen = !isChatbotOpen;
            if (isChatbotOpen) {
                chatbotWindow.classList.remove('hidden');
                chatInput.focus();
                // Load greeting if chat is empty
                if (chatMessages.children.length === 0) {
                    loadGreeting();
                }
            } else {
                chatbotWindow.classList.add('hidden');
            }
        });

        chatbotClose.addEventListener('click', () => {
            isChatbotOpen = false;
            chatbotWindow.classList.add('hidden');
        });

        // Load greeting message
        async function loadGreeting() {
            try {
                const response = await fetch('/api/chatbot/greet');
                const result = await response.json();
                if (result.success) {
                    addMessage(result.data.message, 'bot');
                    if (result.data.quick_replies) {
                        showQuickReplies(result.data.quick_replies);
                    }
                }
            } catch (error) {
                console.error('Error loading greeting:', error);
                addMessage('Selamat datang di SIPS! Ada yang bisa saya bantu?', 'bot');
            }
        }

        // Show quick replies
        function showQuickReplies(replies) {
            quickRepliesContainer.innerHTML = '';
            replies.forEach(reply => {
                const button = document.createElement('button');
                button.className = 'px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs rounded-full whitespace-nowrap transition-colors font-medium';
                button.textContent = reply;
                button.addEventListener('click', () => {
                    chatInput.value = reply;
                    sendMessage();
                });
                quickRepliesContainer.appendChild(button);
            });
        }

        // Add message to chat
        function addMessage(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = sender === 'user' ? 'flex justify-end' : 'flex justify-start';

            const bubbleDiv = document.createElement('div');
            bubbleDiv.className = sender === 'user'
                ? 'max-w-[85%] px-4 py-3 bg-indigo-600 text-white rounded-2xl rounded-br-md text-sm leading-relaxed whitespace-pre-line'
                : 'max-w-[85%] px-4 py-3 bg-white border border-gray-200 text-gray-700 rounded-2xl rounded-bl-md text-sm leading-relaxed shadow-sm whitespace-pre-line';

            bubbleDiv.textContent = text;
            messageDiv.appendChild(bubbleDiv);
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Send message
        async function sendMessage() {
            const message = chatInput.value.trim();
            if (!message) return;

            // Add user message
            addMessage(message, 'user');
            chatInput.value = '';

            // Clear quick replies
            quickRepliesContainer.innerHTML = '';

            // Show loading indicator
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'flex justify-start';
            loadingDiv.innerHTML = '<div class="px-4 py-3 bg-white border border-gray-200 rounded-2xl rounded-bl-md"><div class="flex gap-1"><span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></span><span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span><span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span></div></div>';
            chatMessages.appendChild(loadingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            try {
                const response = await fetch('/api/chatbot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message })
                });

                const result = await response.json();

                // Remove loading indicator
                loadingDiv.remove();

                if (result.success) {
                    addMessage(result.data.message, 'bot');
                    if (result.data.quick_replies) {
                        showQuickReplies(result.data.quick_replies);
                    }
                } else {
                    addMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', 'bot');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                loadingDiv.remove();
                addMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', 'bot');
            }
        }

        // Handle form submission
        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            sendMessage();
        });

        // Handle Enter key
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        });
    </script>

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
    </script>
</body>
</html>

