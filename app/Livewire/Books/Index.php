<?php

namespace App\Livewire\Books;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    // Search & Filter
    public string $search = '';
    public string $categoryFilter = '';
    public string $viewMode = 'table'; // 'table' or 'grid'

    // Modal state
    public bool $showModal = false;
    public bool $isEdit = false;
    public ?int $bookId = null;

    // Form fields
    public string $title = '';
    public string $penulis = '';
    public string $penerbit = '';
    public ?int $tahun_terbit = null;
    public string $isbn = '';
    public int $stok = 1;
    public ?int $categoryId = null;
    public string $deskripsi = '';

    // File uploads
    public $coverImage = null;
    public $bookFile = null;
    public ?string $existingCover = null;
    public ?string $existingFile = null;

    // Delete confirmation
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;

    protected $queryString = ['search', 'categoryFilter'];

    protected function rules(): array
    {
        return [
            'title'        => 'required|min:3|max:255',
            'penulis'      => 'required|min:3|max:255',
            'penerbit'     => 'nullable|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'isbn'         => 'nullable|max:20',
            'stok'         => 'required|integer|min:0',
            'categoryId'   => 'nullable|exists:categories,id',
            'deskripsi'    => 'nullable|max:5000',
            'coverImage'   => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'bookFile'     => 'nullable|mimes:pdf|max:10240',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Open modal for creating a new book.
     */
    public function openModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
        $this->isEdit = false;
    }

    /**
     * Open modal for editing an existing book.
     */
    public function editBook(int $id): void
    {
        $book = Book::findOrFail($id);
        $this->bookId = $book->id;
        $this->title = $book->judul ?? '';
        $this->penulis = $book->penulis ?? '';
        $this->penerbit = $book->penerbit ?? '';
        $this->tahun_terbit = $book->tahun_terbit;
        $this->isbn = $book->isbn ?? '';
        $this->stok = $book->stok;
        $this->categoryId = $book->category_id;
        $this->deskripsi = $book->deskripsi ?? '';
        $this->existingCover = $book->cover_image;
        $this->existingFile = $book->book_file;

        $this->showModal = true;
        $this->isEdit = true;
    }

    /**
     * Save (create or update) a book record.
     */
    public function save(): void
    {
        $this->validate();

        $data = [
            'judul'        => $this->title,
            'penulis'      => $this->penulis,
            'penerbit'     => $this->penerbit ?: null,
            'tahun_terbit' => $this->tahun_terbit,
            'isbn'         => $this->isbn ?: null,
            'stok'         => $this->stok,
            'category_id'  => $this->categoryId,
            'deskripsi'    => $this->deskripsi ?: null,
        ];

        // Handle cover image upload
        if ($this->coverImage) {
            // Delete old cover if replacing
            if ($this->isEdit && $this->existingCover) {
                Storage::disk('public')->delete($this->existingCover);
            }
            $data['cover_image'] = $this->coverImage->store('covers', 'public');
        }

        // Handle book file upload
        if ($this->bookFile) {
            // Delete old file if replacing
            if ($this->isEdit && $this->existingFile) {
                Storage::disk('public')->delete($this->existingFile);
            }
            $data['book_file'] = $this->bookFile->store('books', 'public');
        }

        if ($this->isEdit && $this->bookId) {
            Book::find($this->bookId)->update($data);
            session()->flash('message', 'Buku berhasil diperbarui!');
        } else {
            Book::create($data);
            session()->flash('message', 'Buku berhasil ditambahkan!');
        }

        $this->closeModal();
    }

    /**
     * Show delete confirmation.
     */
    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    /**
     * Delete a book and its associated files.
     */
    public function deleteBook(): void
    {
        if ($this->deleteId) {
            $book = Book::find($this->deleteId);
            if ($book) {
                // Files are cleaned up via the model's deleting event
                $book->delete();
                session()->flash('message', 'Buku berhasil dihapus!');
            }
        }
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    /**
     * Remove the cover image from an existing book.
     */
    public function removeCover(): void
    {
        if ($this->existingCover) {
            if ($this->isEdit && $this->bookId) {
                $book = Book::find($this->bookId);
                if ($book && $book->cover_image) {
                    Storage::disk('public')->delete($book->cover_image);
                    $book->update(['cover_image' => null]);
                }
            }
            $this->existingCover = null;
        }
        $this->coverImage = null;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->bookId = null;
        $this->title = '';
        $this->penulis = '';
        $this->penerbit = '';
        $this->tahun_terbit = null;
        $this->isbn = '';
        $this->stok = 1;
        $this->categoryId = null;
        $this->deskripsi = '';
        $this->coverImage = null;
        $this->bookFile = null;
        $this->existingCover = null;
        $this->existingFile = null;
        $this->isEdit = false;
        $this->resetValidation();
    }

    public function render()
    {
        $books = Book::with('category')
            ->when($this->search, fn($q) => $q->where('judul', 'like', "%{$this->search}%")
                ->orWhere('penulis', 'like', "%{$this->search}%")
                ->orWhere('isbn', 'like', "%{$this->search}%"))
            ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = Category::orderBy('name')->get();

        return view('livewire.books.index', [
            'books'      => $books,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}
