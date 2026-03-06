<?php

namespace App\Livewire\Returns;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Loan;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    // Konfirmasi pengembalian
    public bool $showModal = false;
    public ?int $loanId = null;
    public ?Loan $selectedLoan = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmReturn($id)
    {
        $this->loanId = $id;
        $this->selectedLoan = Loan::with(['user', 'book'])->find($id);
        $this->showModal = true;
    }

    public function processReturn()
    {
        if (!$this->loanId) return;

        $loan = Loan::find($this->loanId);
        
        if ($loan) {
            // Update status peminjaman
            $loan->update([
                'status' => 'dikembalikan',
                'tanggal_dikembalikan' => now(),
            ]);

            // Tambah stok buku
            $loan->book->increment('stok');

            session()->flash('message', 'Buku berhasil dikembalikan!');
        }

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->loanId = null;
        $this->selectedLoan = null;
    }

    public function render()
    {
        // Ambil peminjaman yang belum dikembalikan
        $loans = Loan::with(['user', 'book'])
            ->where('status', 'dipinjam')
            ->when($this->search, fn($q) => $q->whereHas('book', fn($b) => $b->where('judul', 'like', "%{$this->search}%"))
                ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%")))
            ->orderBy('tanggal_kembali', 'asc') // Urutkan berdasarkan batas kembali
            ->paginate(10);

        return view('livewire.returns.index', [
            'loans' => $loans,
        ])->layout('layouts.app');
    }
}