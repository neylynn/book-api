<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function index()
    {
        return Book::all(['id', 'title', 'author', 'price']);
    }

    public function show($bookId)
    {
        return Book::find($bookId);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json(['error' => 'Query parameter is required.'], 400);
        }

        $books = Book::where('title', 'like', "%$query%")
                     ->orWhere('author', 'like', "%$query%")
                     ->get(['id', 'title', 'author', 'price']);

        return response()->json(['books' => $books]);
    }
}
