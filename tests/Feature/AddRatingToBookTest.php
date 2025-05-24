<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddRatingToBookTest extends TestCase
{
    use RefreshDatabase;

    public function testSeeAddRatingForm()
    {
        $this->get('rating/create')
            ->assertOk()
            ->assertSee('rating')
            ->assertSee('author')
            ->assertSee('book');
    }

    public function testItShouldSaveValidData()
    {
        $this->withoutExceptionHandling();

        BookCategory::factory(2)->create();
        Author::factory(10)->create()->each(function ($model) {
            Book::factory(2)->create(['book_category_id' => mt_rand(1, 2), 'author_id' => $model->id]);
        });

        $this->assertDatabaseCount('ratings', 0);

        $payload = [
            'author' => 1,
            'book' => 1,
            'rating' => 10,
        ];

        $this->post('rating', $payload);

        $this->assertDatabaseHas('ratings', [
            'book_id' => 1,
            'value' => 10
        ]);
    }

    public function testItShouldRejectNonExistsAuthor()
    {
        $payload = [
            'author' => 1,
            'book' => 1,
            'rating' => 10,
        ];

        $response = $this->post('rating', $payload);

        $response->assertSessionHasErrors('author');
        $this->assertDatabaseCount('ratings', 0);
    }

    public function testItShouldRejectNonExistsBook()
    {
        BookCategory::factory(2)->create();
        Author::factory(10)->create()->each(function ($model) {
            Book::factory(2)->create(['book_category_id' => mt_rand(1, 2), 'author_id' => $model->id]);
        });

        $payload = [
            'author' => 1,
            'book' => 99999,
            'rating' => 10,
        ];

        $response = $this->post('rating', $payload);

        $response->assertSessionHasErrors('book');
        $this->assertDatabaseCount('ratings', count: 0);
    }

    public function testItShouldRejectRatingLowerThanOne()
    {
        BookCategory::factory(2)->create();
        Author::factory(10)->create()->each(function ($model) {
            Book::factory(2)->create(['book_category_id' => mt_rand(1, 2), 'author_id' => $model->id]);
        });

        $this->assertDatabaseCount('ratings', 0);

        $payload = [
            'author' => 1,
            'book' => 1,
            'rating' => 0,
        ];

        $response = $this->post('rating', $payload);

        $response->assertSessionHasErrors('rating');
        $this->assertDatabaseCount('ratings', count: 0);
    }

    public function testItShouldRejectRatingHigherThanTen()
    {
        BookCategory::factory(2)->create();
        Author::factory(10)->create()->each(function ($model) {
            Book::factory(2)->create(['book_category_id' => mt_rand(1, 2), 'author_id' => $model->id]);
        });

        $this->assertDatabaseCount('ratings', 0);

        $payload = [
            'author' => 1,
            'book' => 1,
            'rating' => 11,
        ];

        $response = $this->post('rating', $payload);

        $response->assertSessionHasErrors('rating');
        $this->assertDatabaseCount('ratings', count: 0);
    }

    public function testItShouldRejectBookNotBelongToAuthor()
    {
        BookCategory::factory(2)->create();
        Author::factory(10)->create()->each(function ($model) {
            Book::factory(2)->create(['book_category_id' => mt_rand(1, 2), 'author_id' => $model->id]);
        });

        $this->assertDatabaseCount('ratings', 0);

        $payload = [
            'author' => 1,
            'book' => 3, // its belong to author id 2
            'rating' => 10,
        ];

        $response = $this->post('rating', $payload);

        $response->assertSessionHasErrors('book');
        $this->assertDatabaseCount('ratings', count: 0);
    }
}
