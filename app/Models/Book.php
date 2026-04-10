<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'stok',
        'deskripsi',
        'cover_image',
        'book_file',
        'category_id',
    ];

    protected $appends = ['cover_url'];

    /**
     * A book belongs to a category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * A book can be loaned many times.
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Check if the book is available for borrowing.
     */
    public function isAvailable(): bool
    {
        return $this->stok > 0;
    }

    /**
     * Get the full URL of the cover image, or a placeholder.
     */
    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_image && Storage::disk('public')->exists($this->cover_image)) {
            return asset('storage/' . $this->cover_image);
        }

        return asset('images/book-placeholder.svg');
    }

    /**
     * Get the full URL of the book PDF file.
     */
    public function getBookFileUrlAttribute(): ?string
    {
        if ($this->book_file && Storage::disk('public')->exists($this->book_file)) {
            return asset('storage/' . $this->book_file);
        }

        return null;
    }

    /**
     * Check if a book has a cover image.
     */
    public function hasCover(): bool
    {
        return !empty($this->cover_image) && Storage::disk('public')->exists($this->cover_image);
    }

    /**
     * Check if a book has a PDF file.
     */
    public function hasFile(): bool
    {
        return !empty($this->book_file) && Storage::disk('public')->exists($this->book_file);
    }

    /**
     * Delete associated files when the book is deleted.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($book) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            if ($book->book_file) {
                Storage::disk('public')->delete($book->book_file);
            }
        });
    }
}
