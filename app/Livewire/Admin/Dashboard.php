<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;

class Dashboard extends Component
{
    public int $totalBuku = 0;
    public int $totalUser = 0;
    public int $totalPeminjamanAktif = 0;

    public function mount(): void
    {
        $this->totalBuku = Book::count();
        $this->totalUser = User::count();
        $this->totalPeminjamanAktif = Loan::where('status', 'dipinjam')->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.app', ['title' => 'Dashboard Admin']);
    }
}
