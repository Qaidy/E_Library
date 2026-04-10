<?php

namespace App\Livewire\Books;

use Livewire\Component;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;

class Detail extends Component
{
    public Book $book;

    public function mount(Book $book): void
    {
        $this->book = $book->load('category');
    }

    /**
     * Borrow this book (for authenticated users).
     */
    public function pinjamBuku(): void
    {
        if (!Auth::check()) {
            return;
        }

        if ($this->book->stok < 1) {
            session()->flash('error', 'Stok buku tidak tersedia.');
            return;
        }

        // Check if user already has this book borrowed
        $existingLoan = Loan::where('user_id', Auth::id())
            ->where('book_id', $this->book->id)
            ->where('status', 'dipinjam')
            ->first();

        if ($existingLoan) {
            session()->flash('error', 'Anda masih meminjam buku ini.');
            return;
        }

        Loan::create([
            'user_id'         => Auth::id(),
            'book_id'         => $this->book->id,
            'tanggal_pinjam'  => now()->format('Y-m-d'),
            'tanggal_kembali' => now()->addDays(7)->format('Y-m-d'),
            'status'          => 'dipinjam',
        ]);

        $this->book->decrement('stok');
        $this->book->refresh();
        session()->flash('success', 'Buku berhasil dipinjam!');
    }

    public function render()
    {
        $relatedBooks = Book::with('category')
            ->where('id', '!=', $this->book->id)
            ->when($this->book->category_id, fn($q) => $q->where('category_id', $this->book->category_id))
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('livewire.books.detail', [
            'relatedBooks' => $relatedBooks,
        ])->layout('layouts.app', ['title' => $this->book->judul]);
    }
}
