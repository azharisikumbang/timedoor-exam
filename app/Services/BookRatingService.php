<?php

namespace App\Services;

use App\Http\Responses\HomeViewResponse;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BookRatingService
{
    private int $total = 10;
    private int $offset = 0;
    private ?string $search = null;
    private int $page = 0;
    private Collection $data;

    public function withQueryString(Request $request): static
    {
        $this->total = $request->get('shown', 10);
        $this->search = $request->get('search', null);
        $this->page = $request->get('page', 0);
        $this->offset = $this->page * $this->total - $this->total;

        return $this;
    }

    public function getBooksWithRatingAndVotes(): static
    {
        if ($this->search)
        {
            $this->searchPopularBooksByTitleOrAuthor();
            return $this;
        }

        $this->getPopularBooks();

        return $this;
    }

    public function getData(): Collection
    {
        return $this->data ?? collect();
    }

    public function toViewResponse()
    {
        return $this->getData()->map(fn(Book $book) => new HomeViewResponse(
            $book->id,
            $book->name,
            $book->author->name,
            $book->category->name,
            $book->ratings_avg_value,
            $book->ratings_count
        ));
    }

    public function toPagination(): Paginator
    {
        return (new \Illuminate\Pagination\Paginator(
            $this->getData(),
            $this->total,
            $this->page,
        ))->withQueryString();
    }

    public function toCollection(): Collection
    {
        return $this->getData();
    }

    public function getTotalDisplayed(): int
    {
        return $this->total;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    private function searchPopularBooksByTitleOrAuthor(): static
    {
        $this->data = $this->createQueryBuilder()
            ->where('name', 'like', "%" . $this->search . "%")
            ->orWhereRelation('author', 'name', 'like', "%" . $this->search . "%")
            ->limit($this->total)
            ->offset($this->offset)
            ->get();

        return $this;
    }

    private function getPopularBooks(): static
    {
        $offset = $this->page * $this->total - $this->total;
        $booksPopular = Rating::popular($this->total, $offset);

        $this->data = $this
            ->createQueryBuilder()
            ->whereKey($booksPopular->pluck('book_id'))
            ->get();

        return $this;
    }

    private function createQueryBuilder(): Builder
    {
        return Book::with(['author', 'category'])
            ->withAvg('ratings', 'value')
            ->withCount('ratings')
            ->orderByDesc('ratings_avg_value')
            ->orderByDesc('ratings_count');
    }
}