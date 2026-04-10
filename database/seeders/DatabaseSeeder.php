<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin123@gmail.com'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => bcrypt('idn12345'),
            ]
        );

        // Book categories
        $categories = [
            ['name' => 'Fiksi',       'slug' => 'fiksi',       'color' => '#8b5cf6', 'description' => 'Novel, cerpen, dan karya fiksi lainnya'],
            ['name' => 'Non-Fiksi',   'slug' => 'non-fiksi',   'color' => '#06b6d4', 'description' => 'Buku pengetahuan umum dan fakta'],
            ['name' => 'Sains',       'slug' => 'sains',       'color' => '#10b981', 'description' => 'Buku ilmu pengetahuan alam dan eksak'],
            ['name' => 'Teknologi',   'slug' => 'teknologi',   'color' => '#3b82f6', 'description' => 'Buku tentang teknologi dan komputer'],
            ['name' => 'Sejarah',     'slug' => 'sejarah',     'color' => '#f59e0b', 'description' => 'Buku tentang sejarah dan peradaban'],
            ['name' => 'Sastra',      'slug' => 'sastra',      'color' => '#ec4899', 'description' => 'Puisi, drama, dan karya sastra'],
            ['name' => 'Pendidikan',  'slug' => 'pendidikan',  'color' => '#14b8a6', 'description' => 'Buku pelajaran dan edukasi'],
            ['name' => 'Agama',       'slug' => 'agama',       'color' => '#6366f1', 'description' => 'Buku keagamaan dan spiritual'],
            ['name' => 'Bisnis',      'slug' => 'bisnis',      'color' => '#f97316', 'description' => 'Buku ekonomi, bisnis, dan manajemen'],
            ['name' => 'Seni',        'slug' => 'seni',        'color' => '#e11d48', 'description' => 'Buku seni rupa, musik, dan desain'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
