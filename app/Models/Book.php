<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'kategori',
        'deskripsi',
    ];

    // Relasi: Satu buku bisa dipinjam berkali-kali (Many)
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function isAvailable()
    {
        return $this->stok > 0;
    }
}
