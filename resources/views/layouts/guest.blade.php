<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-Library') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            {{-- Left: Branded Panel --}}
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-600 via-primary-700 to-sidebar relative overflow-hidden">
                {{-- Decorative circles --}}
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-32 -right-32 w-[500px] h-[500px] bg-primary-400/10 rounded-full blur-3xl"></div>
                <div class="absolute top-1/4 right-1/4 w-64 h-64 bg-white/5 rounded-full blur-2xl"></div>

                {{-- Content --}}
                <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                    {{-- Logo --}}
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="text-white font-bold text-xl">E-Library</span>
                    </div>

                    {{-- Center text --}}
                    <div class="max-w-md">
                        <h2 class="text-4xl font-bold text-white leading-tight mb-4">
                            Kelola perpustakaan Anda dengan mudah.
                        </h2>
                        <p class="text-primary-200 text-lg leading-relaxed">
                            Sistem manajemen perpustakaan modern untuk mengelola koleksi buku, peminjaman, dan pengembalian secara efisien.
                        </p>

                        {{-- Stats preview --}}
                        <div class="mt-10 grid grid-cols-3 gap-6">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-white">1K+</p>
                                <p class="text-sm text-primary-300 mt-1">Koleksi Buku</p>
                            </div>
                            <div class="text-center">
                                <p class="text-3xl font-bold text-white">500+</p>
                                <p class="text-sm text-primary-300 mt-1">Anggota</p>
                            </div>
                            <div class="text-center">
                                <p class="text-3xl font-bold text-white">99%</p>
                                <p class="text-sm text-primary-300 mt-1">Kepuasan</p>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom --}}
                    <p class="text-primary-300 text-sm">&copy; {{ date('Y') }} E-Library. All rights reserved.</p>
                </div>
            </div>

            {{-- Right: Form Area --}}
            <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 bg-slate-50">
                {{-- Mobile logo --}}
                <div class="lg:hidden mb-8 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-slate-900 font-bold text-xl">E-Library</span>
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
