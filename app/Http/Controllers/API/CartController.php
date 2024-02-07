<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {  
        $userId = $request->input('user_id');
        $bookId = $request->input('book_id');
        $quantity = $request->input('quantity');

        $cartItem = CartItem::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'quantity' => $quantity,
        ]);

        return response()->json(['message' => 'Book added to cart', 'cartItem' => $cartItem]);
    }

    public function getCart($userId)
    {
        $cartItems = CartItem::where('user_id', $userId)->with('book')->get();

        return response()->json(['cartItems' => $cartItems]);
    }

    public function updateCart(Request $request)
    {
        $userId = $request->input('user_id');
        $bookId = $request->input('book_id');
        $quantity = $request->input('quantity');

        CartItem::where('user_id', $userId)
                ->where('book_id', $bookId)
                ->update(['quantity' => $quantity]);

        return response()->json(['message' => 'Quantity updated successfully']);
    }

    public function removeFromCart(Request $request)
    {
        $userId = $request->input('user_id');
        $bookId = $request->input('book_id');

        CartItem::where('user_id', $userId)->where('book_id', $bookId)->delete();

        return response()->json(['message' => 'Book removed from cart']);
    }
}
