<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Rating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchBookTest extends TestCase
{
    use RefreshDatabase;

    public function testSearchByBookName()
    {
        $this->generateRandomData();

        $expectedBookTitle = "Expected Book Title 1";
        $expectedAuthor = Author::find(1);
        $book = Book::factory()->create([
            'name' => $expectedBookTitle,
            'book_category_id' => 1,
            'author_id' => $expectedAuthor->id
        ]);
        Rating::factory()->create([
            'value' => mt_rand(6, 10),
            'book_id' => $book->id
        ]);

        $response = $this->get('/?search=book+title');
        $response->assertOk();
        $response->assertSee($expectedBookTitle);
        $response->assertSee($expectedAuthor->name);
    }

    public function testSearchByAuthorName()
    {
        $this->generateRandomData();

        $expectedBookTitle = "Expected Book Title 1";
        $expectedAuthor = Author::factory()->create(['name' => 'Azhari Saputra']);
        $book = Book::factory()->create([
            'name' => $expectedBookTitle,
            'book_category_id' => 1,
            'author_id' => $expectedAuthor->id
        ]);
        Rating::factory()->create([
            'value' => mt_rand(6, 10),
            'book_id' => $book->id
        ]);

        $response = $this->get('/?search=azhari');
        $response->assertOk();
        $response->assertSee($expectedBookTitle);
        $response->assertSee($expectedAuthor->name);
    }


    public function testSearchNotFound()
    {
        $this->generateRandomData();

        $response = $this->get('/?search=some+unknown');
        $response->assertOk();
        $response->assertSee('Tidak ada data.');
    }

    private function generateRandomData()
    {
        BookCategory::factory(2)->create();

        Author::factory(20)
            ->create()
            ->each(function ($author) {
                Book::factory(10)->create([
                    'book_category_id' => 1,
                    'author_id' => $author->id
                ])->each(function ($book) {
                    Rating::factory()->create([
                        'value' => mt_rand(6, 10),
                        'book_id' => $book->id
                    ]);
                });
            });
    }
}
