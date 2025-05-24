<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Rating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FamousAuthorsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * This test will test the author with book ratings only above 5
     * Authors that have book(s) with rating below 6 should not be selected as higher votes even he have other rating above 5
     * Expected Author Ids : (1,3,5,7,9) and Book Ids: (1,3,5,7,9)
     */
    public function testItGetAuthorWithRatingAboveFive()
    {
        // $this->withoutExceptionHandling();
        // create book categories that has 10 books with random author => total 2*10 = 20 books
        BookCategory::factory(2)->create();

        // create 10 authors (id: 1-10) and will be expected rating above 5 (ids: 1 - 10)
        Author::factory(10)->create()->each(function ($model) {
            Book::factory()->create(['book_category_id' => mt_rand(1, 2), 'author_id' => $model->id]);
        });

        // Author with ids (2,4,6,8,10) will have a book with rating below 6 (ids: 11-20)
        Author::findMany([2, 4, 6, 8, 10])->each(function ($model) {
            Book::factory(2)->create(['book_category_id' => mt_rand(1, 2), 'author_id' => $model->id]);
        });

        // create sample 10 (id: 1-10) book with rating > 5
        for ($i = 1; $i <= 10; $i++)
        {
            Rating::factory()->create([
                'book_id' => $i,
                'value' => mt_rand(6, 10)
            ]);
        }

        // create sample 10 (id: 11-20) book with rating <= 5
        for ($i = 11; $i <= 20; $i++)
        {
            Rating::factory()->create([
                'book_id' => $i,
                'value' => mt_rand(1, 5)
            ]);
        }

        // assert
        // get top author with no book rating below or same with 5
        $higherRatings = Author::topAuthors()->pluck('id')->toArray();
        $expectedAuthorThatHasHigherVotesIds = [1, 3, 5, 7, 9];

        // every expected author only have a books with rating above 5, so its should be have same size
        $this->assertCount(count($expectedAuthorThatHasHigherVotesIds), $higherRatings);

        foreach ($higherRatings as $bookId)
        {
            foreach ($expectedAuthorThatHasHigherVotesIds as $expectedAuthorId)
            {
                // books id should same id with author id (based on data supllied)
                if ($bookId == $expectedAuthorId)
                {
                    $authorId = Book::find($bookId)->author_id;
                    $this->assertEquals($expectedAuthorId, $authorId);
                }
            }
        }
    }

    public function testItTopAuthorWithRightVotes()
    {
        $expectedVotes = 10;

        // create 10 author with 2 books, total 20 books
        BookCategory::factory(2)->create();
        Author::factory(10)->create()->each(function ($model) {
            Book::factory(2)->create(['book_category_id' => mt_rand(1, 2), 'author_id' => $model->id]);
        });

        // other votes
        for ($i = 0; $i < 30; $i++)
        {
            Rating::factory()->create([
                'book_id' => mt_rand(3, 20), // book 1 and 2 will be expected
                'value' => 1
            ]);
        }

        // expected votes
        Rating::factory($expectedVotes)->create([
            'book_id' => 1,
            'value' => mt_rand(6, 10)
        ]);


        $topAuthors = Author::topAuthors();

        $this->assertCount(1, $topAuthors);
        foreach ($topAuthors as $author)
        {
            $this->assertInstanceOf(Author::class, $author);
            $this->assertEquals(1, $author->id); // it should be author with id 1
            $this->assertEquals($expectedVotes, $author->total_votes_count); // 10
        }
    }
}
