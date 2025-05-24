<?php

namespace App\Services;

use App\Http\Responses\HomeViewResponse;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Rating;
use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class BookInformationRatingService
{
    public function getBooks(int $total = 10, ?string $search = null, int $page = 1)
    {
        if ($search)
        {
            // tested with no search, its take 4.x second
            $query = $this
                ->createQuery()
                ->whereAny(["b.name", "a.name"], 'like', "%" . $search . "%");
            // $query = $this->applySearch($query, $search);

            return $this->paginateToView($query, $total);
        }

        // its faster for index, diff: 2-3s with the query above
        $offset = $page * $total - $total;
        $booksPopular = Rating::popular($total, $offset);

        $books = Book::with(['author', 'category'])
            ->whereIn('id', $booksPopular->pluck('book_id')->all())
            ->get();

        $transformToView = $booksPopular->map(function ($model) use ($books) {
            $book = $books->where('id', $model->book_id)->first();

            return $this->convertToViewResponse(
                $book,
                $model->avg_rating,
                $model->total_voters
            );
        });

        return (new LengthAwarePaginator(
            $transformToView,
            Rating::getPopularCount(),
            $total,
            $page
        ))->withQueryString();
    }

    private function applySearch(Builder $builder, string $search): Builder
    {
        return $builder->whereAny(["b.name", "a.name"], 'like', "%" . $search . "%");
    }

    private function createQuery(): Builder
    {
        return DB::query()
            ->selectRaw("b.id, b.name, a.name as 'author', bc.name as 'book_category', AVG(value) as avg_rating, COUNT(r.id) as total_voters")
            ->from((new Book)->getTable() . ' as b')
            ->join((new Rating)->getTable() . ' as r', DB::raw('r.book_id'), '=', DB::raw('b.id'))
            ->join((new BookCategory)->getTable() . ' as bc', DB::raw('bc.id'), '=', DB::raw('b.book_category_id'))
            ->join((new Author)->getTable() . ' as a', DB::raw('a.id'), '=', DB::raw('b.author_id'))
            ->groupBy(DB::raw("b.id"))
            ->orderBy(DB::raw("avg_rating"), 'desc')
        ;
    }

    private function paginateToView(Builder $builder, int $total): \Illuminate\Pagination\Paginator
    {
        return $builder
            ->simplePaginate($total)
            ->withQueryString()
            ->through(fn($item) => new HomeViewResponse($item->id, $item->name, $item->author, $item->book_category, $item->avg_rating, $item->total_voters));
    }

    private function convertToViewResponse(Book $book, float $rating, int $voters)
    {
        return new HomeViewResponse(
            $book->id,
            $book->name,
            $book->author->name,
            $book->category->name,
            $rating,
            $voters
        );
    }
}