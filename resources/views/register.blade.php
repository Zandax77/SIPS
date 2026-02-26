<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SIPS">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <title>Registrasi - SIPS</title>
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
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
            20%, 40%, 60%, 80% { transform: translateX(4px); }
        }
        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-100 rounded-full opacity-50 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-100 rounded-full opacity-50 blur-3xl"></div>
    </div>

    <!-- PWA Install Button - Hidden by default, shown when PWA is installable -->
    <button id="install-app-btn" 
            class="hidden fixed bottom-6 right-6 z-50 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-full shadow-lg shadow-indigo-300 hover:shadow-xl transition-all duration-300 flex items-center gap-2 font-medium animate-bounce">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
        </svg>
        <span>Install App</span>
    </button>

    <!-- Registration Card -->
    <div class="relative w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-600 shadow-lg shadow-indigo-200 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-1">SIPS</h1>
            <p class="text-gray-500 text-sm">Sistem Informasi Pelanggaran Siswa</p>
        </div>

        <!-- Registration Form Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 p-8 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">Registrasi Petugas Baru</h2>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm flex items-center gap-2 animate-fade-in">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm animate-shake">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('petugas.register') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Name Input -->
                <div class="relative group">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', '') }}"
                            placeholder="Nama lengkap Anda"
                            required
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all duration-200"
                        >
                    </div>
                </div>

                <!-- Email Input -->
                <div class="relative group">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', '') }}"
                            placeholder="nama@email.com"
                            required
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all duration-200"
                        >
                    </div>
                </div>

                <!-- Jabatan Dropdown -->
                <div class="relative group">
                    <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <select
                            id="jabatan"
                            name="jabatan"
                            required
                            onchange="toggleKelasField()"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all duration-200 appearance-none cursor-pointer"
                        >
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="Kesiswaan" {{ old('jabatan') == 'Kesiswaan' ? 'selected' : '' }}>Kesiswaan</option>
                            <option value="Wali Kelas" {{ old('jabatan') == 'Wali Kelas' ? 'selected' : '' }}>Wali Kelas</option>
                            <option value="Guru BK" {{ old('jabatan') == 'Guru BK' ? 'selected' : '' }}>Guru BK</option>
                            <option value="OSIS" {{ old('jabatan') == 'OSIS' ? 'selected' : '' }}>OSIS (Catat Pelanggaran)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Kelas Dropdown (only for Wali Kelas) -->
                <div id="kelas-field" class="relative group hidden">
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <select
                            id="kelas"
                            name="kelas"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all duration-200 appearance-none cursor-pointer"
                        >
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas }}" {{ old('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    @error('kelas')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="relative group">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            minlength="6"
                            class="w-full pl-12 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all duration-200"
                        >
                        <!-- Show/Hide Password Toggle -->
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center"
                        >
                            <svg id="eye-icon" class="w-5 h-5 text-gray-400 hover:text-indigo-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="eye-off-icon" class="w-5 h-5 text-gray-400 hover:text-indigo-600 transition-colors duration-200 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Password Confirmation Input -->
                <div class="relative group">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="••••••••"
                            required
                            minlength="6"
                            class="w-full pl-12 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all duration-200"
                        >
                    </div>
                </div>

                <!-- Register Button -->
                <button
                    type="submit"
                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200"
                >
                    Daftar Sekarang
                </button>
            </form>

            <!-- Back to Login -->
            <div class="text-center mt-6">
                <p class="text-gray-500 text-sm">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium transition-colors duration-200">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-500 text-sm mt-6">
            &copy; {{ date('Y') }} SIPS. All rights reserved.
        </p>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                confirmPasswordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                confirmPasswordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }

        function toggleKelasField() {
            const jabatan = document.getElementById('jabatan').value;
            const kelasField = document.getElementById('kelas-field');

            if (jabatan === 'Wali Kelas') {
                kelasField.classList.remove('hidden');
            } else {
                kelasField.classList.add('hidden');
            }
        }

        // Check on page load if Jabatan is Wali Kelas
        document.addEventListener('DOMContentLoaded', function() {
            toggleKelasField();
        });

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

