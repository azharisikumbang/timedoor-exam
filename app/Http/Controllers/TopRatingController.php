<?php

namespace App\Http\Controllers;

use App\Http\Responses\TopRatingViewResponse;
use App\Models\Author;
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
        $authors = Author::topAuthors();

        $topRatings = $authors->map(
            fn($author): TopRatingViewResponse =>
            new TopRatingViewResponse(
                $author->id,
                $author->name,
                $author->total_votes_count
            )
        );

        return view('top-rating', [
            'topRatings' => $topRatings->toArray()
        ]);
    }
}
