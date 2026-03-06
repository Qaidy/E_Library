<div class="space-y-6 animate-slide-up">
    {{-- Page Header --}}
    <div class="page-header">
        <h1 class="page-title">Pengembalian</h1>
        <p class="page-subtitle">Proses pengembalian buku yang sedang dipinjam</p>
    </div>

    {{-- Search Bar --}}
    <div class="card p-4">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input wire:model.live="search" type="text" placeholder="Cari nama peminjam atau judul buku..."
                class="input input-with-icon">
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
                        <th>Jatuh Tempo</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-primary-50 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs font-semibold text-primary-600">{{ strtoupper(substr($loan->user->name, 0, 1)) }}</span>
                                    </div>
                                    <span class="font-medium text-slate-900">{{ $loan->user->name }}</span>
                                </div>
                            </td>
                            <td class="font-medium text-slate-700">{{ $loan->book->judul }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="{{ $loan->isOverdue() ? 'text-red-600 font-semibold' : 'text-slate-600' }}">
                                        {{ $loan->tanggal_kembali->format('d M Y') }}
                                    </span>
                                    @if($loan->isOverdue())
                                        <span class="badge badge-danger">Terlambat</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-right">
                                <button wire:click="confirmReturn({{ $loan->id }})" class="btn-success btn-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Proses Kembali
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state py-8">
                                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="empty-state-title">Semua buku sudah dikembalikan</p>
                                    <p class="empty-state-text">Tidak ada peminjaman aktif saat ini.</p>
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

    {{-- Modal Konfirmasi --}}
    @if($showModal && $selectedLoan)
        <div class="modal-backdrop">
            <div class="modal max-w-sm">
                <div class="p-6 text-center">
                    <div class="w-14 h-14 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Konfirmasi Pengembalian</h3>
                    <p class="text-sm text-slate-500 mb-1">Terima buku <strong class="text-slate-700">{{ $selectedLoan->book->judul }}</strong></p>
                    <p class="text-sm text-slate-500 mb-6">dari <strong class="text-slate-700">{{ $selectedLoan->user->name }}</strong>?</p>
                    <div class="flex items-center justify-center gap-3">
                        <button wire:click="$set('showModal', false)" class="btn-secondary">Batal</button>
                        <button wire:click="processReturn" class="btn-success">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Ya, Terima Buku
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>