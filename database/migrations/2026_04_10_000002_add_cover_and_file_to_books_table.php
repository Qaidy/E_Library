<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // First, add the new columns
        Schema::table('books', function (Blueprint $table) {
            $table->string('cover_image')->nullable()->after('deskripsi');
            $table->string('book_file')->nullable()->after('cover_image');
            $table->foreignId('category_id')->nullable()->after('book_file')
                  ->constrained('categories')->nullOnDelete();
        });

        // Migrate existing kategori values into the categories table
        $existingCategories = DB::table('books')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori');

        foreach ($existingCategories as $categoryName) {
            $slug = Str::slug($categoryName);
            $exists = DB::table('categories')->where('slug', $slug)->first();

            if (!$exists) {
                $categoryId = DB::table('categories')->insertGetId([
                    'name' => $categoryName,
                    'slug' => $slug,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $categoryId = $exists->id;
            }

            // Update books with the new category_id
            DB::table('books')
                ->where('kategori', $categoryName)
                ->update(['category_id' => $categoryId]);
        }

        // Drop the old kategori column
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('kategori')->nullable()->after('stok');
        });

        // Migrate category_id back to kategori string
        $books = DB::table('books')
            ->join('categories', 'books.category_id', '=', 'categories.id')
            ->select('books.id', 'categories.name')
            ->get();

        foreach ($books as $book) {
            DB::table('books')->where('id', $book->id)->update(['kategori' => $book->name]);
        }

        Schema::table('books', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn(['cover_image', 'book_file']);
        });
    }
};
