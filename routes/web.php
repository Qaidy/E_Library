<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Books\Index as BooksIndex;
use App\Livewire\Loans\Index as LoansIndex;
use App\Livewire\Returns\Index as ReturnsIndex;
use Illuminate\Support\Facades\Route;

// Halaman utama → langsung ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard → cek role, arahkan ke dashboard yang sesuai
Route::get('/dashboard', function () {
    $role = auth()->user()->role ?? 'user';
    if ($role === 'admin') {
        return app(AdminDashboard::class)();
    }

    return app(\App\Livewire\UserDashboard::class)();
})->middleware(['auth', 'verified'])->name('dashboard');

// Halaman-halaman yang butuh login
Route::middleware('auth')->group(function () {
    Route::get('/books', BooksIndex::class)->name('books');
    Route::get('/loans', LoansIndex::class)->name('loans');
    Route::get('/returns', ReturnsIndex::class)->name('returns');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
