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
    <title>SIPS - Loading</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
                        },
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
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'scale-in': 'scaleIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.5)', opacity: '0' },
                            '50%': { transform: 'scale(1.1)' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
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
        .splash-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
        }
    </style>
</head>
<body class="splash-gradient min-h-screen flex flex-col items-center justify-center overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <!-- Floating Orbs -->
        <div class="absolute top-[10%] left-[10%] w-64 h-64 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-[10%] right-[10%] w-80 h-80 bg-white/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-white/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 text-center px-6">
        <!-- Logo Container -->
        <div class="mb-12">
            <!-- Animated Logo Circle -->
            <div class="relative inline-flex">
                <!-- Pulse Ring -->
                <div class="absolute inset-0 rounded-3xl bg-white/30 animate-pulse"></div>
                
                <!-- Logo Box -->
                <div class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-3xl bg-white shadow-2xl flex items-center justify-center animate-scale-in">
                    <!-- Shield Icon -->
                    <svg class="w-14 h-14 sm:w-16 sm:h-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- App Name -->
        <div class="mb-16 animate-slide-up" style="animation-delay: 0.2s;">
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-3 tracking-tight">
                SIPS
            </h1>
            <p class="text-white/80 text-lg sm:text-xl font-medium">
                Sistem Informasi Pelanggaran Siswa
            </p>
        </div>

        <!-- Loading Indicator -->
        <div class="animate-fade-in" style="animation-delay: 0.4s;">
            <!-- Bouncing Dots -->
            <div class="flex items-center justify-center gap-3 mb-8">
                <div class="w-3 h-3 bg-white/70 rounded-full animate-bounce"></div>
                <div class="w-3 h-3 bg-white/70 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
                <div class="w-3 h-3 bg-white/70 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
            </div>
            
            <!-- Progress Bar -->
            <div class="w-48 sm:w-64 mx-auto mb-6">
                <div class="h-1.5 bg-white/30 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full" style="width: 30%; animation: loading 3s ease-in-out infinite;"></div>
                </div>
            </div>
            
            <!-- Loading Text -->
            <p class="text-white/60 text-sm">Memuat aplikasi...</p>
        </div>

        <!-- Skip Button -->
        <div class="mt-16 animate-fade-in" style="animation-delay: 0.6s;">
            <a href="<?php echo e(route('landing')); ?>" class="inline-flex items-center gap-2 px-6 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-medium rounded-full transition-all duration-300 backdrop-blur-sm">
                <span>Lewati</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Bottom Info -->
    <div class="absolute bottom-8 left-0 right-0 text-center animate-fade-in" style="animation-delay: 0.8s;">
        <p class="text-white/50 text-xs">
            &copy; <?php echo e(date('Y')); ?> SIPS. All rights reserved.
        </p>
    </div>

    <style>
        @keyframes loading {
            0% { width: 0%; margin-left: 0; }
            50% { width: 60%; margin-left: 20%; }
            100% { width: 0%; margin-left: 100%; }
        }
    </style>

    <script>
        // Auto redirect after 3 seconds
        setTimeout(function() {
            window.location.href = "<?php echo e(route('landing')); ?>";
        }, 3000);

        // Optional: Listen for any click to skip
        document.addEventListener('click', function() {
            window.location.href = "<?php echo e(route('landing')); ?>";
        });

        // Prevent accidental back navigation
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            history.go(1);
        };
    </script>
</body>
</html>

<?php /**PATH /Users/abscom23/Desktop/SIPS/resources/views/splash.blade.php ENDPATH**/ ?>