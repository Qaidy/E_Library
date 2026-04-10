<div class="space-y-6 animate-slide-up">
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300"
             class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg p-4 flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300"
             class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-primary-600 via-primary-500 to-indigo-500 rounded-2xl p-6 sm:p-8 text-white relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>
        <div class="absolute top-1/2 right-1/4 w-24 h-24 bg-white/5 rounded-full blur-lg"></div>

        <div class="relative z-10">
            <p class="text-primary-200 text-sm font-medium">{{ now()->translatedFormat('l, d F Y') }}</p>
            <h2 class="text-2xl sm:text-3xl font-bold mt-1">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-primary-100 mt-2 text-sm sm:text-base max-w-lg">Jelajahi koleksi buku kami dan temukan bacaan menarik hari ini.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Main Content: Book Catalog --}}
        <div class="lg:col-span-3 space-y-5">
            {{-- Search Bar --}}
            <div class="card p-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari judul atau penulis..."
                            class="input input-with-icon" id="search-catalog">
                    </div>
                    <select wire:model.live="categoryFilter" class="select sm:w-48" id="filter-category-user">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->books_count }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Category Chips --}}
            <div class="flex flex-wrap gap-2">
                <button wire:click="$set('categoryFilter', '')"
                        class="category-chip {{ $categoryFilter === '' ? 'active' : '' }}">
                    Semua
                </button>
                @foreach($categories as $cat)
                    @if($cat->books_count > 0)
                    <button wire:click="$set('categoryFilter', '{{ $cat->id }}')"
                            class="category-chip {{ $categoryFilter == $cat->id ? 'active' : '' }}">
                        <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: {{ $cat->color }}"></span>
                        {{ $cat->name }}
                    </button>
                    @endif
                @endforeach
            </div>

            {{-- Book Grid --}}
            <div>
                @if($books->isNotEmpty())
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($books as $book)
                            <div wire:key="catalog-{{ $book->id }}" class="book-card group">
                                <a href="{{ route('books.show', $book) }}" class="block">
                                    <div class="book-cover-wrapper">
                                        <img src="{{ $book->cover_url }}" alt="{{ $book->judul }}">
                                        <div class="book-overlay">
                                            <span class="btn-sm bg-white/90 text-slate-900 rounded-lg font-medium text-xs px-3 py-1.5">
                                                Lihat Detail
                                            </span>
                                        </div>
                                    </div>
                                </a>
                                <div class="book-info">
                                    <a href="{{ route('books.show', $book) }}" class="block">
                                        <h4 class="font-semibold text-slate-900 text-sm truncate group-hover:text-primary-600 transition-colors" title="{{ $book->judul }}">
                                            {{ $book->judul }}
                                        </h4>
                                        <p class="text-xs text-slate-500 truncate mt-0.5">{{ $book->penulis }}</p>
                                    </a>
                                    <div class="flex items-center justify-between mt-2.5">
                                        @if($book->category)
                                            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-full"
                                                  style="background-color: {{ $book->category->color }}15; color: {{ $book->category->color }}">
                                                {{ $book->category->name }}
                                            </span>
                                        @else
                                            <span></span>
                                        @endif
                                        <span class="text-[10px] font-medium {{ $book->stok > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                            {{ $book->stok > 0 ? 'Tersedia' : 'Habis' }}
                                        </span>
                                    </div>
                                    @if($book->stok > 0)
                                        <button wire:click="pinjamBuku({{ $book->id }})" wire:loading.attr="disabled"
                                                class="w-full mt-3 text-xs bg-primary-50 text-primary-700 hover:bg-primary-100 px-3 py-2 rounded-lg font-medium transition-colors disabled:opacity-50 flex items-center justify-center gap-1.5">
                                            <span wire:loading.remove wire:target="pinjamBuku({{ $book->id }})">Pinjam Sekarang</span>
                                            <span wire:loading wire:target="pinjamBuku({{ $book->id }})">
                                                <svg class="w-4 h-4 animate-spin inline" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($books->hasPages())
                        <div class="mt-6">{{ $books->links() }}</div>
                    @endif
                @else
                    <div class="card">
                        <div class="empty-state py-12">
                            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <p class="empty-state-title">Tidak ada buku ditemukan</p>
                            <p class="empty-state-text">Coba ubah kata kunci pencarian atau filter kategori.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar: Active Loans --}}
        <div class="space-y-5">
            <div class="card p-5 sticky top-20">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pinjaman Aktif
                    @if($myLoans->isNotEmpty())
                        <span class="badge badge-warning text-[10px] px-1.5 py-0">{{ $myLoans->count() }}</span>
                    @endif
                </h3>

                @forelse($myLoans as $loan)
                    <div wire:key="loan-{{ $loan->id }}" class="flex items-start gap-3 py-3 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                        <div class="book-cover book-cover-sm rounded-md flex-shrink-0">
                            <img src="{{ $loan->book->cover_url ?? asset('images/book-placeholder.svg') }}"
                                 alt="{{ $loan->book->judul ?? 'Buku' }}">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-900 text-sm truncate" title="{{ $loan->book->judul ?? 'Buku Dihapus' }}">
                                {{ $loan->book->judul ?? 'Buku tidak ditemukan' }}
                            </p>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="text-xs {{ $loan->isOverdue() ? 'text-red-600 font-semibold' : 'text-slate-500' }}">
                                    {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y') }}
                                </span>
                                @if($loan->isOverdue())
                                    <span class="badge badge-danger text-[10px] px-1 py-0">!</span>
                                @endif
                            </div>
                            <button wire:click="kembalikanBuku({{ $loan->id }})" wire:loading.attr="disabled"
                                    class="mt-2 text-[11px] bg-emerald-50 text-emerald-700 hover:bg-emerald-100 px-2.5 py-1 rounded-md font-medium transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="kembalikanBuku({{ $loan->id }})">Kembalikan</span>
                                <span wire:loading wire:target="kembalikanBuku({{ $loan->id }})">...</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500 font-medium">Tidak ada pinjaman</p>
                        <p class="text-xs text-slate-400 mt-0.5">Anda belum meminjam buku.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
