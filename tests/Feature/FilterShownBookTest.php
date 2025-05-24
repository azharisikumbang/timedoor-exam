<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Rating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilterShownBookTest extends TestCase
{
    use RefreshDatabase;
    public function testItShouldHasSelectFilter()
    {
        $response = $this->get('/');
        $response->assertOk();

        $response->assertSee('List Shown');// add more test for every filter items
    }

    public function testItDisplayTotalTenDataAsDefault()
    {
        $this->generateRandomData();

        $response = $this->get('/');
        $response->assertOk();

        $response->assertSee('<td>10</td>', false);
        $response->assertDontSee('<td>11</td>', false);
    }

    public function testApplyFilterShown()
    {
        $this->generateRandomData();

        $response = $this->get('/?shown=20');
        $response->assertOk();

        $response->assertSee('<td>20</td>', false);
        $response->assertDontSee('<td>21</td>', false);
    }

    public function testApplyFilterShownWithRandomValue()
    {
        $this->generateRandomData();

        $expected = mt_rand(10, 100);
        $response = $this->get('/?shown=' . $expected);
        $response->assertOk();

        $response->assertSee(sprintf("<td>%s</td>", $expected), false);
        $response->assertDontSee(sprintf("<td>%s</td>", $expected + 1), false);
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
