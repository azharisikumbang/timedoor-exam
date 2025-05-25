<?php

namespace App\Http\Controllers;

use App\Services\BookRatingService;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $service = new BookRatingService();
        $service
            ->withQueryString($request)
            ->getBooksWithRatingAndVotes();

        $paginated = (new Paginator(
            $service->toViewResponse(),
            $service->getTotalDisplayed(),
            $service->getPage(),

        ))->withQueryString();

        $nextPage = ($paginated->count() == $service->getTotalDisplayed()) ?
            $paginated->url($paginated->currentPage() + 1) : null;

        return view('welcome', [
            'books' => array_merge($paginated->toArray(), [
                // its neede because we we paginate manually at database level
                'prev_page_url' => $paginated->previousPageUrl(),
                'next_page_url' => $nextPage,
            ])
        ]);
    }
}
