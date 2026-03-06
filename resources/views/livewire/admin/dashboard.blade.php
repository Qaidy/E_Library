<div class="space-y-8 animate-slide-up">
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-primary-600 to-primary-500 rounded-2xl p-6 sm:p-8 text-white relative overflow-hidden">
        {{-- Decorative --}}
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>

        <div class="relative z-10">
            <p class="text-primary-200 text-sm font-medium">{{ now()->translatedFormat('l, d F Y') }}</p>
            <h2 class="text-2xl sm:text-3xl font-bold mt-1">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-primary-100 mt-2 text-sm sm:text-base max-w-lg">Berikut adalah ringkasan perpustakaan Anda hari ini. Kelola buku, peminjaman, dan pengembalian dengan mudah.</p>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        {{-- Total Buku --}}
        <div class="stat-card group" style="--tw-before-bg: #6366f1;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Buku</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($totalBuku) }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center group-hover:bg-primary-100 transition-colors">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100">
                <a href="{{ route('books') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors flex items-center gap-1">
                    Lihat semua buku
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        {{-- Total User --}}
        <div class="stat-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Anggota</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($totalUser) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center group-hover:bg-emerald-100 transition-colors">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100">
                <span class="text-sm text-slate-400">Pengguna terdaftar</span>
            </div>
        </div>

        {{-- Peminjaman Aktif --}}
        <div class="stat-card group sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Peminjaman Aktif</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($totalPeminjamanAktif) }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100">
                <a href="{{ route('loans') }}" class="text-sm font-medium text-amber-600 hover:text-amber-700 transition-colors flex items-center gap-1">
                    Lihat peminjaman
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Quick Actions + Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Quick Actions --}}
        <div class="card p-6">
            <h3 class="text-base font-semibold text-slate-900 mb-4">Aksi Cepat</h3>
            <div class="space-y-2.5">
                <a href="{{ route('books') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors group">
                    <div class="w-10 h-10 bg-primary-50 rounded-lg flex items-center justify-center group-hover:bg-primary-100 transition-colors">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-700">Tambah Buku Baru</p>
                        <p class="text-xs text-slate-400">Kelola koleksi perpustakaan</p>
                    </div>
                </a>
                <a href="{{ route('loans') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors group">
                    <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-700">Catat Peminjaman</p>
                        <p class="text-xs text-slate-400">Proses peminjaman buku</p>
                    </div>
                </a>
                <a href="{{ route('returns') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors group">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center group-hover:bg-emerald-100 transition-colors">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-700">Proses Pengembalian</p>
                        <p class="text-xs text-slate-400">Terima buku kembali</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="card p-6 lg:col-span-2">
            <h3 class="text-base font-semibold text-slate-900 mb-4">Aktivitas Terbaru</h3>
            <div class="empty-state">
                <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="empty-state-title">Belum ada aktivitas</p>
                <p class="empty-state-text">Aktivitas peminjaman dan pengembalian akan muncul di sini.</p>
            </div>
        </div>
    </div>
</div>
