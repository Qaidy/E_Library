<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
    ];

    /**
     * Auto-generate slug from name on creation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * A category has many books.
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Get the book count for display.
     */
    public function getBookCountAttribute(): int
    {
        return $this->books()->count();
    }
}
