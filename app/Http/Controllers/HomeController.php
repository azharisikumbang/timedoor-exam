<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rating;
use App\Services\BookInformationRatingService;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $books = (new BookInformationRatingService)->getBooks(
            $request->get('shown', 10),
            $request->get('search', null),
            $request->get('page', 0),
        );

        return view('welcome', [
            'books' => $books->toArray()
        ]);
    }
}
