<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopAuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::factory(20)->create()->each(function ($author) {
            Book::factory(10)->create([
                'book_category_id' => 1,
                'author_id' => $author->id
            ])->each(function ($book) {
                Rating::factory(mt_rand(100, 500))->create([
                    'value' => mt_rand(6, 10),
                    'book_id' => $book->id
                ]);
            });
        });
    }
}
