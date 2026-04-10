<div class="space-y-8 animate-slide-up">
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-primary-600 via-primary-500 to-indigo-500 rounded-2xl p-6 sm:p-8 text-white relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>
        <div class="absolute top-1/2 right-1/4 w-24 h-24 bg-white/5 rounded-full blur-lg"></div>

        <div class="relative z-10">
            <p class="text-primary-200 text-sm font-medium">{{ now()->translatedFormat('l, d F Y') }}</p>
            <h2 class="text-2xl sm:text-3xl font-bold mt-1">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-primary-100 mt-2 text-sm sm:text-base max-w-lg">Berikut adalah ringkasan perpustakaan Anda hari ini. Kelola buku, peminjaman, dan pengembalian dengan mudah.</p>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
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
                    Kelola buku
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        {{-- Total Anggota --}}
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
        <div class="stat-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Dipinjam</p>
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

        {{-- Terlambat --}}
        <div class="stat-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Terlambat</p>
                    <p class="text-3xl font-bold {{ $totalOverdue > 0 ? 'text-red-600' : 'text-slate-900' }} mt-1">{{ number_format($totalOverdue) }}</p>
                </div>
                <div class="w-12 h-12 {{ $totalOverdue > 0 ? 'bg-red-50' : 'bg-slate-50' }} rounded-xl flex items-center justify-center group-hover:{{ $totalOverdue > 0 ? 'bg-red-100' : 'bg-slate-100' }} transition-colors">
                    <svg class="w-6 h-6 {{ $totalOverdue > 0 ? 'text-red-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100">
                <a href="{{ route('returns') }}" class="text-sm font-medium {{ $totalOverdue > 0 ? 'text-red-600 hover:text-red-700' : 'text-slate-400' }} transition-colors flex items-center gap-1">
                    {{ $totalOverdue > 0 ? 'Tangani sekarang' : 'Semua tepat waktu' }}
                    @if($totalOverdue > 0)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    @endif
                </a>
            </div>
        </div>
    </div>

    {{-- Quick Actions + Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
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

        {{-- Recent Activity Feed --}}
        <div class="card p-6 lg:col-span-2">
            <h3 class="text-base font-semibold text-slate-900 mb-4">Aktivitas Terbaru</h3>
            @if($recentLoans->isNotEmpty())
                <div class="space-y-4">
                    @foreach($recentLoans as $loan)
                        <div class="flex items-start gap-3 group">
                            {{-- Book Cover Thumbnail --}}
                            <div class="book-cover book-cover-sm rounded-md flex-shrink-0">
                                <img src="{{ $loan->book->cover_url ?? asset('images/book-placeholder.svg') }}"
                                     alt="{{ $loan->book->judul ?? 'Buku' }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-slate-900">
                                    <span class="font-medium">{{ $loan->user->name }}</span>
                                    <span class="text-slate-400">
                                        {{ $loan->status === 'dipinjam' ? 'meminjam' : 'mengembalikan' }}
                                    </span>
                                    <span class="font-medium truncate">{{ $loan->book->judul ?? 'Buku dihapus' }}</span>
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="badge {{ $loan->status === 'dipinjam' ? 'badge-warning' : 'badge-success' }} text-[10px]">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $loan->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state py-6">
                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="empty-state-title text-sm">Belum ada aktivitas</p>
                    <p class="empty-state-text text-xs">Aktivitas peminjaman dan pengembalian akan muncul di sini.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Books --}}
    @if($recentBooks->isNotEmpty())
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-slate-900">Buku Terbaru</h3>
            <a href="{{ route('books') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 flex items-center gap-1">
                Lihat semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($recentBooks as $book)
                <a href="{{ route('books.show', $book) }}" class="book-card">
                    <div class="book-cover-wrapper">
                        <img src="{{ $book->cover_url }}" alt="{{ $book->judul }}">
                        <div class="book-overlay">
                            <span class="btn-sm bg-white/90 text-slate-900 rounded-lg font-medium text-xs px-3 py-1.5">Lihat Detail</span>
                        </div>
                    </div>
                    <div class="book-info">
                        <h4 class="font-semibold text-slate-900 text-sm truncate">{{ $book->judul }}</h4>
                        <p class="text-xs text-slate-500 truncate mt-0.5">{{ $book->penulis }}</p>
                        @if($book->category)
                            <span class="inline-block mt-2 text-[10px] font-medium px-2 py-0.5 rounded-full"
                                  style="background-color: {{ $book->category->color }}15; color: {{ $book->category->color }}">
                                {{ $book->category->name }}
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
