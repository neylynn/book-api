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
        return Book::all(['id', 'title', 'author', 'inventory', 'price']);
    }

    public function show($bookId)
    {
        return Book::find($bookId);
    }

    public function search($name)
    {
        $result = Book::where('title', 'LIKE', '%'. $name. '%')->get();
        if(count($result)){
            return Response()->json($result);
        }
        else{
            return response()->json(['Result' => 'No Data found'], 404);
        }
    }
}
