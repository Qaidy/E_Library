<div class="space-y-8 animate-slide-up">
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg p-4 -mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 -mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-primary-600 to-primary-500 rounded-2xl p-6 sm:p-8 text-white relative overflow-hidden">
        {{-- Decorative --}}
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>

        <div class="relative z-10">
            <p class="text-primary-200 text-sm font-medium">{{ now()->translatedFormat('l, d F Y') }}</p>
            <h2 class="text-2xl sm:text-3xl font-bold mt-1">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-primary-100 mt-2 text-sm sm:text-base max-w-lg">Jelajahi koleksi buku kami dan temukan bacaan menarik hari ini.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Kiri: Buku Terbaru (2 Kolom di Desktop) --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900">Buku Terbaru</h3>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($recentBooks as $book)
                    <div class="card p-4 flex gap-4 hover:border-primary-200 transition-colors group">
                        <div class="w-20 h-28 bg-slate-100 rounded-lg flex-shrink-0 flex items-center justify-center border border-slate-200 relative overflow-hidden">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="flex flex-col flex-1 min-w-0">
                            <h4 class="font-semibold text-slate-900 truncate group-hover:text-primary-600 transition-colors" title="{{ $book->judul }}">
                                {{ $book->judul }}
                            </h4>
                            <p class="text-sm text-slate-500 truncate mb-2">{{ $book->penulis }}</p>
                            <div class="mt-auto flex flex-col gap-2">
                                <div class="flex items-center justify-between">
                                    <span class="badge {{ $book->stok > 0 ? 'badge-success' : 'badge-danger' }} text-xs">
                                        {{ $book->stok > 0 ? 'Tersedia' : 'Habis' }}
                                    </span>
                                    @if($book->kategori)
                                        <span class="text-xs text-slate-400">{{ $book->kategori }}</span>
                                    @endif
                                </div>
                                <div class="flex justify-end mt-1">
                                    @if($book->stok > 0)
                                        <button wire:click="pinjamBuku({{ $book->id }})" class="text-xs bg-primary-100 text-primary-700 hover:bg-primary-200 px-3 py-1.5 rounded-md font-medium transition-colors">
                                            Pinjam
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full empty-state py-8">
                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <p class="empty-state-title">Belum ada buku</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Kolom Kanan: Peminjaman Aktif --}}
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-900">Sedang Dipinjam</h3>
            
            <div class="card p-0 overflow-hidden divide-y divide-slate-100">
                @forelse($myLoans as $loan)
                    <div class="p-4 flex flex-col gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-slate-900 truncate" title="{{ $loan->book->judul ?? 'Buku Dihapus' }}">
                                    {{ $loan->book->judul ?? 'Buku tidak ditemukan' }}
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs {{ $loan->isOverdue() ? 'text-red-600 font-semibold' : 'text-slate-500' }}">
                                        Kembali: {{ $loan->tanggal_kembali->format('d M') }}
                                    </span>
                                    @if($loan->isOverdue())
                                        <span class="badge badge-danger text-[10px] px-1.5 py-0">Terlambat</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button wire:click="kembalikanBuku({{ $loan->id }})" class="text-xs bg-emerald-100 text-emerald-700 hover:bg-emerald-200 px-3 py-1.5 rounded-md font-medium transition-colors">
                                Kembalikan
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state py-8">
                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="empty-state-title text-sm">Tidak ada pinjaman gantung</p>
                        <p class="empty-state-text text-xs">Anda belum meminjam buku apa pun saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
