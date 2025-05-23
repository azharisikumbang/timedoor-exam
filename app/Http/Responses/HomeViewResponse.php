<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Arrayable;

final class HomeViewResponse implements Arrayable
{
    public function __construct(
        private readonly int $bookId,
        private readonly string $bookName,
        private readonly string $authorName,
        private readonly string $bookCategoryName,
        private readonly float $avgRating,
        private readonly int $totalVoters,
    ) {
    }

    public function toArray(): array
    {
        return [
            'book_id' => $this->bookId,
            'book_name' => $this->bookName,
            'author_name' => $this->authorName,
            'book_category_name' => $this->bookCategoryName,
            'avg_rating' => $this->avgRating,
            'total_voters' => $this->totalVoters,
        ];
    }
}