<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Models\Author;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rating.create', [
            'authors' => Author::select('id', 'name')->orderBy('name')->get()->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRatingRequest $request)
    {
        $validated = $request->validated();

        Rating::create([
            'book_id' => $validated['book'],
            'value' => $validated['rating']
        ]);

        return to_route('home')->with('success', 'Rating added successfully.');
    }
}
