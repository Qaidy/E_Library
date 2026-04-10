<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Books\Index as BooksIndex;
use App\Livewire\Books\Detail as BookDetail;
use App\Livewire\Loans\Index as LoansIndex;
use App\Livewire\Returns\Index as ReturnsIndex;
use Illuminate\Support\Facades\Route;

// Landing page → redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard → role-based routing
Route::get('/dashboard', function () {
    $role = auth()->user()->role ?? 'user';
    if ($role === 'admin') {
        return app(AdminDashboard::class)();
    }

    return app(\App\Livewire\UserDashboard::class)();
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Admin routes
    Route::get('/books', BooksIndex::class)->name('books');
    Route::get('/loans', LoansIndex::class)->name('loans');
    Route::get('/returns', ReturnsIndex::class)->name('returns');

    // Book detail (available to all authenticated users)
    Route::get('/book/{book}', BookDetail::class)->name('books.show');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
