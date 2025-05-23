<?php

namespace App\Http\Controllers\Api\Authors;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Author $author)
    {
        $books = $author->books()->with('category')->orderBy('name')->get()->toArray();
        return response()->json([
            'data' => $books,
            'total' => count($books),
            'parameters' => [
                [
                    'name' => 'author',
                    'value' => $author->id
                ]

            ]
        ]);
    }
}
