<div class="space-y-6 animate-slide-up">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="page-header mb-0">
            <h1 class="page-title">Kelola Buku</h1>
            <p class="page-subtitle">Kelola koleksi buku perpustakaan Anda</p>
        </div>
        <button wire:click="openModal" class="btn-primary" id="btn-add-book">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Buku
        </button>
    </div>

    {{-- Search, Filter & View Toggle --}}
    <div class="card p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari judul, penulis, atau ISBN..."
                    class="input input-with-icon" id="search-books">
            </div>
            <select wire:model.live="categoryFilter" class="select sm:w-48" id="filter-category">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            {{-- View Toggle --}}
            <div class="view-toggle flex-shrink-0">
                <button wire:click="$set('viewMode', 'table')"
                        class="view-toggle-btn {{ $viewMode === 'table' ? 'active' : '' }}" title="Tampilan Tabel">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </button>
                <button wire:click="$set('viewMode', 'grid')"
                        class="view-toggle-btn {{ $viewMode === 'grid' ? 'active' : '' }}" title="Tampilan Grid">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Table View --}}
    @if($viewMode === 'table')
    <div class="table-container">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-16">No</th>
                        <th>Buku</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>File</th>
                        <th class="w-32 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr wire:key="book-{{ $book->id }}">
                            <td class="font-medium text-slate-400">{{ $loop->iteration + ($books->currentPage() - 1) * $books->perPage() }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="book-cover book-cover-sm rounded-md flex-shrink-0">
                                        <img src="{{ $book->cover_url }}" alt="{{ $book->judul }}">
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('books.show', $book) }}" class="font-semibold text-slate-900 hover:text-primary-600 transition-colors truncate block">{{ $book->judul }}</a>
                                        <p class="text-xs text-slate-500 truncate">{{ $book->penulis }}</p>
                                        @if($book->isbn)
                                            <p class="text-xs text-slate-400 mt-0.5">ISBN: {{ $book->isbn }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($book->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          style="background-color: {{ $book->category->color }}15; color: {{ $book->category->color }}">
                                        {{ $book->category->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $book->stok > 0 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $book->stok }} eks
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1.5">
                                    @if($book->hasCover())
                                        <span class="w-5 h-5 rounded bg-emerald-50 flex items-center justify-center" title="Cover tersedia">
                                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                    @endif
                                    @if($book->hasFile())
                                        <span class="w-5 h-5 rounded bg-red-50 flex items-center justify-center" title="PDF tersedia">
                                            <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="editBook({{ $book->id }})"
                                            class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors"
                                            title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $book->id }})"
                                            class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state py-8">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <p class="empty-state-title">Tidak ada buku ditemukan</p>
                                    <p class="empty-state-text">Mulai tambahkan buku ke perpustakaan Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($books->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $books->links() }}</div>
        @endif
    </div>
    @endif

    {{-- Grid View --}}
    @if($viewMode === 'grid')
    <div>
        @if($books->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                @foreach($books as $book)
                    <div wire:key="book-grid-{{ $book->id }}" class="book-card group">
                        <div class="book-cover-wrapper">
                            <img src="{{ $book->cover_url }}" alt="{{ $book->judul }}">
                            <div class="book-overlay">
                                <div class="flex gap-2">
                                    <button wire:click="editBook({{ $book->id }})"
                                            class="p-2 bg-white/90 text-slate-700 rounded-lg hover:bg-white transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $book->id }})"
                                            class="p-2 bg-red-500/90 text-white rounded-lg hover:bg-red-600 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="book-info">
                            <h4 class="font-semibold text-slate-900 text-sm truncate" title="{{ $book->judul }}">{{ $book->judul }}</h4>
                            <p class="text-xs text-slate-500 truncate mt-0.5">{{ $book->penulis }}</p>
                            <div class="flex items-center justify-between mt-2">
                                @if($book->category)
                                    <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-full"
                                          style="background-color: {{ $book->category->color }}15; color: {{ $book->category->color }}">
                                        {{ $book->category->name }}
                                    </span>
                                @else
                                    <span></span>
                                @endif
                                <span class="badge {{ $book->stok > 0 ? 'badge-success' : 'badge-danger' }} text-[10px] px-1.5 py-0">
                                    {{ $book->stok }}
                                </span>
                            </div>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="empty-state-title">Tidak ada buku ditemukan</p>
                    <p class="empty-state-text">Mulai tambahkan buku ke perpustakaan Anda.</p>
                </div>
            </div>
        @endif
    </div>
    @endif

    {{-- Modal Form (Create/Edit) --}}
    @if ($showModal)
        <div class="modal-backdrop" x-data x-on:keydown.escape.window="$wire.closeModal()">
            <div class="modal max-w-3xl max-h-[90vh] flex flex-col" @click.away="$wire.closeModal()">
                <div class="modal-header flex-shrink-0">
                    <h3 class="text-lg font-semibold text-slate-900">{{ $isEdit ? 'Edit Buku' : 'Tambah Buku Baru' }}</h3>
                    <p class="text-sm text-slate-500 mt-0.5">{{ $isEdit ? 'Perbarui informasi buku' : 'Isi detail buku yang ingin ditambahkan' }}</p>
                </div>

                <form wire:submit.prevent="save" class="flex flex-col flex-1 overflow-hidden">
                    <div class="modal-body overflow-y-auto flex-1">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            {{-- Left: Cover Upload --}}
                            <div class="lg:col-span-1 space-y-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Cover Buku</label>

                                {{-- Cover Preview / Upload --}}
                                <div x-data="{ isDragging: false }"
                                     x-on:dragover.prevent="isDragging = true"
                                     x-on:dragleave.prevent="isDragging = false"
                                     x-on:drop.prevent="isDragging = false">

                                    @if ($coverImage)
                                        {{-- New upload preview --}}
                                        <div class="relative">
                                            <div class="file-upload-preview" style="aspect-ratio: 3/4;">
                                                <img src="{{ $coverImage->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                                            </div>
                                            <button type="button" wire:click="$set('coverImage', null)"
                                                    class="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-lg shadow-sm hover:bg-red-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @elseif ($existingCover)
                                        {{-- Existing cover --}}
                                        <div class="relative">
                                            <div class="file-upload-preview" style="aspect-ratio: 3/4;">
                                                <img src="{{ asset('storage/' . $existingCover) }}" alt="Cover" class="w-full h-full object-cover">
                                            </div>
                                            <button type="button" wire:click="removeCover"
                                                    class="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-lg shadow-sm hover:bg-red-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        {{-- Upload zone --}}
                                        <label class="file-upload" :class="{ 'border-primary-400 bg-primary-50': isDragging }" style="aspect-ratio: 3/4;">
                                            <input type="file" wire:model="coverImage" accept="image/jpeg,image/png,image/webp" class="sr-only">
                                            <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <p class="text-xs text-slate-500 text-center">Klik atau seret gambar<br><span class="text-slate-400">JPG, PNG, WebP (maks 2MB)</span></p>
                                        </label>
                                    @endif

                                    <div wire:loading wire:target="coverImage" class="mt-2">
                                        <div class="flex items-center gap-2 text-xs text-primary-600">
                                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            Mengunggah...
                                        </div>
                                    </div>
                                </div>
                                @error('coverImage') <span class="text-sm text-red-600 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Right: Book Details --}}
                            <div class="lg:col-span-2 space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul Buku <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="title" class="input" placeholder="Masukkan judul buku">
                                    @error('title') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Penulis <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model="penulis" class="input" placeholder="Nama penulis">
                                        @error('penulis') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Penerbit</label>
                                        <input type="text" wire:model="penerbit" class="input" placeholder="Nama penerbit">
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Stok <span class="text-red-500">*</span></label>
                                        <input type="number" wire:model="stok" class="input" min="0" placeholder="0">
                                        @error('stok') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Tahun</label>
                                        <input type="number" wire:model="tahun_terbit" class="input" placeholder="{{ date('Y') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1.5">ISBN</label>
                                        <input type="text" wire:model="isbn" class="input" placeholder="978-xxx">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Kategori</label>
                                    <select wire:model="categoryId" class="select">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
                                    <textarea wire:model="deskripsi" rows="3" class="input" placeholder="Deskripsi singkat tentang buku..."></textarea>
                                </div>

                                {{-- PDF Upload --}}
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1.5">File PDF</label>
                                    @if($existingFile)
                                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-slate-200">
                                            <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-700 truncate">File PDF tersimpan</p>
                                                <p class="text-xs text-slate-400">Unggah file baru untuk mengganti</p>
                                            </div>
                                        </div>
                                    @endif
                                    <label class="file-upload mt-2 {{ $bookFile ? 'has-file' : '' }}">
                                        <input type="file" wire:model="bookFile" accept="application/pdf" class="sr-only">
                                        @if($bookFile)
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span class="text-sm text-primary-700 font-medium">{{ $bookFile->getClientOriginalName() }}</span>
                                            </div>
                                        @else
                                            <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            <p class="text-xs text-slate-500">Klik untuk unggah PDF <span class="text-slate-400">(maks 10MB)</span></p>
                                        @endif
                                    </label>
                                    <div wire:loading wire:target="bookFile" class="mt-2">
                                        <div class="flex items-center gap-2 text-xs text-primary-600">
                                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            Mengunggah...
                                        </div>
                                    </div>
                                    @error('bookFile') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer flex-shrink-0">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                            <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <svg wire:loading wire:target="save" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            {{ $isEdit ? 'Perbarui' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteModal)
        <div class="modal-backdrop">
            <div class="modal max-w-sm">
                <div class="p-6 text-center">
                    <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-1">Hapus Buku?</h3>
                    <p class="text-sm text-slate-500 mb-6">Buku beserta file cover dan PDF akan dihapus permanen. Yakin ingin melanjutkan?</p>
                    <div class="flex items-center justify-center gap-3">
                        <button wire:click="$set('showDeleteModal', false)" class="btn-secondary">Batal</button>
                        <button wire:click="deleteBook" class="btn-danger">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
