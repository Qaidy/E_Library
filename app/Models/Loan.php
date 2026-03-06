<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'book_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'date',
    ];

    //Relasi: Peminjaman ini milik siapa (One User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Relasi: Peminjaman ini meminjam buku apa (One Book)
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    //Cek apakah buku terlambat dikembalikan
    public function isOverdue()
    {
        return $this->status === 'dipinjam' && $this->tanggal_kembali < now();
    }

}
