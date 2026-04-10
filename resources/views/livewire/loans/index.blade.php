<div class="space-y-6 animate-slide-up">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="page-header mb-0">
            <h1 class="page-title">Peminjaman</h1>
            <p class="page-subtitle">Kelola data peminjaman buku perpustakaan</p>
        </div>
        <button wire:click="openModal" class="btn-primary" id="btn-add-loan">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Catat Peminjaman
        </button>
    </div>

    {{-- Search & Filter --}}
    <div class="card p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari peminjam atau buku..."
                    class="input input-with-icon">
            </div>
            <select wire:model.live="status" class="select sm:w-48">
                <option value="">Semua Status</option>
                <option value="dipinjam">Dipinjam</option>
                <option value="dikembalikan">Dikembalikan</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-container">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr wire:key="loan-{{ $loan->id }}">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-primary-50 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs font-semibold text-primary-600">{{ strtoupper(substr($loan->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-medium text-slate-900 block truncate">{{ $loan->user->name }}</span>
                                        <span class="text-xs text-slate-400 truncate block">{{ $loan->user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="book-cover book-cover-sm rounded-md flex-shrink-0">
                                        <img src="{{ $loan->book->cover_url ?? asset('images/book-placeholder.svg') }}"
                                             alt="{{ $loan->book->judul ?? '' }}">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-slate-700 truncate">{{ $loan->book->judul ?? 'Buku dihapus' }}</p>
                                        @if($loan->book && $loan->book->category)
                                            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-full inline-block mt-0.5"
                                                  style="background-color: {{ $loan->book->category->color }}15; color: {{ $loan->book->category->color }}">
                                                {{ $loan->book->category->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="space-y-0.5">
                                    <p class="text-xs text-slate-500">
                                        <span class="text-slate-400">Pinjam:</span> {{ $loan->tanggal_pinjam->format('d M Y') }}
                                    </p>
                                    <p class="text-xs {{ $loan->status == 'dipinjam' && $loan->tanggal_kembali < now() ? 'text-red-600 font-semibold' : 'text-slate-500' }}">
                                        <span class="text-slate-400">Kembali:</span> {{ $loan->tanggal_kembali->format('d M Y') }}
                                    </p>
                                </div>
                            </td>
                            <td>
                                @if($loan->status == 'dipinjam' && $loan->tanggal_kembali < now())
                                    <span class="badge badge-danger">Terlambat</span>
                                @elseif($loan->status == 'dipinjam')
                                    <span class="badge badge-warning">Dipinjam</span>
                                @else
                                    <span class="badge badge-success">Dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state py-8">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                    <p class="empty-state-title">Belum ada peminjaman</p>
                                    <p class="empty-state-text">Data peminjaman akan ditampilkan di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($loans->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $loans->links() }}</div>
        @endif
    </div>

    {{-- Modal Form --}}
    @if ($showModal)
        <div class="modal-backdrop" x-data x-on:keydown.escape.window="$wire.closeModal()">
            <div class="modal max-w-lg" @click.away="$wire.closeModal()">
                <div class="modal-header">
                    <h3 class="text-lg font-semibold text-slate-900">Catat Peminjaman</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Isi detail peminjaman buku</p>
                </div>

                <form wire:submit.prevent="save">
                    <div class="modal-body space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Peminjam <span class="text-red-500">*</span></label>
                            <select wire:model="userId" class="select">
                                <option value="">Pilih Peminjam</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} — {{ $user->email }}</option>
                                @endforeach
                            </select>
                            @error('userId') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Buku <span class="text-red-500">*</span></label>
                            <select wire:model="bookId" class="select">
                                <option value="">Pilih Buku</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->judul }} (Sisa: {{ $book->stok }})</option>
                                @endforeach
                            </select>
                            @error('bookId') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Pinjam</label>
                                <input type="date" wire:model="tanggalPinjam" class="input">
                                @error('tanggalPinjam') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Kembali</label>
                                <input type="date" wire:model="tanggalKembali" class="input">
                                @error('tanggalKembali') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                            <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <svg wire:loading wire:target="save" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
