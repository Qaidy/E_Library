<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');   // Siapa yang pinjam
            $table->foreignId('book_id')->constrained()->onDelete('cascade');   // Buku apa
            $table->date('tanggal_pinjam');                                       // Kapan dipinjam
            $table->date('tanggal_kembali');                                      // Batas waktu
    $table->date('tanggal_dikembalikan')->nullable();                     // Aktual kembali
    $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])
          ->default('dipinjam');                                           // Status saat ini
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
