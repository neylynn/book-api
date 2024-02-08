<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Book;
use App\Models\User;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function getWishlist($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $wishlist = $user->wishlist;

        return response()->json(['wishlist' => $wishlist]);
    }

    public function addToWishlist($userId, Request $request)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $bookId = $request->input('book_id');
        $book = Book::find($bookId);

        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        // Add the book to the wishlist if not already present
        if (!$user->wishlist->contains($bookId)) {
            $user->wishlist()->attach($bookId);
        }

        return response()->json(['message' => 'Book added to wishlist successfully']);
    }

    public function removeFromWishlist($userId, $bookId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Detach the book from the wishlist
        $user->wishlist()->detach($bookId);

        return response()->json(['message' => 'Book removed from wishlist successfully']);
    }
}
