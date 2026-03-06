<div class="space-y-6 animate-slide-up">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="page-header mb-0">
            <h1 class="page-title">Kelola Buku</h1>
            <p class="page-subtitle">Kelola koleksi buku perpustakaan Anda</p>
        </div>
        <button wire:click="openModal" class="btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Buku
        </button>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="card p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari judul atau penulis..."
                    class="input input-with-icon">
            </div>
            <select wire:model.live="kategori" class="select sm:w-48">
                <option value="">Semua Kategori</option>
                @foreach ($kategoris as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-container">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-16">No</th>
                        <th>Buku</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th class="w-32 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr>
                            <td class="font-medium text-slate-400">{{ $loop->iteration }}</td>
                            <td>
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $book->judul }}</p>
                                    @if($book->isbn)
                                        <p class="text-xs text-slate-400 mt-0.5">ISBN: {{ $book->isbn }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="text-slate-600">{{ $book->penulis }}</td>
                            <td>
                                @if($book->kategori)
                                    <span class="badge badge-info">{{ $book->kategori }}</span>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $book->stok > 0 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $book->stok }} eks
                                </span>
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

    {{-- Modal Form (Create/Edit) --}}
    @if ($showModal)
        <div class="modal-backdrop">
            <div class="modal max-w-2xl">
                <div class="modal-header">
                    <h3 class="text-lg font-semibold text-slate-900">{{ $isEdit ? 'Edit Buku' : 'Tambah Buku Baru' }}</h3>
                    <p class="text-sm text-slate-500 mt-0.5">{{ $isEdit ? 'Perbarui informasi buku' : 'Isi detail buku yang ingin ditambahkan' }}</p>
                </div>

                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul Buku</label>
                                <input type="text" wire:model="title" class="input" placeholder="Masukkan judul buku">
                                @error('title') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Penulis</label>
                                <input type="text" wire:model="penulis" class="input" placeholder="Nama penulis">
                                @error('penulis') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Penerbit</label>
                                <input type="text" wire:model="penerbit" class="input" placeholder="Nama penerbit">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Stok</label>
                                <input type="number" wire:model="stok" class="input" min="0" placeholder="0">
                                @error('stok') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Kategori</label>
                                <input type="text" wire:model="kategoriInput" class="input" placeholder="Contoh: Fiksi">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">ISBN</label>
                                <input type="text" wire:model="isbn" class="input" placeholder="978-xxx-xxx">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Tahun Terbit</label>
                                <input type="number" wire:model="tahun_terbit" class="input" placeholder="{{ date('Y') }}">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
                                <textarea wire:model="deskripsi" rows="3" class="input" placeholder="Deskripsi singkat tentang buku..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" wire:click="$set('showModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
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
                    <p class="text-sm text-slate-500 mb-6">Buku yang dihapus tidak dapat dikembalikan. Yakin ingin melanjutkan?</p>
                    <div class="flex items-center justify-center gap-3">
                        <button wire:click="$set('showDeleteModal', false)" class="btn-secondary">Batal</button>
                        <button wire:click="deleteBook" class="btn-danger">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
