<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Category;

class Dashboard extends Component
{
    public int $totalBuku = 0;
    public int $totalUser = 0;
    public int $totalPeminjamanAktif = 0;
    public int $totalKategori = 0;
    public int $totalOverdue = 0;

    public function mount(): void
    {
        $this->totalBuku = Book::count();
        $this->totalUser = User::where('role', 'user')->count();
        $this->totalPeminjamanAktif = Loan::where('status', 'dipinjam')->count();
        $this->totalKategori = Category::count();
        $this->totalOverdue = Loan::where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', now())
            ->count();
    }

    public function render()
    {
        $recentLoans = Loan::with(['user', 'book'])
            ->latest()
            ->take(5)
            ->get();

        $recentBooks = Book::with('category')
            ->latest()
            ->take(4)
            ->get();

        return view('livewire.admin.dashboard', [
            'recentLoans' => $recentLoans,
            'recentBooks' => $recentBooks,
        ])->layout('layouts.app', ['title' => 'Dashboard Admin']);
    }
}
