<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class UserDashboard extends Component
{
    use WithPagination;

    public string $search = '';
    public string $categoryFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function pinjamBuku(int $bookId): void
    {
        $book = Book::find($bookId);

        if (!$book) {
            session()->flash('error', 'Buku tidak ditemukan.');
            return;
        }

        if ($book->stok < 1) {
            session()->flash('error', 'Stok buku tidak tersedia.');
            return;
        }

        $existingLoan = Loan::where('user_id', Auth::id())
            ->where('book_id', $bookId)
            ->where('status', 'dipinjam')
            ->first();

        if ($existingLoan) {
            session()->flash('error', 'Anda masih meminjam buku ini.');
            return;
        }

        Loan::create([
            'user_id'         => Auth::id(),
            'book_id'         => $bookId,
            'tanggal_pinjam'  => now()->format('Y-m-d'),
            'tanggal_kembali' => now()->addDays(7)->format('Y-m-d'),
            'status'          => 'dipinjam',
        ]);

        $book->decrement('stok');
        session()->flash('success', 'Buku berhasil dipinjam!');
    }

    public function kembalikanBuku(int $loanId): void
    {
        $loan = Loan::where('id', $loanId)
            ->where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->first();

        if (!$loan) {
            session()->flash('error', 'Peminjaman tidak valid.');
            return;
        }

        $loan->update([
            'status'                => 'dikembalikan',
            'tanggal_dikembalikan'  => now(),
        ]);

        if ($loan->book) {
            $loan->book->increment('stok');
        }

        session()->flash('success', 'Buku berhasil dikembalikan!');
    }

    public function render()
    {
        $books = Book::with('category')
            ->when($this->search, fn($q) => $q->where('judul', 'like', "%{$this->search}%")
                ->orWhere('penulis', 'like', "%{$this->search}%"))
            ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        $categories = Category::withCount('books')->orderBy('name')->get();

        $myLoans = Loan::with('book')
            ->where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->get();

        return view('livewire.user-dashboard', [
            'books'      => $books,
            'categories' => $categories,
            'myLoans'    => $myLoans,
        ])->layout('layouts.app');
    }
}
