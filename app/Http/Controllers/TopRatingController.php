<?php

namespace App\Http\Controllers;

use App\Http\Responses\TopRatingViewResponse;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;

class TopRatingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $popularRating = Rating::higherVotes();

        $authors = Book::with(['author' => fn($query) => $query->select('id', 'name')])
            ->select('id', 'author_id')
            ->whereIn('id', $popularRating->pluck('book_id')->all())
            ->get()
            ->keyBy('id')
            ->toArray();

        $topRatings = $popularRating->map(
            fn($rating): TopRatingViewResponse =>
            new TopRatingViewResponse(
                $authors[$rating->book_id]['author']['id'],
                $authors[$rating->book_id]['author']['name'],
                $rating['total_voters']
            )
        );

        return view('top-rating', [
            'topRatings' => $topRatings->toArray()
        ]);
    }
}
