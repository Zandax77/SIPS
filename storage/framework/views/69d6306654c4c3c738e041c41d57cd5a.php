<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SIPS">
    <link rel="apple-touch-icon" href="<?php echo e(asset('apple-touch-icon.png')); ?>">
    <link rel="manifest" href="<?php echo e(asset('manifest.webmanifest')); ?>">
    <title>SIPS - Sistem Informasi Pelanggaran Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'slide-down': 'slideDown 0.5s ease-out',
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'scale-in': 'scaleIn 0.3s ease-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        slideDown: {
                            '0%': { transform: 'translateY(-20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.9)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        },
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .hero-gradient {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 50%, #e0e7ff 100%);
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- PWA Install Button - Hidden by default, shown when PWA is installable -->
    <button id="install-app-btn" 
            class="hidden fixed bottom-6 right-6 z-50 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-full shadow-lg shadow-indigo-300 hover:shadow-xl transition-all duration-300 flex items-center gap-2 font-medium animate-bounce">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
        </svg>
        <span>Install App</span>
    </button>

    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <?php if($sekolah && $sekolah->logo_url): ?>
                        <img src="<?php echo e($sekolah->logo_url); ?>" alt="Logo" class="w-10 h-10 rounded-xl object-contain">
                    <?php else: ?>
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-lg shadow-primary-500/25">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <span class="text-xl font-bold text-gray-800"><?php echo e($sekolah && $sekolah->nama_sekolah ? $sekolah->nama_sekolah : 'SIPS'); ?></span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#fitur" class="text-gray-600 hover:text-primary-600 transition-colors font-medium relative group">
                        Fitur
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-600 transition-all group-hover:w-full"></span>
                    </a>
                    <a href="#statistik" class="text-gray-600 hover:text-primary-600 transition-colors font-medium relative group">
                        Statistik
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-600 transition-all group-hover:w-full"></span>
                    </a>
                    <a href="#cara-pakai" class="text-gray-600 hover:text-primary-600 transition-colors font-medium relative group">
                        Cara Pakai
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary-600 transition-all group-hover:w-full"></span>
                    </a>
                </div>

                <!-- CTA Buttons -->
                <div class="flex items-center gap-3">
                    <a href="<?php echo e(route('login')); ?>" class="px-4 py-2 text-gray-600 hover:text-primary-600 font-medium transition-colors">
                        Masuk
                    </a>
                    <a href="<?php echo e(route('petugas.register.show')); ?>" class="px-5 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium rounded-lg hover:from-primary-600 hover:to-primary-700 transition-all shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 transform hover:-translate-y-0.5">
                        Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient py-16 lg:py-24 relative overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-20 -right-20 w-96 h-96 bg-primary-200 rounded-full opacity-30 blur-3xl animate-pulse-slow"></div>
            <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-purple-200 rounded-full opacity-30 blur-3xl animate-pulse-slow" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gradient-to-r from-primary-100 to-purple-100 rounded-full opacity-20 blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="text-center lg:text-left animate-slide-up">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/60 rounded-full mb-6 shadow-sm">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="text-sm text-gray-600 font-medium">Sistem Terintegrasi</span>
                    </div>
                    
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                        Kelola Pelanggaran<br>
                        <span class="gradient-text">Siswa Lebih Mudah</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 max-w-xl mx-auto lg:mx-0">
                        Aplikasi modern untuk mencatat, mengelola, dan memantau pelanggaran siswa secara terstruktur dan efektif dengan sistem poin otomatis.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="<?php echo e(route('petugas.register.show')); ?>" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 transform hover:-translate-y-0.5">
                            <span>Mulai Sekarang</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="<?php echo e(route('login')); ?>" class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-md border border-gray-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login
                        </a>
                    </div>

                    <!-- Trust Badges -->
                    <div class="mt-8 flex items-center gap-6 justify-center lg:justify-start">
                    </div>
                </div>

                <!-- Stats Cards with Animation -->
                <div class="grid grid-cols-2 gap-4 animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 transform hover:scale-105 transition-transform duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                                <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-gray-900" id="totalSiswa">-</p>
                                <p class="text-sm text-gray-500">Total Siswa</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 transform hover:scale-105 transition-transform duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center">
                                <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-gray-900" id="totalPelanggaran">-</p>
                                <p class="text-sm text-gray-500">Pelanggaran</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 transform hover:scale-105 transition-transform duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center">
                                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-gray-900" id="totalPetugas">-</p>
                                <p class="text-sm text-gray-500">Petugas</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 transform hover:scale-105 transition-transform duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-100 to-rose-200 flex items-center justify-center">
                                <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-gray-900" id="todayTotal">-</p>
                                <p class="text-sm text-gray-500">Hari Ini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Chart Section -->
    <section id="statistik" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 animate-fade-in">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">Tren Pelanggaran 7 Hari Terakhir</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">Visualisasi data pelanggaran berdasarkan kategori untuk membantu analisis</p>
            </div>
            
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl p-6 sm:p-8 border border-gray-200 shadow-lg animate-fade-in" style="animation-delay: 0.2s;">
                <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6 mb-6">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                        <span class="text-sm text-gray-600 font-medium">Ringan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                        <span class="text-sm text-gray-600 font-medium">Sedang</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                        <span class="text-sm text-gray-600 font-medium">Berat</span>
                    </div>
                </div>
                <div class="h-64 sm:h-80">
                    <canvas id="landingChart"></canvas>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-16 bg-gradient-to-br from-gray-50 via-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 animate-fade-in">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">Fitur Utama</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">Fitur lengkap untuk mengelola pelanggaran siswa dengan mudah</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="feature-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100 transition-all duration-300 hover:shadow-xl animate-slide-up" style="animation-delay: 0.1s;">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-100 to-primary-200 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pencatatan Cepat</h3>
                    <p class="text-gray-600 text-sm">
                        Catat pelanggaran siswa dengan cepat menggunakan pencarian nama atau scan QR code.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100 transition-all duration-300 hover:shadow-xl animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Dashboard Interaktif</h3>
                    <p class="text-gray-600 text-sm">
                        Pantau data pelanggaran dengan visualisasi grafik yang informatif dan real-time.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100 transition-all duration-300 hover:shadow-xl animate-slide-up" style="animation-delay: 0.3s;">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-100 to-amber-200 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Manajemen Poin</h3>
                    <p class="text-gray-600 text-sm">
                        Sistem poin otomatis berdasarkan kategori pelanggaran Ringan, Sedang, dan Berat.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100 transition-all duration-300 hover:shadow-xl animate-slide-up" style="animation-delay: 0.4s;">
                    <div class="w-14 h-14 bg-gradient-to-br from-rose-100 to-rose-200 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Kategorisasi Lengkap</h3>
                    <p class="text-gray-600 text-sm">
                        Kelola jenis pelanggaran dengan kategori yang dapat disesuaikan sesuai kebutuhan.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100 transition-all duration-300 hover:shadow-xl animate-slide-up" style="animation-delay: 0.5s;">
                    <div class="w-14 h-14 bg-gradient-to-br from-violet-100 to-violet-200 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Kelola Petugas</h3>
                    <p class="text-gray-600 text-sm">
                        Kelola akun petugas dengan peran berbeda: Admin, Kesiswaan, Guru BK, Wali Kelas, OSIS.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card bg-white rounded-2xl p-6 shadow-lg border border-gray-100 transition-all duration-300 hover:shadow-xl animate-slide-up" style="animation-delay: 0.6s;">
                    <div class="w-14 h-14 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Saran Tindakan AI</h3>
                    <p class="text-gray-600 text-sm">
                        Dapatkan saran tindakan yang tepat berdasarkan analisis riwayat pelanggaran siswa.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How to Use Section -->
    <section id="cara-pakai" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 animate-fade-in">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">Cara Menggunakan</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">Langkah mudah untuk mulai menggunakan SIPS</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center animate-slide-up" style="animation-delay: 0.1s;">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-primary-500/25">
                        <span class="text-2xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Daftar Akun</h3>
                    <p class="text-gray-600 text-sm">Daftar sebagai petugas sekolah dengan memilih jabatan yang sesuai</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-primary-500/25">
                        <span class="text-2xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Catat Pelanggaran</h3>
                    <p class="text-gray-600 text-sm">Catat pelanggaran siswa dengan mudah melalui pencarian atau scan QR</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center animate-slide-up" style="animation-delay: 0.3s;">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-primary-500/25">
                        <span class="text-2xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pantau & Analisis</h3>
                    <p class="text-gray-600 text-sm">Pantau data pelanggaran melalui dashboard dan grafik untuk analisis lebih lanjut</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-primary-600 to-primary-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Siap Memulai?</h2>
            <p class="text-primary-100 mb-8 max-w-2xl mx-auto">Daftar sekarang dan mulai kelola pelanggaran siswa dengan lebih mudah dan terstruktur</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo e(route('petugas.register.show')); ?>" class="px-8 py-3 bg-white text-primary-600 font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-lg">
                    Daftar Sekarang
                </a>
                <a href="<?php echo e(route('login')); ?>" class="px-8 py-3 bg-primary-700 text-white font-medium rounded-xl hover:bg-primary-600 transition-colors border border-primary-500">
                    Login
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <span class="font-semibold text-white">SIPS</span>
                </div>
                <p class="text-sm text-gray-400">
                    &copy; <?php echo e(date('Y')); ?> SIPS. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Fetch landing page statistics
        async function fetchLandingStats() {
            try {
                const response = await fetch('/api/landing/stats');
                const result = await response.json();
                
                if (result.success && result.data) {
                    const data = result.data;
                    
                    // Animate numbers
                    animateValue('totalSiswa', 0, data.total_siswa, 1000);
                    animateValue('totalPelanggaran', 0, data.total_pelanggaran, 1000);
                    animateValue('totalPetugas', 0, data.total_petugas, 1000);
                    animateValue('todayTotal', 0, data.today.total, 1000);
                    
                    // Initialize chart
                    initChart(data.chart);
                }
            } catch (error) {
                console.error('Error fetching landing stats:', error);
                // Show fallback values
                document.getElementById('totalSiswa').textContent = '0';
                document.getElementById('totalPelanggaran').textContent = '0';
                document.getElementById('totalPetugas').textContent = '0';
                document.getElementById('todayTotal').textContent = '0';
            }
        }

        // Animate number counting
        function animateValue(id, start, end, duration) {
            const element = document.getElementById(id);
            const range = end - start;
            const startTime = performance.now();
            
            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const easeOut = 1 - Math.pow(1 - progress, 3);
                const current = Math.round(start + (range * easeOut));
                element.textContent = current;
                
                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }
            
            requestAnimationFrame(update);
        }

        // Initialize Chart
        function initChart(chartData) {
            const ctx = document.getElementById('landingChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.dates,
                    datasets: [
                        {
                            label: 'Ringan',
                            data: chartData.ringan,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        },
                        {
                            label: 'Sedang',
                            data: chartData.sedang,
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#f59e0b',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        },
                        {
                            label: 'Berat',
                            data: chartData.berat,
                            borderColor: '#f43f5e',
                            backgroundColor: 'rgba(244, 63, 94, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#f43f5e',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', fetchLandingStats);

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
<?php /**PATH /Users/abscom23/Desktop/SIPS/resources/views/landing.blade.php ENDPATH**/ ?>