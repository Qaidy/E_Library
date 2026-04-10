<?php

namespace App\Livewire\Loans;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Loan;
use App\Models\Book;
use App\Models\User;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';

    // Form Peminjaman
    public bool $showModal = false;
    public ?int $userId = null;
    public ?int $bookId = null;
    public string $tanggalPinjam = '';
    public string $tanggalKembali = '';

    public function rules(): array
    {
        return [
            'userId'         => 'required|exists:users,id',
            'bookId'         => 'required|exists:books,id',
            'tanggalPinjam'  => 'required|date',
            'tanggalKembali' => 'required|date|after:tanggalPinjam',
        ];
    }

    public function mount(): void
    {
        $this->tanggalPinjam = now()->format('Y-m-d');
        $this->tanggalKembali = now()->addDays(7)->format('Y-m-d');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openModal(): void
    {
        $this->reset(['userId', 'bookId']);
        $this->tanggalPinjam = now()->format('Y-m-d');
        $this->tanggalKembali = now()->addDays(7)->format('Y-m-d');
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();
        $book = Book::find($this->bookId);

        if (!$book || $book->stok < 1) {
            session()->flash('error', 'Stok buku tidak tersedia!');
            return;
        }

        Loan::create([
            'user_id'         => $this->userId,
            'book_id'         => $this->bookId,
            'tanggal_pinjam'  => $this->tanggalPinjam,
            'tanggal_kembali' => $this->tanggalKembali,
            'status'          => 'dipinjam',
        ]);

        $book->decrement('stok');

        session()->flash('message', 'Peminjaman berhasil dicatat!');
        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function render()
    {
        $loans = Loan::with(['user', 'book.category'])
            ->when($this->search, fn($q) => $q->whereHas('book', fn($b) => $b->where('judul', 'like', "%{$this->search}%"))
                ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%")))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $users = User::where('role', 'user')->orderBy('name')->get();
        $books = Book::where('stok', '>', 0)->orderBy('judul')->get();

        return view('livewire.loans.index', [
            'loans' => $loans,
            'users' => $users,
            'books' => $books,
        ])->layout('layouts.app');
    }
}
