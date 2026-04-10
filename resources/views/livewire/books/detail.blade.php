<div class="animate-slide-up">
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 mb-6">
            {{ session('error') }}
        </div>
    @endif

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-primary-600 transition-colors">Beranda</a>
        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-900 font-medium truncate">{{ $book->judul }}</span>
    </nav>

    {{-- Book Detail Hero --}}
    <div class="card overflow-hidden">
        <div class="flex flex-col md:flex-row gap-8 p-6 sm:p-8">
            {{-- Cover Image --}}
            <div class="flex-shrink-0 mx-auto md:mx-0">
                <div class="book-cover book-cover-lg rounded-xl shadow-elevated">
                    <img src="{{ $book->cover_url }}" alt="{{ $book->judul }}" class="rounded-xl">
                </div>
            </div>

            {{-- Book Info --}}
            <div class="flex-1 min-w-0 space-y-4">
                {{-- Title & Author --}}
                <div>
                    @if($book->category)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold mb-3"
                              style="background-color: {{ $book->category->color }}15; color: {{ $book->category->color }}">
                            {{ $book->category->name }}
                        </span>
                    @endif
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 leading-tight">{{ $book->judul }}</h1>
                    <p class="text-lg text-slate-500 mt-1">oleh <span class="font-medium text-slate-700">{{ $book->penulis }}</span></p>
                </div>

                {{-- Availability --}}
                <div class="flex items-center gap-3">
                    @if($book->isAvailable())
                        <span class="badge badge-success text-sm px-3 py-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Tersedia ({{ $book->stok }} eksemplar)
                        </span>
                    @else
                        <span class="badge badge-danger text-sm px-3 py-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Stok Habis
                        </span>
                    @endif
                </div>

                {{-- Metadata Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 py-4 border-t border-b border-slate-100">
                    @if($book->penerbit)
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider font-medium">Penerbit</p>
                        <p class="text-sm font-medium text-slate-700 mt-0.5">{{ $book->penerbit }}</p>
                    </div>
                    @endif
                    @if($book->tahun_terbit)
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider font-medium">Tahun</p>
                        <p class="text-sm font-medium text-slate-700 mt-0.5">{{ $book->tahun_terbit }}</p>
                    </div>
                    @endif
                    @if($book->isbn)
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider font-medium">ISBN</p>
                        <p class="text-sm font-medium text-slate-700 mt-0.5">{{ $book->isbn }}</p>
                    </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-3">
                    @auth
                        @if(auth()->user()->role !== 'admin')
                            @if($book->isAvailable())
                                <button wire:click="pinjamBuku" wire:loading.attr="disabled" class="btn-primary">
                                    <svg wire:loading.remove wire:target="pinjamBuku" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <span wire:loading.remove wire:target="pinjamBuku">Pinjam Buku</span>
                                    <span wire:loading wire:target="pinjamBuku">Memproses...</span>
                                </button>
                            @endif
                        @endif
                    @endauth

                    @if($book->hasFile())
                        <a href="{{ $book->book_file_url }}" target="_blank" class="btn-secondary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Description --}}
        @if($book->deskripsi)
        <div class="px-6 sm:px-8 pb-6 sm:pb-8">
            <h3 class="text-base font-semibold text-slate-900 mb-3">Deskripsi</h3>
            <div class="text-sm text-slate-600 leading-relaxed prose prose-sm max-w-none">
                {!! nl2br(e($book->deskripsi)) !!}
            </div>
        </div>
        @endif
    </div>

    {{-- Related Books --}}
    @if($relatedBooks->isNotEmpty())
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Buku Serupa</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($relatedBooks as $related)
                <a href="{{ route('books.show', $related) }}" wire:navigate class="book-card">
                    <div class="book-cover-wrapper">
                        <img src="{{ $related->cover_url }}" alt="{{ $related->judul }}">
                        <div class="book-overlay">
                            <span class="btn-sm bg-white/90 text-slate-900 rounded-lg font-medium text-xs px-3 py-1.5">Lihat Detail</span>
                        </div>
                    </div>
                    <div class="book-info">
                        <h4 class="font-semibold text-slate-900 text-sm truncate">{{ $related->judul }}</h4>
                        <p class="text-xs text-slate-500 truncate mt-0.5">{{ $related->penulis }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
